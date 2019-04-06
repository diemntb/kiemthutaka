<?php
session_start();
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

if (!isset($_SESSION["IsLogin"])) {
    $_SESSION["IsLogin"] = 0; // chưa đăng nhập
}
require_once DOCUMENT_ROOT . '/entities/categories.php';
require_once DOCUMENT_ROOT . '/entities/classify.php';
require_once DOCUMENT_ROOT . '/helper/Utils.php';
require_once DOCUMENT_ROOT . '/entities/Products.php';
require_once DOCUMENT_ROOT . '/helper/CartProcessing.php';
require_once DOCUMENT_ROOT . '/helper/Context.php';

// đặt hàng
if (isset($_POST["txtMaSP"])) {
    $masp = $_POST["txtMaSP"];
    $solg = 1;
    CartProcessing::addItem($masp, $solg);
}

$categories = categories::loadAll();


?>
<?php
if (isset($_POST["btnSearch"])) {
    $value = str_replace("'", "", $_POST['txtSearch']);
    $value = str_replace("  ", "", $value);
    $value = str_replace(" ", "%", $value);

    $url = "search.php?nsx=" . $_POST['selectHSX'] . "&value=" . $value . "&gia=" . $_POST['selectGia'];
    Utils::RedirectTo($url);
}
?>
<?php
// Recovery
require_once  './entities/reset.php';
require_once  './entities/User.php';
if (isset($_POST["btnCheck"])) {
    $result = "";
    $token = $_POST["txtToken"];
    $idUser = $_GET["id"];

    $reset = new reset($idUser, $token);
    $rightToken = $reset->checkToken();

    if ($rightToken) { // Correct token
        //echo $reset->getExpireTime();
        // Check expire
        // $now = $reset->getNowTime();
        // $expire = $reset->getExpireTime();
        $url = "recovery.php?id=$idUser";
        Utils::RedirectTo($url);
        //echo $now." ".$expire;

        // if($expire > $now){
        //     // Valid expire and token
        //     $url = "recovery.php?id=$idUser";
        //     Utils::RedirectTo($url);
        // }
        // else{
        //     $result = "Quá 10 phút kể từ khi khởi tạo mã xác nhận. Vui lòng khởi tạo lại mã mới";
        //     $reset->deleteToken();
        // }

    } else {

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title> Taka Graden - Khôi phục mật khẩu </title>
    <meta charset="UTF-8">
    <meta name="keywords" content="html,htm5,web">
    <meta name="description" content="Do an web, home, trang chu">
    <link href="img/logog.png" rel="shourtcut icon"/>

    <!-- Style CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,700&subset=latin-ext" rel="stylesheet">

</head>
<body class="main">
<!-- Header -->
<?php include 'header.php'; ?>
<!-- /Header -->

<!-- Content -->
<div class="content">
    <div class="content-product senda">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="breadcrumbs">
                        <p>
                            <a href="index.php">Trang chủ</a>
                            <i class="fa fa-caret-right"></i>
                            <a href="#">Đăng nhập</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="main_body">
                        <form id="fr" name="fr" method="post" action="">
                            <h2 align="center">KHÔI PHỤC TÀI KHOẢN</h2>
                            <table id="tableDangNhap" cellpadding="2" cellspacing="0" style="margin-left: 320px;">
                                <span style="color:#F00; font-size:16px;padding-left: 219px;">
                                    <?php if (isset($result) != ""){
                                        echo $result;
                                        unset($result);
                                    }
                                    ?>
                                </span>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Mã xác nhận:</td>
                                    <td><input type="text" name="txtToken" id="txtToken"/></td>
                                    <td>&nbsp;</td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input name="btnCheck" type="submit" class="blueButton" id="btnCheck"
                                               value="Khôi phục"/></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="3"><span style="color: red"></span></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Content -->

<!-- Footer -->
<?php include 'footer.php'; ?>
<!-- /Footer -->

<!-- Backtotop -->
<div class="back-to-top"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
<!-- /Backtotop -->

<!-- Javascript -->
<!-- <script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.3.1.min.js"></script> -->
<script src="js/main-script.js"></script>
</body>
</html>