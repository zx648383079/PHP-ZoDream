<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Repositories\PageRepository;
use Zodream\Disk\Directory;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\Attributes;
use Zodream\Template\Engine\ParserCompiler;
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
        $this->themePage = ThemePageModel::where('id', $this->page->theme_page_id)->first();
        $this->directory = static::templateFolder();
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
        $this->factory = static::newViewFactory()
            ->setDirectory($this->directory);
    }

    public function addWeight($weight) {
        if (!is_array($weight)) {
            $weight = func_get_args();
        }
        $this->boot();
        $this->weights = array_merge($this->weights, $weight);
    }

    protected function loadWeights() {
        // 加载公共模块
        $this->addWeight(PageWeightModel::where('is_share', 1)
            ->where('site_id', $this->page->site_id)->get());
        // 加载页面模块

        $this->addWeight(
            PageWeightModel::where('page_id', $this->page->id)->get()
        );
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
        return static::renderAnyWeight($this, $this->getWeightList($parent_id, $index), $index);
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

    public static function renderAnyWeight(IVisualEngine $engine, array $items, int $index = 0): string {
        // 排序，公共组件在前，同等排序小的在前
        usort($items, function (PageWeightModel $a, PageWeightModel $b) {
            if ($a->is_share === $b->is_share) {
                return $a->position > $b->position ? 1 : -1;
            }
            return $a->is_share ? -1 : 1;
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
<div class="weight-row" data-id="{$engine->rowId()}" data-index="{$index}">
{$html}
</div>
HTML;
        }
        return $html;
    }

    public static function newViewFactory() {
        $factory = new ViewFactory();
        $factory->setEngine(ParserCompiler::class)
            ->setConfigs([
                'suffix' => self::EXT
            ])
            ->getEngine()
            ->registerFunc('weight', '<?=$'.self::ENGINE_KEY.'->weight(%s)?>');
        return $factory;
    }

    /**
     * 获取模板路径
     * @param string $path
     * @return bool|FileObject
     */
    public static function templateFolder(string $path = ''): mixed {
        $folder = new Directory(dirname(dirname(__DIR__)).'/UserInterface/templates');
        if (empty($path)) {
            return $folder;
        }
        return $folder->child($path);
    }

    /**
     * 根据id进入页面
     * @param int $site
     * @param int $id
     * @return VisualPage
     * @throws \Exception
     */
    public static function entry(int $site, int $id = 0): VisualPage {
        $siteModel = SiteModel::findOrThrow($site);
        if ($siteModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('site not publish');
        }
        if ($id > 0) {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('id', $id)->first();
        } else if ($siteModel->default_page_id > 0) {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('id', $siteModel->default_page_id)->first();
        } else {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('status', PageRepository::PUBLISH_STATUS_POSTED)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')->first();
        }
        if (empty($pageModel)) {
            throw new \Exception('page not found');
        }
        if ($pageModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('page not publish');
        }
        return new static($siteModel, $pageModel, false);
    }

    /**
     * 根据域名路径进入页面
     * @param string $domain
     * @param string $path
     * @return VisualPage
     * @throws \Exception
     */
    public static function entryRewrite(string $domain, string $path = ''): VisualPage {
        $siteModel = SiteModel::where('domain', $domain)->first();
        if (empty($siteModel)) {
            throw new \Exception('page not found');
        }
        if ($siteModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('site not publish');
        }
        if (!empty($path)) {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('name', $path)->first();
        } else if ($siteModel->default_page_id > 0) {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('id', $siteModel->default_page_id)->first();
        } else {
            $pageModel = PageModel::where('site_id', $siteModel->id)
                ->where('status', PageRepository::PUBLISH_STATUS_POSTED)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')->first();
        }
        if (empty($pageModel)) {
            throw new \Exception('page not found');
        }
        if ($pageModel->status !== PageRepository::PUBLISH_STATUS_POSTED) {
            throw new \Exception('page not publish');
        }
        return new static($siteModel, $pageModel, false);
    }
}