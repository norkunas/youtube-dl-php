<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src');

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        'ordered_use',
        'short_array_syntax',
        'short_echo_tag',
        '-phpdoc_to_comment',
        '-phpdoc_var_without_name'
    ])
    ->finder($finder);
