<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

interface AssetFamilyApiInterface
{
    /**
     * Gets a single asset family.
     *
     * @throws HttpException
     */
    public function get(string $assetFamilyCode): array;

    /**
     * Gets a cursor to iterate over a list of asset families.
     *
     * @throws HttpException
     */
    public function all(array $queryParameters = []): ResourceCursorInterface;

    /**
     * Creates an asset family if it does not exist yet, otherwise updates partially the asset family.
     *
     * @throws HttpException
     *
     * @return int Status code 201 indicating that the asset family has been well created.
     *             Status code 204 indicating that the asset family has been well updated.
     */
    public function upsert(string $assetFamilyCode, array $data = []): int;
}
