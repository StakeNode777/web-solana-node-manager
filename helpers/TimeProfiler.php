<?php

namespace app\helpers;

class TimeProfiler {
    
    protected static $_marks = array();
    protected static $_label_counter = array();
    protected static $_disabled = 0;
    
    public static function mark($label)
    {
        if (self::$_disabled) return;
        for(;isset(self::$_marks[$label]);){
            if (!isset(self::$_label_counter[$label])) {
                self::$_label_counter[$label] = 1;
            }
            $num = ++self::$_label_counter[$label];
            $label .= "[$num]"; 
        }
        
        self::$_marks[$label] = microtime(true);
    }
    
    public static function disable()
    {
        self::$_disabled = 1;
    }    
    
    public static function reset()
    {
        self::$_marks = array();
    }
    
    public static function results()
    {
        $start_time = current(self::$_marks);
        $results = array();
        foreach(self::$_marks as $key => $value){
            $results[$key] = sprintf("%10.5f", ($value - $start_time));
        }
        return $results;
    } 
    
    public static function html_results()
    {
        $results = self::results();
        $output = "<table>";
        foreach($results as $key => $value) {
            $output .= "<tr><td>$key</td><td>$value</td></tr>";
        }        
        $output .= "</table>";
        return $output;
    }
    
}

