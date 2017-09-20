<?php

require 'vendor/autoload.php';
//include_once('src/Messages/Choice.php');
require('src/Messages/Choice.php');
//
// By: Spicer Matthews <spicer@cloudmanic.com>
// Company: Cloudmanic Labs, LLC
// Date: 5/19/2011
// Description: This is a client to the echo server. It will send 10 test commands, and echo the server response.
//							Run it from the command line "php client.php".
//


set_time_limit(0); 
$address = '127.0.0.1';
$port = '7834';

$fp = fsockopen($address, $port, $errno, $errstr, 300);
if(! $fp) 
{
  echo "$errstr ($errno)\n";
} 
else 
{	
  	$example = new Messages\Choice();
    $example->setIdMessage(1);
    $data = $example->serializeToString();

    // Send message to server
  	$out = "Test\r\n";
  	fwrite($fp, $data);
  	
  	// Read the response from the server
  	$str = fread($fp, 100000);
  	echo $str;
  
  fclose($fp);
}
?>