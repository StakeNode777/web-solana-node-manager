<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\BaseUrl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

use app\helpers\Generate;
use app\helpers\Utils;


class TestController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (YII_ENV=='dev' || Yii::$app->user->can('admin')) {
            if (parent::beforeAction($action)) {
                return true;
            }
        } else {
            throw new ForbiddenHttpException('Access denied');
        }
        return false;
    }    
    
    public function actionIndex()
    {
        echo "super";
    }    
    
    public function actionSomeError()
    {
        $a = $b;
    }
}