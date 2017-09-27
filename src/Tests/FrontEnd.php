<?php
require_once __DIR__ . '/Test.php';
require_once __DIR__ . '/../GPBMetadata/Messages.php';
require_once __DIR__ . '/../Servers/FrontEnd.php';

$server = new FrontEnd();
test('Search for "fund"', $server->search('fund'));
test('Search for "anim"', $server->search('anim'));
test('Search for "asd" (undefined)', $server->search('asd'));

test('Details of book 1.', $server->details(1));
test('Details of book 4.', $server->details(4));
test('Details of book 103 (undefined).', $server->details(103));

test('Buy book with id 2', $server->buy(2));
test('Buy book with id 2', $server->buy(2));
test('Buy book with id 2', $server->buy(2));
test('Buy book with id 203 (undefined)', $server->buy(203));
?>