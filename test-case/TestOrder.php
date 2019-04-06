<?php

require_once 'Entity/Order.php';

class TestOrder extends PHPUnit_Framework_TestCase
{
    public function testCountOrder(){
        $orders = Order::loadAll();
        $this->assertTrue(sizeof($orders) == 6); //db 6 don hang
    }
}
