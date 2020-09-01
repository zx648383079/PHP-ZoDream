<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class Unknow implements ParserInterface {

    public function languages(): array {
        return [];
    }

    public function formatString(array $item): string {
        return '';
    }

    public function formatNull(array $item): string {
        return '';
    }

    public function formatInt(array $item): string {
        return '';
    }

    public function formatFloat(array $item): string {
        return '';
    }

    public function formatDouble(array $item): string {
        return '';
    }

    public function formatBool(array $item): string {
        return '';
    }

    public function formatObject(array $item): string {
        return '';
    }

    public function formatArray(array $item): string {
        return '';
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        return '';
    }

    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string {
        return '';
    }
}