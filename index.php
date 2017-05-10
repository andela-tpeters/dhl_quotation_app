<?php 

if(!defined('BASEDIR')) {
	define("BASEDIR", __DIR__."/");
}


require __DIR__ . '/vendor/autoload.php';
use App\App;


$dhl = new App();
$viewResult = '';

function dd($val) {
	echo "<pre>";
	print_r($val);
	echo "</pre>";

	die();
}


$request_path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
// $request_path = $_SERVER['PATH_INFO'];
$path_info = str_replace('/dhl/','', $request_path);
// dd($_SERVER);
// dd($path_info);

// return $path_info !== "/" ? $dhl->build($path_info, $_SERVER["REQUEST_METHOD"])->response() : $dhl->build()->response();

if(!preg_match('/index.php/',$path_info) && !empty($path_info)) {
  try {
  	$viewResult = $dhl->build($path_info, $_SERVER["REQUEST_METHOD"])->response();
  } catch (Exception $e) {
    dd($e->getMessage());
  }
} else {
  try {
  	$viewResult = $dhl->build()->response();
  } catch (Exception $e) {
    dd($e->getMessage());
  }
}
echo $viewResult;