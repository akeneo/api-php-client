<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ReferenceEntityAttributeOptionApiInterface
{
    /**
     * Get an attribute option for a given attribute of a given reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $attributeCode       Code of the attribute
     * @param string $attributeOptionCode Code of the attribute option
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get(string $referenceEntityCode, string $attributeCode, string $attributeOptionCode): array;

    /**
     * Get the list of attribute options of a given attribute for a given reference entity.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $attributeCode       Code of the attribute
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function all(string $referenceEntityCode, string $attributeCode): array;

    /**
     * Creates a reference entity attribute option if it does not exist yet, otherwise updates partially the attribute option.
     *
     * @param string $referenceEntityCode Code of the reference entity
     * @param string $attributeCode       Code of the attribute
     * @param string $attributeOptionCode Code of the attribute option
     * @param array  $data                Data of the attribute option to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the reference entity attribute option has been well created.
     *             Status code 204 indicating that the reference entity attribute option has been well updated.
     */
    public function upsert(string $referenceEntityCode, string $attributeCode, string $attributeOptionCode, array $data = []): int;
}
