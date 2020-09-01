<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class Go implements ParserInterface {

    public function languages(): array {
        return ['go', 'golang'];
    }

    private function goAttributeLine($name, $type) {
        $end = '';
        if (preg_match('/_.+s/', $name, $match)) {
            $end = sprintf('`json:"%s"`', $name);
        }
        return sprintf('%s%s %s%s', CodeParser::TAB, Str::studly($name), $type, $end);
    }

    public function formatString(array $item): string {
        return $this->goAttributeLine($item['name'], 'string');
    }

    public function formatNull(array $item): string {
        return $this->goAttributeLine($item['name'], 'interface{}');
    }

    public function formatInt(array $item): string {
        return $this->goAttributeLine($item['name'], 'int');
    }

    public function formatFloat(array $item): string {
        return $this->goAttributeLine($item['name'], 'float');
    }

    public function formatDouble(array $item): string {
        return $this->goAttributeLine($item['name'], 'double');
    }

    public function formatBool(array $item): string {
        return $this->goAttributeLine($item['name'], 'bool');
    }

    public function formatObject(array $item): string {
        return $this->goAttributeLine($item['name'], Str::studly($item['package']));
    }

    public function formatArray(array $item): string {
        return $this->goAttributeLine($item['name'], '[]'.Str::studly($item['package']));
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        return implode(CodeParser::NEW_LINE, [
            sprintf('type %s struct {', Str::studly($name)),
            $attributes,
            '}'
        ]);
    }

    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string {
        return '';
    }
}