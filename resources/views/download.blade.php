<?php
/**
 * @var $file \App\File
 */

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file->name . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

echo $file->getContent();