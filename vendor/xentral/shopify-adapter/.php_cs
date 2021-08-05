<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()->in('src');

$config = new Config();
return $config->setIndent('  ')
  ->setRules([
    '@PSR2' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_before_statement' => ['statements' => ['return']]
  ])
  ->setFinder($finder);
