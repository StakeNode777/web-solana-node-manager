<?php

namespace app\commands;

use yii\console\Controller;
use app\models\UnsuccessfulSaveSsh;

class CleanupController extends Controller
{
    /**
     * Deletes records older than 2 months.
     * Usage: php yii cleanup/unsuccessful-ssh
     */
    public function actionUnsuccessfulSsh()
    {
        // Calculate threshold date
        $threshold = date('Y-m-d H:i:s', strtotime('-2 months'));

        $deleted = UnsuccessfulSaveSsh::deleteAll(['<', 'created_at', $threshold]);

        echo "Deleted {$deleted} outdated unsuccessful SSH save records.\n";

        return 0;
    }
}
