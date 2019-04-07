<?php

require_once 'Entity/Order.php';

class TestOrder extends PHPUnit_Framework_TestCase
{
    public function testCountOrder(){
        $orders = Order::loadAll();
        $this->assertTrue(sizeof($orders) == 6); //db 6 don hang
    }

    public function testDeleteOrder(){
        $idOrder = 132; //check db
        $result = Order::delete($idOrder);

        $this ->assertTrue($result==true); //true xoa thanh cong
    }

    public function testCountOrderById(){

        $search = 136;
        $order = Order::loadOrderbyId($search);

        $this->assertTrue(sizeof($order) == 1);
    }

    public function testUpdateOrder(){
        $params = [
            'id' => 134,
            'status' => 1
        ];

        $result = Order::updateOrder($params);

        $this->assertTrue($result == true); // true => thành công, false => lỗi
    }




}
