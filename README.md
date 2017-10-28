
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
$response = $compiler->setJsCode($code)->compile();

if ($response && $response->isWithoutErrors()) {
    echo $response->getCompiledCode();

} else {
    echo $code;
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
    $response = $this->compiler->setJsCode($code)->compile();
    
    if ($response && $response->isWithoutErrors()) {
        $code = $response->getCompiledCode();
    }
    
    $this->template->jsCode = $code;
}
```

And in the config neon
```
extensions:
    - GoogleClosureCompiler\Bridges\Nette\GoogleClosureCompilerExtension
```

## Usage
Direct and main information can be found on [https://developers.google.com/closure/compiler/docs/api-ref](https://developers.google.com/closure/compiler/docs/api-ref).

### Compiler
Returns [GoogleClosureCompiler\Response](https://github.com/Machy8/google-closure-compiler/blob/master/src/Compiler/Response.php) if connection was successful otherwise returns NULL.
setDefaultStreamContextCreateTimeout

|         Method                        |        Parameters        |      Constants      |
|---------------------------------------|--------------------------|---------------------|
| compile                               |                          |                     |
| enableClosureCompiler                 |                          |                     |
| enableStatistics                      |                          |                     |
| excludeDefaultExterns                 |                          |                     |
| setCodeUrl                            | string \| string [] $url |                     |
| setCompilationLevel                   | string $level            | COMPILATION_LEVEL_* |
| setConnectionTimeout                  | int $time                |                     |
| setExternsUrl                         | string \| string[] $value|                     |
| setFormattingType                     | string $type             | FORMATTING_*        |
| setJsCode                             | string $code             |                     |
| setJsExterns                          | string $jsCode           |                     |
| setLanguage                           | string $language         | LANGUAGE_*          |
| setLanguageOut                        | string $language         | LANGUAGE_OUT_*      |
| setOutputFileName                     | string $name             |                     |
| setWarningLevel                       | string $level            | WARNING_LEVEL_*     |

### Response
Is parsed json from response of previous request.

|          Method         |                      Returns                      |
|-------------------------|---------------------------------------------------|
| getCompiledCode         | string - compiled code                            |
| getErrors               | array - errors                                    |
| getOutputFilePath       | string - url path to file                         |
| getResponse             | stdClass - whole response                         |
| getServerErrors         | array - server errors                             |
| getStatistics           | stdClass - statistics                             |
| getWarnings             | array - warnings                                  |
| hasErrors               | bool - if code to compile contains errors         |
| hasServerErrors         | bool - if response contains server errors         |
| hasWarnings             | bool - if code to compile contains warnings       |
| isWithoutErrors         | bool - combination of hasServerErrors & hasErrors |
