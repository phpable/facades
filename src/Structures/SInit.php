<?php
namespace Able\Facades\Structures;

use \Able\Struct\AStruct;

/**
 * @property array args
 * @property callable handler
 */
class SInit extends AStruct {

	/**
	 * @var array
	 */
	protected static $Prototype = ['args', 'handler'];

	/**
	 * @const array
	 */
	protected const defaultArgsValue = [];

	/**
	 * @param array $Args
	 * @return array
	 */
	protected final function setArgsProperty(array $Args = []): array {
		return $Args;
	}

	/**
	 * @param callable $Handler
	 * @return callable
	 */
	protected final function setHandlerProperty(callable $Handler): callable {
		return $Handler;
	}
}
