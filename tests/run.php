<?php

require __DIR__ . '/../src/Twiglet.php';

echo "Running Twiglet tests...\n\n";

$tests = glob(__DIR__ . '/*.php');
sort($tests);

foreach ($tests as $test) {
    if (basename($test) === 'run.php') {
        continue;
    }

    echo "-> " . basename($test) . "\n";
    require $test;
    echo "\n";
}

echo "All tests executed.\n";
