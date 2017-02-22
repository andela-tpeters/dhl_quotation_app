<?php 

namespace App\Controllers;
use Pug\Pug;
/**
* This the base controller that helps in the rendering of pages
*/
class BaseController
{
	protected $views_folder;
	protected $engine;
	protected $config;

	function __construct($views_folder = "views") {
		$this->config = require BASEDIR . "config/config.php";
		$this->engine = new Pug(array(
			'prettyprint' => true,
    		'extension' => '.pug',

    	));
		$this->views_folder = BASEDIR . "app/" . $views_folder."/";
	}

	protected function view() {
		return $this->views_folder;
	}

	protected function render($viewname = "welcome", $variables = array()) {
		$view_file = $this->get_file($viewname);
		return $this->engine->render($view_file, $variables);
	}

	public function not_found($variables = ['message'=>"Error path does not exists!!! Check the url and try again"]) {
		return $this->engine->render($this->get_file('404'), $variables);
	}

	private function get_file($name) {
		return $this->view().$name.'.pug';
	}
}