## Introduction
The phpABLE abstractions library provides the facade pattern implementation. 

## Requirements
* PHP >= 7.2.0
* [Able/Statics](https://github.com/phpable/statics)

## Install
There's a simple way to install ```Able/Facades``` into your project via [Composer](http://getcomposer.org):

```bash
composer require able/facades
```

## Usage
Please, follow the example below:    

```php
class Recipient {
    private $name = "Unknown";
    
    public function __construct(string $name) {
        $this->name = $name;
    }
    
    public function changeName(string $name): void {
        $this->name = $name;
    }

    public function sayHello(): void {
        echo sprintf("Hello %s!", $this->name);
    }
}
```

```php
use \Able\Facades\AFacade;
use \Able\Facades\Structures\SInit;

class FacadeExample extends AFacade {
    
    /**
     * @var string
     */
    protected static $Recipient = Recipient::class;

    /**
     * The initialize method can be used to provide the necessary arguments if any. 
     * @throws \Exception
     */
    protected final static function initialize(): SInit {
        return new SInit(["John"]);
    }
}

FacadeExample::sayHello();

// Hello John!
```

It's also possible to provide the callback function as a second field of the structure. 
This function going to be executed directly after the creation 
and will obtain the created object as an argument.  

```php
use \Able\Facades\AFacade;
use \Able\Facades\Structures\SInit;

class FacadeExample extends AFacade {
    
    /**
     * @var string
     */
    protected static $Recipient = Recipient::class;

    /**
     * The initialize method can be used to provide the necessary arguments if any. 
     * @throws \Exception
     */
    protected final static function initialize(): SInit {
        return new SInit(["John", function(Recipient $Object){
            $Object->changeName("Barbara");
        } ]);
    }
}

FacadeExample::sayHello();

// Hello Barbara!
```

By default, the only instance of the recipient class going to be created. 
This behavior is similar to a singleton pattern and could be changed via ```$keepSingle``` protected property. 

```php
use \Able\Facades\AFacade;

class FacadeExample extends AFacade {
    
    /**
     * If this property set to false, the new instance of the recipient object 
     * going to be created before any method call.
     * @var string
     */
    protected static $keepSingle  = true;

}
```

## License
This package is released under the [MIT license](https://github.com/phpable/facades/blob/master/LICENSE).
