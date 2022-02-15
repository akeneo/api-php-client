<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Exception\HttpException;

interface AssetAttributeApiInterface
{
    /**
     * Gets a single asset attribute.
     *
     * @throws HttpException
     */
    public function get(string $assetFamilyCode, string $attributeCode): array;

    /**
     * Gets the list of the attributes of a given asset family.
     *
     * @throws HttpException
     */
    public function all(string $assetFamilyCode, array $queryParameters = []): array;

    /**
     * Creates a asset attribute if it does not exist yet, otherwise updates partially the attribute.
     *
     * @throws HttpException
     *
     * @return int Status code 201 indicating that the asset attribute has been well created.
     *             Status code 204 indicating that the asset attribute has been well updated.
     */
    public function upsert(string $assetFamilyCode, string $attributeCode, array $data = []): int;
}
