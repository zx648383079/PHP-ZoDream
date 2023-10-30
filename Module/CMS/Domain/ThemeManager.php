<?php
declare(strict_types=1);
namespace Module\CMS\Domain;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Scene\MultiScene;
use Module\Forum\Domain\Model\ForumModel;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\Database\DB;
use Zodream\Database\Relation;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Error\Exception;

class ThemeManager {

    /**
     * @var Directory
     */
    protected Directory $src;
    /**
     * @var Directory
     */
    protected Directory $dist;

    protected array $cache = [];

    public function __construct() {
        $this->src = ThemeManager::themeRootFolder();
        $this->dist = public_path();
    }

    /**
     * 获取主题的路径
     * @return Directory
     * @throws \Exception
     */
    public static function themeRootFolder(): Directory {
        $path = config('view.cms_directory');
        if (empty($path)) {
            return new Directory(dirname(__DIR__).'/UserInterface');
        }
        return app_path()->directory($path);
    }

    /**
     * @return Directory
     */
    public function getSrc() {
        return $this->src;
    }

    public function pack(): void {
        $this->src = $this->src->directory('default_'.time());
        $data = [
            'name' => 'default',
            'description' => '默认主题',
            'author' => 'zodream',
            'cover' => 'assets/images/screenshot.png',
            'script' => [
                [
                    'action' => 'copy',
                    'src' => 'assets',
                    'dist' => 'assets'
                ],
            ]
        ];
        $data[] = $this->packOption();
        $data['script'] = array_merge($data['script'], $this->packModel(), $this->packChannel(), $this->packContent());
        $this->src->create();
        $this->src->addFile('theme.json', Json::encode($data));
        $zip = ZipStream::create($this->dist->file('theme.zip'));
        $zip->addDirectory($data['name'], $this->src);
        $zip->comment($data['description'])->close();
    }

    protected function packOption(): array {
        $data = [];
        $args = OptionModel::query()->orderBy('parent_id', 'asc')->all();
        foreach ($args as $item) {
            if ($item['parent_id'] < 1) {
                unset($item['parent_id']);
            } else {
                $item['parent_id'] = $this->getCacheId($item['parent_id'], 'option');
            }
            $this->setCache([
                $item['id'] => '@option:'.$item['code']
            ], 'option');
            unset($item['id']);
            $data[] = $item;
        }
        return [
            'action' => 'option',
            'data' => $data
        ];
    }

    protected function packModel(): array {
        $data = [];
        $model_list = ModelModel::query()->asArray()->all();
        foreach ($model_list as $item) {
            $this->setCache([
                $item['id'] => '@model:'.$item['table']
            ], 'model');
        }
        foreach ($model_list as $item) {
            $item['action'] = 'model';
            $item['setting'] = Json::decode($item['setting']);
            $item['fields'] = $this->packFields($item['id']);
            $item['child'] = $this->getCacheId($item['child_model'], 'model');
            unset($item['id'], $item['child_model']);
            $data[] = $item;

        }
        return $data;
    }

    protected function packFields(int $model_id): array {
        $data = [];
        $fields = ModelFieldModel::query()->where('model_id', $model_id)->asArray()->get();
        foreach ($fields as $item) {
            $item['setting'] = Json::decode($item['setting']);
            unset($item['model_id'], $item['id']);
            if (!in_array($item['field'], ['title', 'keywords', 'description', 'thumb', 'content'])) {
                $data[] = $item;
                continue;
            }
            if ($item['is_disable'] > 0) {
                $data[] = [
                    'action' => 'disable',
                    'field' => $item['field']
                ];
            }
        }
        return $data;
    }

    protected function packChannel(int $parent_id = 0): array {
        $data = [];
        $model_list = CategoryModel::query()->where('parent_id', $parent_id)
            ->asArray()->all();
        foreach ($model_list as $item) {
            $item['action'] = 'channel';
            $item['setting'] = Json::decode($item['setting']);
            $children = $this->packChannel($item['id']);
            if (!empty($children)) {
                $item['children'] = $children;
            }
            $item['type'] = $this->packChannelType($item);
            $this->setCache([
                $item['id'] => '@channel:'.$item['name']
            ], 'channel');
            unset($item['model_id'], $item['parent_id'], $item['id']);
            $data[] = $item;

        }
        return $data;
    }

    protected function packChannelType(array $item): string|int {
        if ($item['type'] === CategoryEntity::TYPE_LINK) {
            return 'link';
        }
        if ($item['type'] === CategoryEntity::TYPE_PAGE) {
            return 'page';
        }
        return $this->getCacheId($item['model_id'], 'model');
    }

    protected function packContent(): array {
        $data = [];
        $model_list = ModelModel::query()->get();
        foreach ($model_list as $model) {
            $scene = CMSRepository::scene()->setModel($model);
            $cats = CategoryModel::where('model_id', $model->id)->pluck('id');
            if (empty($cats)) {
                continue;
            }
            $args = $scene->query()->whereIn('cat_id', $cats)->all();
            $args = Relation::create($args, [
                'extend' => [
                    'query' => $scene->extendQuery(),
                    'link' => [
                        'id' => 'id'
                    ],
                    'type' => Relation::TYPE_ONE
                ],
            ]);
            foreach ($args as $item) {
                if (!empty($item['extend'])) {
                    $item = array_merge($item['extend'], $item);
                }
                $item['cat_id'] = $this->getCacheId($item['cat_id'], 'channel');
                unset($item['extend'], $item['id']);
                $item['action'] = 'content';
                $data[] = $item;
            }
        }
        return $data;
    }

    public function unpack(): void {
        $zip = new ZipStream($this->src->file('theme.zip'));
        $zip->extractTo($this->src);
        $this->apply('theme');
    }

    public function apply(string $theme = ''): void {
        if (!empty($theme)) {
            $this->src = $this->src->directory($theme);
        }
        $file = $this->src->file('theme.json');
        if (!$file->exist()) {
            return;
        }
        $this->setCache(GroupModel::pluck('id', 'name'), 'group');
        $this->setCache(ModelModel::query()->pluck('id', 'table'), 'model');
        $this->setCache(CategoryModel::pluck('id', 'name'), 'channel');
        $configs = Json::decode($file->read());
        $this->runScript($configs['script']);
    }

    protected function setCache(array $data, string $prefix): void {
        foreach ($data as $name => $id) {
            $this->cache[sprintf('@%s:%s', $prefix, $name)] = $id;
        }
    }

    protected function getCacheId(string|int $name, string $prefix = ''): mixed {
        $key = empty($prefix) && str_starts_with($name, '@') ? $name : sprintf('@%s:%s', $prefix, $name);
        return $this->cache[$key] ?? 0;
    }

    protected function runScript(array $data): void {
        usort($data, function ($pre, $next) {
            $maps = ['group' => 1, 'linkage' => 2, 'model' => 3, 'form' => 4, 'field' => 5, 'channel' => 6, 'content' => 7];
            if (!isset($maps[$pre['action']])) {
                return -1;
            }
            if (!isset($maps[$next['action']])) {
                return 1;
            }
            if ($maps[$pre['action']] > $maps[$next['action']]) {
                return 1;
            }
            return $maps[$pre['action']] < $maps[$next['action']] ? -1 : 0;
        });
        foreach ($data as $item) {
            $method = 'runAction'.Str::studly($item['action']);
            if (method_exists($this, $method)) {
                $this->{$method}($item);
            }
        }
    }



    protected function runActionCopy(array $data): void {
        $this->src->directory($data['src'])->copy($this->dist->directory($data['dist']));
    }

    protected function runActionGroup(array $data): void {
        if (!isset($data['data'])) {
            $this->insertGroup($data);
        }
    }

    protected function runActionForm(array $data): void {
        $data['type'] = 1;
        $this->runActionModel($data);
    }

    protected function runActionLinkage(array $data): void {
        $data = $this->formatI18n($data);
        $items = $data['data'] ?? [];
        unset($data['data'], $data['action']);
        $data['language'] = CMSRepository::siteLanguage();
        $model = LinkageModel::where('code', $data['code'])
            ->where('language', $data['language'])->first();
        if (empty($model)) {
            $model = LinkageModel::create($data);
        }
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->code => $model->id, $model->id => $model], 'linkage');
        $this->runActionLinkageData($items, 0, '', $model->id);
    }

    public function runActionLinkageData(array $data, int $parent_id, string $prefix, int $linkage_id): void {
        foreach ($data as $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);
            $item = $this->formatI18n($item);
            $item['parent_id'] = $parent_id;
            $item['linkage_id'] = $linkage_id;
            $item['full_name'] = $prefix.' '.$item['name'];
            $model = LinkageDataModel::create($item);
            if (!$model || empty($children)) {
                continue;
            }
            $this->runActionLinkageData($children, $model->id, $item['full_name'], $linkage_id);
        }
    }

    protected function runActionModel(array $data): void {
        $fields = $data['fields'] ?? [];
        if (isset($data['child'])) {
            $data['child_model'] = $this->getCacheId($data['child']);
        }
        unset($data['fields'], $data['action'], $data['child']);
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        $model = ModelModel::where('`table`', $data['table'])->first();
        if (empty($model)) {
            $model = ModelModel::create($data);
        }
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->table => $model->id, $model->id => $model], 'model');
        CMSRepository::scene()->setModel($model)->initModel();
        foreach ($fields as $field) {
            $field['model_id'] = $model->id;
            $this->runActionField($field);
        }
    }

    protected function runActionField(array $data): void {
        if (isset($data['model'])) {
            $data['model_id'] = $this->getCacheId($data['model']);
        }
        if (isset($data['action']) && $data['action'] === 'disable') {
            ModelFieldModel::query()->where('model_id', $data['model_id'])
                ->where('field', $data['field'])
                ->update([
                    'is_disable' => 1
                ]);
            return;
        }
        unset($data['model'], $data['action']);
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        if (!isset($data['type'])) {
            $data['type'] = 'text';
        } elseif (str_starts_with($data['type'], '@model:')) {
            $data['setting']['option']['model'] = $this->getCacheId($data['type']);
            $data['type'] = 'model';
        } elseif (str_starts_with($data['type'], '@')) {
            $type = $data['type'];
            $i = strpos($type, ':');
            $data['type'] = substr($type, 1, $i === false ? null : ($i - 1));
            if (in_array($data['type'], ['linkage', 'linkages'])) {
                $data['setting']['option']['linkage_id'] = substr($type, $i + 1); //$this->getCacheId($data['type']);
            }
        }
        $model = ModelFieldModel::where('field', $data['field'])->where('model_id', $data['model_id'])
            ->first();
        if (!empty($model)) {
            if (empty($data['name']) || $model->name === $data['name']) {
                return;
            }
            /** @var ModelFieldModel $model */
            $model->name = $data['name'];
            $model->save();
            return;
        }
        $scene = CMSRepository::scene();
        $model = ModelFieldModel::createOrThrow($data, '数据错误');
        $scene = $scene->setModel($this->getCacheId((string)$model->model_id, 'model'));
        $scene->addField($model);
    }

    protected function runActionChannel(array $data): void {
        $data = $this->formatI18n($data);
        $type = $data['type'] ?? null;
        if ($type === 'link') {
            $data['type'] = CategoryEntity::TYPE_LINK;
        } else if (!empty($type)) {
            $data['model_id'] = $this->getCacheId($type);
            $data['type'] = CategoryEntity::TYPE_CONTENT;
        } else {
            $data['type'] = CategoryEntity::TYPE_PAGE;
        }
        $children = $data['children'] ?? [];
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        if (isset($data['group'])) {
            $data['groups'] = implode(',', (array)$data['group']);
        }
        if (empty($data['name'])) {
            $data['name'] = CMSRepository::generateTableName($data['title']);
        }
        $model = CategoryModel::where('name', $data['name'])->first();
        if (empty($model)) {
            $model = CategoryModel::create($data);
        }
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->name => $model->id, $model->id => $model], 'channel');
        foreach ($children as $item) {
            if (isset($item['action']) && $item['action'] === 'content') {
                $item['cat_id'] = $model->id;
                $this->runActionContent($item);
                continue;
            }
            if (empty($item['type'])) {
                $item['type'] = $type;
            }
            $item['parent_id'] = $model->id;
            $this->runActionChannel($item);
        }
    }

    protected function runActionContent(array $data): void {
        if (isset($data['cat_id']) && !is_numeric($data['cat_id'])) {
            $data['cat_id'] = $this->getCacheId($data['cat_id']);
        } elseif (isset($data['type'])) {
            $data['cat_id'] = $this->getCacheId($data['type']);
        }
        unset($data['type'], $data['action']);
        $cat = $this->getCacheId($data['cat_id'], 'channel');
        $scene = CMSRepository::scene()
            ->setModel($this->getCacheId($cat->model_id, 'model'));
        $scene->insert($this->formatI18n($data));
    }

    protected function insertGroup(array $item): void {
        if ($this->getCacheId($item['name'], 'group') > 0) {
            return;
        }
        $model = GroupModel::where('name', $item['name'])->first();
        if (empty($model)) {
            $model = GroupModel::create($item);
        }
        if ($model) {
            $this->setCache([$model->name => $model->id], 'group');
        }
    }

    protected function runActionOption(array $data): void {
        $newOptions = [];
        foreach ($data['data'] as $item) {
            if (isset($item['items'])) {
                $item['default_value'] = implode("\n", $item['items']);
            }
            if (isset($item['default'])) {
                $item['default_value'] = $item['default'];
            }
            unset($item['default'], $item['items'], $item['id']);
            $newOptions[] = $item;
        }
        CMSRepository::site()->saveOption($newOptions);
    }

    protected function formatI18n(array $data): array {
        $lang = CMSRepository::siteLanguage();
        $res = [];
        foreach ($data as $key => $item) {
            $i = strpos($key, ':');
            if ($i === false) {
                $res[$key] = $item;
                continue;
            }
            if (substr($key, 0, $i) !== $lang) {
                continue;
            }
            $res[substr($key, $i + 1)] = $item;
        }
        return $res;
    }

    protected function getOptionParent(string $code): int {
        $code = substr($code, 8);
        return intval(OptionModel::where('code', $code)->value('id'));
    }

    /**
     * 获取主题
     * @return array{name: string, description: string, author: string, cover: string}[]
     */
    public function loadThemes(): array {
        $data = [];
        $this->src->map(function ($file) use (&$data) {
            if (!$file instanceof Directory) {
                return;
            }
            $json = $file->file('theme.json');
            if (!$json->exist()) {
                return;
            }
            $item = Json::decode($json->read());
            $data[] = [
                'name' => $item['name'],
                'description' => $item['description'],
                'author' => $item['author'],
                'cover' => $item['cover'],
            ];
        });
        return $data;
    }

    /**
     * 获取主题下的模板
     * @param string $theme
     * @return array{content: string[], channel: string[], form: string[]}
     * @throws \Exception
     */
    public function loadTemplates(string $theme): array {
        $folder = static::themeRootFolder()->directory($theme);
        if (!$folder->exist()) {
            return [];
        }
        $cb = function (Directory $root) {
            if (!$root->exist()) {
                return [];
            }
            $items = [];
            $root->mapDeep(function ($file) use ($root, &$items) {
                if (!($file instanceof File)) {
                    return;
                }
                if ($file->getExtension() !== 'html') {
                    return;
                }
                $name = substr($file->getRelative($root), 0, -5);
                $items[] = ['name' => $name, 'value' => $name];
            });
            return $items;
        };
        return [
            'content' => $cb($folder->directory('Content')),
            'channel' => $cb($folder->directory('Category')),
            'form' => $cb($folder->directory('form')),
        ];
    }

    public static function clear(): void {
        $truncateTables = [
            ModelModel::tableName(),
            ModelFieldModel::tableName(),
            CategoryModel::tableName(),
            GroupModel::tableName(),
            LinkageModel::tableName(),
            LinkageDataModel::tableName(),
            ForumModel::tableName()
        ];
        $model_list = ModelModel::query()->get();
        foreach ($model_list as $model) {
            $scene = CMSRepository::scene()->setModel($model);
            CreateCmsTables::dropTable($scene->getExtendTable());
            if ($scene instanceof MultiScene) {
                CreateCmsTables::dropTable($scene->getMainTable());
                continue;
            }
            if (in_array($scene->getMainTable(), $truncateTables)) {
                continue;
            }
            $truncateTables[] = $scene->getMainTable();
        }
        $db = db();
        $grammar = DB::schemaGrammar();
        foreach($truncateTables as $table) {
            $db->execute($grammar->compileTableTruncate($table));
        }
    }
}