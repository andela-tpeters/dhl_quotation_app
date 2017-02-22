<?php
namespace App;

/**
* This is the base class that controls the whole application
*/
class App
{
	private $path_parts;
	private $base_controller = "App\Controllers\BaseController";
	private $method;

	public function build($path_info = "/pages/index", $method = "GET") {
		$this->method = $method;
		$this->get_parts($path_info);
		return $this;
	}

	public function response() {
		list($klass, $method) = $this->path_parts;


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

	private function get_parts($path_info) {
		$parts = explode("/", $path_info);
		$parts[0] == "" ? array_shift($parts) : null;
		$klass = $this->get_route_class($parts[0]);
		$parts[0] = new $klass;
		$parts[1] = $this->get_method($parts[1]);
		$this->path_parts = $parts;
	}

	private function get_route_class($class_name) {
		$formatted_string = $this->format_string($class_name);
		$klass = "App\Controllers\\".$formatted_string."Controller";
		return class_exists($klass) ? $klass : $this->base_controller;
	}

	private function get_method($method) {
		$formatted_string = $this->format_string($method);
		return strtolower($this->method).$formatted_string;

	}

	private function format_string($string) {
		$split_str = explode("_", $string);
		$string_concat = join(" ", $split_str);
		$capitalized_words = ucwords($string_concat);
		$caps_words = explode(" ", $capitalized_words);
		return join("", $caps_words);
	}
}