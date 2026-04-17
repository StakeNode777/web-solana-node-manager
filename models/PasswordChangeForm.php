<?php

namespace app\models;

use yii\base\Model;
use Yii;

class PasswordChangeForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        $rules = [
            ['newPassword', 'required'],
            ['newPassword', 'string', 'min' => 6],
        ];
        if(!$this->canEnterOnlyNewPassword()){
            $rules[] = ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'message'=>'"Confirm password" should be equal to "New password".'];
            $rules[] = [['newPasswordRepeat'], 'required'];
            if ($this->isOldPassword()){
                $rules[] = [['currentPassword'], 'required'];
                $rules[] = ['currentPassword', 'currentPassword'];
            }
        }
        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'currentPassword'=>'Old password',
            'newPassword'=>'New password',
            'newPasswordRepeat'=>'Confirm password',
        ];
    }

    public function currentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Old password is wrong');
            }
        }
    }

    public function canEnterOnlyNewPassword()
    {
        return Yii::$app->user->can('admin') && Yii::$app->user->identity->id!=$this->_user->id;
    }

    public function isOldPassword()
    {
        return (bool) $this->_user->password_hash;
    }

    public function changePassword($data = [])
    {
        $this->newPassword = $data['newPassword'];
        if(!$this->canEnterOnlyNewPassword()){
            if($this->isOldPassword())
                $this->currentPassword = $data['currentPassword'];
            $this->newPasswordRepeat = $data['newPasswordRepeat'];
        }

        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            return $user->save();
        } else {
            return false;
        }
    }

}