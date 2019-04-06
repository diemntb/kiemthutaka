<?php
    define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define("SERVER1", "localhost");
    define("DB1", "quanlytaka"); // quanlytaka
    define("UID1", "root"); // root
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
            die("Câu truy vấn bị sai");
        }

        return $result;
    }

    function loadData($id){
        /*  Simple query
         *   SELECT count(orders.orderId) as sum, orders.orderDate from orderdetails details, orders orders
             WHERE details.ProId = 2
             and details.orderID = orders.orderId
             GROUP by orders.orderDate
         * */
        $ret = array();
        $sql = "SELECT count(orders.orderId) as sum, orders.orderDate from orderdetails details, orders orders
            WHERE details.ProId = $id
            and details.orderID = orders.orderId
            GROUP by orders.orderDate";
        $list = execQuery($sql);

        while ($row = mysqli_fetch_assoc($list)) {
            $proId = $row["sum"];
            $proName = $row["orderDate"];
            $tinyDes = '';
            $fullDes = '';
            $price = '';
            $quantity = '';
            $view = '';
            $dayAdd = '';
            $catId = '';
            $classify = '';

            $p = new Products($proId, $proName, $tinyDes, $fullDes, $price, $quantity, $catId, $view, $dayAdd, $classify);
            array_push($ret, $p);
        }

        return $ret;

    }    
    
    $detail = loadData($id);
    echo json_encode($detail, JSON_UNESCAPED_UNICODE);
?>