<?php

require_once 'Entity/Products.php';
require_once 'Entity/Search.php';

class TestProducts extends PHPUnit_Framework_TestCase
{
    public function testCountAllProducts(){
        $product = Products::loadProductsAll();

        $this->assertTrue(sizeof($product) == 13);
    }
}
