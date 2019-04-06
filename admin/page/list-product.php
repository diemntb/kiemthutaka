<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . './entities/Products.php';
    //$opt = isset($_GET['opt'])?$_GET['opt']?:0;
    //$id = isset($_GET['id'])?$_GET['id']:0;

    $product = Products::loadProductsAll();
    //$productJSON = json_encode($product, JSON_UNESCAPED_UNICODE);
    echo 'a';
            

    // switch ($opt){
    //     case 1: // List
    //         //$product = Products::loadProductsAll();
    //         //$productJSON = json_encode($product, JSON_UNESCAPED_UNICODE);
    //         echo 'a';
    //         break;
    //     case 2: // Detail
    //         $detail = Products::loadProductByProId($id);
    //         echo json_encode($detail, JSON_UNESCAPED_UNICODE);
    //         break;
    // }
?>
