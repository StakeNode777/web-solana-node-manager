<?php
use yii\helpers\Html;
?>

<table class="table table-sm table-hover table-info">
    <tbody>
        <tr>
            <th>msg</th>
            <td class="msg"><?= Html::encode($data->message) ?></td>
        </tr>
        <tr>
            <th>name</th>
            <td><?= $data->getCustomData('name') ?></td>
        </tr>
        <tr>
            <th>file</th>
            <td><?= $data->getCustomData('file') ?></td>
        </tr>
        <tr>
            <th>line</th>
            <td><?= $data->getCustomData('line') ?></td>
        </tr>
        <tr>
            <th>user</th>
            <td>
                <?= $data->userInfo ?>
            </td>
        </tr>
        <tr>
            <th><?=$data->isConsole() ? 'cmd' : 'url'?></th>
            <td><?=$data->isConsole() ? $data->url : Html::a($data->url, $data->url) ?></td>
        </tr>
        <tr>
            <th>ref</th>
            <td><?= Html::a($data->referrer, $data->referrer) ?></td>
        </tr>
    </tbody>
</table>