<?php
    define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define("SERVER1", "localhost");
    define("DB1", "quanlytaka");
    define("UID1", "root");
    define("PWD1", "");

    require_once DOCUMENT_ROOT.'/entities/Products.php';
    $opt = isset($_GET["opt"])?$_GET["opt"]:0;
    $id = isset($_GET["id"])?$_GET["id"]:0;

    function execQuery($sql)
    {
        $cn = mysqli_connect(SERVER1, UID1, PWD1, DB1) or die ('Không thể kết nối tới DataProviderMain');
        mysqli_set_charset($cn, 'UTF8');

        $result = mysqli_query($cn, $sql);
        if (!$result) {
            die ('Câu truy vấn bị sai');
        }

        return $result;
    }

    function loadProductsAll()
    {
        $ret = array();

        $sql = "SELECT * FROM products p, categories c where p.CatID = c.CatId";
        $list = execQuery($sql);

        while ($row = mysqli_fetch_array($list)) {
            $proId = $row["ProID"];
            $proName = $row["ProName"];
            $tinyDes = $row["TinyDes"];
            $fullDes = $row["FullDes"];
            $price = $row["Price"];
            $quantity = $row["Quantity"];
            $view = $row["NView"];
            $dayAdd = $row["DayAdd"];
            $catId = $row["CatName"];
            $classify = $row["Classify"];
            $onsale = $row["onsale"];
            $salesprice = $row["salesprice"];

            $p = new Products($proId, $proName, $tinyDes, $fullDes, $price, $quantity, $catId, $view, $dayAdd, $classify, $onsale, $salesprice);
            array_push($ret, $p);
        }

        return $ret;
    }

    function loadProductByProId($p_proId)
    {

        $sql = "select * from products p, categories c where ProID = $p_proId and p.CatID = c.CatId";
        $list = execQuery($sql);
        if ($row = mysqli_fetch_array($list)) {

            //$proId = $row["ProID"];
            //$proId = $p_proId;
            $proName = $row["ProName"];
            $tinyDes = $row["TinyDes"];
            $fullDes = $row["FullDes"];
            $price = $row["Price"];
            $quantity = $row["Quantity"];
            $catId = $row["CatName"];
            $view = $row["NView"];
            $dayAdd = $row["DayAdd"];
            $classify = $row["Classify"];
            $onsale = $row['onsale'];
            $salesprice = $row['salesprice'];

            $p = new Products($p_proId, $proName, $tinyDes, $fullDes, $price, $quantity, $catId, $view, $dayAdd, $classify, $onsale, $salesprice);
            return $p;
        }

        return NULL;
    }


    // echo $id.' '.$opt;

    // if($opt == 1){
    //     $product = loadProductsAll();

    //     $productJSON = json_encode($product, JSON_UNESCAPED_UNICODE);
    //     echo $productJSON;
    // }

    switch ($opt){
        case 0:
            $product = loadProductsAll();
            $productJSON = json_encode($product, JSON_UNESCAPED_UNICODE);
            echo $productJSON;
            break;
        case 1: // List
            $product = loadProductsAll();
            $productJSON = json_encode($product, JSON_UNESCAPED_UNICODE);
            echo $productJSON;
            break;
        case 2: // Detail
            $detail = loadProductByProId($id);
            echo json_encode($detail, JSON_UNESCAPED_UNICODE);
            break;
    }
?>