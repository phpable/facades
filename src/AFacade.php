<?php
namespace Able\Facades;

use \Able\Facades\Structures\SInit;

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
	 * @var bool
	 */
	protected static $keepSingleInstance  = true;

	/**
	 * @var array
	 */
	private static $Cache = [];

	/**
	 * @return object
	 * @throws \Throwable
	 */
	private final static function prepare(): object {
		if (!static::$keepSingleInstance || !isset(self::$Cache[static::class])){

			/**
			 * Each inheriting class has to be obligatorily linked
			 * to a real recipient class.
			 */
			if (!class_exists(static::$Recipient)){
				throw new \Exception('Undefined recepient class!');
			}

			try {
				$Initializer = static::initialize();

				/**
				 * The argument list required by the recipient class constructor
				 * must be provided via the related field of the initializing structure.
				 */
				self::$Cache[static::class] = new static::$Recipient(...$Initializer->args);

				/**
				 * The second field of structure could contain an initialize method.
				 * It has to be called right after the creation of the recipient instance.
				 */
				if (!is_null($Initializer->handler)){
					call_user_func($Initializer->handler, self::$Cache[static::class]);
				}

			} catch (\Throwable $Exception){
				throw new \Exception('Facade initialization failed!', -1, $Exception);
			}
		}

		return self::$Cache[static::class];
	}

	/**
	 * @return object
	 * @throws \Throwable
	 */
	public final static function getRecipientInstance(): object {
		return self::prepare();
	}

	/**
	 * @param string $name
	 * @param array $Args
	 * @return mixed
	 * @throws \Throwable
	 */
	public final static function __callStatic(string $name, array $Args) {
		return self::prepare()->{$name}(...$Args);
	}

	/**
	 * @return SInit
	 */
	abstract protected static function initialize(): SInit;
}
