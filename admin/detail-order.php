<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/entities/Order.php';

    $id = $_GET['id'];

    $list = Order::loadOrderDetail($id);
    echo json_encode($list, JSON_UNESCAPED_UNICODE);
?>