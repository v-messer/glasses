<?php 

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

require __DIR__ . '/clsOrder.php';

function mailOrder($recip, $subject, $message) {

mail($recip, $subject, $message);

// $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
// try {
//     //Server settings
//     $mail->SMTPDebug = 2;                                 // Enable verbose debug output
//     $mail->isSMTP();                                      // Set mailer to use SMTP
//     $mail->Host = 'smtp.mail.ru';  // Specify main and backup SMTP servers
//     $mail->SMTPAuth = true;                               // Enable SMTP authentication
//     $mail->Username = 'blankbot@mail.ru';                 // SMTP username
//     $mail->Password = 'botblank2018';                           // SMTP password
//     $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//     $mail->Port = 587;                                    // TCP port to connect to
//     $mail->CharSet = 'UTF-8';

//     //Recipients
//     $mail->setFrom('blankbot@mail.ru', 'Бланк Заказа');
//     $mail->addAddress($recip);     // Add a recipient
//     //$mail->addAddress('ellen@example.com');               // Name is optional
//     $mail->addReplyTo('blankbot@mail.ru', 'Information');
//     //$mail->addCC('cc@example.com');
//     //$mail->addBCC('bcc@example.com');

//     //Attachments
//     //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//     //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

//     //Content
//     $mail->isHTML(false);                                  // Set email format to HTML
//     $mail->Subject = $subject;
//     $mail->Body    = $message;
//     //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
// }

}


function addOption ($orderItem, $blank, $key, $value) {

	$currentId = explode('-', $key)[0];
	$currentPlusMinus = explode('-', $key)[1];
	$currentSizeIndex = explode('-', $key)[2];
	$currentSizeCount = $value;

	$orderItem->name = $blank->glasses[$currentId-1]->name;
	$orderItem->price = $blank->glasses[$currentId-1]->price;
	$orderItem->imgName = __DIR__.'/images/'.$blank->hash.'/'.$blank->glasses[$currentId-1]->imgName;
	switch ($currentPlusMinus) {
		case 'plus':
			$orderItem->plusSizes[] = $blank->glasses[$currentId-1]->plusSizes[$currentSizeIndex-1];
			$orderItem->plusCount[] = $currentSizeCount;
			break;
		
		case 'minus':
			$orderItem->minusSizes[] = $blank->glasses[$currentId-1]->minusSizes[$currentSizeIndex-1];
			$orderItem->minusCount[] = $currentSizeCount;
			break;
	}

	return $orderItem;
}

function getOrderText($order){
	$source = 'Источник заказа:'.$order->mail."\n";
	$name = 'Бланк: '.$order->name."\n";
	$link = 'Ссылка: '.$order->link."\n";
	$total = 'Сумма:'.$order->total."\n";

	$items = '';
	foreach ($order->items as $orderItem) {
		
		$itemText=$orderItem->name."\n".'Цена: '.$orderItem->price."\n".'Подитог: '.$orderItem->subTotal."\n";
		
		$i = 0;
		if (!is_null($orderItem->plusSizes)) {
			foreach ($orderItem->plusSizes as $plusSize) {
				$itemText=$itemText.'    +'.$plusSize. ' - '.$orderItem->plusCount[$i].' шт.'."\n";
				$i++;
			}
		}	

		$i = 0;
		if (!is_null($orderItem->minusSizes)) {
			foreach ($orderItem->minusSizes as $minusSize) {
				$itemText=$itemText.'    -'.$minusSize. ' - '.$orderItem->minusCount[$i].' шт.'."\n";
				$i++;
			}
		}

		$items = $items."\n".$itemText;
	}

	return $source.$name.$link.$total.$items;
}

function makeOrder($POST, $blankUrl) {
	//var_dump($POST);

	parse_str(parse_url($blankUrl)['query'], $output);	

	$blankHash = $output['id'];
	$blankMail = $output['mail'];

	$order = new order;

	$blank = getBlank($blankHash);

	$order->name = $blank->date."; ".$blank->title;
	$order->link = $blankUrl;
	
	//собираем данные заказа

	$lastId ='';
	$subtotal = 0;
	$total = 0;

	foreach ($POST as $key=>$value) {
	 //echo $key.' '.$value."\n";
		if ($value!='') {
			
			$currentId = explode('-', $key)[0];
			$currentPlusMinus = explode('-', $key)[1];
			$currentSizeIndex = explode('-', $key)[2];
			$currentSizeCount = $value;
			
			if ($currentId!=$lastId) { 
				if ($lastId!='') {
					//если до этого уже был создан orderItem, то сохраняем его в массиd
					$orderItem->subTotal = $subtotal;
					$orderItems[] = $orderItem;
					$total = $total+$subtotal;
					$subtotal = 0;
				} 

				$orderItem = new orderItem;

				$orderItem = addOption($orderItem, $blank, $key, $value);
				$subtotal = $subtotal + ($blank->glasses[$currentId-1]->price) * $currentSizeCount;
				$lastId = $currentId;
			} else {
				$orderItem = addOption($orderItem, $blank, $key, $value);
				$subtotal = $subtotal + ($blank->glasses[$currentId-1]->price) * $currentSizeCount;
			}
		}
	}

	if (isset($orderItem)) {
		$orderItem->subTotal = $subtotal;
		$orderItems[] = $orderItem;
		$total = $total+$subtotal;

		$order->items = $orderItems;
		$order->total = $total;
		$order->mail = $blankMail;

		//print_r($order);
		//формируем текст заказа:
		$orderText = getOrderText($order);


		//print_r($orderText);

		$fp = fopen('order.txt', 'w');
		fwrite($fp, $orderText);
		fclose($fp);

		mailOrder('blankbot@mail.ru', 'Бланк заказа от '.$order->mail, $orderText);
		echo '<h1>Ваш заказ успешно отправлен</h1>';
	} else {
		echo 'Бланк не был заполнен';
	}

}
 ?>