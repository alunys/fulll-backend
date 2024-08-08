<?php

declare(strict_types=1);

$rules = [
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHP83Migration' => true,
    '@PSR2' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'header_comment' => ['header' => ''],
    'list_syntax' => ['syntax' => 'short'],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
    'native_function_invocation' => true,
    'php_unit_strict' => false,
    'phpdoc_to_comment' => false, // Nécessaire pour les annotations psalm
    'comment_to_phpdoc' => ['ignored_tags' => ['phpstan-ignore-line']],
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/migrations')
    ->in(__DIR__.'/tests')
    ->append([
        __FILE__,
    ])
;

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
