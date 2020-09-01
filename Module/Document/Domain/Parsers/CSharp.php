<?php
namespace Module\Document\Domain\Parsers;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\ApiModel;
use Zodream\Helpers\Str;

class CSharp implements ParserInterface {

    public function languages(): array {
        return ['c#', 'csharp', '.net', '.net core', 'asp.net', 'uwp', 'wpf'];
    }

    private function netAttributeLine($name, $type) {
        $lines = [];
        if (preg_match('/_.+/', $name, $match)) {
            $lines[] = sprintf('%s[JsonProperty(PropertyName = "%s")]', CodeParser::TAB, $name);
        }
        $lines[] = sprintf('%spublic %s %s { get; set; }', CodeParser::TAB, $type, Str::studly($name));
        return implode(CodeParser::NEW_LINE, $lines);
    }

    public function formatString(array $item): string {
        return $this->netAttributeLine($item['name'], 'string');
    }

    public function formatNull(array $item): string {
        return $this->netAttributeLine($item['name'], 'object');
    }

    public function formatInt(array $item): string {
        return $this->netAttributeLine($item['name'], 'int');
    }

    public function formatFloat(array $item): string {
        return $this->netAttributeLine($item['name'], 'float');
    }

    public function formatDouble(array $item): string {
        return $this->netAttributeLine($item['name'], 'double');
    }

    public function formatBool(array $item): string {
        return $this->netAttributeLine($item['name'], 'bool');
    }

    public function formatObject(array $item): string {
        return $this->netAttributeLine($item['name'], Str::studly($item['package']));
    }

    public function formatArray(array $item): string {
        return $this->netAttributeLine($item['name'], sprintf('IEnumerable<%s>', Str::studly($item['package'])));
    }

    public function formatPackage(string $name, string $attributes, array $package): string {
        return implode(CodeParser::NEW_LINE, [
            sprintf('public class %s', Str::studly($name)),
            '{',
            $attributes,
            '}',
        ]);
    }

    public function formatHttp(ApiModel $api, string $name, string $request, string $response): string {
        return implode(CodeParser::NEW_LINE, [
            sprintf('public interface I%sRepository', $name),
            '{',
            sprintf('%sTask<%s> Get%sAsync(%s, Action<HttpException> action = null);',
                CodeParser::TAB, $response, $name, !empty($request) ? sprintf('%s params', $request) : ''),
            '}',
            sprintf('public class Rest%sRepository: I%sRepository', $name, $name),
            '{',
            CodeParser::TAB.'private readonly HttpHelper _http;',
            CodeParser::TAB.'public RestCategoryRepository(string baseUrl)',
            CodeParser::TAB.'{',
            CodeParser::TAB.CodeParser::TAB.'_http = new HttpHelper(baseUrl);',
            CodeParser::TAB.'}',
            sprintf('%spublic async Task<%s> Get%sAsync(%sAction<HttpException> action = null)',
                CodeParser::TAB, $response, $name, !empty($request) ? sprintf('%s params, ', $request) : ''),
            sprintf('%s%s=> await _http.%sAsync<%s>("%s", %saction);',
                CodeParser::TAB, CodeParser::TAB, ucfirst(strtolower($api->method)), $response, $api->uri,
                !empty($request) ? 'params, ' : ''),
            '}',
            '// 注册',
            sprintf('public I%sRepository %s => new Rest%sRepository(_url);', $name, $name, $name),

            '// 使用',
            sprintf('var data = await App.Repository.%s.Get%sAsync();', $name, $name),
            'if (data == null)',
            '{',
            '    return;',
            '}',
            'await DispatcherHelper.ExecuteOnUIThreadAsync(() =>',
            '{',
            '',
            '});'
        ]);
    }
}