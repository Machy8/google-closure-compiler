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


class Compiler
{

	/** @internal */
	const
		CLOSURE_COMPILER_URL = 'https://closure-compiler.appspot.com/compile',
		PARAMETERS_ACCEPTING_MULTIPLE_VALUES = ['code_url', 'externs_url', 'formatting', 'output_info'];

	const
		COMPILATION_LEVEL_ADVANCED_OPTIMIZATIONS = 'ADVANCED_OPTIMIZATIONS',
		COMPILATION_LEVEL_SIMPLE_OPTIMIZATIONS = 'SIMPLE_OPTIMIZATIONS',
		COMPILATION_LEVEL_WHITESPACE_ONLY = 'WHITESPACE_ONLY',
		ALLOWED_COMPILATION_LEVEL_OPTIONS = [
			self::COMPILATION_LEVEL_ADVANCED_OPTIMIZATIONS,
			self::COMPILATION_LEVEL_SIMPLE_OPTIMIZATIONS,
			self::COMPILATION_LEVEL_WHITESPACE_ONLY
		];

	const
		FORMATTING_PRETTY_PRINT = 'pretty_print',
		FORMATTING_PRETTY_PRINT_DELIMITER = 'print_input_delimiter',
		ALLOWED_FORMATTING_OPTIONS = [
			self::FORMATTING_PRETTY_PRINT,
			self::FORMATTING_PRETTY_PRINT_DELIMITER
		];

	const
		LANGUAGE_ECMASCRIPT_3 = 'ECMASCRIPT3',
		LANGUAGE_ECMASCRIPT_5 = 'ECMASCRIPT5',
		LANGUAGE_ECMASCRIPT_5_STRICT = 'ECMASCRIPT5_STRICT',
		LANGUAGE_ECMASCRIPT_6 = 'ECMASCRIPT6',
		LANGUAGE_ECMASCRIPT_6_STRICT = 'ECMASCRIPT6_STRICT',
		ALLOWED_LANGUAGE_OPTIONS = [
			self::LANGUAGE_ECMASCRIPT_3,
			self::LANGUAGE_ECMASCRIPT_5,
			self::LANGUAGE_ECMASCRIPT_5_STRICT,
			self::LANGUAGE_ECMASCRIPT_6,
			self::LANGUAGE_ECMASCRIPT_6_STRICT
		];

	const
		LANGUAGE_OUT_ECMASCRIPT_3 = self::LANGUAGE_ECMASCRIPT_3,
		LANGUAGE_OUT_ECMASCRIPT_5 = self::LANGUAGE_ECMASCRIPT_5,
		LANGUAGE_OUT_ECMASCRIPT_5_STRICT = self::LANGUAGE_ECMASCRIPT_5_STRICT,
		LANGUAGE_OUT_ECMASCRIPT_6 = self::LANGUAGE_ECMASCRIPT_6,
		LANGUAGE_OUT_ECMASCRIPT_6_STRICT = self::LANGUAGE_ECMASCRIPT_6_STRICT,
		ALLOWED_LANGUAGE_OUT_OPTIONS = [
			self::LANGUAGE_OUT_ECMASCRIPT_3,
			self::LANGUAGE_OUT_ECMASCRIPT_5,
			self::LANGUAGE_OUT_ECMASCRIPT_5_STRICT,
			self::LANGUAGE_OUT_ECMASCRIPT_6,
			self::LANGUAGE_OUT_ECMASCRIPT_6_STRICT
		];

	/**
	 * @internal
	 */
	 const
		OUTPUT_FORMAT_JSON = 'json',
		OUTPUT_FORMAT_TEXT = 'text',
		OUTPUT_FORMAT_XML = 'xml',
		ALLOWED_OUTPUT_FORMAT_OPTIONS = [
			self::OUTPUT_FORMAT_JSON,
			self::OUTPUT_FORMAT_TEXT,
			self::OUTPUT_FORMAT_XML
		];

	const
		OUTPUT_INFO_COMPILED_CODE = 'compiled_code',
		OUTPUT_INFO_ERRORS = 'errors',
		OUTPUT_INFO_STATISTICS = 'statistics',
		OUTPUT_INFO_WARNINGS = 'warnings',
		ALLOWED_OUTPUT_INFO_OPTIONS = [
			self::OUTPUT_INFO_COMPILED_CODE,
			self::OUTPUT_INFO_ERRORS,
			self::OUTPUT_INFO_STATISTICS,
			self::OUTPUT_INFO_WARNINGS
		];

	const
		WARNING_LEVEL_DEFAULT = 'DEFAULT',
		WARNING_LEVEL_VERBOSE = 'VERBOSE',
		WARNING_LEVEL_QUIET = 'QUIET',
		ALLOWED_WARNING_LEVEL_OPTIONS = [
			self::WARNING_LEVEL_DEFAULT,
			self::WARNING_LEVEL_QUIET,
			self::WARNING_LEVEL_VERBOSE
		];

	/**
	 * @var int
	 */
	private $streamContextCreateTimeout = 15; // seconds

	/**
	 * @var array
	 */
	private $httpQueryParameters = [];


	public function __construct()
	{
		$this->setCompilationLevel(self::COMPILATION_LEVEL_SIMPLE_OPTIMIZATIONS);
		$this->setOutputFormat(self::OUTPUT_FORMAT_JSON);
		$this->setOutputInfoType(self::OUTPUT_INFO_COMPILED_CODE);
		$this->setOutputInfoType(self::OUTPUT_INFO_WARNINGS);
		$this->setOutputInfoType(self::OUTPUT_INFO_ERRORS);
	}


	/**
	 * @return Response|NULL
	 * @throws CompileException
	 */
	public function compile()
	{
		$jsCodeSet = $this->parameterExists('js_code');

		if ( ! $jsCodeSet && ! $this->parameterExists('code_url')) {
			throw new CompileException(
				'Missing required "js_code" or "code_url" parameter. Set it by setJsCode or setCodeUrl method.'
			);
		}

		$context = stream_context_create(['http' => [
			'content' => $this->buildHttpQuery(),
			'header' => "Content-type: application/x-www-form-urlencoded",
			'method' => 'POST',
			'timeout' => $this->streamContextCreateTimeout
		]]);

		$response = @file_get_contents(self::CLOSURE_COMPILER_URL, FALSE, $context);

		if ($response === FALSE) {
			return NULL;
		}

		return new Response($response);
	}


	public function enableClosureLibrary(): Compiler
	{
		$this->addHttpQueryParameter('use_closure_library', 'true');
		return $this;
	}


	public function enableStatistics(): Compiler
	{
		$this->setOutputInfoType(self::OUTPUT_INFO_STATISTICS);
		return $this;
	}


	public function excludeDefaultExterns(): Compiler
	{
		$this->addHttpQueryParameter('exclude_default_externs', 'true');
		return $this;
	}


	public function setCodeUrl($url): Compiler
	{
		$this->addHttpQueryParameter('code_url', $url);
		return $this;
	}


	public function setCompilationLevel(string $level): Compiler
	{
		if ( ! in_array($level, self::ALLOWED_COMPILATION_LEVEL_OPTIONS)) {
			throw new SetupException('Unknown compilation level "' . $level . '".');
		}

		$this->addHttpQueryParameter('compilation_level', $level);
		return $this;
	}


	public function setConnectionTimeout(int $time): Compiler
	{
		$this->streamContextCreateTimeout = $time;
		return $this;
	}


	public function setExternsUrl($url): Compiler
	{
		$this->addHttpQueryParameter('externs_url', $url);
		return $this;
	}


	public function setFormattingType(string $type): Compiler
	{
		if ( ! in_array($type, self::ALLOWED_FORMATTING_OPTIONS)) {
			throw new SetupException('Unknown formatting type "' . $type . '".');
		}

		$this->addHttpQueryParameter('formatting', $type);
		return $this;
	}


	public function setJsCode(string $code): Compiler
	{
		$this->addHttpQueryParameter('js_code', $code);
		return $this;
	}


	public function setJsExterns(string $jsCode): Compiler
	{
		$this->addHttpQueryParameter('js_externs', $jsCode);
		return $this;
	}


	public function setLanguage(string $language): Compiler
	{
		if ( ! in_array($language, self::ALLOWED_LANGUAGE_OPTIONS)) {
			throw new SetupException('Unknown language type "' . $language . '".');
		}

		$this->addHttpQueryParameter('language', $language);
		return $this;
	}


	public function setLanguageOut(string $language): Compiler
	{
		if ( ! in_array($language, self::ALLOWED_LANGUAGE_OUT_OPTIONS)) {
			throw new SetupException('Unknown language type "' . $language . '" for language out option.');
		}

		$this->addHttpQueryParameter('language_out', $language);
		return $this;
	}


	public function setOutputFileName(string $name): Compiler
	{
		$this->addHttpQueryParameter('output_file_name', $name);
		return $this;
	}


	public function setWarningLevel(string $level): Compiler
	{
		if ( ! in_array($level, self::ALLOWED_WARNING_LEVEL_OPTIONS)) {
			throw new SetupException('Unknown warning level "' . $level . '".');
		}

		$this->addHttpQueryParameter('warning_level', $level);
		return $this;
	}


	/**
	 * @param string|string[] $value
	 */
	private function addHttpQueryParameter(string $key, $value)
	{
		if ( ! $this->parameterExists($key)) {
			$this->httpQueryParameters[$key] = [];
		}

		if (in_array($key, self::PARAMETERS_ACCEPTING_MULTIPLE_VALUES)) {
			if (is_array($value)) {
				$this->httpQueryParameters[$key] = array_merge($this->httpQueryParameters[$key], $value);

			} else {
				$this->httpQueryParameters[$key][] = $value;
			}

		} else {
			$this->httpQueryParameters[$key] = [$value];
		}
	}


	private function buildHttpQuery(): string
	{
		$queryString = [];

		foreach ($this->httpQueryParameters as $key => $values) {
			foreach ($values as $value) {
				$queryString[] = $key . '=' . urlencode($value);
			}
		}

		$queryString = join('&', $queryString);
		return $queryString;
	}


	private function parameterExists(string $key): bool
	{
		return array_key_exists($key, $this->httpQueryParameters);
	}


	private function setOutputInfoType(string $type): Compiler
	{
		if ( ! in_array($type, self::ALLOWED_OUTPUT_INFO_OPTIONS)) {
			throw new SetupException('Unknown output info type . "' . $type . '"');
		}

		$this->addHttpQueryParameter('output_info', $type);
		return $this;
	}


	private function setOutputFormat(string $format): Compiler
	{
		if ( ! in_array($format, self::ALLOWED_OUTPUT_FORMAT_OPTIONS)) {
			throw new SetupException('Unknown output format . "' . $format . '"');
		}

		$this->addHttpQueryParameter('output_format', $format);
		return $this;
	}

}
