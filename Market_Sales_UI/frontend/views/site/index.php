<?php
/* @var $this yii\web\View */

$this->title = 'Home';

use yii\helpers\Html;

if (!empty($_GET['_msg'])) {
    \raoul2000\widget\pnotify\PNotify::registerStack(
            [
        'stack_top_left' => [
            'dir1' => 'left',
            'dir2' => 'left',
            'push' => 'top'
        ]
            ], $this
    );

// display a notification using the "stack_top_left" stack.
// Note that you must use yii\web\JsExpression for the "stack" plugin option value. 

    \raoul2000\widget\pnotify\PNotify::widget([
        'pluginOptions' => [
            'title' => 'INFORMATION',
            'text' => $_GET['_msg'],
            'type' => 'success',
            'delay' => '22000',
            'stack' => new yii\web\JsExpression('stack_top_left'),
            'addclass' => 'stack-topright',
            'width' => '500px',
            'animation' => 'fade',
            'desktop' => [
                'desktop' => true
            ],
            'buttons' => [
                'closer_hover' => false
            ]
        ]
    ]);
}
?>




<div class="body-content" >
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-10"><p>Welcome to Market Sales. <i>A Mobile based trading platform</i> </p></div>

                <div class="col-lg-4">
                    <div class="col-md-4">
                        <a>
                            <?= Html::img('@web/img/airtelmoney.jpeg', ['style' => 'width:75px;', "class" => "img-responsive"]); ?>
                        </a>

                    </div>
                    <div class="col-md-4">
                        <a>
                            <?= Html::img('@web/img/zamtelkwacha.png', ['style' => 'width:75px;height:75px;', "class" => "img-responsive"]); ?>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a>
                            <?= Html::img('@web/img/mtnmoeny.jpeg', ['style' => 'width:90px;height:75px;', "class" => "img-responsive"]); ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <div class="row">
                <div class="divider">&nbsp;</div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-4">
            <section class="panel panel-featured-left panel-featured-quartenary">
                <div class="panel-body">
                    <?= Html::a('<div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-quartenary">
                                    <i class="fa fa-bus"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Buy Bus Ticket</h4>
                                </div>
                            </div>', ['#'], ['class' => 'widget-summary']);
                    ?>

                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel panel-featured-left panel-featured-tertiary">
                <div class="summary-icon bg-"></div>
                <div class="panel-body">
                    <?= Html::a('<div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-tertiary">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Order Goods</h4>
                                </div>
                            </div>', ['site/order-goods'], ['class' => 'widget-summary']);
                    ?>
                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel panel-featured-left panel-featured-primary">
                <div class="panel-body">
                    <?= Html::a('<div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Make a Sale</h4>
                                </div>
                            </div>', ['site/sale'], ['class' => 'widget-summary']);
                    ?>
                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel panel-featured-left panel-featured-secondary">
                <div class="panel-body">
                    <?= Html::a('<div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-secondary">
                                    <i class="fa fa-usd"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Check Balance</h4>
                                </div>
                            </div>', ['#'], ['class' => 'widget-summary']);
                    ?>
                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel panel-featured-left panel-featured-primary">
                <div class="panel-body">
                    <?= Html::a('<div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-success">
                                    <i class="fa fa-files-o"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">My Transactions</h4>
                                </div>
                            </div>', ['create'], ['class' => 'widget-summary']);
                    ?>
                </div>
            </section>
        </div>
        <div class="col-lg-4"></div>
    </div>

</div>

