<?php
namespace App\Controllers;

/**
* This is the controller that generates the pages
*/
class PagesController extends BaseController
{
	public function getIndex() {
		return $this->render('welcome');
	}
}