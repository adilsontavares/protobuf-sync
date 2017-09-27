<?php
require __DIR__ . '/Test.php';
require __DIR__ . '/../Servers/FrontEnd.php';

$server = new FrontEnd('127.0.0.1', 8888);
test('Search for "fund"', $server->search('fund'));
test('Search for "anim"', $server->search('anim'));

test('Buy book with id 2', $server->buy(2));
test('Buy book with id 2', $server->buy(2));
test('Buy book with id 2', $server->buy(2));
?>