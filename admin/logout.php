<?php

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
session_start();
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        require_once $_SERVER["DOCUMENT_ROOT"]. '/helper/Context.php';
        require_once $_SERVER["DOCUMENT_ROOT"]. '/helper/Utils.php';
        
        Context::destroy();
        Utils::RedirectTo('login.php');
        ?>
    </body>
</html>
