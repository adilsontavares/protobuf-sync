<?php

include_once './vendor/autoload.php';
//include_once('src/Messages/Choice.php');
require_once 'src/GPBMetadata/Messages.php';

require('src/Messages/Choice.php');
//
// By: Spicer Matthews <spicer@cloudmanic.com>
// Company: Cloudmanic Labs, LLC
// Date: 5/19/2011
// Description: This is a client to the echo server. It will send 10 test commands, and echo the server response.
//							Run it from the command line "php client.php".
//
use GPBMetadata\Messages;

set_time_limit(0); 
$address = '127.0.0.1';
$port = '9090';

$fp = fsockopen($address, $port, $errno, $errstr, 300);
if(! $fp) 
{
  echo "$errstr ($errno)\n";
} 
else 
{	
  	$example = new \Messages\Choice();
    $example->setIdMessage(69);
    $data = $example->serializeToString();

    $arrr = new \Messages\Choice();
    $arrr->mergeFromString($data);

   // echo 'AKOKAd : '. $arrr->getIdMessage();
    // Send message to server
  	$out = "Test\r\n";
    $aux  = (string)(strlen($data));
    $batata = $aux . $data;
    echo "Data -> " . $data . "\n";
  	// fwrite($fp, $aux);
    $data_ = "12" . "Lucas";
    $packed = pack("LA*", $aux, $data);
    fwrite($fp, $packed);
  	
  	// Read the response from the server
  	// $str = fread($fp, 100000);
  	// echo $str;
  
  fclose($fp);
}
?>