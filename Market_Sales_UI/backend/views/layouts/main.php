<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$session = Yii::$app->session;
$name = $session['user'];
AppAsset::register($this);
//$model = User::findOne(['user_id' => Yii::$app->user->identity->user_id]);
$image = \backend\models\Image::findOne(['user_id' => Yii::$app->user->identity->user_id]);
$pic_name = "";
if (!empty($image->file)) {
    $pic_name = $image->file;
}

$this->registerJs("
    $(function () {
        $('[data-toggle=\"tooltip\"]').tooltip();
    });
", $this::POS_END, 'tooltips');

$this->beginPage()
?>
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
        <section class="body">

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

                <!-- start: search & user box -->
                <div class="header-right">
                    <div id="userbox" class="userbox">
                        <a href="#" data-toggle="dropdown">
                            <figure class="profile-picture">
                                <?= Html::img('@web/uploads/profile/' . $pic_name, ['class' => 'img-circle']); ?>
                            </figure>
                            <div class="profile-info">
                                <span class="name"><?php echo backend\models\User::getUsernameById(Yii::$app->user->identity->id); ?></span>
                                <span class="role"><?php echo Yii::$app->getUser()->identity->role->name; ?></span>
                            </div>

                            <i class="fa custom-caret"></i>
                        </a>

                        <div class="dropdown-menu">
                            <ul class="list-unstyled">
                                <li class="divider"></li>
                                <li>
                                    <?= Html::a('<i class="fa fa-user"></i> My Profile', ['user/profile', 'id' => Yii::$app->user->identity->id], []); ?>
                                </li>
                                <li>
                                    <?= Html::a('<i class="fa fa-refresh"></i> Change Password', ['site/change-password'], []) ?>
                                </li>
                                <li>
                                <li>
                                    <a class="menuitem mb-xs mt-xs mr-xs modal-basic" href="#modalCenterIcon">
                                        <i class="fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end: search & user box -->
            </header>
            <!-- end: header -->

            <div class="inner-wrapper">
                <!-- start: sidebar -->
                <aside id="sidebar-left" class="sidebar-left">

                    <div class="sidebar-header">
                        <div class="sidebar-title">
                            Navigation
                        </div>
                        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                        </div>
                    </div>

                    <div class="nano">
                        <div class="nano-content">
                            <nav id="menu" class="nav-main" role="navigation">
                                <ul class="nav nav-main">
                                    <?php
                                    if (Yii::$app->controller->id == "site") {
                                        echo '<li class="nav-active">';
                                    } else {
                                        echo '<li>';
                                    }
                                    ?>
                                    <a href="<?= Url::toRoute(['site/home']) ?>">
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                        <span>Dashboard</span>
                                    </a>
                                    </li>
                                    <!-------------------------------TRADERS MANAGEMENT STARTS----------------------->
                                    <?php
                                    if (User::userIsAllowedTo("Manage traders") || User::userIsAllowedTo("View traders") ||
                                            User::userIsAllowedTo("View Trader sales")) {//||
                                        // User::userIsAllowedTo("Manage product categories") || User::userIsAllowedTo("View product categories")) {
                                        if (Yii::$app->controller->id == "traders" ||
                                                Yii::$app->controller->id == "sales"
                                        ) {
                                            echo ' <li class="nav-parent nav-expanded nav-active">';
                                        } else {
                                            echo ' <li class="nav-parent">';
                                        }
                                        ?>
                                        <a>
                                            <i class="fa fa-usd" aria-hidden="true"></i>
                                            <span>Trader Management</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <?php
                                            if (User::userIsAllowedTo("Manage traders") || User::userIsAllowedTo("View traders")) {
                                                if (Yii::$app->controller->id == "traders" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Traders', ['traders/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Traders', ['traders/index'], []) . '</li>';
                                                }
                                            }
                                            if (User::userIsAllowedTo("View Trader sales")) {
                                                if (Yii::$app->controller->id == "sales" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Sales', ['sales/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Sales', ['sales/index'], []) . '</li>';
                                                }
                                            }
                                            /*
                                              if (User::userIsAllowedTo("Manage token redemptions") || User::userIsAllowedTo("View token redemptions")) {
                                              if (Yii::$app->controller->id == "token-redemption" &&
                                              (Yii::$app->controller->action->id == "index" ||
                                              Yii::$app->controller->action->id == "view" ||
                                              Yii::$app->controller->action->id == "create" ||
                                              Yii::$app->controller->action->id == "update")) {
                                              echo '<li class="nav-active">' . Html::a('Token Redemption', ['token-redemption/index'], []) . '</li>';
                                              } else {
                                              echo '<li>' . Html::a('Token Redemption', ['token-redemption/index'], []) . '</li>';
                                              }
                                              } */
                                            ?>

                                        </ul>
                                        </li>
                                    <?php } ?>
                                    <!-------------------------------TRADERS MANAGEMENT ENDS----------------------->
                                    <!-------------------------------PRODUCT MANAGEMENT STARTS----------------------->
                                    <?php
                                    /* if (User::userIsAllowedTo("Manage products") || User::userIsAllowedTo("View products") ||
                                      User::userIsAllowedTo("Manage product measures") || User::userIsAllowedTo("View product measures") ||
                                      User::userIsAllowedTo("Manage product categories") || User::userIsAllowedTo("View product categories")) {
                                      if (Yii::$app->controller->id == "products" ||
                                      Yii::$app->controller->id == "product-measures" ||
                                      Yii::$app->controller->id == "product-categories" ||
                                      Yii::$app->controller->id == "marketeer-products"
                                      ) {
                                      echo ' <li class="nav-parent nav-expanded nav-active">';
                                      } else {
                                      echo ' <li class="nav-parent">';
                                      }

                                      echo '<a>
                                      <i class="fa fa-product-hunt" aria-hidden="true"></i>
                                      <span>Product Management</span>
                                      </a>
                                      <ul class="nav nav-children">';

                                      if (User::userIsAllowedTo("Manage product measures") || User::userIsAllowedTo("View product measures")) {
                                      if (Yii::$app->controller->id == "product-measures" &&
                                      (Yii::$app->controller->action->id == "index" ||
                                      Yii::$app->controller->action->id == "view" ||
                                      Yii::$app->controller->action->id == "create" ||
                                      Yii::$app->controller->action->id == "update")) {
                                      echo '<li class="nav-active">' . Html::a('Product Measures', ['product-measures/index'], []) . '</li>';
                                      } else {
                                      echo '<li>' . Html::a('Product Measures', ['product-measures/index'], []) . '</li>';
                                      }
                                      }
                                      if (User::userIsAllowedTo("Manage product categories") || User::userIsAllowedTo("View product categories")) {
                                      if (Yii::$app->controller->id == "product-categories" &&
                                      (Yii::$app->controller->action->id == "index" ||
                                      Yii::$app->controller->action->id == "view" ||
                                      Yii::$app->controller->action->id == "create" ||
                                      Yii::$app->controller->action->id == "update")) {
                                      echo '<li class="nav-active">' . Html::a('Product Category', ['product-categories/index'], []) . '</li>';
                                      } else {
                                      echo '<li>' . Html::a('Product Category', ['product-categories/index'], []) . '</li>';
                                      }
                                      }
                                      if (User::userIsAllowedTo("Manage products") || User::userIsAllowedTo("View products")) {
                                      if (Yii::$app->controller->id == "products" &&
                                      (Yii::$app->controller->action->id == "index" ||
                                      Yii::$app->controller->action->id == "view" ||
                                      Yii::$app->controller->action->id == "create" ||
                                      Yii::$app->controller->action->id == "update")) {
                                      echo '<li class="nav-active">' . Html::a('Products', ['products/index'], []) . '</li>';
                                      } else {
                                      echo '<li>' . Html::a('Products', ['products/index'], []) . '</li>';
                                      }
                                      }
                                      if (User::userIsAllowedTo("Manage marketeer products") || User::userIsAllowedTo("View marketeer products")) {
                                      if (Yii::$app->controller->id == "marketeer-products" &&
                                      (Yii::$app->controller->action->id == "index" ||
                                      Yii::$app->controller->action->id == "view" ||
                                      Yii::$app->controller->action->id == "create" ||
                                      Yii::$app->controller->action->id == "update")) {
                                      echo '<li class="nav-active">' . Html::a('Marketeer Products', ['marketeer-products/index'], []) . '</li>';
                                      } else {
                                      echo '<li>' . Html::a('Marketeer Products', ['marketeer-products/index'], []) . '</li>';
                                      }
                                      }


                                      echo '</ul>
                                      </li>'; */
                                    ?>
                                    <!-------------------------------PRODUCT MANAGEMENT ENDS----------------------->
                                    <!-------------------------------USER MANAGEMENT STARTS----------------------->
                                    <?php
                                    if (User::userIsAllowedTo("Manage Users") || User::userIsAllowedTo("View Users") ||
                                            User::userIsAllowedTo("Manage Roles") || User::userIsAllowedTo("View Roles")) {
                                        if (Yii::$app->controller->id == "user" ||
                                                Yii::$app->controller->id == "roles" ||
                                                Yii::$app->controller->id == "permissions" ||
                                                Yii::$app->controller->id == "roletouser"
                                        ) {
                                            echo ' <li class="nav-parent nav-expanded nav-active">';
                                        } else {
                                            echo ' <li class="nav-parent">';
                                        }
                                        ?>
                                        <a>
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                            <span>User Management</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <?php
                                            if (Yii::$app->controller->id == "user" &&
                                                    (Yii::$app->controller->action->id == "profile")) {
                                                echo '<li class="nav-active">' . Html::a('My profile', ['user/profile', 'id' => Yii::$app->user->identity->id], []) . '</li>';
                                            } else {
                                                echo '<li>' . Html::a('My profile', ['user/profile', 'id' => Yii::$app->user->identity->id], []) . '</li>';
                                            }

                                            if (User::userIsAllowedTo("Manage Roles") || User::userIsAllowedTo("View Roles")) {
                                                if (Yii::$app->controller->id == "roles" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('User roles', ['roles/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('User roles', ['roles/index'], []) . '</li>';
                                                }
                                            }
                                            if (User::userIsAllowedTo("Manage Users") || User::userIsAllowedTo("View Users")) {
                                                if (Yii::$app->controller->id == "user" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Users', ['user/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Users', ['user/index'], []) . '</li>';
                                                }
                                            }
                                            if (User::userIsAllowedTo("Manage permissions") || User::userIsAllowedTo("View permissions")) {
                                                if (Yii::$app->controller->id == "permissions" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Permissions', ['permissions/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Permissions', ['permissions/index'], []) . '</li>';
                                                }
                                            }
                                            ?>

                                        </ul>
                                        </li>
                                    <?php } ?>
                                    <!-------------------------------USER MANAGEMENT ENDS----------------------->
                                    <!-------------------------------CONFIGS STARTS----------------------->
                                    <?php
                                    if (User::userIsAllowedTo("Manage market charges") || User::userIsAllowedTo("View market charges") ||
                                            User::userIsAllowedTo("Manage market nofications") || User::userIsAllowedTo("View market notifications")) {
                                        if (Yii::$app->controller->id == "market-charges" ||
                                                // Yii::$app->controller->id == "roles" ||
                                                //Yii::$app->controller->id == "permissions" ||
                                                Yii::$app->controller->id == "market-notifications"
                                        ) {
                                            echo ' <li class="nav-parent nav-expanded nav-active">';
                                        } else {
                                            echo ' <li class="nav-parent">';
                                        }
                                        ?>
                                        <a>
                                            <i class="fa fa-cogs" aria-hidden="true"></i>
                                            <span>Configs</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <?php
                                            if (User::userIsAllowedTo("Manage market charges") || User::userIsAllowedTo("View market charges")) {
                                                if (Yii::$app->controller->id == "market-charges" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Market Charges', ['market-charges/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Market Charges', ['market-charges/index'], []) . '</li>';
                                                }
                                            }
                                            if (User::userIsAllowedTo("Manage market nofications") || User::userIsAllowedTo("View market notifications")) {
                                                if (Yii::$app->controller->id == "market-notifications" &&
                                                        (Yii::$app->controller->action->id == "index" ||
                                                        Yii::$app->controller->action->id == "view" ||
                                                        Yii::$app->controller->action->id == "create" ||
                                                        Yii::$app->controller->action->id == "update")) {
                                                    echo '<li class="nav-active">' . Html::a('Market Notifications', ['market-notifications/index'], []) . '</li>';
                                                } else {
                                                    echo '<li>' . Html::a('Market Notifications', ['market-notifications/index'], []) . '</li>';
                                                }
                                            }
                                            ?>

                                        </ul>
                                        </li>
                                    <?php } ?>
                                    <!-------------------------------CONFIGS ENDS----------------------->
                                </ul>
                            </nav>


                        </div>

                    </div>

                </aside>
                <!-- end: sidebar -->

                <section role="main" class="content-body">
                    <header class="page-header">
                        <h2><?= Html::encode($this->title) ?></h2>
                    </header>
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => ['label' => 'Home',
                            'url' => Yii::$app->getHomeUrl() . 'site/home'],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                    <?= $content ?>

                </section>
        </section>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2019 - NAPSA. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
        <!-- Logout Modal-->

        <div id="modalCenterIcon" class="modal-block modal-block-primary mfp-hide" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <section class="panel">
                <div class="panel-body text-center">
                    <div class="modal-wrapper">
                        <div class="modal-icon center">
                            <i class="fa fa-question-circle"></i>
                        </div>
                        <div class="modal-text">
                            <h4>Confirm Logout?</h4>
                            <p>Are you sure you want to end your current session?</p>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?=
                            Html::a('<span>Yes</span>', ['site/logout'], ['data' => ['method' => 'POST'], 'id' => 'logout',
                                'class' => 'btn btn-warning btn-sm'])
                            ?>
                            <button class="btn btn-default btn-sm modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
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
                    offset: 70,
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
                    offset: 70,
                    allow_dismiss: true,
                    newest_on_top: true,
                    timer: 5000,
                    placement: {from: 'top', align: 'right'}
                });
            }
        </script>
    </body>
</html>
<?php $this->endPage() ?>