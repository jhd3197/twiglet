<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$twiglet->add_filter('wrap', function ($value, $left = '[', $right = ']') {
    return $left . (string) $value . $right;
});

$twiglet->add_filter('repeat', function ($value, int $times = 2) {
    return str_repeat((string) $value, $times);
});

$template = <<<HTML
<p>{{ name | wrap("(", ")") }}</p>
<p>{{ name | repeat(3) }}</p>
<p>{{ empty | default("fallback") | upper }}</p>
HTML;

file_put_contents(__DIR__ . '/_advanced.html', $template);

$output = $twiglet->render(__DIR__ . '/_advanced.html', [
    'name' => 'Twiglet',
]);

unlink(__DIR__ . '/_advanced.html');

$pass = true;

if (strpos($output, '(Twiglet)') === false) {
    echo "FAIL: custom filter with args\n";
    $pass = false;
}

if (strpos($output, 'TwigletTwigletTwiglet') === false) {
    echo "FAIL: numeric args\n";
    $pass = false;
}

if (strpos($output, 'FALLBACK') === false) {
    echo "FAIL: default + upper chain\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: custom filters and args\n";
}
