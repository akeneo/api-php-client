<?php

namespace Akeneo\Pim\Routing;

/**
 * Class UriGenerator
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UriGenerator implements UriGeneratorInterface
{
    /** @var string */
    protected $baseUri;

    /** @var array */
    protected $routes;

    /**
     * @param string $baseUri Base URI of the API
     * @param array  $routes  The list of routes of the API
     */
    public function __construct($baseUri, array $routes)
    {
        $this->baseUri = rtrim($baseUri, '/');
        $this->routes = $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($routeName, array $uriParameters = [], array $queryParameters = [])
    {
        $route = $this->findRoute($routeName);
        $uriParameters = $this->encodeUriParameters($uriParameters);

        $uri = $this->baseUri.'/'.vsprintf(ltrim($route, '/'), $uriParameters);

        if (! empty($queryParameters)) {
            $uri .= '?'.http_build_query($queryParameters, null, '&', PHP_QUERY_RFC3986);
        }

        return $uri;
    }

    /**
     * @param string $routeName
     *
     * @return string
     */
    protected function findRoute($routeName)
    {
        if (!isset($this->routes[$routeName])) {
            throw new \InvalidArgumentException(sprintf('The route %s does not exists', $routeName));
        }

        return $this->routes[$routeName];
    }

    /**
     * @param array $uriParameters
     *
     * @return array
     */
    protected function encodeUriParameters(array $uriParameters) {
        return array_map(function($uriParameter) {
            $uriParameter = rawurlencode($uriParameter);

            return preg_replace('~\%2F~', '/', $uriParameter);
        }, $uriParameters);
    }
}
