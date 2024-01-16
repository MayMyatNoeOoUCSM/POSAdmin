<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('bootstrap/')
    ->exclude('public/')
    ->exclude('resources/')
    ->exclude('storage/')
    ->in(__DIR__)
;
return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR2' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => false,
        'not_operator_with_successor_space' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'method_chaining_indentation' => true,
        'no_unneeded_curly_braces' => true,
        'no_useless_else' => true,
        'ordered_imports' => true,
        'ordered_class_elements' => true,
    ))
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder)
;
