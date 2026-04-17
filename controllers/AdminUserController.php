<?php

namespace app\controllers;

use Yii;
use app\models\UserSearch;
use app\models\User;
use yii\base\InvalidParamException;
use yii\base\ErrorException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\bootstrap5\ActiveForm;

/**
 * Admin controller
 */
class AdminUserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoginAs($id) // id = user id
    {
        if (($user = User::findOne(['id' => $id])) === null)
            throw new NotFoundHttpException('User not found');
        if($user->hasRole(User::ROLE_ROOT))
            throw new ForbiddenHttpException('Access denied');

        if(Yii::$app->user->login($user))
            return $this->goHome();
        throw new ErrorException('Logging failed.');
    }
}
