
<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/helper/DataProvider.php';

class User {

    var $id, $username, $password, $name, $email, $dob, $permission;

    function __construct($id, $username, $password, $name, $email, $dob, $permission) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
        $this->dob = $dob;
        $this->permission = $permission;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDob() {
        return $this->dob;
    }

    public function getPermission() {
        return $this->permission;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setDob($dob) {
        $this->dob = $dob;
    }

    public function setPermission($permission) {
        $this->permission = $permission;
    }
	
	
	public static function loadUserName() {
        $ret = array();
        $sql = "select * from users";
        $list = DataProvider::execQuery($sql);

        while ($row = mysqli_fetch_assoc($list)) {

            $id = $row["Id"];
            $username = $row["Username"];
            $password = $row["Password"];
            $name = $row["Name"];
            $email = $row["Email"];
            $dob = $row["DOB"];
            $permission = $row["Permission"];

            $users = new User($id, $username, $password, $name, $email, $dob, $permission);
            array_push($ret, $users);

        }

        return $ret;
    }

    public static function loadAllUser(){
        $ret = array();
        $sql = "select * from users";
        $list = DataProvider::execQuery($sql);

        while ($row = mysqli_fetch_assoc($list)) {

            $id = $row["Id"];
            $username = $row["Username"];
            $password = $row["Password"];
            $name = $row["Name"];
            $email = $row["Email"];
            $dob = $row["DOB"];
            $permission = $row["Permission"];

            $users = new User($id, $username, $password, $name, $email, $dob, $permission);
            array_push($ret, $users);

        }

        return $ret;
    }

    public function insert() {

        $str_username = str_replace("'", "''", $this->username);
        $str_name = str_replace("'", "''", $this->name);
        $str_email = str_replace("'", "''", $this->email);
        $enc_pwd = md5($this->password);
        $str_dob = date('Y-m-d H:i:s', $this->dob);

        $sql = "insert into users (Username, Password, Name, Email, DOB, Permission) "
                . "values('$str_username', '$enc_pwd', '$str_name', '$str_email', '$str_dob', $this->permission)";

        //echo $sql;

        DataProvider::execQuery($sql);
    }

    public function login() {
        $ret = false;

        $str_username = str_replace("'", "''", $this->username);
        $enc_pwd = md5($this->password);
        $sql = "select * from users where Username='$str_username' and Password='$enc_pwd'";
        $list = DataProvider::execQuery($sql);

        if ($row = mysqli_fetch_array ($list)) {

            $this->id = $row["Id"];
            //$this->username = $row["f_Username"];
            //$this->password = $row["f_Password"];
            $this->name = $row["Name"];
            $this->email = $row["Email"];
            $this->dob = strtotime($row["DOB"]);
            $this->permission = $row["Permission"];

            $ret = true;
        }

        return $ret;
    }

    public function getIdUser(){
        $ret = 0;

        $str_username = str_replace("'", "''", $this->username);
        $sql = "select Id from users where Username='$str_username'";
        $list = DataProvider::execQuery($sql);

        if ($row = mysqli_fetch_array ($list)) {
            $this->id = $row["Id"];

            $ret = $this->id;
        }

        return $ret;
    }

     public function checkUsernameEmail(){
        $ret = false;

        $str_username = str_replace("'", "''", $this->username);
        $email = str_replace("'", "''", $this->password);
        $sql = "select * from users where Username='$str_username' and Email='$email'";
        $list = DataProvider::execQuery($sql);

        if ($row = mysqli_fetch_array ($list)) {

            $this->id = $row["Id"];
            //$this->username = $row["f_Username"];
            //$this->password = $row["f_Password"];
            $this->name = $row["Name"];
            $this->email = $row["Email"];
            $this->dob = strtotime($row["DOB"]);
            $this->permission = $row["Permission"];

            $ret = true;
        }
        else{
            $ret = false;
        }

        return $ret;
    }

    public static function changePassword($username, $pwd, $newPwd) {

        $enc_pwd = md5($pwd);
        $enc_new_pwd = md5($newPwd);

        $sql = "update users set Password = '$enc_new_pwd' "
                . "where Username = '$username' and Password = '$enc_pwd'";

        return DataProvider::execNonQueryAffectedRows($sql);
    }

    public static function changePasswordRecovery($username, $newPwd, $idUser) {

//        $enc_pwd = md5($pwd);
        $enc_new_pwd = md5($newPwd);

        $sql = "update users set Password = '$enc_new_pwd' "
            . "where Username = '$username' AND Id = $idUser";

        return DataProvider::execNonQueryAffectedRows($sql);
    }

    public static function getInfo($username) {
        $p = NULL;

        $sql = "select * from users where Username='$username'";
        $list = DataProvider::execQuery($sql);
        if ($row = mysqli_fetch_array ($list)) {
            $p = new User(
                    $row["Id"], 
                    $username, "", 
                    $row["Name"], 
                    $row["Email"], 
                    strtotime($row["DOB"]), 
                    $row["Permission"]
            );
        }

        return $p;
    }

    public static function getInfoById($id){
        $p = NULL;

        $sql = "select * from users where Id=$id";
        $list = DataProvider::execQuery($sql);
        if ($row = mysqli_fetch_array ($list)) {
            $p = new User(
                $row["Id"],
                $row["Username"], "",
                $row["Name"],
                $row["Email"],
                $row["DOB"],
                $row["Permission"]
            );
        }

        return $p;
    }

    public static function deleteAll(){
        $query = "DELETE FROM Users u WHERE u.Permission = 0";
        DataProvider::execQuery($query);

    }

    public static function deleteUserById($idUser){
        $query = "DELETE FROM users u WHERE u.Id = $idUser";
        DataProvider::execQuery($query);
        return true;
    }

    public static function describeTable(){
        $query = "DESCRIBE users";
        DataProvider::execQuery($query);
    }

}

