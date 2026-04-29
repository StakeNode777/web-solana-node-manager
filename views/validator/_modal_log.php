<?php
use yii\grid\GridView;
?>


<?php
        echo GridView::widget([
            'dataProvider' => $logProvider,
            'showHeader' => false, // hide column titles
            'summary' => false,
            'columns' => [
                [
                    'value' => function ($row) {
                        return $row; // row is just a string
                    },
                ],
            ],
        ]);
?>

<!-- Custom CSS -->
<?php $this->registerCss('
    .modal-log-container {
        font-size: 12px;
        font-family: "Courier New", Courier, monospace;
        background-color: #f8f9fa;
    }

    .modal-log-container td {
        padding: .2rem;
    }
'); ?>

