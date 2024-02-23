<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Module\Template\Domain\Repositories\ComponentRepository;
use Zodream\Database\Relation;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\Attributes;
use Zodream\Template\ViewFactory;

class VisualPage implements IVisualEngine {

    const ENGINE_KEY = 'visualEditor';

    use Attributes;

    const EXT = '.html';

    /**
     * @var SitePageWeightModel[]
     */
    protected array $weights = [];

    /**
     * @var ViewFactory
     */
    protected mixed $factory;

    /**
     * @var Directory
     */
    protected Directory $directory;

    protected SiteComponentModel $themePage;

    protected File $pageFileName;

    protected bool $booted = false;

    public function __construct(
        protected SiteModel $site,
        protected SitePageModel $page,
        protected bool $editable = false) {
        VisualFactory::unlock();
        VisualFactory::cache()->set(SiteModel::class, $this->site->id, $this->site);
        VisualFactory::cache()->set(SitePageModel::class, $this->page->id, $this->page);
        $this->themePage = VisualFactory::cache()->getOrSet(SiteComponentModel::class,
            $this->page->component_id, function () {
            return SiteComponentModel::where('component_id', $this->page->component_id)
                ->where('site_id', $this->page->site_id)
                ->where('type', 0)->first();
        });
        $this->pageFileName = ComponentRepository::root()->file($this->themePage->path);// VisualFactory::templateFolder();
        $this->directory = $this->pageFileName->getDirectory();
    }

    public function boot() {
        if ($this->booted) {
            return;
        }
        $this->booted = true;
        $this->loadWeights();
    }

    public function rowId(): int {
        return 0;
    }

    public function pageId(): int {
        return $this->page->id;
    }

    /**
     * 获取缓存时间
     * @return int
     */
    public function cacheTime(): int {
        return intval($this->page->setting('cache_time'));
    }

    /**
     *
     * @param bool $editable
     * @return static
     */
    public function setEditable(bool $editable) {
        $this->editable = $editable;
        return $this;
    }

    public function editable(): bool {
        return $this->editable;
    }

    public function asyncable(): bool {
        return !$this->editable;
    }

    /**
     * @param array $weights
     * @return static
     */
    public function setWeights(array $weights) {
        $this->weights = $weights;
        return $this;
    }

    /**
     * 获取页面数据
     * @param string $key
     * @return SitePageModel|mixed
     */
    public function getPage(string $key = ''): mixed {
        if (empty($key)) {
            return $this->page;
        }
        return $this->page->{$key};
    }

    public function renderer(): ViewFactory {
        if (empty($this->factory)) {
            $this->initFactory();
        }
        $this->factory->set(self::ENGINE_KEY, $this);
        return $this->factory;
    }

    public function resetRenderer() {
        $this->renderer()->setDirectory($this->directory);
    }

    protected function initFactory() {
        $this->factory = VisualFactory::newViewFactory()
            ->setDirectory($this->directory);
        $this->factory->getEngine()
            ->registerFunc('asset', function (string $val) {
                $path = trim($val, '\'');
                $file = $this->factory->getDirectory()->file($path);
                return sprintf('/assets/themes/%s', $file->getRelative(ComponentRepository::root()));
            });
    }

    public function addWeight(SitePageWeightModel|array $weight) {
        if (!is_array($weight)) {
            $weight = func_get_args();
        }
        $this->boot();
        $this->weights = array_merge($this->weights, $weight);
        $renderer = $this->renderer();
        foreach ($weight as $item) {
            $siteWeight = VisualFactory::cache()->getOrSet(SiteWeightModel::class, $item['weight_id'], function () use ($item) {
                return SiteWeightModel::find($item['weight_id']);
            });
            $themeWeight = VisualFactory::cache()->getOrSet(SiteComponentModel::class,
                $siteWeight['component_id'], function () use ($siteWeight) {
                return SiteComponentModel::where('component_id', $siteWeight['component_id'])
                    ->where('site_id', $siteWeight['site_id'])->first();
            });
            foreach ($themeWeight->dependencies as $file) {
                if (str_ends_with($file, '.js')) {
                    $renderer->registerJsFile($file);
                } elseif(str_ends_with($file, '.css')) {
                    $renderer->registerCssFile($file);
                }
            }
        }
    }

    protected function loadWeights() {
        $items = SitePageWeightModel::where('page_id', $this->page->id)->get();
        if (empty($items)) {
            return;
        }
        static::cacheAnyWeight($items);
        $this->addWeight($items);
    }

    /**
     * 根据父id 获取组件，排序方式
     * @param int $parent_id
     * @param int $index
     * @return SitePageWeightModel[]
     */
    public function getWeightList(int $parent_id = 0, int $index = 0): array {
        $this->boot();
        $data = [];
        foreach ($this->weights as $weight) {
            if ($weight->parent_id !== $parent_id) {
                continue;
            }
            if ($index > 0 && $weight->parent_index !== $index) {
                continue;
            }
            $data[] = $weight;
        }
        return $data;
    }

    public function renderRow(int $parent_id, int $index = 0): string {
        VisualFactory::lock($parent_id, $index);
        return static::renderAnyWeight($this, $this->getWeightList($parent_id, $index), $parent_id, $index);
    }

    /**
     * 显示此位置的部件，请避免死循环
     * @param int $index
     * @return string
     */
    public function weight(int $index): string {
        return $this->renderRow(0, $index);
    }

    public function render() {
        $this->boot();
        $renderer = $this->renderer();
        if ($this->editable) {
            $renderer->registerCssFile('@template_edit.min.css');
        }
        return $renderer->render($this->pageFileName, [
            'title' => $this->page->title,
            'keywords' => $this->page->keywords,
            'description' => $this->page->description,
        ]);
    }

    public function __toString() {
        return $this->render();
    }


    public static function cacheAnyWeight(array $items) {
        if (empty($items)) {
            return;
        }
        $siteId = $items[0]['site_id'];
        VisualFactory::cache()->setAny(SitePageWeightModel::class, $items);
        $weightItems = VisualFactory::cache()->getAutoSet(
            Relation::columns($items, 'weight_id'),
            SiteWeightModel::class,
            function (array $idItems) use ($siteId) {
                return SiteWeightModel::whereIn('id', $idItems)
                    ->where('site_id', $siteId)
                    ->get();
            }
        );
        VisualFactory::cache()->getAutoSet(
            Relation::columns($weightItems, 'component_id'),
            SiteComponentModel::class,
            function (array $idItems) use ($siteId) {
                return SiteComponentModel::whereIn('component_id', $idItems)
                    ->where('site_id', $siteId)
                    ->where('type', 1)
                    ->get();
            }
        );
        VisualFactory::cache()->getAutoSet(
            Relation::columns($weightItems, 'style_id'),
            ThemeStyleModel::class,
            function (array $idItems) {
                return ThemeStyleModel::whereIn('id', $idItems)
                    ->get();
            }
        );
    }

    public static function renderAnyWeight(IVisualEngine $engine, array $items, int $rowId = 0, int $index = 0): string {
        // 排序
        usort($items, function (SitePageWeightModel $a, SitePageWeightModel $b) {
            if ($a->position === $b->position) {
                return 0;
            }
            return $a->position > $b->position ? 1 : -1;
        });
        $args = [];
        foreach ($items as $weight) {
            $args[] = (new VisualWeight($weight, $engine))
                ->render($engine->editable(), $engine->asyncable());
        }
        // 修正当前的文件夹
        $engine->resetRenderer();
        $html = implode(PHP_EOL, $args);
        return static::renderRowHtml($engine->editable(), $rowId, $index, $html);
    }

    /**
     * 是否生成编辑模式下的内容
     * @param bool $editable
     * @param int $rowId
     * @param int $index
     * @param string $html
     * @return string
     */
    public static function renderRowHtml(bool $editable, int $rowId, int $index, string $html) {
        if ($editable) {
            return <<<HTML
<div class="visual-edit-row" data-id="{$rowId}" data-index="{$index}">
{$html}
</div>
HTML;
        }
        return $html;
    }
}