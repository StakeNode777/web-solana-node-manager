<?php

namespace app\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\AccountTabsHelper;
use app\models\Question;
use app\models\Service;

class NavbarHelper
{
    public static function getItems()
    {
        $user = Yii::$app->user;
        $items = $adminItems = $commonItems = [];

         
        $items[] = ['label' => 'Home', 'url' => ['/']];
       

        if(!$user->isGuest){
            $commonItems2 = []; 

            if (Yii::$app->user->can('admin')) {               
                $commonItems2 = [
                    //['label' => '&nbsp;&nbsp;'],      
                    ['label' => 'Site Alerts', 'url' => ['/site-alert']],                 
                    ['label' => 'Users', 'url' => ['/admin-user']],
                ];

            }

            $items[] = [
                'label' => $user->identity->getProfileName(),
                'items' => array_merge($commonItems, $commonItems2),
                'options' => [
                    'class'=>'nav-common-user-menu',
                    'title'=>$user->identity->email,
                ],
            ];

            $items[] =
                '<li class="nav-item"  title="Log out">'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton('Log Out', ['class' => 'nav-link btn btn-link logout'])
                . Html::endForm()
                . '</li>';
        } else{
            $login_url = Url::toRoute('site/login');
            $items[] = "<a class='nav-link modal-login-btn' href='{$login_url}'>Sign In</a>";
            $reg_url = Url::toRoute('site/register');
            $items[] = "<a class='nav-link' href='{$reg_url}'>Sign Up</a>";
        }

        return $items;
    }
}