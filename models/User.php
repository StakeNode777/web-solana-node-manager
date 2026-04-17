<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use app\models\Order;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends MyActiveRecord implements IdentityInterface
{
    const ROLE_ROOT = 1;
    const ROLE_CLIENT = 8;
    const ROLE_EDITOR = 32;

    //status deleted is not ready for usage!!!
    const STATUS_DELETED = 0; //TODO: we should think how to do it by the right way. May be we should 'deleted ' prefix to each text field or email only

    const STATUS_ACTIVE = 10;
    const STATUS_BLOCKED = 11;

    const SCENARIO_FED_REG = 'fed_reg';

    public $acceptAgreement = null;
    
    protected $_activeSub = null;
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED, self::STATUS_DELETED]],
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique'],
            [['acceptAgreement'], 'required', 'requiredValue' => 1, 'message' => 'Please agree with terms and conditions', 'on' => self::SCENARIO_FED_REG],
            [['acceptAgreement'], 'boolean', 'on' => self::SCENARIO_FED_REG],
            [['acceptAgreement'], 'default', 'value' => 0, 'on' => self::SCENARIO_FED_REG],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!$this->password_hash) return false;
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function sendEmailThanksForRegistration()
    {
        return Yii::$app->mailer->compose(
            ['html' => 'thanksForRegistration-html', 'text' => 'thanksForRegistration-text'],
            ['user' => $this]
        )
        ->setFrom([\Yii::$app->params['infoEmail'] => \Yii::$app->name])
        ->setTo($this->email)
        ->setSubject('Registration on ' . \Yii::$app->name)
        ->send();
    }

    public function sendEmailForPasswordReset()
    {
        return Yii::$app->mailer->compose(
            ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
            ['user' => $this]
        )
        ->setFrom([\Yii::$app->params['infoEmail'] => \Yii::$app->name])
        ->setTo($this->email)
        ->setSubject('Password reset ' . \Yii::$app->name)
        ->send();
    }
  
    public static function getStatusLabels()
    {
        return [
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_BLOCKED => 'Blocked'
        ];
    }
    
    public function getStatusName( )
    {
        $labels = self::getStatusLabels();
        if( array_key_exists($this->status, $labels) )
            return $labels[$this->status];
        else
            return 'Undefinded';
    }    

    public static function getRoleLabels()
    {
        return[
            self::ROLE_ROOT => 'ROOT',
            self::ROLE_CLIENT => 'CLIENT',          
        ];
    }

    public function getRoleName( )
    {
        $labels = self::getRoleLabels();
        if( array_key_exists($this->roles, $labels) )
            return $labels[$this->roles];
        else
            return false;
    }

    public function getShortNameOrEmail($length=10)
    {
        $st = $res = '';
        if($this->email)
            $st = $this->email;
        else
            $st = $this->email;
        $res = substr($st, 0, $length);
        if(strlen($st)>  strlen($res))
            $res .= '...';

        return $res;
    }
    
    public function getProfileName()
    {
        $length = 18;
        if ($this->email) {
            $st = $this->email; 
            $res = substr($st, 0, $length);
            if(strlen($st)>  strlen($res))
                $res .= '...';  
            return $res;
        }
        return 'Profile';
    }

    public function hasRole($role)
    {
        return $this->roles & $role;
    }


}