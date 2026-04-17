<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Register form
 */
class RegisterForm extends Model
{
    public $email;
    public $password;
    //public $name;
    //public $phone;
    public $agree;

    public $reCaptcha;

    public function rules()
    {
        $rules = [
            [['email', 'password'], 'required'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address already exists'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            //[['email', 'name', 'phone'], 'filter', 'filter' => 'trim'],
            [['email'], 'filter', 'filter' => 'trim'],
            ['password', 'string', 'min' => 6],
            //['name', 'string', 'min' => 2, 'max' => 255],
            [['agree'], 'required', 'requiredValue' => 1, 'message' => 'Please agree with terms and conditions'],
            [['agree'], 'boolean'],
            [['agree'], 'default', 'value' => 0],
            //['phone', 'safe'],
        ];

        if (!empty(\Yii::$app->params['reCaptcha.siteKey'])) {
            $rules[] = [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::class];
        }

        return $rules;
    }

    public function attributeLabels(){
        return [
            'email'=>'Email',
            'password'=>'Password',
            //'name'=>'Name',
            //'phone'=>'Phone',
        ];
    }

    /**
     * Register user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        //$user->name = $this->name;
        $user->email = $this->email; // 1486033204
        //$user->phone = $this->phone;
        $user->roles = User::ROLE_CLIENT;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->save()){            
            $user->sendEmailThanksForRegistration();
            return $user;
        }
        else{
            return null;
        }
    }
}