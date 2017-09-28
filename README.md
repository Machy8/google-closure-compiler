
# Google Closure Compiler
[![Build Status](https://travis-ci.org/Machy8/google-closure-compiler.svg?branch=master)](https://travis-ci.org/Machy8/google-closure-compiler)
[![Coverage Status](https://coveralls.io/repos/github/Machy8/google-closure-compiler/badge.svg?branch=master)](https://coveralls.io/github/Machy8/google-closure-compiler?branch=master)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/Machy8/google-closure-compiler/blob/master/license.md)

⚡ PHP client for the [Google Closure Compiler](https://closure-compiler.appspot.com/home) API in one file.

## Requirements
- PHP 7.0+
- If you use Nette Framework - v2.3+

## Installation
**1 - Download the Google Closure Compiler client using composer:**
```
 composer require machy8/google-closure-compiler
```
**2 - Usage:**

*Typical:*

```php
$compiler = new GoogleClosureCompiler\Compiler;
$compiled = $compiler->setJsCode($code)->compile();

if ($compiled) {
    echo $compiled->getCompiledCode();

} else {
    echo "Error";
}

```

*Nette framework:*
```PHP

use GoogleClosureCompiler\Compiler;

/**
 * @var Compiler
 */
private $compiler;


public function __construct(Compiler $compiler) 
{
    $this->compiler = $compiler;
}


public function renderDefault() 
{
    $code = file_get_contents('/path/to/script.js');
    $this->template->jsCode = $this->compiler->setJsCode($code)->compile();
}
```

And in the config neon
```
extensions:
    - GoogleClosureCompiler\Bridges\CompilerNette\GoogleClosureCompilerExtension
```

## Usage
Direct and main information can be found on [https://developers.google.com/closure/compiler/docs/api-ref](https://developers.google.com/closure/compiler/docs/api-ref).

### Compiler
Returns [GoogleClosureCompiler\Response](https://github.com/Machy8/google-closure-compiler/blob/master/src/Compiler/Response.php) if connection was successful otherwise returns NULL.

|         Method        |        Parameters        |      Constants      |
|:---------------------:|:------------------------:|:-------------------:|
| compile               |                          |                     |
| enableClosureCompiler |                          |                     |
| enableStatistics      |                          |                     |
| excludeDefaultExterns |                          |                     |
| setCodeUrl            | string | string [] $url  |                     |
| setCompilationLevel   | string $level            | COMPILATION_LEVEL_* |
| setExternsUrl         | string | string[] $value |                     |
| setFormattingType     | string $type             | FORMATTING_*        |
| setJsCode             | string $code             |                     |
| setJsExterns          | string $jsCode           |                     |
| setLanguage           | string $language         | LANGUAGE_*          |
| setLanguageOut        | string $language         | LANGUAGE_OUT_*      |
| setOutputFileName     | string $name             |                     |
| setWarningLevel       | string $level            | WARNING_LEVEL_*     |

### Response
Is parsed json from previous request

|          Method         |                      Returns                      |
|:-----------------------:|:-------------------------------------------------:|
| getCompiledCode         | string - compiled code                            |
| getErrors               | array - errors                                    |
| getOutputFilePath       | string - url path to file                         |
| getResponse             | stdClass - whole response                         |
| getServerErrors         | array - server errors                             |
| getStatistics           | stdClass - statistics                             |
| getWarnings             | array - warnings                                  |
| hasErrors               | bool - if code to compile contain errors          |
| hasServerErrors         | bool - if response contains server errors         |
| hasWarnings             | bool - if warnings about compiled code exists     |
| isWithoutErrors         | bool - combination of hasServerErrors & hasErrors |
