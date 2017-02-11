<?php 

if(!defined('BASEDIR')) {
	define("BASEDIR", __DIR__."/");
}

require __DIR__ . '/vendor/autoload.php';
use App\App;

$dhl = new App();

function dd($val) {
	echo "<pre>";
	print_r($val);
	echo "</pre>";

	die();
}

isset($_SERVER["PATH_INFO"]) ? $dhl->build($_SERVER["PATH_INFO"], $_SERVER["REQUEST_METHOD"])::response() : $dhl->build()::response();


