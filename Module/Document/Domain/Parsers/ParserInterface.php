<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\Model\ApiModel;

interface ParserInterface {

    /**
     * 获取支持的语言
     * @return array
     */
    public function languages(): array;

    public function formatString(array $item): string;
    public function formatNull(array $item): string;
    public function formatInt(array $item): string;
    public function formatFloat(array $item): string;
    public function formatDouble(array $item): string;
    public function formatBool(array $item): string;
    public function formatObject(array $item): string;
    public function formatArray(array $item): string;

    public function formatPackage(string $name, string $attributes, array $package): string;
    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string;
}