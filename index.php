<?php 

require __DIR__ . '/printBlank.php';
require __DIR__ . '/getBlank.php';
require __DIR__ . '/makeOrder.php';
require __DIR__ . '/admin.php';


if (sizeof($_GET)!=0) {

	$queryId = $_GET['id'];


	if (isset($_GET['mail'])){
		$queryMail = $_GET['mail'];
	} else {
		$queryMail = '';
	}
	

	switch ($queryId) {
		case 'master':
			if (isset($_POST['deleteButton'])) {
				deleteBlanks($_POST);
			}

			if (isset($_POST['inviteButton'])) {
				inviteBlanks($_POST);
			}

			if (sizeof($_FILES)==0){
				$FILES = '';
			} else {
				$FILES =$_FILES;
			}

			adminInput($FILES); 
			break;
		default:
			printBlank(getBlank($queryId, $queryMail));
	}
} else {
	if (isset($_POST)!=0) {	
	makeOrder($_POST, $_SERVER['HTTP_REFERER']);
	}
}

 ?>