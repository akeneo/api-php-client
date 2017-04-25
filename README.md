# PHP Akeneo PIM API

A simple PHP client to use the Akeneo PIM API.

# Requirements

* PHP >= 5.6
* Composer 

# Installation instructions

We use HTTPPlug as the HTTP client abstraction layer.
In this example, we will use [Guzzle](https://github.com/guzzle/guzzle) v6 as the HTTP client implementation.

`api-php-client` uses [Composer](http://getcomposer.org).
The first step to use `api-php-client` is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then, run the following command to require the library:
```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter
```

If you want to use another HTTP client implementation, you can check [here](https://packagist.org/providers/php-http/client-implementation) the full list of HTTP client implementations. 

## License

`php-api-client` is licensed under the Open Software License version 3.0 - see the LICENSE file for details
