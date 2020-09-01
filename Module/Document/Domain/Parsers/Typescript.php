<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class Typescript implements ParserInterface {

    public function languages(): array {
        return ['typescript', 'ts'];
    }

    private function tsAttributeLine($name, $type) {
        return sprintf('%s%s: %s;', CodeParser::TAB, $name, $type);
    }

    public function formatString(array $item): string {
        return $this->tsAttributeLine($item['name'], 'string');
    }

    public function formatNull(array $item): string {
        return $this->tsAttributeLine($item['name'], 'any');
    }

    public function formatInt(array $item): string {
        return $this->tsAttributeLine($item['name'], 'number');
    }

    public function formatFloat(array $item): string {
        return $this->formatInt($item);
    }

    public function formatDouble(array $item): string {
        return $this->formatInt($item);
    }

    public function formatBool(array $item): string {
        return $this->tsAttributeLine($item['name'], 'boolean');
    }

    public function formatObject(array $item): string {
        return $this->tsAttributeLine($item['name'], Str::studly($item['package']));
    }

    public function formatArray(array $item): string {
        return $this->tsAttributeLine($item['name'], Str::studly($item['package']).'[]');
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        return implode(CodeParser::NEW_LINE, [
            sprintf('interface %s {', Str::studly($name)),
            $attributes,
            '}'
        ]);
    }

    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string {
        $maps = [
            'get' => 'fetch',
            'delete' => 'deleteRequest'
        ];
        $method = strtolower($api->method);
        return implode(CodeParser::NEW_LINE, [
            sprintf('export const get%s = (%s) => %s%s%s<%s>(\'%s\'%s);', $name,
                !empty($request) ? sprintf('params: %s', $request) : '',
                CodeParser::NEW_LINE, CodeParser::TAB, isset($maps[$method]) ?
                    $maps[$method] : $method ,
                $response, $api->uri, !empty($request) ? ', params' : ''),
            sprintf('get%s({}).then(res => {});', $name),
        ]);
    }
}