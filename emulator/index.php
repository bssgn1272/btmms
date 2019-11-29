<?php
/*
 * @author Francis Chulu <chulu1francis@gmail.com>
 * @since 2015
 * Index file
 */

//Load the classes in advance
require_once __DIR__ . '/vendor/autoload.php';

use App\Lib\Config;
use App\Lib\Logger\Emulogger;
use App\Lib;

if (isset($_POST['dial'])) {
    //Lets check that the number belongs to the selected network
    if (!empty($_POST['mobile'])) {
        if (Lib\SharedUtils::validateMsisdn($_POST['mobile'], $_POST['mno'])) {
            //All is ok :-
            //Lets keep our variables in the session
            session_start();
            $_SESSION['mno'] = $_POST['mno'];
            $_SESSION['mobile'] = $_POST['mobile'];
            $_SESSION['shortcode'] = $_POST['shortcode'];
            Lib\SharedUtils::redirect("app/Emulator.php");
        } else {
            //Well! mismatch --
            if (Config::mno_zamtel == $_POST['mno']) {
                $error_msg = "Mobile number " . $_POST['mobile'] . " is not a " . $_POST['mno'] . " number!";
            } else {
                $error_msg = "Mobile number " . $_POST['mobile'] . " is not an " . $_POST['mno'] . " number!";
            }
        }
    } else {
        $error_msg = "Mobile number cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Config::title ?></title>
        <link rel="icon" type="image/png" sizes="96x96" href="images/favicon.png">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="assets/bootstrap.min.css" >
        <!-- Optional theme -->

        <link rel="stylesheet" href="assets/bootstrap-theme.min.css" >
        <script src="assets/jquery.min.js"></script>
    </head>

    <body >
        <div class="container" >
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
                    <div class="panel" style="height: 630px;background-image: url('images/bg_img.png');background-repeat: no-repeat;">
                        <div class="panel-body">
                            <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>" style="padding-top: 77px;padding-left: 81px;">
                                <div class="form-group">
                                    <textarea readonly style="width: inherit;" class="form-control"  name="input" rows="10" cols="27"><?php
                                        if (!empty($error_msg)) {
                                            echo $error_msg;
                                        } else {
                                            echo Config::default_index_msg;
                                        }
                                        ?></textarea>
                                </div>
                                <div style="margin-left: -20px;" class="col-md-12 form-group form-inline">
                                    <label class="col-sm-3 col-form-label"  for="mno">Number</label>
                                    <input size="12" style="width: 180px;margin-left: -20px;" type="text" required="required" name="mobile" required class="form-control" placeholder="i.e 2609xxxxxxxxx">
                                </div>
                                <div style="margin-left: -20px;" class="col-md-12 form-group form-inline">
                                    <label class="col-sm-3 col-form-label"  for="mno">Network</label>
                                    <select style="width: 180px;margin-left: -20px;" class="form-control" id="mno" name="mno">
                                        <option value="Airtel">Airtel</option>
                                        <option value="MTN">MTN</option>
                                        <option value="Zamtel">Zamtel</option>
                                    </select> 
                                </div>
                                <div style="margin-left: -20px;" class="col-md-12 form-group form-inline">
                                    <label class="col-sm-4 col-form-label"  for="mno">short code</label>
                                    <select style="width: 165px;margin-left: -40px;" class="form-control" id="mno" name="shortcode">
                                        <option value="911">911</option>
                                    </select> 
                                </div>
                                <div style="margin-left: 0px;" class="col-md-12 form-group form-inline">
                                    <button name="dial" style="width:57%;" class="btn btn-raised btn-sm btn-success" type="submit">Dial</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>