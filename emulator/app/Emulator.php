<?php
/*
 * @author Francis Chulu <chulu1francis@gmail.com>
 * @since 2015
 * Main app file
 */
ini_set('error_log', 'error.log'); //Error log for general php (application) errors.
require_once __DIR__ . '/../vendor/autoload.php';

use App\Lib;
use App\Lib\Logger\Emulogger;
use App\Lib\Config;

$request_ussd_body = "";
$response_ussd_body = "";
session_start();



if (isset($_POST['send'])) {
    //MNO is Airtel
    if ($_SESSION['mno'] == \App\Lib\Config::mno_airtel) {
        $url = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-airtel");
        $result = Lib\SharedUtils::httpPost(Lib\SharedUtils::buildAirtelRequest($_POST['input'], $_SESSION['session_id'], $_SESSION['mobile']), $url, "POST");
        $decode_result = xmlrpc_decode($result);
        $response_ussd_body = $decode_result['USSD_BODY'];
    } else if ($_SESSION['mno'] == \App\Lib\Config::mno_mtn) {
        //MNO is mtn 
        $url = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-mtn");
        $response_ussd_body = Lib\SharedUtils::httpPost(Lib\SharedUtils::buildMTNRequest($_POST['input'], $_SESSION['session_id'], $_SESSION['mobile']), $url, "POST");
        //  $response_ussd_body = $result['USSD_BODY'];
    } else {
        //MNO is Zamtel
        $url1 = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-zamtel");
        $url = Lib\SharedUtils::buildZamtelRequest($url1, "*".$_SESSION['shortcode']."*".$_POST['input'], $_SESSION['session_id'], $_SESSION['mobile'], 2, $_SESSION['shortcode']);
        $result = Lib\SharedUtils::httpPost("", $url, "GET");
        $response_arr = explode("&", $result);
        $response_ussd_body = "System is currently busy. Please try again later!";
        if (!empty($response_arr)) {
            $response_ussd_body = Lib\SharedUtils::getZamtelUSSDBody($response_arr);
        }
    }
} else if (isset($_POST['cancel'])) {
    unset($_SESSION);
    Lib\SharedUtils::redirect("../index.php");
    exit;
} else {
    $_SESSION['session_id'] = Lib\SharedUtils::generateSessionID(6);
    //MNO is Airtel
    if ($_SESSION['mno'] == \App\Lib\Config::mno_airtel) {
        $url = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-airtel");
        if (!empty($url)) {
            $result = Lib\SharedUtils::httpPost(Lib\SharedUtils::buildAirtelRequest($request_ussd_body, $_SESSION['session_id'], $_SESSION['mobile']), $url, "POST");
            $decode_result = xmlrpc_decode($result);
            $response_ussd_body = $decode_result['USSD_BODY'];
        } else {
            $response_ussd_body = "Warning!! Short code " . $_SESSION['shortcode'] . " is not configured on the emulator!";
        }
    } else if ($_SESSION['mno'] == \App\Lib\Config::mno_mtn) {
        //MNO is mtn 
        $url = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-mtn");
        $response_ussd_body = Lib\SharedUtils::httpPost(Lib\SharedUtils::buildMTNRequest($request_ussd_body, $_SESSION['session_id'], $_SESSION['mobile']), $url, "POST");
    } else {
        //MNO is Zamtel
        $url = Lib\SharedUtils::getURL($_SESSION['shortcode'] . "-zamtel");
        $url = Lib\SharedUtils::buildZamtelRequest($url, $request_ussd_body, $_SESSION['session_id'], $_SESSION['mobile'], 1, $_SESSION['shortcode']);
        $result = Lib\SharedUtils::httpPost("", $url, "GET");
        $response_arr = explode("&", $result);
        $response_ussd_body = "System is currently busy. Please try again later!";
        if (!empty($response_arr)) {
            $response_ussd_body = Lib\SharedUtils::getZamtelUSSDBody($response_arr);
        }
    }
}
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <title><?= Config::title ?></title>
        <!--Latest compiled and minified CSS -->
        <link rel="icon" type="image/png" sizes="96x96" href="../images/favicon.png">
        <link rel = "stylesheet" href = "../assets/bootstrap.min.css" >
        <!--Optional theme -->
        <link rel = "stylesheet" href = "../assets/bootstrap-theme.min.css" >
        <script src = "../assets/jquery.min.js"></script>
    </head>

    <body >
        <div class="container" >
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
                    <div class="panel" style="height: 630px;background-image: url('../images/bg_img.png');background-repeat: no-repeat;">
                        <div class="panel-body">
                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="padding-top: 77px;padding-left: 81px;">
                                <div class="form-group">
                                    <textarea readonly style="width: inherit;" class="form-control"  name="" rows="12" cols="27"><?php echo $response_ussd_body; ?></textarea>
                                </div>
                                <div >&nbsp;</div>
                                <div style="margin-left: 0px;" class="col-md-12 form-group form-inline">
                                    <input size="12" style="width: 225px;margin-left: 0px;" type="text" name="input" class="form-control" placeholder="Enter input here">
                                </div>
                                <div style="margin-left: 0px;" class="col-md-12 form-group form-inline">
                                    <button style="margin-right: 30px; width:100px;" name="send" class="btn btn-raised btn-sm btn-success" type="submit">Send</button>
                                    <button style="margin-right: 30px; width:89px;" name="cancel" class="btn btn-raised btn-sm btn-danger" type="submit">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>
