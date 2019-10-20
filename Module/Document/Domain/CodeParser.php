<?php
namespace Module\Document\Domain;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

class CodeParser {

    const NEW_LINE = PHP_EOL;
    const TAB = '    ';

    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_DOUBLE = 'double';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_NULL = 'null';

    protected $langMaps = [
        'ts' => 'Ts',
        'typescript' => 'Ts',
        'dart' => 'Dart',
        'flutter' => 'Dart',
        'go' => 'Go',
        'c#' => 'Net',
        'csharp' => 'Net',
        '.net' => 'Net',
        '.net core' => 'Net',
        'asp.net' => 'Net',
        'uwp' => 'Net',
        'wpf' => 'Net',
        'java' => 'java',
    ];

    public function formatHttp($api_id, $lang) {
        $api = ApiModel::find($api_id);
        $method = lcfirst(Str::studly(sprintf('format_http_%s', $this->langMaps[$lang])));

        $name = str_replace(['/', '.'], '_', trim($api->uri, '/'));
        $lines = [
            $this->formatField($api_id, 1, $name.'_request', $lang),
            $this->formatField($api_id, 2, $name.'_response', $lang),
        ];
        if (method_exists($this, $method)) {
            $lines[] = $this->{$method}($api, Str::studly($name), empty($lines[0]) ? null :
                Str::studly($name.'_request'), Str::studly($name.'_response'));
        }
        return implode(self::NEW_LINE, $lines);
    }

    public function formatField($api_id, $kind, $name, $lang, $parent_id = 0) {
        if (empty($name)) {
            $name = 'object_item';
        }
        $api_id = $api_id ? $api_id : 0;
        $packages = $this->formatFieldItem($name, $api_id, $kind, $parent_id);
        $files = [];
        foreach ($packages as $name => $package) {
            $content = $this->formatAttribute($package, $lang);
            $files[] = $this->formatPackage($name, $content, $package, $lang);
        }
        return implode(self::NEW_LINE, $files);
    }

    public function formatFieldItem($name, $api_id, $kind, $parent_id = 0) {
        $fields = FieldModel::where('kind', $kind)->where('api_id', $api_id)
            ->where('parent_id', $parent_id)->all();
        if ($kind != FieldModel::KIND_RESPONSE && empty($fields)) {
            return [];
        }
        $package = [$name => []];
        $maps = [
            'number' => 'int',
            'boolean'=> 'bool',
        ];
        foreach ($fields as $item) {
            $type = $item['type'];
            if (isset($maps[$item['type']])) {
                $type = $maps[$item['type']];
            }
            if ($type !== self::TYPE_ARRAY && $type !== self::TYPE_OBJECT) {
                $package[$name][] = [
                    'name' => $item['name'],
                    'type' => $type
                ];
                continue;
            }
            $itemPackage = $this->formatFieldItem($item['name'], $api_id, $kind, $item['id']);
            $package[$name][] = [
                'name' => $item['name'],
                'type' => $type,
                'package' => key($itemPackage)
            ];
            $package = array_merge($package, $itemPackage);
        }
        return $package;
    }

    public function formatJson($data, $name, $lang) {
        if (is_string($data)) {
            $data = Json::decode($data);
        }
        if (empty($data)) {
            return false;
        }
        if (empty($name)) {
            $name = 'object_item';
        }
        $packages = $this->formatItem($data, $name);
        $files = [];
        foreach ($packages as $name => $package) {
            $content = $this->formatAttribute($package, $lang);
            $files[] = $this->formatPackage($name, $content, $package, $lang);
        }
        return implode(self::NEW_LINE, $files);
    }

    public function formatAttribute(array $data, $lang) {
        $lines = [];
        foreach ($data as $item) {
            $method = lcfirst(Str::studly(sprintf('format_%s_%s', $this->langMaps[$lang], $item['type'])));
            if (method_exists($this, $method)) {
                $lines[] = $this->{$method}($item);
                continue;
            }
            dd($method);
        }
        return implode(self::NEW_LINE, $lines);
    }

    public function formatPackage($name, $attributes, array $package, $lang) {
        $method = lcfirst(Str::studly(sprintf('format_package_%s', $this->langMaps[$lang])));
        if (method_exists($this, $method)) {
            return $this->{$method}($name, $attributes, $package);
        }
        dd($method);
    }

    public function formatItem($data, $name) {
        $package = [$name => []];
        foreach ($data as $key => $item) {
            if (is_null($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_NULL
                ];
                continue;
            }
            if (is_string($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_STRING
                ];
                continue;
            }
            if (is_bool($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_BOOL
                ];
                continue;
            }
            if (is_integer($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_INT
                ];
                continue;
            }
            if (is_float($item) && strlen(preg_replace('/\d+\./', '', $item)) < 4) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_FLOAT
                ];
                continue;
            }
            if (is_double($item)) {
                $package[$name][] = [
                    'name' => $key,
                    'type' => self::TYPE_DOUBLE
                ];
                continue;
            }
            if (!is_array($item) || empty($item)) {
                continue;
            }
            $type = self::TYPE_OBJECT;
            if (!Arr::isAssoc($item)) {
                $type = self::TYPE_ARRAY;
                $item = reset($item);
            }
            $itemPackage = $this->formatItem($item, $key);
            $package[$name][] = [
                'name' => $key,
                'type' => $type,
                'package' => key($itemPackage)
            ];
            $package = array_merge($package, $itemPackage);
        }
        return $package;
    }




    // go

    private function goAttributeLine($name, $type) {
        $end = '';
        if (preg_match('/_.+s/', $name, $match)) {
            $end = sprintf('`json:"%s"`', $name);
        }
        return sprintf('%s%s %s%s', self::TAB, Str::studly($name), $type, $end);
    }

    private function formatGoString(array $item) {
        return $this->goAttributeLine($item['name'], 'string');
    }

    private function formatGoNull(array $item) {
        return $this->goAttributeLine($item['name'], 'interface{}');
    }

    private function formatGoInt(array $item) {
        return $this->goAttributeLine($item['name'], 'int');
    }

    private function formatGoFloat(array $item) {
        return $this->goAttributeLine($item['name'], 'float');
    }

    private function formatGoDouble(array $item) {
        return $this->goAttributeLine($item['name'], 'double');
    }

    private function formatGoBool(array $item) {
        return $this->goAttributeLine($item['name'], 'bool');
    }

    private function formatGoObject(array $item) {
        return $this->goAttributeLine($item['name'], Str::studly($item['package']));
    }

    private function formatGoArray(array $item) {
        return $this->goAttributeLine($item['name'], '[]'.Str::studly($item['package']));
    }

    private function formatPackageGo($name, $attributes, array $package) {
        return implode(self::NEW_LINE, [
            sprintf('type %s struct {', Str::studly($name)),
            $attributes,
            '}'
        ]);
    }

    // net

    private function netAttributeLine($name, $type) {
        $lines = [];
        if (preg_match('/_.+/', $name, $match)) {
            $lines[] = sprintf('%s[JsonProperty(PropertyName = "%s")]', self::TAB, $name);
        }
        $lines[] = sprintf('%spublic %s %s { get; set; }', self::TAB, $type, Str::studly($name));
        return implode(self::NEW_LINE, $lines);
    }

    private function formatNetString(array $item) {
        return $this->netAttributeLine($item['name'], 'string');
    }

    private function formatNetNull(array $item) {
        return $this->netAttributeLine($item['name'], 'object');
    }

    private function formatNetInt(array $item) {
        return $this->netAttributeLine($item['name'], 'int');
    }

    private function formatNetFloat(array $item) {
        return $this->netAttributeLine($item['name'], 'float');
    }

    private function formatNetDouble(array $item) {
        return $this->netAttributeLine($item['name'], 'double');
    }

    private function formatNetBool(array $item) {
        return $this->netAttributeLine($item['name'], 'bool');
    }

    private function formatNetObject(array $item) {
        return $this->netAttributeLine($item['name'], Str::studly($item['package']));
    }

    private function formatNetArray(array $item) {
        return $this->netAttributeLine($item['name'], sprintf('IEnumerable<%s>', Str::studly($item['package'])));
    }

    private function formatPackageNet($name, $attributes, array $package) {
        return implode(self::NEW_LINE, [
            sprintf('public class %s', Str::studly($name)),
            '{',
            $attributes,
            '}',
        ]);
    }

    private function formatHttpNet(ApiModel $api, $name, $request, $response) {
        return implode(self::NEW_LINE, [
            sprintf('public interface I%sRepository', $name),
            '{',
            sprintf('%sTask<%s> Get%sAsync(%s, Action<HttpException> action = null);',
                self::TAB, $response, $name, !empty($request) ? sprintf('%s params', $request) : ''),
            '}',
            sprintf('public class Rest%sRepository: I%sRepository', $name, $name),
            '{',
            self::TAB.'private readonly HttpHelper _http;',
            self::TAB.'public RestCategoryRepository(string baseUrl)',
            self::TAB.'{',
                self::TAB.self::TAB.'_http = new HttpHelper(baseUrl);',
            self::TAB.'}',
            sprintf('%spublic async Task<%s> Get%sAsync(%sAction<HttpException> action = null);',
                self::TAB, $response, $name, !empty($request) ? sprintf('%s params, ', $request) : ''),
            sprintf('%s%s=> await _http.%sAsync<%s>("%s", %saction);',
            self::TAB, self::TAB, strtolower($api->method), $response, $api->uri,
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


    // ts

    private function tsAttributeLine($name, $type) {
        return sprintf('%s%s: %s;', self::TAB, $name, $type);
    }

    private function formatTsString(array $item) {
        return $this->tsAttributeLine($item['name'], 'string');
    }

    private function formatTsNull(array $item) {
        return $this->tsAttributeLine($item['name'], 'any');
    }

    private function formatTsInt(array $item) {
        return $this->tsAttributeLine($item['name'], 'number');
    }

    private function formatTsFloat(array $item) {
        return $this->formatTsInt($item);
    }

    private function formatTsDouble(array $item) {
        return $this->formatTsInt($item);
    }

    private function formatTsBool(array $item) {
        return $this->tsAttributeLine($item['name'], 'boolean');
    }

    private function formatTsObject(array $item) {
        return $this->tsAttributeLine($item['name'], Str::studly($item['package']));
    }

    private function formatTsArray(array $item) {
        return $this->tsAttributeLine($item['name'], Str::studly($item['package']).'[]');
    }

    private function formatPackageTs($name, $attributes, array $package) {
        return implode(self::NEW_LINE, [
            sprintf('interface %s {', Str::studly($name)),
            $attributes,
            '}'
        ]);
    }

    private function formatHttpTs(ApiModel $api, $name, $request, $response) {
        $maps = [
            'get' => 'fetch',
            'delete' => 'deleteRequest'
        ];
        $method = strtolower($api->method);
        return implode(self::NEW_LINE, [
            sprintf('export const get%s = (%s) => %s%s%s<%s>(\'%s\'%s);', $name,
                !empty($request) ? sprintf('params: %s', $request) : '',
                self::NEW_LINE, self::TAB, isset($maps[$method]) ?
                    $maps[$method] : $method ,
                $response, $api->uri, !empty($request) ? ', params' : ''),
            sprintf('get%s({}).then(res => {});', $name),
        ]);
    }

    // dart

    private function dartAttributeLine($name, $type) {
        return sprintf('%s%s %s;', self::TAB, $type, lcfirst(Str::studly($name)));
    }

    private function formatDartString(array $item) {
        return $this->dartAttributeLine($item['name'], 'String');
    }

    private function formatDartNull(array $item) {
        return $this->dartAttributeLine($item['name'], 'Map');
    }

    private function formatDartInt(array $item) {
        return $this->dartAttributeLine($item['name'], 'int');
    }

    private function formatDartFloat(array $item) {
        return $this->formatDartDouble($item);
    }

    private function formatDartDouble(array $item) {
        return $this->dartAttributeLine($item['name'], 'double');
    }

    private function formatDartBool(array $item) {
        return $this->dartAttributeLine($item['name'], 'bool');
    }

    private function formatDartObject(array $item) {
        return $this->dartAttributeLine($item['name'], Str::studly($item['package']));
    }

    private function formatDartArray(array $item) {
        return $this->dartAttributeLine($item['name'], sprintf('List<%s>', Str::studly($item['package'])));
    }

    private function formatPackageDart($name, $attributes, array $package) {
        $packageName = Str::studly($name);
        $ctor = [];
        $from = [
            sprintf('%s%s.fromJson(Map<String, dynamic> json) {', self::TAB, $packageName)
        ];
        $tab = str_repeat(self::TAB, 2);
        $to = [
            sprintf('%sMap<String, dynamic> toJson() {', self::TAB),
            sprintf('%sfinal Map<String, dynamic> data = new Map<String, dynamic>();', $tab)
        ];
        foreach ($package as $item) {
            $name = lcfirst(Str::studly($item['name']));
            $ctor[] = 'this.'.$name;
            $from[] = sprintf('%s%s = json[\'%s\'];', $tab, $name, $item['name']);
            $to[] = sprintf('%s%s = json[\'%s\'];', $tab, $name, $item['name']);
        }
        $from[] = sprintf('%s}', self::TAB);
        $to[] = sprintf('%sreturn data;', $tab);
        $to[] = sprintf('%s}', self::TAB);
        return implode(self::NEW_LINE, [
            sprintf('class %s {', $packageName),
            $attributes,
            sprintf('%s%s({%s});', self::TAB, $packageName, implode(', ', $ctor)),
            implode(self::NEW_LINE, $from),
            implode(self::NEW_LINE, $to),
            '}'
        ]);
    }

    private function formatHttpDart(ApiModel $api, $name, $request, $response) {
        return implode(self::NEW_LINE, [
            sprintf('Future<%s> get%s(%s[func action]) async {', $response, $name,
                !empty($request) ? sprintf('%s params, ', $request) : ''),
            sprintf('%sreturn await RestClient.%s<%s>(\'%s\', %saction)', self::TAB,
                strtolower($api->method), $response, $api->uri,
                !empty($request) ? 'params, ' : ''
            ),
            sprintf('var data = await get%s({});', $name),
        ]);
    }
}