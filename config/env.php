<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function authConfig(){
    return [
        'class' => 'app\components\PhpAuthManager',
        'itemFile' => '@app/rbac/items.php',
        'assignmentFile' => '@app/rbac/assignments.php',
        'ruleFile' => '@app/rbac/rules.php'
    ];
}

function envBool(string $name, bool $default = false): bool
{
    $value = getenv($name);

    if ($value === '') {
        return $default;
    }

    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
           ?? $default;
}
