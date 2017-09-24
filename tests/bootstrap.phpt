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


$response = getCompiler()
	->setJsCode(getFileContent($testName))
	->setOutputFileName('test.js')
	->compile();
$response
	? Assert::true( (bool) $response->getOutputFilePath())
	: Assert::fail('Failed to connect to closure compiler');


$response = getCompiler()
	->setJsCode('alert(;')
	->compile();
$response
	? Assert::true( (bool) $response->hasErrors())
	: Assert::fail('Failed to connect to closure compiler');


$response = getCompiler()
	->setJsCode("a; alert('a');")
	->compile();
$response
	? Assert::true( (bool) $response->hasWarnings())
	: Assert::fail('Failed to connect to closure compiler');



//------------------------------------------------ HELPERS ------------------------------------------------
function matchJsFile(string $name, Compiler $compiler)
{
	$result = $compiler->compile();
	$result
		? Assert::matchFile('expected/' . $name .'.js', $result->getCompiledCode())
		: Assert::fail('Failed to connect to closure compiler');
}


function getFileContent(string $name): string
{
	return file_get_contents('actual/' . $name . '.js');
}


function getCompiler(): Compiler
{
	return new Compiler;
}
