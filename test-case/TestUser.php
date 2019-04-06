<?php

//require_once  '../vendor/autoload.php';
require_once 'Entity/User.php';

class TestUser extends PHPUnit_Framework_TestCase
{
    public function testLogin(){
        $username = "diem";
        $pass = "1234567"; // mkhau dung 123456

        $user = new User(0, $username, $pass, '', '', '', 0);

        // Nếu true là đăng nhập thành công, false là ko thành công
        $this->assertTrue($user->login() == true, 'Test Login');

    }

    public function testDuplicate(){
        $username = "diem"; // diem là có rồi -> true : trùng
        $pass = "123456";
        $name = "Diễm";
        $email = "ntbdiem01@gmail.com";
        $dob = "04-04-1994";
        //$id, $username, $password, $name, $email, $dob, $permission
        $user = new User(0, $username, $pass, $name, $email, $dob, 0);

        $this->assertTrue($user->checkDuplicate($username) == true); // true => trùng, false => chưa trùng
    }
}