<?php
$src = imagecreatefrompng('assets/img/logo.png');
$w = imagesx($src); $h = imagesy($src);
foreach([192, 512] as $s) {
    $dest = imagecreatetruecolor($s, $s);
    $white = imagecolorallocate($dest, 255, 255, 255);
    imagefill($dest, 0, 0, $white);
    $scale = min($s / $w, $s / $h) * 0.8; // 80% size to have some padding
    $nw = $w * $scale; $nh = $h * $scale;
    $x = ($s - $nw) / 2; $y = ($s - $nh) / 2;
    imagecopyresampled($dest, $src, $x, $y, 0, 0, $nw, $nh, $w, $h);
    imagepng($dest, 'assets/img/icon-'.$s.'x'.$s.'.png');
    imagedestroy($dest);
}
imagedestroy($src);
echo "Icons generated.\n";
