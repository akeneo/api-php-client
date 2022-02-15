<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ReferenceEntityAttributeApiInterface
{
    /**
     * Gets a single reference entity attribute.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $attributeCode       Code of the attribute
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get(string $referenceEntityCode, string $attributeCode): array;

    /**
     * Gets the list of the attributes of a given reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param array  $queryParameters     Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function all(string $referenceEntityCode, array $queryParameters = []): array;

    /**
     * Creates a reference entity attribute if it does not exist yet, otherwise updates partially the attribute.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $attributeCode       Code of the attribute
     * @param array  $data                Data of the attribute to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the reference entity attribute has been well created.
     *             Status code 204 indicating that the reference entity attribute has been well updated.
     */
    public function upsert(string $referenceEntityCode, string $attributeCode, array $data = []): int;
}
