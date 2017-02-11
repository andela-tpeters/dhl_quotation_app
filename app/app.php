<?php
namespace App;

/**
* This is the base class that controls the whole application
*/
class App
{
	private static $path_parts;
	private static $base_controller = "App\Controllers\BaseController";
	private static $method;

	public static function build($path_info = "/pages/index", $method = "GET") {
		self::$method = $method;
		self::get_parts($path_info);
		return get_called_class();
	}

	public static function response() {
		list($klass, $method) = self::$path_parts;
		
		if(method_exists($klass, $method)) {
			$klass_method = array($klass, $method);
			if(is_callable($klass_method, true)) {
				echo call_user_func($klass_method, $_REQUEST);
			} else {
				echo $klass->not_found();
			}
		} else {
			echo $klass->not_found();
		}
	}

	private static function get_parts($path_info) {
		$parts = explode("/", $path_info);
		$parts[0] == "" ? array_shift($parts) : null;
		$klass = self::get_route_class($parts[0]);
		$parts[0] = new $klass;
		$parts[1] = self::get_method($parts[1]);
		self::$path_parts = $parts;
	}

	private static function get_route_class($class_name) {
		$formatted_string = self::format_string($class_name);
		$klass = "App\Controllers\\".$formatted_string."Controller";
		return class_exists($klass) ? $klass : self::$base_controller;
	}

	private static function get_method($method) {
		$formatted_string = self::format_string($method);
		return strtolower(self::$method).$formatted_string;

	}

	private static function format_string($string) {
		$split_str = explode("_", $string);
		$string_concat = join(" ", $split_str);
		$capitalized_words = ucwords($string_concat);
		$caps_words = explode(" ", $capitalized_words);
		return join("", $caps_words);
	}
}