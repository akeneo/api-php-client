<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Exception\HttpException;

interface AssetAttributeOptionApiInterface
{
    /**
     * Get an attribute option for a given attribute of a given asset family.
     *
     * @throws HttpException
     */
    public function get(string $assetFamilyCode, string $attributeCode, string $attributeOptionCode): array;

    /**
     * Get the list of attribute options of a given attribute for a given asset family.
     *
     * @throws HttpException
     */
    public function all(string $assetFamilyCode, string $attributeCode): array;

    /**
     * Creates a asset attribute option if it does not exist yet, otherwise updates partially the attribute option.
     *
     * @throws HttpException
     *
     * @return int Status code 201 indicating that the asset attribute option has been well created.
     *             Status code 204 indicating that the asset attribute option has been well updated.
     */
    public function upsert(string $assetFamilyCode, string $attributeCode, string $attributeOptionCode, array $data = []): int;
}
