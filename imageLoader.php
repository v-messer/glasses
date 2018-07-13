<?php

function grabImages ($spreadsheet, $path) {

$i = 0;

foreach ($spreadsheet->getActiveSheet(0)->getDrawingCollection() as $drawing) {
    if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
        ob_start();
        call_user_func(
            $drawing->getRenderingFunction(),
            $drawing->getImageResource()
        );
        $imageContents = ob_get_contents();
        ob_end_clean();
        switch ($drawing->getMimeType()) {
            case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG :
                $extension = 'png';
                break;
            case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
                $extension = 'gif';
                break;
            case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG :
                $extension = 'jpg';
                break;
        }
    } else {
        $zipReader = fopen($drawing->getPath(),'r');
        $imageContents = '';
        while (!feof($zipReader)) {
            $imageContents .= fread($zipReader,1024);
        }
        fclose($zipReader);
        $extension = $drawing->getExtension();
    }
    $myFileName = $path.'/00_Image_'.++$i.'.'.$extension;
    file_put_contents($myFileName,$imageContents);
}

}