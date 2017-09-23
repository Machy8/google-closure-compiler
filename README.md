
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
    $this->template->jsCode = $this->compiler->setJsCode($code)->compiler();
}
```

And in the config neon
```
extensions:
    - GoogleClosureCompiler\Bridges\CompilerNette\GoogleClosureCompilerExtension
```
