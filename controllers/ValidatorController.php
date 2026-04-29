<?php
// controllers/ValidatorController.php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Validator;
use app\models\Server;
use app\models\SnmActionLog;
use app\models\SshSaveCounter;
use app\models\UnsuccessfulSaveSsh;
use app\models\User;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\base\ErrorException;
use diecoding\toastr\ToastrFlash;
use app\services\snm\NodeManagerService;
use app\services\snm\TransferDTO;
use app\services\snm\SSHConfig;
use app\services\snm\RPCProcessor;
use yii\web\Response;
use app\services\snm\SyncService;

class ValidatorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create'], // Apply to create action
                'rules' => [
                    [
                        'allow' => true, // Allow access if the rule matches
                        'roles' => ['@'], // Only for authenticated users
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    // Redirect example
                    return $this->redirect(['site/login']); // Or throw new \yii\web\ForbiddenHttpException('Access denied');
                },
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Validator::find()->own();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $model = Validator::findOne($id);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException();
        }

        $servers = Server::getList($model->id);

        $query = SnmActionLog::find()->own()->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $serverDataProvider = new ActiveDataProvider([
            'query' => Server::find()
                ->where(['validator_id' => $model->id])
                ->orderBy(['name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $lastUpdated = Server::find()->max('updated_at');
        $lastUpdated = Yii::$app->formatter->asDatetime($lastUpdated) ?? 'error';

        // log in modal
        $logProvider = new ArrayDataProvider([
            'allModels' => [],
            'pagination' => false,
        ]);

        if (Yii::$app->request->isAjax && Yii::$app->request->get('type') === 'servers') {
            // fetch new data
            (new SyncService())->updateById($model->id);

            // render result
            return $this->renderAjax('_servers', [
                'serverDataProvider' => $serverDataProvider, 'lastUpdated' => $lastUpdated
            ]);
        } elseif (Yii::$app->request->isAjax && Yii::$app->request->get('type') === 'modal-log') {
            $logs = Yii::$app->request->post('logs') ?? [];
            $logProvider = new ArrayDataProvider([
                'allModels' => $logs,
                'pagination' => false,
            ]);
            return $this->renderAjax('_modal_log', ['model' => $model, 'logProvider' => $logProvider]);
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_logs_table', ['model' => $model, 'servers' => $servers, 'dataProvider' => $dataProvider]);
        }

        return $this->render('view', [
            'model' => $model, 
            'servers' => $servers, 
            'serversData' => Server::find()->all(),
            'dataProvider' => $dataProvider, 
            'serverDataProvider' => $serverDataProvider,
            'lastUpdated' => $lastUpdated,
            'logProvider' => $logProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Validator::findOne($id);
        if ($model !== null) {
            $model->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionConfigure($id)
    {
        $model = Validator::findOne($id);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $password = $model->snm_ssh_password;
            if ($password) {
                $model->setSshPassword($password);
            }

            $user = User::findOne($model->user_id);
            if ($user === null) {
                throw new \Exception("UserID:" . $model->user_id . " not found for validator: " . $model->id);
            }

            if (UnsuccessfulSaveSsh::areLimitsExceeded($user)) {
                throw new \Exception("SSH save attempts exceeded.");
            }

            if (!$this->validateSSH($model, $password)) {
                UnsuccessfulSaveSSH::registerUnsuccessfulTry($user);
                throw new \Exception("SSH doesn't work. Exit...");
            }

            // if (!SshSaveCounter::checkAndIncrease($user)) {
            //     throw new \Exception("SSH save attempts exceeded.");
            // }

            $model->configured = true;
            if ($model->validate(['snm_server', 'snm_ssh_login', 'snm_ssh_password'])) {
                $model->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('configure', ['model' => $model]);
    }

    private function validateSSH(Validator $validator, $password): bool
    {
        $ssh = new SSHConfig(
            $validator->snm_server,
            $validator->snm_ssh_login,
            $password,
        );
        
        $res = (new SyncService())->GetInfo($ssh); //$this->GetInfo($ssh);
        $hasErrors = ArrayHelper::getValue($res, 'has_errors');
        if ($hasErrors) {
            return false;
        }

        $data = json_decode(ArrayHelper::getValue($res, 'data'), true);
        if (!is_array($data)) {
            return false;
        }

        $servers = ArrayHelper::getValue($data, 'servers');
        if (!is_array($servers)) {
            return false;
        }

        return true;
    }

    public function actionCreateStep1()
    {
        $model = new Validator();
        $session = Yii::$app->session;
        if ($session->has('validator_wizard')) {
            $data = $session->get('validator_wizard');
            $model->identity = $data['identity'] ?? '';
            $model->cluster = $data['cluster'] ?? '';
        }

        if (Yii::$app->request->isPost) {
            // check if Identity already exists for a given user
            $_identity = Yii::$app->request->post()['Validator']['identity'] ?? '';
            if (Validator::find()->where(['identity' => $_identity, 'user_id' => Yii::$app->user->id])->exists()) {
                //Yii::$app->session->setFlash('error', 'Validator with this identity already exists.');
                Yii::$app->session->setFlash('error', [
                    [
                        'title' => 'Error',
                        'message' => 'Validator with this identity already exists.',
                        'options' => [
                            'progressBar' => true, 
                            'timeOut' => 5000, 
                            'closeButton' => true,
                            "positionClass" => ToastrFlash::POSITION_TOP_CENTER,
                        ]

                    ]
                ]);
                return $this->redirect(['create-step1']);
            }

            $model->load(Yii::$app->request->post());
            if ($model->validate(['identity', 'cluster'])) {
                $network = strtolower($model->cluster);
                $account = $model->identity;
                $token = getenv('VALIDATORS_APP_TOKEN');
                if (!$token) {
                    //Yii::$app->session->setFlash('error', 'Missing API token.');
                    Yii::$app->session->setFlash('error', [
                        [
                            'title' => 'Error',
                            'message' => 'Missing API token.',
                            'options' => [
                                'progressBar' => true, 
                                'timeOut' => 5000, 
                                'closeButton' => true,
                                "positionClass" => ToastrFlash::POSITION_TOP_CENTER,
                            ]

                        ]
                    ]);
                    return $this->render('create-step1', ['model' => $model]);
                }
                $url = "https://www.validators.app/api/v1/validators/{$network}/{$account}.json";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Token: {$token}"]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200) {
                    $apiData = json_decode($response, true);
                    $vote_account = $apiData['vote_account'] ?? '';
                    $name = $apiData['name'] ?? null;
                    $avatar_url = $apiData['avatar_url'] ?? '';
                    $wizardData = [
                        'identity' => $model->identity,
                        'cluster' => $model->cluster,
                        'vote_account' => $vote_account,
                        'name' => $name,
                        'img_url' => $avatar_url,
                    ];
                    $session->set('validator_wizard', $wizardData);
                    return $this->redirect(['create-step2']);
                } else {
                    //Yii::$app->session->setFlash('error', 'API call failed with code: ' . $httpCode);
                    Yii::$app->session->setFlash('error', [
                        [
                            'title' => 'Error',
                            'message' => 'Wrong Validator Identity',
                            'options' => [
                                'progressBar' => true, 
                                'timeOut' => 5000, 
                                'closeButton' => true,
                                "positionClass" => ToastrFlash::POSITION_TOP_CENTER,
                            ]

                        ]
                    ]);
                }
            }
        }

        return $this->render('create-step1', ['model' => $model]);
    }

    public function actionCreateStep2()
    {
        $session = Yii::$app->session;
        if (!$session->has('validator_wizard')) {
            return $this->redirect(['create-step1']);
        }
        $data = $session->get('validator_wizard');

        if (Yii::$app->request->isPost) {
            $model = new Validator();
            $model->identity = $data['identity'];
            $model->cluster = $data['cluster'];
            $model->vote_account = $data['vote_account'];
            $model->name = $data['name'];
            $model->img_url = $data['img_url'];
            $model->health = 'OK';
            $model->snm_server = '';
            $model->configured = false;
            if ($model->save()) {
                $session->set('new_validator_id', $model->id);
                $session->remove('validator_wizard');
                return $this->redirect(['create-step3']);
            } else {
                
                $errorString = '';
                foreach ($model->getErrors() as $attribute => $errors) {
                    $errorString .= $attribute . ': ' . implode(', ', $errors) . '; ';
                }
                //Yii::$app->session->setFlash('error', 'Failed to save validator: ' . $errors);
                throw new ErrorException($errorString);
            }
        }

        return $this->render('create-step2', ['data' => $data]);
    }

    public function actionCreateStep3()
    {
        $session = Yii::$app->session;
        $id = $session->get('new_validator_id');
        if (!$id) {
            return $this->redirect(['index']);
        }
        return $this->render('create-step3', ['id' => $id]);
    }

    public function actionTransferApi()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (\Yii::$app->request->isPost) {
            $option = \Yii::$app->request->post('option');
            $safe = \Yii::$app->request->post('safe') === 'true';
            
            try {     
                if ($option === 'transfer') {
                    // Handle transfer logic
                    $result = $this->performTransfer($safe);
                } else {
                    // Handle activation logic
                    $result = $this->performActivation($safe);
                }

                $success = ArrayHelper::getValue($result, 'has_errors') === false;
                $message = $success
                    ? 'Operation completed successfully'
                    : ArrayHelper::getValue($result, 'data.data', 'something went wrong.');
                
                return [
                    'success' => $success,
                    'transferred' => true,
                    'message' => $message,
                    'log' => ArrayHelper::getValue($result, 'data.log', []),
                ];
                
            } catch (\Exception $e) {
                return [
                    'transferred' => false,
                    'success' => false,
                    'message' => $e->getMessage(),
                    'log' => [],
                ];
            }
        }
        
        return [
            'transferred' => false,
            'success' => false,
            'message' => 'Invalid request method',
            'log' => [],
        ];
    }

    private function performTransfer($safe)
    {
        $validatorID = \Yii::$app->request->post('validatorID');
        $serverFrom = \Yii::$app->request->post('serverFrom');
        $serverTo = \Yii::$app->request->post('serverTo');

        if (!$validatorID) {
            return;
        }

        $v = Validator::findOne($validatorID);
        if (!$v || !$v->identity) {
            return;
        }

        $dto = new TransferDTO(
            "transfer",
            true,
            $v->identity,
            $serverFrom,
            $serverTo,
        );

        $ssh = new SSHConfig(
                $v->snm_server,
                $v->snm_ssh_login,
                $v->getSshPassword(),
            );

        $res = NodeManagerService::process()->DoTransfer($ssh, $dto);

        return ['transferred' => true, 'safe_mode' => $safe, 'data' => $res];
    }

    private function performActivation($safe)
    {
        // Your activation logic here
        return ['activated' => true, 'safe_mode' => $safe];
    }
}