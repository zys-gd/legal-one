<?php

const CS_RULESET = [
    '@Symfony' => true,
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
    'concat_space' => ['spacing' => 'one'],
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
    'yoda_style' => ['equal' => null, 'identical' => null, 'less_and_greater' => null,],
    'phpdoc_align' => ['align' => 'left'],
];

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('bin')
    ->exclude('config')
    ->exclude('public')
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules(CS_RULESET)
    ->setFinder($finder)
    ->setRiskyAllowed(true);

