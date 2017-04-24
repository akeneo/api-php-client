# PHP Akeneo PIM API

A simple PHP client to use the Akeneo PIM API.

# Requirements

* PHP >= 5.6
* Composer 

# Installation instructions

We use HTTPPlug as the HTTP client abstraction layer.
In this example, we will use [Guzzle](https://github.com/guzzle/guzzle) v6 as the HTTP client implementation.

Run the following command to require the library:
```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter
```

If you want to use another HTTP client implementation, [here](https://packagist.org/providers/php-http/client-implementation) a full list of available packages. 

## License

`php-api-client` is licensed under the Open Software License version 3.0 - see the LICENSE file for details
