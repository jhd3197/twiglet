<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$template =  <<<HTML
<p>{{ text | trim | upper }}</p>
<p>{{ text | trim | lower }}</p>
<p>{{ text | trim | length }}</p>
<p>{{ text | truncate(6, "...") }}</p>
<p>{{ text | trim | title }}</p>
<p>{{ list | join("-") }}</p>
<p>{{ csv | split(",") | join(" / ") }}</p>
<p>{{ csv | split(",") | length }}</p>
HTML;

$output = $twiglet->render_string($template, [
    'text' => '  twiglet is tiny  ',
    'list' => ['a', 'b', 'c'],
    'csv' => 'red,green,blue',
]);


$pass = true;

if (strpos($output, 'TWIGLET IS TINY') === false) {
    echo "FAIL: upper + trim\n";
    $pass = false;
}

if (strpos($output, 'twiglet is tiny') === false) {
    echo "FAIL: lower + trim\n";
    $pass = false;
}

if (strpos($output, '<p>15</p>') === false) {
    echo "FAIL: length on string\n";
    $pass = false;
}

if (strpos($output, 'twigle...') === false) {
    echo "FAIL: truncate\n";
    $pass = false;
}

if (strpos($output, 'Twiglet Is Tiny') === false) {
    echo "FAIL: title case\n";
    $pass = false;
}

if (strpos($output, 'a-b-c') === false) {
    echo "FAIL: join\n";
    $pass = false;
}

if (strpos($output, 'red / green / blue') === false) {
    echo "FAIL: split + join\n";
    $pass = false;
}

if (strpos($output, '<p>3</p>') === false) {
    echo "FAIL: length on array\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: built-in filters\n";
}
