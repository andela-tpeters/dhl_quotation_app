<?php
namespace App\Controllers;

/**
* This is the controller that generates the pages
*/
class PagesController extends BaseController
{
	public function getIndex() {
		$states = json_decode(file_get_contents("http://locationsng-api.herokuapp.com/api/v1/states"));
		return $this->render('welcome', ['states' => $states]);
	}

	public function postCheckout($params) {
		return $this->render('checkout', ['quote' => $params]);
	}

	public function postSummary($params) {
		return $this->render('summary', ['checkout'=> $params['checkout']]);
	}

	public function postMail($params) {
		$value = unserialize($params['checkout']);
		$headers = [];
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=UTF-8';
		$headers[] = 'From: New Shipping <noreply@gcl.com>';
		$headers[] = 'CC: '. $value['mail_to'] .'noreply@gcl.com';
		$to = $value['mail_from'];
		if(mail($to,'New Shipping', $this->render('layouts/mail', $value), implode("\r\n",$headers))) {
			return $this->render('404', ['message'=>"Thank you for using our service;\nAn email has been sent to you"]);
		} else {
			return $this->render('404', ['message'=>"Something went wrong sending your mail;\nPlease try again"]);
		}
	}
}