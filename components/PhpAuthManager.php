<?php

// SEE ALSO ./rbac FOLDER for creating new roles!!!

namespace app\components;

use Yii;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;
use app\models\User;

class PhpAuthManager extends PhpManager {

    public function init()
    {
        parent::init();
        //error_log("role = ".Yii::app()->user->role." user_id = ".Yii::app()->user->id);
        $map = [
            'root' => User::ROLE_ROOT,
            'client' => User::ROLE_CLIENT,
        ];
        if (!isset(Yii::$app->user)) return; //for console mode

        $user = Yii::$app->user;
        if (!$user->getIsGuest()) {
            $userModel = $user->getIdentity(false); //userModel should be app\models\User see config

            foreach($map as $roleName => $roleValue){
                $isAssigned = $userModel->roles & $roleValue;
                $role = $this->getRole($roleName);
                if ($isAssigned && $role) {
                    $this->assign($role, $user->id); //мы делаем роли на лету
                }
            }
        }
    }

    public function assign($role, $userId) //переназначили этот метод, чтобы не сохранялось назначение роли
    {
        if (!isset($this->items[$role->name])) {
            throw new InvalidParamException("Unknown role '{$role->name}'.");
        } elseif (isset($this->assignments[$userId][$role->name])) {
            throw new InvalidParamException("Authorization item '{$role->name}' has already been assigned to user '$userId'.");
        } else {
            $this->assignments[$userId][$role->name] = new Assignment([
                'userId' => $userId,
                'roleName' => $role->name,
                'createdAt' => time(),
            ]);
            //$this->saveAssignments();
            return $this->assignments[$userId][$role->name];
        }
    }

}
