<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\User;
use app\models\SiteAlert;
use app\helpers\Utils;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

use app\helpers\HTTP;
use app\helpers\TrSign;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/validator']);
        }           
        return $this->render('index');        
    }

    public function actionThank()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/validator']);
        }           
        return $this->render('thank');
    }

    public function actionLogin()
    {        
        $model = new LoginForm();
        $req = Yii::$app->request;
        if ($req->isAjax && $model->load($req->post())) {
            if($model->login()){
                return $this->redirect(['thank']);
            }
            else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

        }
        if (Yii::$app->user->getId()) return $this->redirect(['site/index']);
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect(Yii::$app->user->getReturnUrl());
                }
            }
        }

        if (Yii::$app->user->getId()) return $this->redirect(['site/index']);
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', [
                    [
                        'title'=>'Password reset successfully!', 
                        'message' => 'Check your email for futher steps.'
                    ]
                ]);
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    ['message' => 'Unfortunately we can\'t reset the password for this email.']
                ]);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', [['message' => 'New password saved successfully.']]);
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionError() 
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', [
                'name' => Utils::getExceptionName($exception),
                'message' => Utils::getExceptionMessage($exception),
                'exception' => $exception,
            ]);
        }
    }
}
