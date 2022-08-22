<?php

$finder = PhpCsFixer\Finder::create()
    // exclude dir
    //->exclude('somedir')
    // exclude file
    //->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__ . '/src')
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
