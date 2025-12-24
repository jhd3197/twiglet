<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$template = "<p>{{ post.title }}</p><p>{{ post.meta.author }}</p><p>{{ post.meta.stats.views }}</p><p>{{ post.meta.missing }}</p>";

file_put_contents(__DIR__ . '/_dot.html', $template);

$output = $twiglet->render(__DIR__ . '/_dot.html', [
    'post' => [
        'title' => 'Twiglet',
        'meta' => [
            'author' => 'Juan',
            'stats' => [
                'views' => 42,
            ],
        ],
    ],
]);

unlink(__DIR__ . '/_dot.html');

$pass = true;

if (strpos($output, 'Twiglet') === false) {
    echo "FAIL: nested title\n";
    $pass = false;
}

if (strpos($output, 'Juan') === false) {
    echo "FAIL: nested author\n";
    $pass = false;
}

if (strpos($output, '42') === false) {
    echo "FAIL: deep nesting\n";
    $pass = false;
}

if (strpos($output, '<p></p>') === false) {
    echo "FAIL: missing key not empty\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: dot notation\n";
}
