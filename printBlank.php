<?php function printBlank ($blank) { ?>
	<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<meta charset="UTF-8">
	<title>Бланк заказа</title>
	<style type="text/css">
		.oneGlass {
			border-width: medium;
			border-color: black;
			border-top-style: solid;

		}
		.glassName {
			text-align: center;
		}
		
		table tr {
			height: 25px;
		}

		table tr th, table tr td{
			width: 30px;
			text-align: center;
			font-size: 0.8em;
			max-height: 25px;
			margin-top: 0px;
			margin-bottom: 0px;
			border: 1px solid #ddd;
		}

		table tr td input  {
			width: 30px;
			height: 25px;
			font-size: 1.4em;
			text-align: center;
		}

		.count {
			border: none;
		}

		.price {
			text-align: center;
			font-size: 1.5em;
			padding-left:45px;
		}

		.plusminus {
			font-size: 1.5em;
			margin-top: 0px;
			border: none;
		}

		.plusminus div {
			height: 25px;
		}

		img {
			display: inline-block;
			height: auto;
			max-width: 100%;
		}

		.tableContainer {
			padding-bottom: 10px;
			padding-left: 0px;
		}

		.buttonContainer {
			padding-top:10px;
			padding-bottom:10px;
		}

		.button {
			padding-left: 0px;
		}

		.topRow {
			padding-top: 20px;
		}

		.plus{
			background-color: rgb(0, 176, 240);
		}

		.minus{
			background-color: rgb(255, 217, 102);
		}

		.img {
			height: 100%;
		}
	</style>
</head>
<body>
	<form method="POST" action="index.php">
		<div class="container">

			

			<div class="row">
				<div class="col-md-12"><h3><?php echo $blank->date ?></h3></div>
			</div>
			<div class="row">
				<div class="col-md-12"><h3><?php echo $blank->title ?></h3></div>
			</div>

			<div class="col-md-12 buttonContainer">

				<div class="col-md-10"></div>
				<div class="col-md-1 button">
					<input type="reset" class="btn btn-primary" value="Очистить">
				</div>
				<div class="col-md-1">
					<input type="submit" class="btn btn-success" value="Отправить">
				</div>
			</div>
			
			<?php foreach ($blank->glasses as $oneGlass) { ?>
			<div class="col-md-12 oneGlass">
				<div class="col-md-12 glassName"><h4><?php echo $oneGlass->name?></h4></div>	

				<div class="col-md-3 img"><img src="images/<?php echo $blank->hash.'/'.$oneGlass->imgName?>"></div>
			
				<div class="col-md-9"> 
					<div class="col-md-12 tableContainer">
						<table class = "table-borderless">
							<tr>
								<th class="plusminus"><div>+</div></th>
								<?php if (!is_null($oneGlass->plusSizes)){
								 foreach ($oneGlass->plusSizes as $size) {?>
								<th class ="plus"><?php echo $size; ?></th>
								<?php }}?>

							</tr>
							<tr>
								<td class="count">шт</td>
								<?php 
								if (!is_null($oneGlass->plusSizes)){
								$k=1; foreach ($oneGlass->plusSizes as $size) {
								?>
								<td><input type="text" pattern="\d{1,3}" name="<?php echo $oneGlass->id.'-plus-'.$k ?>"></td>
								<?php $k++;}}?>
							</tr>
						</table>
					</div>

					<div class="col-md-12 tableContainer">
						<table class = "table-borderless">
							<tr>
								<th class="plusminus"><div>&ndash;</div></th>
								
								<?php  if (!is_null($oneGlass->minusSizes)){
									foreach ($oneGlass->minusSizes as $size) {?>
								<th class = "minus"><?php echo $size; ?></th>
								<?php }}?>
								
							</tr>
							<tr>
								<td class="count">шт</td>
								<?php if (!is_null($oneGlass->minusSizes)){
								$k=1; foreach ($oneGlass->minusSizes as $size) {?>
								<td><input type="text" name="<?php echo $oneGlass->id.'-minus-'.$k ?>"></td>
								<?php $k++;}}?>
							</tr>
						</table>
					</div>

					<div class="col-md-2 price"><p class="bg-primary"><?php echo round($oneGlass->price, 2) ?></p></div>
				</div>
					
			</div>
			<?php } //end foreach glasses ?>
			<div class="col-md-12 buttonContainer">

				<div class="col-md-10"></div>
				<div class="col-md-1 button">
					<input type="reset" class="btn btn-primary" value="Очистить">
				</div>
				<div class="col-md-1">
					<input type="submit" class="btn btn-success" value="Отправить">
				</div>
			</div>
	</div>
	</form>
</body>
</html>

<?php } //end function ?>