<?php

require_once 'Entity/Products.php';
require_once 'Entity/Search.php';

class TestProducts extends PHPUnit_Framework_TestCase
{
    public function testCountAllProducts(){
        $product = Products::loadProductsAll();

        $this->assertTrue(sizeof($product) == 13);
    }

    public function testCountProductsByBrand(){
        /*
            1	Sen đá
            2	Xương rồng
            3	Terrarium
            4	Chậu
            5	Phụ kiện trang trí
         * */
        $search = 1;
        $product = Products::loadProductsByCatId($search);

        $this->assertTrue(sizeof($product) == 5); /* Count Cat = 1*/
//        $this->assertTrue(sizeof($product) == 4); /* Count Cat = 2*/
//        $this->assertTrue(sizeof($product) == 4); /* Count Cat = 3*/
//        $this->assertTrue(sizeof($product) == 0); /* Count Cat = 4*/
//        $this->assertTrue(sizeof($product) == 0); /* Count Cat = 5*/
    }

    public function testFindProduct(){
        $search = "sen đá"; // true
        $searchFail = "testing value"; // false
        $result = Search::searchByName($search);

        $this->assertTrue(sizeof($result) >0); // true => tìm thấy, false không có
    }

    public function testFindByPrice(){
        $name = "sen đá";
        $searchFail = "testing value"; // false
        $price = "100000";

        $result = Search::SearchName($name, 0, $price);
        $this->assertTrue(sizeof($result) >0); // true => tìm thấy, false không có
    }

    public function testUpdateProduct(){
        $params = [
            'id' => 1,
            'Price' => 10000,
            'ProName' => 'Sen thơm',
            'onsale' => 0,
            'salesprice' => 50000,
            'TinyDes' => '',
            'FullDes' => 'Cập nhật sau',
            'catId' => 1,
            'Quantity' => 1500
        ];

        $result = Products::updateProduct($params);

        $this->assertTrue($result == true); // true => thành công, false => lỗi
    }

    public function testInsertProduct(){
        $param = [
            'ProName' => 'Test Add Product',
            'CatID' => 1,
            'Price' => 10000,
            'TinyDes' => '',
            'FullDes' => 'TEST',
            'Quantity' => 1000,
            'onsale' => 1,
            'salesprice' => 15000,
        ];

        $result = Products::saveProducts($param);

        $this->assertTrue($result > 0);

    }
}
