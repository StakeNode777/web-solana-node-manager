<?php
// controllers/ValidatorController.php

namespace app\controllers;

require_once(__DIR__ . '/../helpers/logger.php');

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
                'only' => ['index', 'view', 'create', 'delete', 'configure', 'createStep1', 'createStep2', 'createStep3', 'transferApi'], // Apply to all actions
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

    /**
     * Check if the current user is the owner of the validator
     * @param int $validatorId ID of the validator to check
     * @return Validator|null Returns the validator model if the user is the owner, otherwise null
     * @throws \yii\web\ForbiddenHttpException If the user is not the owner
     */
    private function checkValidatorOwnership($validatorId)
    {
        $validator = Validator::findOne($validatorId);
        
        if ($validator === null) {
            throw new \yii\web\NotFoundHttpException('Validator not found.');
        }
        
        // check if user is logged in
        if (Yii::$app->user->isGuest) {
            throw new \yii\web\ForbiddenHttpException('You must be logged in to access this validator.');
        }
        
        // check if the current user is the owner of the validator
        if ($validator->user_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException('You do not have permission to access this validator.');
        }
        
        return $validator;
    }

    public function actionIndex()
    {
        $query = Validator::find()->own();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $validators = $query->all();

        return $this->render('index', ['dataProvider' => $dataProvider, 'validators' => $validators]);
    }

    public function actionView($id)
    {
        $model = $this->checkValidatorOwnership($id);

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
            $lastUpdated = Server::find()->max('updated_at');
            $lastUpdated = Yii::$app->formatter->asDatetime($lastUpdated) ?? 'error';

            // render partial into string
            $html = $this->renderPartial('_servers', [
                'serverDataProvider' => $serverDataProvider,
                'lastUpdated' => $lastUpdated,
            ]);

            \Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'html' => $html,
                'lastUpdated' => $lastUpdated,
            ];
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
            'serversData' => Server::find()->where(['validator_id' => $model->id])->all(),
            'dataProvider' => $dataProvider, 
            'serverDataProvider' => $serverDataProvider,
            'lastUpdated' => $lastUpdated,
            'logProvider' => $logProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->checkValidatorOwnership($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionConfigure($id)
    {
        $model = $this->checkValidatorOwnership($id);

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
            info('[actionCreateStep1] New validator is being created...');

            // check if Identity already exists for a given user
            $_identity = Yii::$app->request->post()['Validator']['identity'] ?? '';
            if (Validator::find()->where(['identity' => $_identity, 'user_id' => Yii::$app->user->id])->exists()) {
                info('[actionCreateStep1] Validator with this identity already exists.');
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
                    info('[actionCreateStep1] Error: Missing API token.');
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

                info('[actionCreateStep1] Fetching validator infos. Calling endpoint: ' . $url);

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

                    info('[actionCreateStep1] Got Validator info: ' . \json_encode($wizardData));

                    return $this->redirect(['create-step2']);
                } else {
                    info('[actionCreateStep1] Error: Wrong Validator Identity. ' . 
                            'API call failed with code: ' . $httpCode);
                    Yii::$app->session->setFlash('error', [
                        [
                            'title' => 'Error',
                            'message' => 'Wrong Validator Identity. ' . 
                                            'API call failed with code: ' . $httpCode,
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
            info('[actionCreateStep2]  session from step1 is absent, redirecting to Step1...');
            return $this->redirect(['create-step1']);
        }
        $data = $session->get('validator_wizard');

        info('[actionCreateStep2] found session data from Step1: ' . \json_encode($data));

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
            $model->user_id = Yii::$app->user->id; // Set current user as owner
            
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
                info('[actionCreateStep2] Error: Failed to save validator: ' . $errors);
                throw new ErrorException($errorString);
            }
        }

        info('[actionCreateStep2] Validator successfully added!');

        return $this->render('create-step2', ['data' => $data]);
    }

    public function actionCreateStep3()
    {
        $session = Yii::$app->session;
        $id = $session->get('new_validator_id');
        if (!$id) {
            return $this->redirect(['index']);
        }
        
        // Check ownership of the newly created validator
        $validator = $this->checkValidatorOwnership($id);
        
        return $this->render('create-step3', ['id' => $id, 'validator' => $validator]);
    }

    public function actionTransferApi()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (\Yii::$app->request->isPost) {
            $option = \Yii::$app->request->post('option');
            $safe = \Yii::$app->request->post('safe') === 'true';
            
            try {     
                $result = $this->performTransfer($safe, $option);

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

    private function performTransfer($safe, $option)
    {
        $validatorID = \Yii::$app->request->post('validatorID');
        $serverFrom = \Yii::$app->request->post('serverFrom');
        $serverTo = \Yii::$app->request->post('serverTo');

        if (!$validatorID) {
            return;
        }

        $v = $this->checkValidatorOwnership($validatorID);
        if (!$v->identity) {
            throw new \yii\web\ForbiddenHttpException('Validator identity is missing.');
        }

        // [ transfer, activate ] 
        $operation = $option;

        $dto = new TransferDTO(
            $option,
            $safe,
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

    private function makeOperation(bool $safe, string $option): string
    {
        $operation = '';
        if ($safe) {
            $operation = $option === 'transfer' 
                ? 'safe' 
                : 'safe_activate';
        } else {
            $operation = $option === 'transfer' 
                ? 'transfer_only' 
                : 'activate_only';
        }
        
        return $operation;
    }
}