<?php 

include __DIR__ . '/clsGlass.php';

function getGlasses($spreadsheet, $endrow) {

$imageCounter = 1;


for ($i = 9; $i<=$endrow; $i+=7) {
	$oneGlass = new glass;
	$oneGlass->id = $imageCounter;
	$oneGlass->name = $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue();
	$oneGlass->price = $spreadsheet->getActiveSheet()->getCell('AB'.$i)->getCalculatedValue();
	$oneGlass->plusSizes = getSizes($spreadsheet, 'plus', $i);
	$oneGlass->minusSizes = getSizes($spreadsheet, 'minus', $i);
	$oneGlass->imgName = '00_Image_'.$imageCounter.'.jpg';

	$glassesArr[] = $oneGlass;
	
	$imageCounter++;
}

return $glassesArr;

}

function getSizes($spreadsheet, $type, $rowNum)  {

if ($type == 'plus') {
	$rowOffset = 1;
} else {
	$rowOffset = 4;
}

$row = $rowNum + $rowOffset;

$col = 3;

$arrSizes = array();

$nextGlassSize = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue();
while ($nextGlassSize!='') {
	$glassSize = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue();
	$arrSizes[] = $glassSize;
	$col++;
	$nextGlassSize = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue();
}

return $arrSizes;

}

 ?>

