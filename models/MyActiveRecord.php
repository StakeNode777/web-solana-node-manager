<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\BaseUrl;

/**
 *
 * This class extends ActiveRecord for better handling images.
 * Example of $_imageFields:
 *
 * class Profile extends MyActiveRecord{
 * 		protected $_imageFields = array(
 * 			'image'=>array(
 * 				'default' => 'images/nophoto.jpg',
 * 				'path' =>'images/bands',
 * 				'sizes' => array(
 * 							array('width'=>250, 'type' => 'jpg'),
 * 							array('prefix'=>'mini_', 'width'=>150, 'type' => 'jpg'),
 * 							array('prefix'=>'mini2_', 'height'=>350, 'type' => 'jpg', 'notIncrease'=>false),
 * 							array('prefix'=>'orig_') //but default type will be jpg
 * 				)
 *
 * 			 )
 * 		);
 *
 * 		public $imageDefault = "images/default/band.jpg";
 *
 * 		public function rules(){
 *      	return array(
 *          	array('image', 'file', 'types'=>'jpg, gif, png'),
 *      	);
 *   	}
 * }
 *
 * @author viking_zp@i.ua
 *
 * Maxim - CREATE: saveOrigFileName and prefix; 04.02.2019
 *
 */
class MyActiveRecord extends ActiveRecord
{
    public $oldAttributes = [];

    protected $_oldImages = array();
    protected $_imageFields = array();
    protected $_customData = null;

    /**
    * Sets custom data for objects with serializable custom data.
    *
    * @param string|array $arg1 can either be a key or a hash table
    * @param mixed $arg2 if present is the value of the key
    */
    public function setCustomData($arg1,$arg2=null)
    {
        if (is_null($this->_customData)) $this->getCustomData(); //init this field
        if ($this->_customData===false) return $this;
        if (is_array($arg1)) {
            $newData = $arg1;
        } else {
            $newData = array();
            if ($arg2 === null) {
                unset($this->_customData[$arg1]);
            } else {
                $newData = array($arg1 => $arg2);
            }
        }

        $this->_customData = array_merge($this->_customData, $newData);
        $serialized = serialize($this->_customData);
        if ($this->_getMaxCustomDataSize() && (strlen($serialized) > $this->_getMaxCustomDataSize())) {
            throw new AppExc_CodingError('Could not properly serialize custom data because of its size.');
        }
        $this->custom = $serialized;
        return $this;
    }

    /**
    * Returns the value of a field in custom data array
    *
    * @param string $key
    * @return mixed
    */
    public function getCustomData($key=null)
    {
        if (is_null($this->_customData)) {
            if (!isset($this->custom) || !$this->custom)
                $this->_customData = array();
            else
                $this->_customData = unserialize($this->custom);
        }

        if (!$key) return $this->_customData;
        if (!array_key_exists($key,$this->_customData)) {
             return null;
        }
        return $this->_customData[$key];
    }

    /**
     * PHP getter magic method.
     * This method is overridden so that attributes and related objects can be accessed like properties.
     *
     * @param string $name property name
     * @throws \yii\base\InvalidParamException if relation name is wrong
     * @return mixed property value
     * @see getAttribute()
     */
    public function __get($name)
    {
        if (strpos($name, 'custom_')===0 && !$this->hasAttribute($name)) {
            $cname = str_replace('custom_', '', $name);
            return $this->getCustomData($cname);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * PHP setter magic method.
     * This method is overridden so that AR attributes can be accessed like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name, $value)
    {
        if (strpos($name, 'custom_')===0 && !$this->hasAttribute($name)) {
            $cname = str_replace('custom_', '', $name);
            $this->setCustomData($cname, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * Sets a component property to be null.
     * This method overrides the parent implementation by clearing
     * the specified attribute value.
     * @param string $name the property name or the event name
     */
    public function __unset($name)
    {
        if ($this->hasAttribute($name) || array_key_exists($name, $this->_related) || $this->getRelation($name, false) === null) {
            parent::__unset($name);
        } elseif (strpos($name, 'custom_')===0 && !$this->hasAttribute($name)) {
            $this->setCustomData($name, null);
        } else {
            parent::__unset($name);
        }
    }

    protected function _getMaxCustomDataSize(){
        return 65536;
    }

    public function getImageFields() {
        return $this->_imageFields;
    }

    protected function _saveImages() {
        $newImages = array();
        foreach($this->_imageFields as $field => $value) {
            if( !isset($this->_oldImages[$field]) || $this->_oldImages[$field] != $this->$field ) {
                $newImages[$field] = $this->$field;
            }
        }
        foreach($newImages as $field => $filename) {
            if (!$filename) continue;
            if (!is_file($filename)) {
                $this->addError($field, $this->_errorFileMsg . " " . $filename);
                continue;
            }

            $this->_deleteOldImages();  // delete all old images after updating mainPhoto

            if( !$this->_imageFields[$field] ) {
                throw new CException("'$field' is not image field in class " . get_class($this));
            }
            $data = $this->_imageFields[$field];

            if( !isset($data['path']) ) {
                throw new CException("'savePath' for images field is not set for image field '$field' in class " . get_class($this));
            }
            $path = $data['path'];
            if( !isset($data['sizes']) && !count($data['sizes']) ) {
                throw new CException("'sizes' need to contains at least one size for image field '$field' in class " . get_class($this));
            }

            $picManager = PicManager::getInstance();
            $time = time();

            foreach ($data['sizes'] as $size) {
                $def = array('width' => 0, 'height' => 0, 'type' => 'jpg', 'notIncrease' => true, 'prefix' => '', 'postfix' => '');
                $size = array_merge($def, $size);

                if( !isset($this->_imageFields['mainPhoto']['saveOrigFileName']) ) $this->_imageFields['mainPhoto']['saveOrigFileName'] = false;

                if ($this->_imageFields['mainPhoto']['saveOrigFileName'])
                    $new_value = pathinfo($filename, PATHINFO_FILENAME);
                else
                    $new_value = $this->getOldPrimaryKey() . "_{$time}";

                if( !is_dir($path) ) {
                    mkdir($path, 0777, 1);
                }
                $newFilename = "$path/{$size['prefix']}" . $new_value . $size['postfix'] . '.' . $size['type'];
                $picManager->formatPicture($filename, $newFilename, $size['width'], $size['height'], $size['type'], $size['notIncrease']);
            }
            //echo "file = {$this->$field}\n";
            $this->_oldImages[$field] = $new_value . '.' . $size['type'];
            $this->setIsNewRecord(false);
            $this->updateAttributes(['mainPhoto' => $new_value . '.' . $size['type']]);
        }
    }

    // delete all old images after updating mainPhoto
    public function _deleteOldImages() {
        $path = $this->_imageFields['mainPhoto']['path'];
        if( isset($this->_oldImages['mainPhoto']) ) {  // && is_file($path."/".$this->_oldImages['mainPhoto']), а если нет именно картинки без префикса а осталные есть, то они не удалятся, пусть всегда проверяет все префиксы
            $sizes = $this->_imageFields['mainPhoto']['sizes'];
            foreach($sizes as $size) {
                $prefix = $postfix = '';
                if( isset($size['postfix']) ) $postfix = $size['postfix'];
                if( isset($size['prefix']) ) $prefix = $size['prefix'];
                $file = pathinfo($this->_oldImages['mainPhoto'], PATHINFO_FILENAME);
                $filename = $prefix . $file . $postfix . '.' . $size['type'];
                if( is_file($path . "/" . $filename) )
                    unlink($path . "/" . $filename);
            }
        }
    }

    public function getImagePath($imageField, $prefix = '', $postfix = '') {
        $filename = pathinfo($this->_oldImages['mainPhoto'], PATHINFO_FILENAME);
        $type = pathinfo($this->_oldImages['mainPhoto'], PATHINFO_EXTENSION);
        $defaultImage = $this->_imageFields[$imageField]['default'];
        if( $filename ) {
            return BaseUrl::base() . DIRECTORY_SEPARATOR . $this->_imageFields[$imageField]['path'] . DIRECTORY_SEPARATOR . $prefix . $filename . $postfix . '.' . $type;
        }
        else if( isset($defaultImage) && $defaultImage ) {
            return BaseUrl::base() . DIRECTORY_SEPARATOR . $defaultImage;
        }
    }

    //validator is used in the inheriting models for testing images
    public function isFile($attribute, $params) {
        if( !$this->$attribute ) {
            return;
        }
        if( isset($this->_oldImages[$attribute]) &&
            $this->$attribute == $this->_oldImages[$attribute] ) {
            return;
        }

        if( !is_file($this->$attribute) ) {
            $msg = isset($params['notFoundMsg']) ? $params['notFoundMsg'] : "File not found";
            $this->addError($attribute, $msg);
            return;
        }

        $filesize = filesize($this->$attribute);
        if( isset($params['maxSize']) && is_numeric($params['maxSize']) && $filesize > $params['maxSize'] ) {
            $msg = isset($params['tooLarge']) ? $params['tooLarge'] : "Big file size";
            $this->addError($attribute, $msg);
            return;
        }
        if( isset($params['minSize']) && is_numeric($params['minSize']) && $filesize < $params['minSize'] ) {
            $msg = isset($params['tooSmall']) ? $params['tooSmall'] : "Big file size";
            $this->addError($attribute, $msg);
            return;
        }

        if( isset($params['types']) ) {
            if( is_string($params['types']) )
                $types = preg_split('/[\s,]+/', strtolower($params['types']), -1, PREG_SPLIT_NO_EMPTY);
            else
                $types = $params['types'];

            if( !in_array(strtolower(pathinfo($this->$attribute, PATHINFO_EXTENSION)), $types) ) {
                $message = $params['wrongType'] !== null ? $params['wrongType'] : Yii::t('yii', 'Bad file "{file}". Only files with these extensions are allowed: {extensions}.');
                $this->addError($attribute, $message, array('{file}' => $this->$attribute, '{extensions}' => implode(', ', $types)));
            }
        }
    }

    //validator is used in the inheriting models for testing images
    public function isPicFile($attribute, $params) {
        if( !$this->$attribute ) {
            return;
        }
        if( isset($this->_oldImages[$attribute]) && $this->$attribute == $this->_oldImages[$attribute] ) {
            return;
        }

        $picManager = new PicManager;
        if( !$picManager->isPicFile($this->$attribute) ) {
            $msg = isset($params['message']) ? $params['message'] : "Bad Image File. Can be only types jpg,png,gif";
            $this->addError($attribute, $msg);
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->_saveImages();
    }

    public function afterFind() {
        parent::afterFind();
        $this->_oldImages = [];
        foreach($this->_imageFields as $field => $info){
            $this->_oldImages[$field] = $this->{$field};
        }
        $this->oldAttributes = (object) $this->attributes;
    }

    public function afterDelete() {
        parent::afterDelete();
        if( isset($this->mainPhoto) )
            $this->_deleteOldImages();
    }
    
    public static function findById($id)
    {
        $primaryKey = static::primaryKey();
        if (isset($primaryKey[0])) {       
            return self::findOne([$primaryKey[0] => $id]);
        } else {
            throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
        }
    }    
}