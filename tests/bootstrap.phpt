<?php

require __DIR__ . '/../vendor/autoload.php';

use Tester\Assert;
use GoogleClosureCompiler\Compiler;

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


//------------------------------------------------ TESTS ------------------------------------------------
$testName = 'advanced-optimization';
$compiler = getCompiler()
	->setJsCode(getFileContent($testName))
	->setCompilationLevel(Compiler::COMPILATION_LEVEL_ADVANCED_OPTIMIZATIONS);
matchJsFile($testName, $compiler);

$testName = 'pretty-output';
$compiler = getCompiler()
	->setJsCode(getFileContent($testName))
	->setFormattingType(Compiler::FORMATTING_PRETTY_PRINT);
matchJsFile($testName, $compiler);

$testName = 'simple-optimization';
$compiler = getCompiler()
	->setJsCode(getFileContent($testName));
matchJsFile($testName, $compiler);

$testName = 'whitespace-only-optimization';
$compiler = getCompiler()
	->setJsCode(getFileContent($testName))
	->setCompilationLevel(Compiler::COMPILATION_LEVEL_WHITESPACE_ONLY);
matchJsFile($testName, $compiler);




//------------------------------------------------ HELPERS ------------------------------------------------
function matchJsFile(string $name, Compiler $compiler)
{
	$result = $compiler->compile();

	if ($result === NULL) {
		Assert::fail('Failed to connect to closure compiler');
	}

	Assert::matchFile('expected/' . $name .'.js', $result->getCompiledCode());
}


function getFileContent(string $name): string
{
	return file_get_contents('actual/' . $name . '.js');
}


function getCompiler(): Compiler
{
	return new Compiler;
}
