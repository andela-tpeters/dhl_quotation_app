<?php

namespace App\Controllers;
use DHL\Entity\AM\GetQuote;
use DHL\Datatype\AM\PieceType;
use DHL\Client\Web as WebserviceClient;

/**
* This is for the quotation controller
*/
class QuotationController extends BaseController
{
	public function postIndex($params) {
		$info = $params["quote"][0];
		// dd($info);
		$xml = $this->getQuote($info);
		// dd($xml);
		if(isset($xml->Response->Status) || isset($xml->Response->Note)) {
			return $this->render('404',['message'=>$xml->Response->Status->Condition->ConditionData]);
		}

		if(isset($xml->GetQuoteResponse->Status) || isset($xml->GetQuoteResponse->Note)) {
			return $this->render('404',['message'=>$xml->GetQuoteResponse->Note->Condition->ConditionData]);
		}
		return $this->render('quotations', ['quote'=> $xml->GetQuoteResponse, 'quote_params'=>$info]);
	}

	public function postBook($params) {
		return $this->render('booking', $params);
	}

	public function postMail($params) {
	}

	private function buildMail($params) {
	}

	private function getQuote($req) {
		$q = $this->buildQuoteXml($req);
		$client = new WebserviceClient('staging');
		$xml = new \SimpleXMLElement($client->call($q));
		return $xml;
	}

	private function buildPiece($req, $id) {
		$piece = new PieceType();
		$piece->PieceID = $id;
		$piece->Height = $req['height'];
		$piece->Depth = $req['length'];
		$piece->Width = $req['width'];
		$piece->Weight = $req['weight'];
		return $piece;
	}

	private function buildQuoteXml($req) {
		$q = new GetQuote();
		$q->SiteID = $this->config["dhl"]['id'];
		$q->Password = $this->config['dhl']['pass'];
		$q->BkgDetails->Date = date('Y-m-d');
		$q->MessageTime = strftime("%Y-%m-%dT%H:%M:%S", strtotime('now'));
		$q->MessageReference = '12345678901234567890123456789';
		$piece = $this->buildPiece($req, 1);
		$q->BkgDetails->addPiece($piece);
		$q->From->CountryCode = $req['from'];

		if(preg_match('/\d+/', $req['from_postal_code'])) {
			$q->From->Postalcode = strtoupper($req['from_postal_code']);
		} else {
			$q->From->City = strtoupper($req['from_postal_code']);
		}

		$q->To->CountryCode = $req['to'];

		if(preg_match('/\d+/', $req['to_postal_code'])) {
			$q->To->Postalcode = $req['to_postal_code'];
		} else {
			$q->To->City = strtoupper($req['to_postal_code']);
		}

		$q->BkgDetails->ReadyTime = 'PT10H21M';
		$q->BkgDetails->ReadyTimeGMTOffset = date('P');
		$q->BkgDetails->DimensionUnit = $req['dim_unit'];
		$q->BkgDetails->WeightUnit = $req['weight_unit'];
		$q->BkgDetails->PaymentCountryCode = $req['payment_country_code'];
		$q->BkgDetails->NumberOfPieces = $req['no_of_pieces'];
		return $q;
	}
}