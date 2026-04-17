<?php

namespace app\helpers;

use Yii;
use SimpleXMLElement;
use yii\helpers\BaseUrl;

use DateTime;
use DateTimeZone;

class Utils
{
    public static function prettyConsolePrint($header, $line)
    {
        echo "\n";
        echo $header;
        echo "\n";
        echo $line;
        echo "\n";
    }

    /********
     *
     *  ALL following static methods are depricated and
     *  Moved to FS helper class
     *
     * */

    public static function getTmpDir()
    {
        $tmp = Yii::getAlias("@runtime/tmp");
        if (!is_dir($tmp)) mkdir($tmp);
        return $tmp;
    }

    /*
     * AmzCompanyFinder (ACF)
     */
    public static function getCasperACFScriptFile()
    {
        return self::getAmzScriptDir().'/acf_main.js';
    }

    public static function arrayToXml($root, $data)
    {
        $xml = new \app\models\ArrayToXml();
        return $xml->buildXML($data, $root);
    }

    public static function md5FromParams($params)
    {
        $queryParameters = array();
        foreach ($params as $key => $value) {
            $queryParameters[] = $key . '=' . $value;
        }
        return md5(implode('&', $queryParameters));
    }


    public static function getExceptionCode($exception)
    {
        if ($exception instanceof \yii\web\HttpException) {
            return $exception->statusCode;
        }
        return $exception->getCode();
    }

    public static function getExceptionName($exception)
    {
        if ($exception instanceof \yii\base\Exception) {
            $name = $exception->getName();
        } else {
            $name = 'Exception';
        }
        if ($code = self::getExceptionCode($exception)) {
            $name .= " (#$code)";
        }
        return $name;
    }

    public static function getExceptionMessage($exception)
    {
        if ($exception instanceof \yii\base\UserException) {
            return $exception->getMessage();
        }
        return 'An internal server error occurred.';
    }

}