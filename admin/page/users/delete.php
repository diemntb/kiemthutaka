<?php 
	require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/entities/User.php';

	if( isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] != '' ){
	    $result = Users::deleteUserById($_GET['id']);
	    
	    header("Location: /admin/?act=user&type=list");die();
	}
?>