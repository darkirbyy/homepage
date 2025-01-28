<?php

$finder = (new TwigCsFixer\File\Finder())
    ->in(__DIR__.'/../templates')
;

$ruleset = new TwigCsFixer\Ruleset\Ruleset();
$ruleset->addStandard(new TwigCsFixer\Standard\TwigCsFixer());

// $ruleset->addRule(new TwigCsFixer\Rules\File\FileExtensionRule());
// $ruleset->removeRule(TwigCsFixer\Rules\Whitespace\EmptyLinesRule::class);
$ruleset->overrideRule(new TwigCsFixer\Rules\Punctuation\PunctuationSpacingRule(
    ['}' => 1],
    ['{' => 1],
));

$config = new TwigCsFixer\Config\Config();
$config->setRuleset($ruleset)
       ->setCacheFile("var/cache/linter/.twig-cs-fixer.cache")
       ->setFinder($finder)
;

return $config;