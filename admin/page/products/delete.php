<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/entities/Products.php';

if( isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] != '' ){
    $result = Products::delete($_GET['id']);
    
    header("Location: /admin/?act=products&type=index");die();
}
?>