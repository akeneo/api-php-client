<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Generic client interface to execute common request on resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ResourceClientInterface
{
    /**
     * Gets a resource.
     *
     * @param string $uri             URI of the resource
     * @param array  $uriParameters   URI parameters of the resource
     * @param array  $queryParameters Query parameters of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function getResource(string $uri, array $uriParameters = [], array $queryParameters = []): array;

    /**
     * Gets a list of resources.
     *
     * @param string   $uri             URI of the resource
     * @param array    $uriParameters   URI parameters of the resource
     * @param null|int $limit           The maximum number of resources to return.
     *                                  Do note that the server has a default value if you don't specify anything.
     *                                  The server has a maximum limit allowed as well.
     * @param null|bool $withCount      Set to true to return the total count of resources.
     *                                  This parameter could decrease drastically the performance when set to true.
     * @param array    $queryParameters Additional query parameters of the request
     *
     * @throws InvalidArgumentException If a query parameter is invalid.
     * @throws HttpException            If the request failed.
     *
     * @return array
     */
    public function getResources(string $uri, array $uriParameters = [], ?int $limit = 10, ?bool $withCount = false, array $queryParameters = []): array;

    /**
     * Creates a resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     * @param array  $body          Body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     */
    public function createResource(string $uri, array $uriParameters = [], array $body = []): int;

    /**
     * Creates a resource using a multipart request.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resources
     * @param array  $requestParts  Parts of the request. Each part is defined with "name", "contents", and "options"
     *
     * @throws InvalidArgumentException If a given request part is invalid.
     * @throws HttpException            If the request failed.
     *
     * @return ResponseInterface the response of the creation request
     */
    public function createMultipartResource(string $uri, array $uriParameters = [], array $requestParts = []): ResponseInterface;

    /**
     * Creates a resource if it does not exist yet, otherwise updates partially the resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     * @param array  $body          Body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     *             Status code 204 indicating that the resource has been well updated.
     */
    public function upsertResource(string $uri, array $uriParameters = [], array $body = []): int;

    /**
     * Updates or creates several resources using a stream for the request and the response.
     *
     * @param string                $uri           URI of the resource
     * @param array                 $uriParameters URI parameters of the resource
     * @param array|StreamInterface $resources     array of resources to create or update.
     *                                             You can pass your own StreamInterface implementation as well.
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the resources or any part thereof are invalid.
     *
     * @return \Traversable returns an iterable object, each entry corresponding to the response of the upserted resource
     */
    public function upsertStreamResourceList(string $uri, array $uriParameters = [], $resources = []) : \Traversable;

    /**
     * Updates or creates several resources using a single JSON string for the request and the response.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     * @param array  $resources     array of resources to create or update.
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the resources or any part thereof are invalid.
     *
     * @return array
     */
    public function upsertJsonResourceList(string $uri, array $uriParameters = [], array $resources = []): array;

    /**
     * Deletes a resource.
     *
     * @param string $uri           URI of the resource to delete
     * @param array  $uriParameters URI parameters of the resource
     *
     * @throws HttpException If the request failed
     *
     * @return int Status code 204 indicating that the resource has been well deleted
     */
    public function deleteResource(string $uri, array $uriParameters = []): int;

    /**
     * Gets a streamed resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     *
     * @throws HttpException If the request failed
     *
     * @return ResponseInterface The response of the streamed resource request
     */
    public function getStreamedResource(string $uri, array $uriParameters = []): ResponseInterface;
}
