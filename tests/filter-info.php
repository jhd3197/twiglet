<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$twiglet->add_filter('wrap', function ($value, $left = '[', $right = ']') {
    return $left . (string) $value . $right;
}, 'Wrap a value with left and right strings.');

$filters = $twiglet->list_filters();

$pass = true;

if (!array_key_exists('upper', $filters)) {
    echo "FAIL: list_filters missing upper\n";
    $pass = false;
}

if (($filters['upper'] ?? '') === '') {
    echo "FAIL: list_filters missing description for upper\n";
    $pass = false;
}

if (($filters['wrap'] ?? '') !== 'Wrap a value with left and right strings.') {
    echo "FAIL: list_filters custom description\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: list_filters\n";
}
