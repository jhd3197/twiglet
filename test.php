<?php

require __DIR__ . '/src/Twiglet.php';

use Twiglet\Twiglet;

$twiglet = new Twiglet();

echo $twiglet->render(__DIR__ . '/views/test.html', [
    'title'   => 'Twiglet Test',
    'enabled' => true,
    'post'    => [
        'excerpt' => 'Twiglet is a tiny Twig-inspired template renderer built for PHP plugins and admin views.',
    ],
]);
