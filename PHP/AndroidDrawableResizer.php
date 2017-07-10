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

// CONFIGURATION
define('IMAGE_MAGICK_BINARY_PATH', 'convert'); // or '/path/to/convert' or 'C:\Path\To\ImageMagick\convert.exe'

$densityPaths = [
									'ldpi' => 'drawable-ldpi',
								 	'mdpi' => 'drawable-mdpi',
								  'hdpi' => 'drawable-hdpi',
									'xhdpi' => 'drawable-xhdpi',
									'xxhdpi' => 'drawable-xxhdpi',
									'xxxhdpi' => 'drawable-xxxhdpi'
			 				];


$currentInputDirectory = getcwd() === false ? '' : getcwd();
$currentOutputDirectory = getcwd() === false ? '' : getcwd();
$currentDensity = '';
$replaceOld = false;
$oneFile = false;

$help = [
	'AndroidDrawableResizer',
	'Automatic resizing of Android drawables from one density to all others.',
	'',
	'Options:',
	'-i input directory, if not set, will use current working directory.',
	'-f one file input, instead of a whole directory, can not be used with -i option.',
	'-o output directory (optional), if not set, will use current working directory.',
	'-d current density of input images (can be: ldpi, mdpi, hdpi, xhdpi, xxhdpi, xxxhdpi), if not set, will prompt a question.',
	'-r replace current drawables folders if exists.',
	'-h show this help message.',
	'',
	'Example:',
	'[*] A whole images directory: php AndroidDrawableResizer.php -i=~/images -o=~/Desktop/output',
	'[*] One file: php AndroidDrawableResizer.php -f=~/images/oneImage.png -o=~/Desktop/output',
	'[*] Specify desity and force rewriting on old files: php AndroidDrawableResizer.php -r -d=mdpi',
	'[*] You can also use it without options for current directory: php AndroidDrawableResizer.php'
];

//retrieve given command params
$options = getopt("i::o::f::d::rh", ['input::', 'output::', 'density::', 'replace', 'help']);

## show help
if(isset($options['h'])){
	xecho(implode(PHP_EOL, $help));
	exit;
}

#if no options, but a given file
if((empty($options) && !empty(trim($argv[1]))) || isset($options['f'])){
	$oneFile = isset($options['f']) ? trim($options['f']) : (!empty(trim($argv[1])) ? trim($argv[1]) : '');

	if(!is_file($oneFile)){
		xecho('[Warning] Use -i for directory, -f option is only for files (for more information -h to show the help message).');
		exit;
	}

	if(empty($oneFile)){
		xecho('[Warning] The given file\'s path is empty!.');
		exit;
	}
}


#if no input directory given
if(!isset($options['i']) && !$oneFile){
	xecho('[info] No input directory was given using -i option, will use the current directory: (' . $currentInputDirectory . ').');
}else{
	$currentInputDirectory = isset($options['i']) ? trim($options['i']) : $currentInputDirectory;
}

#if no input directory given
if(!isset($options['o'])){
	xecho('[info] No output directory was given using -o option, will use the current directory: (' . $currentOutputDirectory . ').');
}else{
	$currentOutputDirectory = trim($options['o']);
}

#if no density given
if(isset($options['d'])){
	$currentDensity = trim($options['d']);
}

if(empty($currentDensity)){
		xecho('- You did\'nt specify a density using -d option. Type a density now, or keep it empty for (mdpi):');
		$currentDensity = trim(fgets(STDIN));
		if(empty($currentDensity)){
			$currentDensity = 'mdpi';
		}
}

xecho('[info] Current Density: ' . $currentDensity);

if(isset($options['r'])){
	xecho('[info] Force running if output folder has drawables folders.');
	$replaceOld = true;
}


$percent = [];

if (strcasecmp($currentDensity, 'ldpi') === 0) {
	$percent['ldpi'] = 100;
}
elseif (strcasecmp($currentDensity, 'mdpi') === 0) {
	$percent['ldpi'] = 75;
	$percent['mdpi'] = 100;
}
elseif (strcasecmp($currentDensity, 'hdpi') === 0) {
	$percent['ldpi'] = 50;
	$percent['mdpi'] = 66 + 2 / 3;
	$percent['hdpi'] = 100;
}
elseif (strcasecmp($currentDensity, 'xhdpi') === 0) {
	$percent['ldpi'] = 37.5;
	$percent['mdpi'] = 50;
	$percent['hdpi'] = 75;
	$percent['xhdpi'] = 100;
}
elseif (strcasecmp($currentDensity, 'xxhdpi') === 0) {
	$percent['ldpi'] = 25;
	$percent['mdpi'] = 33 + 1 / 3;
	$percent['hdpi'] = 50;
	$percent['xhdpi'] = 66 + 2 / 3;
	$percent['xxhdpi'] = 100;
}
elseif (strcasecmp($currentDensity, 'xxxhdpi') === 0) {
	$percent['ldpi'] = 18.75;
	$percent['mdpi'] = 25;
	$percent['hdpi'] = 37.5;
	$percent['xhdpi'] = 50;
	$percent['xxhdpi'] = 75;
	$percent['xxxhdpi'] = 100;
}
else {
	xecho('Unknown density \'' . $currentDensity . '\'');
	exit;
}

//create drawable folders
foreach($densityPaths as $density => $path){
	makeFolderOrSkip($path);
}



//process files
xecho('Processing: ');

if($oneFile){

	foreach($densityPaths as $density => $path){
		if (isset($percent[$density])) {
			exec(IMAGE_MAGICK_BINARY_PATH . ' "' . realpath($oneFile) . '" -resize ' . $percent[$density] . '% "' . outputDirectory($path . '/' . basename($oneFile)) . '"');
		}
	}

}else{
	$Processedfiles = 0;
	foreach (glob(inputDirectory() . '*.png', GLOB_NOSORT | GLOB_ERR) as $filename) {

			$Processedfiles++;

			echo '['.$Processedfiles.'] ' . $filename;
			echo PHP_EOL;

			foreach($densityPaths as $density => $path){
				if (isset($percent[$density])) {
					exec(IMAGE_MAGICK_BINARY_PATH . ' "' . $filename . '" -resize ' . $percent[$density] . '% "' . outputDirectory($path . '/' . basename($filename)) . '"');
				}
			}
	}

}

xecho('Finished procssing ('.$Processedfiles.' files), output folder: ' . outputDirectory(''));


/**
 * helpers
 */

/**
 * print a text in a good terminal format
 * @param  string  $string
 * @param  boolean $indentation add spaces in the begining of the string
 * @return void
 */
function xecho($string, $indentation = false){
	echo PHP_EOL;
	echo ($indentation ? '    ' : '') . $string;
	echo PHP_EOL;
}

/**
 * return the output directory
 * @param  string $appendPath if given then it will be added to the end
 * @return string output path
 */
function outputDirectory($appendPath = ''){
	global $currentOutputDirectory;
	return rtrim(expandPath($currentOutputDirectory), DIRECTORY_SEPARATOR) .  DIRECTORY_SEPARATOR . $appendPath;
}

/**
* return the input directory
* @param  string $appendPath if given then it will be added to the end
* @return string input path
 */
function inputDirectory($appendPath = ''){
	global $currentInputDirectory;
	return rtrim(expandPath($currentInputDirectory), DIRECTORY_SEPARATOR) .  DIRECTORY_SEPARATOR . $appendPath;
}

/**
 * create a drawable folder if not exists
 * @param  string $drwablePath the path for the drawable folder
 * @return void
 */
function makeFolderOrSkip($drwablePath){
	global $replaceOld, $currentOutputDirectory;


	if (file_exists(outputDirectory($drwablePath))) {
			if(!$replaceOld){
				xecho('Directory \'' . outputDirectory($drwablePath) . '\' does already exist. halted! (to force running use -r. for help use -h)');
				exit;
			}
	}
	else {
			@mkdir(outputDirectory($drwablePath));
			if(!file_exists(outputDirectory($drwablePath))){
				xecho('[Warning] Can not make drwable folder, It might require permissions: '.outputDirectory($drwablePath).'');
			}
	}
}


/**
 * if given path has tilde ~, then exapnd it to the real path
 * @var string $path path to expand
 */
function expandPath($path){
	if(empty($_SERVER['HOME'])){
		return $path;
	}


	if(substr(trim($path), 0, 2) == '~/'){
		return rtrim($_SERVER['HOME'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . substr(trim($path), 2);
	}

	return $path;
}
