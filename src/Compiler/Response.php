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

namespace GoogleClosureCompiler;


class Response
{

	/**
	 * @var \stdClass
	 */
	private $response;


	public function __construct(string $response)
	{
		$this->response = json_decode($response);
	}


	public function getCompiledCode(): string
	{
		return $this->response->compiledCode ?? '';
	}


	public function getErrors(): array
	{
		return $this->response->errors ?? [];
	}


	public function getOutputFilePath(): string
	{
		return $this->response->outputFilePath ?? '';
	}


	public function getResponse(): \stdClass
	{
		return $this->response;
	}


	public function getServerErrors(): array
	{
		return $this->response->serverErrors ?? [];
	}


	public function getStatistics(): \stdClass
	{
		return $this->response->statistics;
	}


	public function getWarnings(): array
	{
		return $this->response->warnings ?? [];
	}


	public function hasErrors(): bool
	{
		return (bool) $this->getErrors();
	}


	public function hasServerErrors(): bool
	{
		return (bool) $this->getServerErrors();
	}


	public function hasWarnings(): bool
	{
		return (bool) $this->getWarnings();
	}


	public function isWithoutErrors(): bool
	{
		return ! (bool) $this->getErrors() && ! $this->getServerErrors();
	}

}
