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
require_once './entities/reset.php';
require_once './entities/User.php';
require_once './mail/PHPMailer.php';
require_once './mail/SMTP.php';
require_once './mail/Exception.php';
// require_once __DIR__ . '/vendor/autoload.php'; 

function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendEmailReset($email, $token, $iduser){
    /* GỬI EMAIL */
    //  Config Cấu hình email : GMail
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail -> CharSet = "UTF-8";

    $mail->IsSMTP(); //giao thức email: SMTP: Simple mail tranfer protocol
    // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";
    $mail->Host       = "smtp.gmail.com";      // SMTP server
    $mail->Port       = 587;                   // SMTP port
    $mail->Username   = "chamsockhachhangdtonline@gmail.com";  // username
    $mail->Password   = "cskhdtonline@112";            // password

    $mail->SetFrom('chamsockhachhangdtonline@gmail.com', 'Taka Garden');

    $mail->Subject    = "[Taka Garden] Khôi phục mật khẩu";


    $content = '<h4>Chào bạn </h4>';
    $content .= "Mã xác nhận của bạn là : <strong> $token </strong> <br>";
    $content .= "Vui lòng click vào <a href='http://taka.giaiphapvang.com.vn/token.php?id=$iduser'> Đây </a> để khôi phục mật khẩu";


    $emailUser = $email;

    $mail->MsgHTML($content);


    $mail->AddAddress($emailUser, "");

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {

    }
}

if (isset($_POST["btnReset"])) {
    $result = "";
    $uid = $_POST["txtTenDN"];
    $email = $_POST["txtEmail"];

    $u = new User(-1, $uid, $email, '', '', time(), 0);

    $loginRet = $u->checkUsernameEmail();
    // echo $loginRet;
    if ($loginRet) {
        $idUser = $u->getIdUser();
        $token = generateRandomString();
        // Save to DB
        $reset = new reset($idUser, $token);
        $check = $reset->checkId();
        if(!$check){
            $reset->add();
            sendEmailReset($email, $token, $idUser);
            $result = "Đã gửi mã xác nhận về email, vui lòng kiểm tra email để khôi phục mật khẩu";
        }
        else{
            $result = "Đã yêu cầu khôi phục trước đó, vui lòng kiểm tra lại email !";
        }
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
                                    <td width="15px">&nbsp;</td>
                                    <td width="120px">Tên đăng nhập:</td>
                                    <td width="200px"><input type="text" name="txtTenDN" id="txtTenDN"/></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Email:</td>
                                    <td><input type="text" name="txtEmail" id="txtEmail"/></td>
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
                                    <td><input name="btnReset" type="submit" class="blueButton" id="btnDangNhap"
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