<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

$template = "<p>{{ title }}</p><p>{{ post.meta.author }}</p><p>{{ text | upper }}</p>";

$output = $twiglet->render_string($template, [
    'title' => 'Hello',
    'post' => [
        'meta' => [
            'author' => 'Juan',
        ],
    ],
    'text' => 'twiglet',
]);

$pass = true;

if (strpos($output, 'Hello') === false) {
    echo "FAIL: string render title\n";
    $pass = false;
}

if (strpos($output, 'Juan') === false) {
    echo "FAIL: string render dot notation\n";
    $pass = false;
}

if (strpos($output, 'TWIGLET') === false) {
    echo "FAIL: string render filters\n";
    $pass = false;
}

if ($pass) {
    echo "PASS: render_string\n";
}
