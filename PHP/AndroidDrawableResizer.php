<?php

/*
 * AndroidDrawableResizer (https://github.com/delight-im/AndroidDrawableResizer)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'stdout');

// output will be plain text in UTF-8
header('Content-type: text/html; charset=utf-8');

// BEGIN CONFIGURATION
define('IMAGE_MAGICK_BINARY_PATH', 'convert'); // or '/path/to/convert' or 'C:\Path\To\ImageMagick\convert.exe'
define('PATH_LDPI', 'drawable-ldpi');
define('PATH_MDPI', 'drawable-mdpi');
define('PATH_HDPI', 'drawable-hdpi');
define('PATH_XHDPI', 'drawable-xhdpi');
define('PATH_XXHDPI', 'drawable-xxhdpi');
define('PATH_XXXHDPI', 'drawable-xxxhdpi');
// END CONFIGURATION

echo 'Resize all PNGs in the current working directory for all densities?';
echo PHP_EOL;

if (getcwd() !== false) {
	echo '    ' . getcwd();
	echo PHP_EOL;
}

echo PHP_EOL;

echo 'Type current density of those PNGs below:';
echo PHP_EOL;
echo '    Example: mdpi';
echo PHP_EOL;

echo PHP_EOL;

$inputDensity = trim(fgets(STDIN));

$percent = [];

if (strcasecmp($inputDensity, 'ldpi') === 0) {
	$percent['ldpi'] = 100;
}
elseif (strcasecmp($inputDensity, 'mdpi') === 0) {
	$percent['ldpi'] = 75;
	$percent['mdpi'] = 100;
}
elseif (strcasecmp($inputDensity, 'hdpi') === 0) {
	$percent['ldpi'] = 50;
	$percent['mdpi'] = 66 + 2 / 3;
	$percent['hdpi'] = 100;
}
elseif (strcasecmp($inputDensity, 'xhdpi') === 0) {
	$percent['ldpi'] = 37.5;
	$percent['mdpi'] = 50;
	$percent['hdpi'] = 75;
	$percent['xhdpi'] = 100;
}
elseif (strcasecmp($inputDensity, 'xxhdpi') === 0) {
	$percent['ldpi'] = 25;
	$percent['mdpi'] = 33 + 1 / 3;
	$percent['hdpi'] = 50;
	$percent['xhdpi'] = 66 + 2 / 3;
	$percent['xxhdpi'] = 100;
}
elseif (strcasecmp($inputDensity, 'xxxhdpi') === 0) {
	$percent['ldpi'] = 18.75;
	$percent['mdpi'] = 25;
	$percent['hdpi'] = 37.5;
	$percent['xhdpi'] = 50;
	$percent['xxhdpi'] = 75;
	$percent['xxxhdpi'] = 100;
}
else {
	echo 'Unknown density \'' . $inputDensity . '\'';
	echo PHP_EOL;

	exit;
}

echo PHP_EOL;

if (file_exists(PATH_LDPI)) {
	echo 'Directory \'' . PATH_LDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_LDPI);
}

if (file_exists(PATH_MDPI)) {
	echo 'Directory \'' . PATH_MDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_MDPI);
}

if (file_exists(PATH_HDPI)) {
	echo 'Directory \'' . PATH_HDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_HDPI);
}

if (file_exists(PATH_XHDPI)) {
	echo 'Directory \'' . PATH_XHDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_XHDPI);
}

if (file_exists(PATH_XXHDPI)) {
	echo 'Directory \'' . PATH_XXHDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_XXHDPI);
}

if (file_exists(PATH_XXXHDPI)) {
	echo 'Directory \'' . PATH_XXXHDPI . '\' does already exist';
	echo PHP_EOL;

	echo 'Shutting down';
	echo PHP_EOL;

	exit;
}
else {
	mkdir(PATH_XXXHDPI);
}

foreach (glob('*.png', GLOB_NOSORT | GLOB_ERR) as $filename) {
	echo $filename;
	echo PHP_EOL;

	if (isset($percent['ldpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['ldpi'] . '% "' . PATH_LDPI . '/' . $filename . '"');
	}
	if (isset($percent['mdpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['mdpi'] . '% "' . PATH_MDPI . '/' . $filename . '"');
	}
	if (isset($percent['hdpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['hdpi'] . '% "' . PATH_HDPI . '/' . $filename . '"');
	}
	if (isset($percent['xhdpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['xhdpi'] . '% "' . PATH_XHDPI . '/' . $filename . '"');
	}
	if (isset($percent['xxhdpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['xxhdpi'] . '% "' . PATH_XXHDPI . '/' . $filename . '"');
	}
	if (isset($percent['xxxhdpi'])) {
		exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent['xxxhdpi'] . '% "' . PATH_XXXHDPI . '/' . $filename . '"');
	}
}

echo PHP_EOL;

echo 'Finished';
echo PHP_EOL;
