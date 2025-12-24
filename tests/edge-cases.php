<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$pass = true;

$output = $twiglet->render(__DIR__ . '/_missing.html');

if ($output !== '') {
    echo "FAIL: missing template not empty\n";
    $pass = false;
}

$template = "<p>{{ text | doesnotexist }}</p><p>{{ text | trim | doesnotexist | upper }}</p>";

file_put_contents(__DIR__ . '/_edge.html', $template);

$output = $twiglet->render(__DIR__ . '/_edge.html', [
    'text' => '  Twiglet ',
]);

unlink(__DIR__ . '/_edge.html');

if (strpos($output, 'Twiglet') === false) {
    echo "FAIL: unknown filter should no-op\n";
    $pass = false;
}

if (strpos($output, 'TWIGLET') === false) {
    echo "FAIL: filter chain with unknown filter\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: edge cases\n";
}
