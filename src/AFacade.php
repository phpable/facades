<?php
namespace Able\Facades;

use \Able\Prototypes\TUnclonable;
use \Able\Prototypes\TUncreatable;

abstract class AFacade {
	use TUncreatable;
	use TUnclonable;

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

			/**
			 * Each inheriting class has to be obligatorily linked
			 * to a real recipient class.
			 */
			if (!class_exists(static::$Recipient)){
				throw new \Exception('Undefined recepient class!');
			}

			self::$Cache[static::class] = new static::$Recipient(...static::provide());

			/**
			 * The initialization method is always called after the recipient creation
			 * and can be used for configuration or any similar goals.
			 */
			static::initialize();
		}

		return self::$Cache[static::class];
	}

	/**
	 * The initialization method does nothing by default,
	 * so it only exists to be overridden in inheriting classes.
	 */
	protected static function initialize(): void {}

	/**
	 * Returns a singleton instance of the recipient class.
	 *
	 * @return object
	 * @throws \Exception
	 */
	public final static function recipient(){
		return static::prepare();
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
	 * creation if needed.
	 *
	 * @return array
	 */
	protected abstract static function provide(): array;
}
