<?php
use DHL\Entity\AM\GetQuote;
use DHL\Datatype\AM\PieceType;
use DHL\Client\Web as WebserviceClient;

$config = require BASEDIR . "config/config.php";

$dhl = $config['dhl'];
// Test a getQuote using DHL XML API
$sample = new GetQuote();
$sample->SiteID = $dhl['id'];
$sample->Password = $dhl['pass'];
// Set values of the request
$sample->MessageTime = '2001-12-17T09:30:47-05:00';
$sample->MessageReference = '1234567890123456789012345678901';
$sample->BkgDetails->Date = date('Y-m-d');
$piece = new PieceType();
$piece->PieceID = 1;
$piece->Height = 10;
$piece->Depth = 5;
$piece->Width = 10;
$piece->Weight = 10;
$sample->BkgDetails->addPiece($piece);
$sample->BkgDetails->IsDutiable = 'Y';
$sample->BkgDetails->QtdShp->QtdShpExChrg->SpecialServiceType = 'WY';
$sample->BkgDetails->ReadyTime = 'PT10H21M';
$sample->BkgDetails->ReadyTimeGMTOffset = '+01:00';
$sample->BkgDetails->DimensionUnit = 'CM';
$sample->BkgDetails->WeightUnit = 'KG';
$sample->BkgDetails->PaymentCountryCode = 'CA';
$sample->BkgDetails->IsDutiable = 'Y';
// Request Paperless trade
$sample->BkgDetails->QtdShp->QtdShpExChrg->SpecialServiceType = 'WY';
$sample->From->CountryCode = 'CA';
$sample->From->Postalcode = 'H3E1B6';
$sample->From->City = 'Montreal';
$sample->To->CountryCode = 'CH';
$sample->To->Postalcode = '1226';
$sample->To->City = 'Thonex';
$sample->Dutiable->DeclaredValue = '100.00';
$sample->Dutiable->DeclaredCurrency = 'CHF';
// Call DHL XML API
$start = microtime(true);
$client = new WebserviceClient('staging');
$xml = $client->call($sample);
echo $xml;
echo "<hr />";
$converted_xml = new SimpleXMLElement($xml);
echo PHP_EOL . 'Executed in ' . (microtime(true) - $start) . ' seconds.' . PHP_EOL;
echo "<hr />";
echo "<pre>";
print_r($converted_xml->GetQuoteResponse);
echo "</pre>";

// var_dump($_SERVER);