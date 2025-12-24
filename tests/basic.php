<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$template = "<p>{{ title }}</p><p>{{ enabled }}</p><p>{{ missing }}</p>";

file_put_contents(__DIR__ . '/_basic.html', $template);

$output = $twiglet->render(__DIR__ . '/_basic.html', [
    'title' => 'Hello',
    'enabled' => true,
]);

unlink(__DIR__ . '/_basic.html');

$pass = true;

if (strpos($output, 'Hello') === false) {
    echo "FAIL: title not rendered\n";
    $pass = false;
}

if (strpos($output, 'true') === false) {
    echo "FAIL: boolean not rendered\n";
    $pass = false;
}

if (strpos($output, '<p></p>') === false) {
    echo "FAIL: missing variable not empty\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: basic rendering\n";
}
