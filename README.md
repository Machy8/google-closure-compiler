
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
