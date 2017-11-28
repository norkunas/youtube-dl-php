<?php

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src');

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
