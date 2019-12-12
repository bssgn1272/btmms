<?php

/**
 * Created by PhpStorm.
 * User: Alinani
 * Date: 29/11/2018
 * Time: 15:00
 */
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en" class="fixed">
    <head>
        <?= Html::csrfMetaTags() ?>
        <!-- Basic -->
        <meta charset="UTF-8">
        <title><?= Html::encode($this->title . ' | ' . Yii::$app->name) ?></title>

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="icon" type="image/png" sizes="96x96" href="<?= Url::to('@web/img/favicon.png') ?>">
        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <?php $this->head() ?>

        <!-- Head Libs -->
        <script src="<?= Url::to('@web/vendor/modernizr/modernizr.js') ?>"></script>

    </head>
    <body>
        <?php $this->beginBody() ?>
        <!-- start: page -->
        <section class="body-sign">
            <!-- start: header -->
            <header class="header">
                <div class="logo-container">
                    <a href="../" class="logo">
                        <?= Html::img('@web/img/logo.png', ['style' => 'width:150px; height: 40px']); ?>
                    </a>
                    <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                        <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                    </div>
                </div>


                <!-- end: search & user box -->
            </header>

            <div class="center-sign">
                <?= $content ?>
                <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2019 - NAPSA. All rights reserved.</p>
            </div>

        </section>

        <!-- end: page -->
        <?php $this->endBody() ?>
        <script>
            var myArrSuccess = [<?php
        $flashMessage = Yii::$app->session->getFlash('success');
        if ($flashMessage) {
            echo '"' . $flashMessage . '",';
        }
        ?>];
            for (var i = 0; i < myArrSuccess.length; i++) {
                $.notify(myArrSuccess[i], {
                    type: 'success',
                    offset: 90,
                    allow_dismiss: true,
                    newest_on_top: true,
                    timer: 5000,
                    placement: {from: 'top', align: 'right'}
                });
            }
            var myArrError = [<?php
        $flashMessage = Yii::$app->session->getFlash('error');
        if ($flashMessage) {
            echo '"' . $flashMessage . '",';
        }
        ?>];
            for (var j = 0; j < myArrError.length; j++) {
                $.notify(myArrError[j], {
                    type: 'danger',
                    offset: 90,
                    allow_dismiss: true,
                    newest_on_top: true,
                    timer: 5000,
                    placement: {from: 'top', align: 'right'}
                });
            }
        </script>
        <?php
        if (strpos(Yii::$app->request->getUserAgent(), 'MSIE') ||
                strpos(Yii::$app->request->getUserAgent(), 'Trident/7.0')) {
            echo "<script>$('#welcomeModal').modal({backdrop: 'static', keyboard: false});</script>";
        }
        ?>
    </body>
</html>
<?php $this->endPage() ?>