<?php

require_once 'vendor/autoload.php';

use Tracy\Debugger;
use GoogleClosureCompiler\Compiler;

Debugger::$strictMode = TRUE;
Debugger::enable(DEBUGGER::DEVELOPMENT);

$compiler = new Compiler;
$input = file_get_contents('input.js');

$result = $compiler
	->setJsCode($input)
	->compile();

file_put_contents('output.js', $result->getCompiledCode());

dump($result->getResponse());
