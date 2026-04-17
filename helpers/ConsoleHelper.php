<?php

namespace app\helpers;

class ConsoleHelper 
{
    /**
     * count running commands by name $cmd
     * you should run yii command by ConsoleHelper::runYiiCommand 
     * $cmd should not contain word 'yii'
     */
    public static function countCurYiiCommands($cmd)
    {
        exec("ps -ef | grep '$cmd'", $output);
        $k = 0;
        foreach($output as $el){
            //TODO change to if (strpos($el, 'yii') && strpos($el, 'php')===0) {
            //TODO because could be a case when it count sh -c php yii too
            if (strpos($el, 'yii')) { 
               $k++; 
            }
        }
        return $k;
    }  
    
    /**
     * run yii command from php
     * @param string $cmd - it should have next format <command>/<action> <extra_params>
     * for example: 'migration/create super_table_name'
     * @param type $async
     */
    public static function runYiiCommand($cmd, $async = true)
    {
        $yiipath = file_exists('./yii') ? 'yii' : '../yii';

        if (ENV_DEV_KOSTECMW) {
            if ($async) {
                $cmd = "php $yiipath $cmd";
                shell_exec($cmd);
            } else {
                return shell_exec("php $yiipath $cmd");
            }
        } else {
            if ($async) {
                $cmd = "nohup php $yiipath $cmd > /dev/null 2>&1 &";
                shell_exec($cmd);
            } else {
                return shell_exec("php $yiipath $cmd 2>&1");
            }
        }
    }

    public static function runYiiCommandAlt($cmd, $async = true)
    {
        $yiipath = file_exists('./yii') ? 'yii' : '../yii';

        if (ENV_DEV_KOSTECMW) {
            if ($async) {
                $cmd = "php $yiipath $cmd";
                shell_exec($cmd);
            } else {
                return shell_exec("php $yiipath $cmd");
            }
        } else {
            if ($async) {
                $cmd = "nohup php $yiipath $cmd >> seller.log &";
                shell_exec($cmd);
            } else {
                return shell_exec("php $yiipath $cmd 2>&1");
            }
        }
    }

    /*
     * Colored Echo
     *
     * additional colors
     * $yellow = "\x1b[1;33m";
     * $blue = "\x1b[1;34m";
     * */

    public static function echoRed($str)
    {
        $red="\x1b[1;31m";
        self::ln(self::colored($str, $red));
    }

    public static function echoGreen($str)
    {
        $green="\x1b[1;32m";
        self::ln(self::colored($str, $green));
    }

    public static function echoYellow($str)
    {
        $yellow="\x1b[1;33m";
        self::ln(self::colored($str, $yellow));
    }

    private static function colored($str, $color_esc)
    {
        $no_color = "\x1b[0m";
        return "{$color_esc} {$str} {$no_color}";
    }

    private static function ln($msg)
    {
        echo "$msg \n";
    }
}

