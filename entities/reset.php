<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/helper/DataProvider.php';

class reset
{
    var $iduser, $token, $expire;

    public function __construct($iduser, $token)
    {
        $this->iduser = $iduser;
        $this->token = $token;
//        $this->expire = $expire;
    }

    public function getIduser()
    {
        return $this->iduser;
    }


    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setExpire($expire)
    {
        $this->expire = $expire;
    }

    public function add(){
        $query = "INSERT INTO reset_password (iduser, token, expire) VALUES ($this->iduser, '$this->token', NOW() + INTERVAL + 10 MINUTE )";
        DataProvider::execQuery($query);
    }

    public function getExpireTime(){
        $expire = new DateTime();
        $query = "select expire from reset_password where token = '$this->token' AND iduser = $this->iduser";
        $list = DataProvider::execQuery($query);
        if ($row = mysqli_fetch_array ($list)) {
            $expire = $row["expire"];
        }

        return $expire;

    }

    public function checkToken(){
        $rightToken = false;
        $query = "select * from reset_password where token = '$this->token' AND iduser = $this->iduser";
        $list = DataProvider::execQuery($query);
        if ($row = mysqli_fetch_array ($list)) {
            $rightToken = true;
        }

        return $rightToken;

    }

    public function deleteToken(){
        $query = "DELETE FROM reset_password WHERE iduser = $this->iduser ";
        DataProvider::execQuery($query);
    }

    public function checkId(){
        $check = false;
        $sql = "select * from reset_password where iduser = $this->iduser";
        $list = DataProvider::execQuery($sql);

        if ($row = mysqli_fetch_array ($list)) {
            $check = true;
        }
        return $check;
    }

    public function getNowTime(){
        $now = new DateTime();
        $sql = "select NOW() as now";
        $list = DataProvider::execQuery($sql);

        if ($row = mysqli_fetch_array ($list)) {
            $now = $row["now"];
        }
        return $now;
    }
}