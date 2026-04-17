<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\base\ErrorException;
use yii\helpers\Html;

/**
 * This is the model class for table "site_alert".
 *
 * @property integer $id
 * @property integer $type
 * @property string $message
 * @property integer $user_id
 * @property string $url
 * @property string $referrer
 * @property string $custom
 * @property string $comment
 * @property integer $status
 * @property string $created
 */
class SiteAlert extends MyActiveRecord
{

    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_SYSTEM_DONE = 2;


    const TYPE_FATAL_ERROR = 10;
    const TYPE_NONFATAL_ERROR = 20;

    const TYPE_EXCEPTION_HTTP = 30;
    const TYPE_EXCEPTION_PAGE_NOT_FOUND = 40;
    const TYPE_EXCEPTION_DB = 50;

    const TYPE_ERROR_JS = 60;
    const TYPE_ERROR_CRON = 70;
    const TYPE_ERROR_FEDERATED = 80;
    const TYPE_API_PROBLEM = 90;
    const TYPE_SITE_ERROR = 100;
    const TYPE_SITE_CRITICAL_ERROR = 110;

    public static function tableName()
    {
        return 'site_alert';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public function rules()
    {
        return [
            [['type', 'user_id', 'status'], 'integer'],
            [['custom', 'comment'], 'string'],
            [['message'], 'string', 'max' => 2048],
            [['url', 'referrer'], 'string', 'max' => 255],
            [['created'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'message' => 'Message',
            'user_id' => 'User ID',
            'url' => 'Url',
            'referrer' => 'Referrer',
            'custom' => 'Custom',
            'comment' => 'Comment',
            'status' => 'Status',
            'created' => 'Created',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);;
    }

    public static function generate($type, $message='', $custom=[]){
        //error_log($type.' - '.$message, 3, './error.log');        
        $alert = new self();
        $alert->type = $type;
        $alert->message = $message;
        $req = Yii::$app->request;
        $is_console = $req->isConsoleRequest;

        if (!$is_console) {            
            $alert->url = $req->hostInfo . $req->url;
            $alert->referrer = $req->referrer;

            if( !Yii::$app->user->isGuest )
                $alert->user_id = Yii::$app->user->id;

            if( !empty($_POST) )
                $custom['post'] = $_POST;
        } else {
            $c = Yii::$app->controller;
            $alert->url = $c ? $c->id . '/' . $c->action->id : 'NO CONTROLLER';                
        }
        
        $ex = new \Exception();
        $trace = $ex->getTrace();
        $custom0 = [];
        if (count($trace)>1) {
            $custom0 = [
                'file' => isset($trace[1]['file']) ? $trace[1]['file'] : '',
                'line' => isset($trace[1]['line']) ? $trace[1]['line'] : '',
                'trace' => $ex->getTraceAsString(),
                'is_console' => $is_console,
            ];
        }


        $alert->setCustomData( array_merge(self::getDefaultCustom(), $custom0, $custom) );
        $alert->save();
    }

    public static function getDefaultCustom()
    {
        return [
            'name' => null,
            'file' => null,
            'line' => null,
            'code' => null,
            'trace' => null,
            'post' => null,
        ];
    }

    public static function saveNewYiiException($ex)
    {   //var_dump($ex->getMessage()); die; // если исключ. от обычное от php а не от yii но сделать обработку
        $custom = [
            'name' => $ex instanceof \Exception ? get_class($ex) : 'Error',
            'file' => $ex->getFile(),
            'line' => $ex->getLine(),
            'code' => $ex->getCode(),
            'trace' => $ex->getTraceAsString(),
        ];
        self::generate(self::getAlertType($ex), $ex->getMessage(), $custom);
    }

    public static function getAlertType($ex)
    {
        $type = self::TYPE_NONFATAL_ERROR;

        if (ErrorException::isFatalError(['type'=>$ex->getCode()]))
            $type = self::TYPE_FATAL_ERROR;

        if ($ex instanceof \yii\db\Exception)
            $type = self::TYPE_EXCEPTION_DB;

        if ($ex instanceof \yii\web\HttpException){
            $type = self::TYPE_EXCEPTION_HTTP;
            if($ex->statusCode == 404)
                $type = self::TYPE_EXCEPTION_PAGE_NOT_FOUND;
        }

        return $type;
    }

    public static function getTypeLabels($type='')
    {
        $labels = [
            self::TYPE_FATAL_ERROR => 'Fatal error',
            self::TYPE_NONFATAL_ERROR => 'Nonfatal error',
            self::TYPE_EXCEPTION_HTTP => 'Http exception',
            self::TYPE_EXCEPTION_PAGE_NOT_FOUND => 'Page not found',
            self::TYPE_EXCEPTION_DB => 'DB exception',
            self::TYPE_ERROR_JS => 'JS error',
            self::TYPE_ERROR_CRON => 'Cron error',
            self::TYPE_ERROR_FEDERATED => 'Federated error',
            self::TYPE_API_PROBLEM => 'API problem',
            self::TYPE_SITE_ERROR => 'Site error',
            self::TYPE_SITE_CRITICAL_ERROR => 'Site critical error'
        ];
        if($type) {
            if (isset($labels[$type])) {
                return $labels[$type];
            } else {
                return "Undefined #{$type}";
            }
        }
            
        return $labels;
    }

    public function getTypeLabel()
    {
        return $this->type . ' - ' . self::getTypeLabels($this->type);
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_DONE => 'Done',
            self::STATUS_SYSTEM_DONE => 'Auto Done'
        ];
    }

    public function getUserInfo()
    {
        if ($this->user_id)
            return $this->user->email;
        else
            return 'guest';
    }

    public function getName()
    {
        return $this->getCustomData('name');
    }

    public function getFile()
    {
        return $this->getCustomData('file');
    }

    public function getLine()
    {
        return $this->getCustomData('line');
    }

    public function getTrace()
    {
        return $this->getCustomData('trace');
    }

    public function getPost()
    {
        return $this->getCustomData('post');
    }
    
    public function isConsole()
    {
        return $this->custom_is_console;
    }
    
    public static function handleUnimportant()
    {
        $border_date0 = date(SQL_FULL_DATE, strtotime('-2 hours'));
        
        $cond = "created < :border_date AND `status` = :status "
            . "AND (url = 'mws-import/import-prices' OR url = 'mws-import/import-bsrs' OR url = 'mws-import/import-categories') " 
            . "AND ( "
            . "message LIKE 'Empty reply from server%' "
            . "OR message LIKE '_importAlienPrice: Empty reply from server%' "
            . "OR message LIKE 'OpenSSL SSL_connect: SSL_ERROR_SYSCALL in connection to stakenode777.com:443%' "
            . "OR message LIKE 'importAlienPrice: OpenSSL SSL_connect: SSL_ERROR_SYSCALL in connection to stakenode777.com:443%' "
            . "OR message LIKE 'Failed to connect to stakenode777.com port 443: Connection refused%' "
            . ") ";
        
        $attrs = ['status' => self::STATUS_SYSTEM_DONE];
        self::updateAll($attrs, $cond, ['border_date' => $border_date0, 'status' => self::STATUS_NEW]);
        
        $border_date1 = date(SQL_FULL_DATE, strtotime('-8 hours'));
        
        $cond = "(user_id = 0 OR user_id IS NULL) AND created < :border_date  AND `status` = :status " 
            . "AND message LIKE 'Page not found.'";
        
        self::updateAll($attrs, $cond, ['border_date' => $border_date1, 'status' => self::STATUS_NEW]);   
               
        //remove site alerts which have more than 6 months old
        //if you really need older ones use backup
        $border_date_remove = date(SQL_FULL_DATE, strtotime('-6 months'));
        
        $cond = "created < :border_date AND `status` = :status ";
        self::deleteAll($cond, ['border_date' => $border_date_remove, 'status' => self::STATUS_SYSTEM_DONE]);        
    }

}
