<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class Kotlin implements ParserInterface {

    public function languages(): array {
        return ['kotlin'];
    }

    private function kotlinAttributeLine($name, $type) {
        return sprintf('%svar %s: %s', CodeParser::TAB, lcfirst(Str::studly($name)), $type);
    }

    public function formatString(array $item): string {
        return $this->kotlinAttributeLine($item['name'], 'String');
    }

    public function formatNull(array $item): string {
        return $this->kotlinAttributeLine($item['name'], 'Any');
    }

    public function formatInt(array $item): string {
        return $this->kotlinAttributeLine($item['name'], 'Int');
    }

    public function formatFloat(array $item): string {
        return $this->formatDouble($item);
    }

    public function formatDouble(array $item): string {
        return $this->kotlinAttributeLine($item['name'], 'Double');
    }

    public function formatBool(array $item): string {
        return $this->kotlinAttributeLine($item['name'], 'Boolean');
    }

    public function formatObject(array $item): string {
        return $this->kotlinAttributeLine($item['name'], Str::studly($item['package']));
    }

    public function formatArray(array $item): string {
        return $this->kotlinAttributeLine($item['name'], sprintf('List<%s>', Str::studly($item['package'])));
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        $packageName = Str::studly($name);
        $ctor = [];
        $from = [
            sprintf('%sfun fromJson(json: JSONObject) {', CodeParser::TAB)
        ];
        $tab = str_repeat(CodeParser::TAB, 2);
        $to = [
            sprintf('%sfun toJson(): JSONObject {', CodeParser::TAB),
            sprintf('%svar json = SONObject()', $tab)
        ];
        $types = [
            CodeParser::TYPE_INT => 'Int',
            CodeParser::TYPE_STRING => 'String',
            CodeParser::TYPE_BOOL => 'Boolean',
            CodeParser::TYPE_DOUBLE => 'Double',
            CodeParser::TYPE_FLOAT => 'Double',
        ];
        foreach ($package as $item) {
            $name = lcfirst(Str::studly($item['name']));
            $ctor[] = 'this.'.$name;
            $from[] = sprintf('%s%s = json.get%s("%s")', $tab, $name, isset($types[$item['type']]) ? $types[$item['type']] : 'String', $item['name']);
            $to[] = sprintf('%sjson.put("%s", %s)', $tab, $item['name'], $name);
        }
        $from[] = sprintf('%s}', CodeParser::TAB);
        $to[] = sprintf('%sreturn json', $tab);
        $to[] = sprintf('%s}', CodeParser::TAB);
        return implode(CodeParser::NEW_LINE, [
            sprintf('data class %s {', $packageName),
            $attributes,
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