<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class Dart implements ParserInterface {

    public function languages(): array {
        return ['dart', 'flutter'];
    }

    private function dartAttributeLine($name, $type) {
        return sprintf('%s%s %s;', CodeParser::TAB, $type, lcfirst(Str::studly($name)));
    }

    public function formatString(array $item): string {
        return $this->dartAttributeLine($item['name'], 'String');
    }

    public function formatNull(array $item): string {
        return $this->dartAttributeLine($item['name'], 'dynamic');
    }

    public function formatInt(array $item): string {
        return $this->dartAttributeLine($item['name'], 'int');
    }

    public function formatFloat(array $item): string {
        return $this->formatDouble($item);
    }

    public function formatDouble(array $item): string {
        return $this->dartAttributeLine($item['name'], 'double');
    }

    public function formatBool(array $item): string {
        return $this->dartAttributeLine($item['name'], 'bool');
    }

    public function formatObject(array $item): string {
        return $this->dartAttributeLine($item['name'], Str::studly($item['package']));
    }

    public function formatArray(array $item): string {
        return $this->dartAttributeLine($item['name'], sprintf('List<%s>', Str::studly($item['package'])));
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        $packageName = Str::studly($name);
        $ctor = [];
        $from = [
            sprintf('%s%s.fromJson(Map<String, dynamic> json) {', CodeParser::TAB, $packageName)
        ];
        $tab = str_repeat(CodeParser::TAB, 2);
        $to = [
            sprintf('%sMap<String, dynamic> toJson() {', CodeParser::TAB),
            sprintf('%sfinal Map<String, dynamic> json = new Map<String, dynamic>();', $tab)
        ];
        foreach ($package as $item) {
            $name = lcfirst(Str::studly($item['name']));
            $ctor[] = 'this.'.$name;
            $from[] = sprintf('%s%s = json[\'%s\'];', $tab, $name, $item['name']);
            $to[] = sprintf('%sjson[\'%s\'] = %s;', $tab, $item['name'], $name);
        }
        $from[] = sprintf('%s}', CodeParser::TAB);
        $to[] = sprintf('%sreturn json;', $tab);
        $to[] = sprintf('%s}', CodeParser::TAB);
        return implode(CodeParser::NEW_LINE, [
            sprintf('class %s {', $packageName),
            $attributes,
            sprintf('%s%s({%s});', CodeParser::TAB, $packageName, implode(', ', $ctor)),
            implode(CodeParser::NEW_LINE, $from),
            implode(CodeParser::NEW_LINE, $to),
            '}'
        ]);
    }

    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string {
        return implode(CodeParser::NEW_LINE, [
            sprintf('Future<%s> get%s(%s[func action]) async {', $response, $name,
                !empty($request) ? sprintf('%s params, ', $request) : ''),
            sprintf('%sreturn await RestClient.%s<%s>(\'%s\', %saction)', CodeParser::TAB,
                strtolower($api->method), $response, $api->uri,
                !empty($request) ? 'params, ' : ''
            ),
            sprintf('var data = await get%s({});', $name),
        ]);
    }
}