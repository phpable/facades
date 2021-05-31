<?php
namespace Able\Facades;

use \Able\Facades\Structures\SInit;
use \Able\Static\TStatic;

abstract class AFacade {
	use TStatic;

	/**
	 * @var string
	 */
	protected static $Recipient = null;

	/**
	 * @var bool
	 */
	protected static $keepSingle  = true;

	/**
	 * @var array
	 */
	private static $Cache = [];


	/**
	 * @param string $name
	 * @param array $Args
	 * @return mixed
	 * @throws \Exception
	 */
	public final static function __callStatic(string $name, array $Args) {
		if (!static::$keepSingle || !isset(self::$Cache[static::class])){

			/**
			 * Each inheriting class has to be linked
			 * to a represented recipient class.
			 */
			if (!class_exists(static::$Recipient)){
				throw new \Exception('Undefined recepient class!');
			}

			try {
				$Initializer = static::initialize();

				/**
				 * The argument list is required by the recipient class constructor
				 * and must be provided via the related field of the initializing structure.
				 */
				self::$Cache[static::class] = new static::$Recipient(...$Initializer->args);

				/**
				 * The second field of structure could contain an initialize method
				 * to be called right after the creation of the recipient instance.
				 */
				if (!is_null($Initializer->handler)){
					call_user_func($Initializer->handler, self::$Cache[static::class]);
				}

			} catch (\Throwable $Exception){
				throw new \Exception('Facade initialization failed!', -1, $Exception);
			}
		}

		return self::$Cache[static::class]->{$name}(...$Args);
	}

	/**
	 * @return SInit
	 */
	abstract protected static function initialize(): SInit;
}
