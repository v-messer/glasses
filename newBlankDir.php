<?php 
//Создаём новую папку бланка
function newDir() {

$newDirName = uniqId();
$newDirPath = __DIR__ .'/images/'.$newDirName;
mkdir($newDirPath, 0755, true);
return array('name' => $newDirName, 'path' => $newDirPath);
}


 ?>