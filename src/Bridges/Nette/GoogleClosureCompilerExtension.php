<?php

/**
 *
 * Copyright (c) Vladimír Macháček
 *
 * For the full copyright and license information, please view the file license.md
 * that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace GoogleClosureCompiler\Bridges\Nette;

use Nette\DI\CompilerExtension;


class GoogleClosureCompilerExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('compiler'))->setClass('GoogleClosureCompiler\Compiler');
	}

}
