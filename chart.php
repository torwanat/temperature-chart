<?php
require __DIR__ . "/data.php";

header("Content-type: image/png");

$drawline = true;
$scale = 7;
$vertical_interval = ($height - 2 * $padding) / $scale;
$horizontal_interval = ($width - 2 * $padding) / count($data);

$img = imagecreatetruecolor($width, $height);

$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$gray = imagecolorallocate($img, 220, 220, 220);
$red = imagecolorallocate($img, 255, 0, 0);
$blue = imagecolorallocate($img, 0, 0, 255);

imagefilledrectangle($img, 0, 0, $width, $height, $white);

imagesetstyle($img, [$gray, $gray, $gray, $white, $white, $white]);

$scale_value = 36;
for ($i = 0; $i <= $scale; $i++) {
    imageline($img, $padding, $height - $padding - ($vertical_interval * $i), $width - $padding, $height - $padding - ($vertical_interval * $i), IMG_COLOR_STYLED);
    imageline($img, $padding - 5, $height - $padding - ($vertical_interval * $i), $padding + 5, $height - $padding - ($vertical_interval * $i), $black);
    if ($i) {
        $whole_scale = floor($scale_value);
        $decimal_scale = $scale_value - $whole_scale;
        if ($i == 1 || !($i % 6)) {
            imagestring($img, 2, $padding - 35, $height - $padding - ($vertical_interval * $i) - 5, $scale_value, $black);
        } else {
            imagestring($img, 2, $padding - 35, $height - $padding - ($vertical_interval * $i) - 5, number_format($decimal_scale, 1), $black);
        }
        $scale_value += 0.2;
    }
}

for ($i = 0; $i <= count($data); $i++) {
    imageline($img, $padding + ($horizontal_interval * $i), $height - $padding, $padding + ($horizontal_interval * $i), $padding, IMG_COLOR_STYLED);
    imageline($img, $padding + ($horizontal_interval * $i), $height - $padding - 5, $padding + ($horizontal_interval * $i), $height - $padding + 5, $black);
    if ($i) {
        imagestring($img, 2, $padding + ($horizontal_interval * $i), $height - $padding + 10, $i, $black);
    }
}

imageline($img, $padding, $height - $padding, $width - $padding, $height - $padding, $black);
imageline($img, $padding, $height - $padding, $padding, $padding, $black);
imageline($img, $padding + 5, $height - $padding - ($vertical_interval * 6), $width - $padding, $height - $padding - ($vertical_interval * 6), $red);

imagestring($img, 4, $width / 2 - 50, $height - ($padding / 2), "Dzien miesiaca", $black);
imagestringup($img, 4, $padding / 6, $height / 2 + 30, "Temperatura", $black);

$drawline = false;
foreach ($data as $position => $value) {
    if ($value == null) {
        imagefilledellipse($img, $padding + ($horizontal_interval * ($position + 1)), $height - $padding, 10, 10, $gray);
        $drawline = false;
    } elseif ($value > 37 || $value < 36) {
        imagefilledellipse($img, $padding + ($horizontal_interval * ($position + 1)), $height - $padding, 10, 10, $red);
        $drawline = false;
    } else {
        $whole = floor($value);
        $decimal = $value - $whole;
        imagefilledellipse($img, $padding + ($horizontal_interval * ($position + 1)), $height - $padding - ($vertical_interval * ($decimal * 10 + 2) / 2), 10, 10, $blue);
        if ($drawline) {
            $previous = $data[$position - 1];
            $previous_whole = floor($previous);
            $previous_decimal = $previous - $previous_whole;
            imageline($img, $padding + ($horizontal_interval * $position), $height - $padding - ($vertical_interval * ($previous_decimal * 10 + 2) / 2), $padding + ($horizontal_interval * ($position + 1)), $height - $padding - ($vertical_interval * ($decimal * 10 + 2) / 2), $blue);
        } else {
            $drawline = true;
        }
    }
}

imagepng($img);
?>