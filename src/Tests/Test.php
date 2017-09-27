<?php

require __DIR__ . '/../../vendor/autoload.php';

function print_many($var) {
    foreach ($var as $item) {
        print_var($item);
    }
}

function test($name, $var) {
    printf("----------------------------------------\n");
    printf($name . ":\n");
    print_var($var);
    printf("\n");
}

function print_var($var) {

    if (gettype($var) === 'array')
        print_many($var);
    else 
        $var->debug();
}
?>