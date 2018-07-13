 <?php 

require __DIR__ . '/parseXls.php';


function inviteBlanks($POST)
{
	$hashes = array();

	foreach ($POST as $hash=>$status) {
		if (($hash!='inviteButton')&&($hash!='emails')&&($status=='on')) {
			$hashes[] = $hash;
		}
	}

	$emails = explode(';',$POST['emails']);


	foreach ($emails as $email) {
		$links = '';
		foreach ($hashes as $hash) {
			$links = $links.'http://' . $_SERVER['HTTP_HOST'].'/?id='.$hash.'&mail='.$email."\n";
		}

		if ($email!='') {
			mail($email, 'Добрый день, отправляем Вам актуальный бланк заказа на очки и аксессуары. Спасибо. Всего Вам Доброго.', "Добрый день, отправляем Вам актуальный бланк заказа на очки и аксессуары. Будем рады сотрудничать с Вами .  Спасибо. Всего Вам Доброго.\n\nПерейдите по указанным ниже ссылкам для доступа к бланкам:"."\n".$links);	
		}

		
	}


}


function deleteBlanks($POST) {
	foreach ($POST as $hash=>$status) {
		if (($hash!='deleteButton')&&($status=='on')) {
			deleteBlank($hash);
		}
	}
}


function getAllBlanks()
{
	$fp = file_get_contents('blanks.json');
	$blanks = json_decode($fp);
	return $blanks;
}

 function printAdmin($parseMessage) { ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<meta charset="UTF-8">
	<title>Панель управления</title>
	<style type="text/css">
		.header {
			height: 30px;
		}

		.leftPanel {
			border-right: 1px solid #ddd;
		}

		.rightPanel {
			border-left: 1px solid #ddd;
		}

		.uploadTitle {
			/*text-align: center;*/
		}

		.checkbox {
			text-align: right;
			width:50px;
		}

		input[type=file] {
			padding-bottom: 10px;
		}

		input[type=checkbox]
		{
		  /* Double-sized Checkboxes */
		  -ms-transform: scale(1.5); /* IE */
		  -moz-transform: scale(1.5); /* FF */
		  -webkit-transform: scale(1.5); /* Safari and Chrome */
		  -o-transform: scale(1.5); /* Opera */
		  padding: 10px;
		}

		.delete {
			margin-bottom: 20px;
		}

		textarea {
			font-size: 0.8em;
		}

	</style>
</head>
<body>
	<div class="container">
		<div class="col-md-12 header"></div>
		<div class="col-md-12">
			<div class="col-md-12 uploadTitle"><h3>Загрузить бланк</h3></div>
			<div class="col-md-12">
				<form action="?id=master" method="post" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="52275200" />
				    <input type="file" name="fileToUpload">
				    <input type="submit" value="Загрузить" name="fileSubmit" >
				</form>
			</div>
			<div><p><?php echo $parseMessage ?></p></div>
		</div>
		<div class="col-md-12">
			<form method="POST" action="?id=master">
			<div class="col-md-12 uploadedTitle"><h3>Загруженные бланки	</h3></div>
			<div class="col-md-12 delete"><textarea name="emails" rows=5 cols=40 placeholder="Введите e-mail получателей через точку с запятой: mail@mail.ru;mail@yandex.ru;..."></textarea> </div>
			<div class="col-md-2 delete"><input type="Submit" name="deleteButton" value="Удалить выбранные"></div>
			<div class="col-md-10 delete"><input type="Submit" name="inviteButton" value="Отправить выбранные"></div>
			
			<div class="col-md-12">
			
			<?php 
			$blanks = getAllBlanks();
			if (!is_null($blanks)){
				foreach ($blanks as $blank) {
					$blankName = $blank->title."; ".$blank->date;;
					$blankLink = 'http://' . $_SERVER['HTTP_HOST'].explode('?', $_SERVER['REQUEST_URI'])[0] .'?id=' .$blank->hash; 
					$uploadDate = $blank->uploadDate;
					$hash = $blank->hash;
			?>
			
				<div class= "col-md-1 checkbox"><input type="checkbox" name="<?php echo $hash ?>"></div>
				<div class= "col-md-11">
					<p><?php echo $uploadDate ?><br> 
					<?php echo $blankName ?><br>
					<a href="<?php echo $blankLink ?>" target="_blank"> <?php echo $blankLink ?></a></p>	
				</div>

			

			<?php  }}?>
			</div>
			</form>
		</div>
	</div>
</body>
</html>


<?php } //end printAdmin ?>

<?php


function adminInput($FILES) {

$parseResult ='';

if ($FILES!='') {
	if ($FILES['fileToUpload']['error']==0) {
		
		$uploadfile = __DIR__. '/blankfile/blank.xls';
		move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile);
		$parseResult = parseXls();
	}
}

printAdmin($parseResult);

}
  ?>

