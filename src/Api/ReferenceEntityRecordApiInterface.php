<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * API to mange the reference entity records.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ReferenceEntityRecordApiInterface
{
    /**
     * Gets a single reference entity record.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $recordCode          Code of the record
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get(string $referenceEntityCode, string $recordCode): array;

    /**
     * Gets a cursor to iterate over the list of records of a given reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param array  $queryParameters     Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResourceCursorInterface
     */
    public function all(string $referenceEntityCode, array $queryParameters = []): ResourceCursorInterface;

    /**
     * Creates a reference entity record if it does not exist yet, otherwise updates partially the record.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $recordCode          Code of the record
     * @param array  $data                Data of the record to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the reference entity record has been well created.
     *             Status code 204 indicating that the reference entity record has been well updated.
     */
    public function upsert(string $referenceEntityCode, string $recordCode, array $data = []): int;

    /**
     * Updates or creates several reference entity records.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param array  $records             Array containing the records to create or update
     *
     * @throws HttpException
     *
     * @return array returns the list of the responses of each created or updated record.
     */
    public function upsertList(string $referenceEntityCode, array $records): array;
}
