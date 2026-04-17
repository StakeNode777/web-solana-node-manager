<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use app\models\SiteAlert;
use app\models\SiteAlertSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class SiteAlertController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add-js-error'],
                        'roles' => ['@', '?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'add-js-error' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SiteAlertSearch();
        $params = Yii::$app->request->queryParams;
        //error_log(print_r($params,1));
        if (!isset($params['SiteAlertSearch']['status'])) {
            $params['SiteAlertSearch']['status'] = SiteAlert::STATUS_NEW;
        }
        
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        $data = Yii::$app->request->post('SiteAlert');
        if($data){
            $model->attributes = $data;
            if($model->save()){
                Yii::$app->session->setFlash('success', [['message' => 'Saved']]);
                return $this->refresh();
            }
            else
                Yii::$app->session->setFlash('error', [['message' => 'An error occurred when saving']]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', [['message' => 'Removed']]);
        return $this->redirect(['/site-alert']);
    }

    protected function findModel($id)
    {
        if (($model = SiteAlert::findById($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Alert not found.');
        }
    }

    public function actionAddJsError()
    {
        //error_log('actionAddJsError', 3, Yii::getAlias("@app/error.log"));
        Yii::$app->response->format = Response::FORMAT_JSON;
        $msg = Yii::$app->request->post('msg', '');
        $line = Yii::$app->request->post('line', '');
        $url = Yii::$app->request->post('url', '');
        $custom = [
            'line'=>$line,
            'file'=>$url,
        ];
        //avoid repetitions - these data (msg,line,url) already be in custom and attr of record
        unset( $_POST['_csrf'], $_POST['msg'], $_POST['line'], $_POST['url'] );
        SiteAlert::generate(SiteAlert::TYPE_ERROR_JS, $msg, $custom);
        return ['status' => 0];
    }

    public function actionMakeTestError()
    {
        //var_dump( YII_DEBUG );
        //$r = [1=>1, 2=>2]; $r[3]+1;
        //var_dump(1/0);
        //fjdsgh;
        //throw new ForbiddenHttpException('TEST - Access denied');
        //throw new ErrorException('Logging failed.');
        //throw new D
        //throw new \yii\web\BadRequestHttpException(77777777777777);
        //throw new \yii\web\NotFoundHttpException('FFFFFFFFFF');
        //trigger_error("E_USER_NOTICE", E_USER_NOTICE);
        //echo $undefined_var;
        //$var[];
        //undefined_function();
        Yii::$app->db->createCommand('SELECT page, count(*) as c FROM analitics GROUP BY page ORDER BY c DESC LIMIT 5')->queryAll();

        Yii::$app->end();
    }

}
