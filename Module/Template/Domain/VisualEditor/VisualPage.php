<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Model\ThemePageModel;
use Zodream\Database\Relation;
use Zodream\Disk\Directory;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\Attributes;
use Zodream\Template\ViewFactory;

class VisualPage implements IVisualEngine {

    const ENGINE_KEY = 'visualEditor';

    use Attributes;

    const EXT = '.html';

    /**
     * @var PageWeightModel[]
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

    protected ThemePageModel $themePage;

    protected bool $booted = false;

    public function __construct(
        protected SiteModel $site,
        protected PageModel $page,
        protected bool $editable = false) {
        VisualFactory::unlock();
        VisualFactory::set(SiteModel::class, $this->site->id, $this->site);
        VisualFactory::set(PageModel::class, $this->page->id, $this->page);
        $this->themePage = VisualFactory::getOrSet(ThemePageModel::class, $this->page->theme_page_id, function () {
            return ThemePageModel::where('id', $this->page->theme_page_id)->first();
        });
        $this->directory = VisualFactory::templateFolder();
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
     * @return PageModel|mixed
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
    }

    public function addWeight(PageWeightModel|array $weight) {
        if (!is_array($weight)) {
            $weight = func_get_args();
        }
        $this->boot();
        $this->weights = array_merge($this->weights, $weight);
    }

    protected function loadWeights() {
        $items = PageWeightModel::where('page_id', $this->page->id)->get();
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
     * @return PageWeightModel[]
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
            $renderer->registerCssFile('@template.css');
        }
        return $renderer->render(Str::lastReplace($this->themePage->path, self::EXT), [
            'title' => $this->page->title,
            'keywords' => $this->page->keywords,
            'description' => $this->page->description,
        ]);
    }

    public function __toString() {
        return $this->render();
    }


    public static function cacheAnyWeight(array $items) {
        VisualFactory::setAny(PageWeightModel::class, $items);
        $weightItems = VisualFactory::getAutoSet(
            Relation::columns($items, 'weight_id'),
            SiteWeightModel::class,
            function (array $idItems) {
                return SiteWeightModel::whereIn('id', $idItems)
                    ->get();
            }
        );
        VisualFactory::getAutoSet(
            Relation::columns($weightItems, 'weight_id'),
            SiteWeightModel::class,
            function (array $idItems) {
                return SiteWeightModel::whereIn('id', $idItems)
                    ->get();
            }
        );
    }

    public static function renderAnyWeight(IVisualEngine $engine, array $items, int $rowId = 0, int $index = 0): string {
        // 排序
        usort($items, function (PageWeightModel $a, PageWeightModel $b) {
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
        if ($engine->editable()) {
            return <<<HTML
<div class="visual-edit-row" data-id="{$rowId}" data-index="{$index}">
{$html}
</div>
HTML;
        }
        return $html;
    }

}