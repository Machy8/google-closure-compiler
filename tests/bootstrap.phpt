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
	: throwConnectionError();


$response = getCompiler()
	->setJsCode('alert(;')
	->compile();
$response
	? Assert::true( (bool) $response->hasErrors())
	: throwConnectionError();


$response = getCompiler()
	->setJsCode("a; alert('a');")
	->compile();
$response
	? Assert::true( (bool) $response->hasWarnings())
	: throwConnectionError();


$response = getCompiler()
	->setJsCode("alert('Hello world!');")
	->compile();
$response
	? Assert::equal('alert("Hello world!");', $response->getResponse()->compiledCode)
	: throwConnectionError();


$response = getCompiler()
	->setJsCode("alert('Hello world!');")
	->enableStatistics()
	->compile();
$response
	? Assert::true((bool) $response->getStatistics())
	: throwConnectionError();


$response = getCompiler()
	->setJsCode("alert('Hello world!');")
	->enableStatistics()
	->compile();
$response
	? Assert::true((bool) $response->isWithoutErrors())
	: throwConnectionError();


Assert::null(getCompiler()->setJsCode("alert('Hello world!');")->setConnectionTimeout(0)->compile());


Assert::exception(
	function () {
		getCompiler()->compile();
	},
	\GoogleClosureCompiler\CompileException::class,
	'Missing required "js_code" or "code_url" parameter. Set it by setJsCode or setCodeUrl method.'
);


Assert::exception(
	function () {
		getCompiler()
			->setJsCode("alert('Hello world!');")
			->setCompilationLevel('abc')
			->compile();
	},
	\GoogleClosureCompiler\SetupException::class,
	'Unknown compilation level "abc".'
);


Assert::exception(
 	function () {
		getCompiler()
			->setJsCode("alert('Hello world!');")
			->setFormattingType('abc')
			->compile();
	},
	\GoogleClosureCompiler\SetupException::class,
	'Unknown formatting type "abc".'
);


Assert::exception(
	function () {
		getCompiler()
			->setJsCode("alert('Hello world!');")
			->setLanguage('abc')
			->compile();
	},
	\GoogleClosureCompiler\SetupException::class,
	'Unknown language type "abc".'
);


Assert::exception(
	function () {
		getCompiler()
			->setJsCode("alert('Hello world!');")
			->setLanguageOut('abc')
			->compile();
	},
	\GoogleClosureCompiler\SetupException::class,
	'Unknown language type "abc" for language out option.'
);


Assert::exception(
	function () {
		getCompiler()
			->setJsCode("alert('Hello world!');")
			->setWarningLevel('abc')
			->compile();
	},
	\GoogleClosureCompiler\SetupException::class,
	'Unknown warning level "abc".'
);


//------------------------------------------------ HELPERS ------------------------------------------------
function getCompiler(): Compiler
{
	return new Compiler;
}


function getFileContent(string $name): string
{
	return file_get_contents('actual/' . $name . '.js');
}


function matchJsFile(string $name, Compiler $compiler)
{
	$result = $compiler->compile();
	$result
		? Assert::matchFile('expected/' . $name .'.js', $result->getCompiledCode())
		: throwConnectionError();
}


function throwConnectionError()
{
	Assert::fail('Failed to connect to closure compiler');
}
