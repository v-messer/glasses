<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/collectGlasses.php';
require __DIR__ . '/clsBlank.php';
require __DIR__ . '/newBlankDir.php';
require __DIR__ . '/imageLoader.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

function parseXls() {

	ini_set('memory_limit', '512M');

	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__.'/blankfile/blank.xls');

	//находим конец листа
	$endRow = 2;
	while ($spreadsheet->getActiveSheet()->getCell('C'.($endRow+7))->getValue() != '') {
	$endRow = $endRow+7;
	}

	if ($endRow>2) {


		$blankDir = newDir();

		//Grab images
		$files = glob($blankDir['path']); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}

		grabImages($spreadsheet, $blankDir['path']);
        //var_dump($blankDir['path']);
		//Create blanks
		$oneBlank = new blank;

		$oneBlank->uploadDate = date('d-m-Y H:i:s');
		$oneBlank->hash = $blankDir['name'];
		$oneBlank->title = $spreadsheet->getActiveSheet()->getCell('C7')->getValue();
		$oneBlank->date = $spreadsheet->getActiveSheet()->getCell('A3')->getValue();
		$oneBlank->glasses = getGlasses($spreadsheet, $endRow);

		//var_dump($oneBlank);

		$fp = file_get_contents('blanks.json');
		$blanks = json_decode($fp);

		$blanks[] = $oneBlank;
		$blanksJson = json_encode($blanks);

		$fp = fopen('blanks.json', 'w');
		fwrite($fp, $blanksJson);
		fclose($fp);

		$outMsg = 'Файл загружен. Последняя строка: ' . $endRow;

	} else {
		$outMsg = 'Файл не загружен. Отсутствуют данные.';
	}//if endRow

return $outMsg;

}