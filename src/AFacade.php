<?php
namespace Able\Facades;

use \Able\Prototypes\TUnclonable;
use \Able\Prototypes\TUncreatable;

abstract class AFacade {
	use TUnclonable;
	use TUncreatable;

	/**
	 * @var string
	 */
	protected static $Recipient = null;

	/**
	 * @var array
	 */
	private static $Cache = [];

	/**
	 * @return object
	 * @throws \Exception
	 */
	private final static function prepare(): object {
		if (!isset(self::$Cache[static::class])){
			if (!class_exists(static::$Recipient)){
				throw new \Exception('Undefined recepient class!');
			}

			self::$Cache[static::class] = new static::$Recipient(...static::provide());
		}

		return self::$Cache[static::class];
	}

	/**
	 * @param string $name
	 * @param array $Args
	 * @return mixed
	 * @throws \Exception
	 */
	public final static function __callStatic(string $name, array $Args) {
		return self::prepare()->{$name}(...$Args);
	}

	/**
	 * Provides arguments for a recipient object
	 * creation if necessary.
	 *
	 * @return array
	 */
	protected abstract static function provide(): array;
}
