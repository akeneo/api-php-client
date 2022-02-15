<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * API to mange the reference entities.
 *
 * @author    Tamara Robichet <tamara.robichet@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ReferenceEntityApiInterface
{
    /**
     * Gets a single reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get(string $referenceEntityCode): array;

    /**
     * Gets a cursor to iterate over the list of reference entities
     *
     * @param array  $queryParameters     Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResourceCursorInterface
     */
    public function all(array $queryParameters = []): ResourceCursorInterface;

    /**
     * Creates a reference entity if it does not exist yet, otherwise updates partially the reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param array  $data                Data of the reference entity to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the reference entity has been well created.
     *             Status code 204 indicating that the reference entity has been well updated.
     */
    public function upsert(string $referenceEntityCode, array $data = []): int;
}
