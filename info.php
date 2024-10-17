<?php
header('Content-Type: image/png');
$im = imagecreatetruecolor(100, 100);
$bg = imagecolorallocate($im, 0, 0, 0);
$fg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 10, 10, 'Hola', $fg);
imagepng($im);
imagedestroy($im);
?>
