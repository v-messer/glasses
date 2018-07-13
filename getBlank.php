<?php 

function getBlank($hash) {
	$fp = file_get_contents('blanks.json');
	$blanks = json_decode($fp);

	foreach ($blanks as $blank) {
		if ($blank->hash == $hash) {
			$outBlank=$blank;
			break;
		}
	}

	return $outBlank;
}

function deleteBlank($hash) {
	$fp = file_get_contents('blanks.json');
	$blanks = json_decode($fp);
	$i=0;
	
	$blanksArr = array();
	foreach ($blanks as $blank) {
		if ($blank->hash <> $hash) {
			$blanksArr[] = $blank;
		}
		$i++;
	}

	$blanksJson = json_encode($blanksArr);

	$fp = fopen('blanks.json', 'w');
	fwrite($fp, $blanksJson);
	fclose($fp);

	$blankDir = __DIR__.'/images/'.$hash;
	$files = glob($blankDir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}

	rmdir($blankDir);
 }

//deleteBlank('5b2e064d42a12');

 ?>



