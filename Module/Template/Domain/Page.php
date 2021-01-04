<?php
namespace Module\Template\Domain;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Module;
use phpDocumentor\Reflection\Types\Self_;
use Zodream\Disk\Directory;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\Attributes;

use Zodream\Template\Engine\ParserCompiler;
use Zodream\Template\ViewFactory;

class Page {

    use Attributes;

    const EXT = '.html';

    /**
     * @var PageWeightModel[]
     */
    protected $weights = [];

    /**
     * @var PageModel
     */
    protected $page;

    /**
     * @var ViewFactory
     */
    protected $factory;

    /**
     * @var Directory
     */
    protected $directory;

    /**
     * 是否是编辑模式
     * @var bool
     */
    protected $isEditMode = false;

    protected $booted = false;

    public function __construct($page, $isEditMode = false) {
        $this->page = $page instanceof PageModel
            ? $page :
            PageModel::where('name', $page)->one();
        $this->directory = app_path()
            ->directory(Module::templateFolder());
        $this->setIsEditMode($isEditMode);

    }

    public function boot() {
        if ($this->booted) {
            return;
        }
        $this->booted = true;
        $this->loadWeights();
    }

    /**
     *
     * @param bool $isEditMode
     * @return Page
     */
    public function setIsEditMode($isEditMode) {
        $this->isEditMode = $isEditMode;
        return $this;
    }

    public function isEditMode() {
        return $this->isEditMode;
    }

    /**
     * @param array $weights
     */
    public function setWeights($weights) {
        $this->weights = $weights;
    }

    /**
     * 获取页面数据
     * @param null $key
     * @return PageModel
     */
    public function getPage($key = null) {
        if (empty($key)) {
            return $this->page;
        }
        return $this->page->{$key};
    }

    public function getFactory() {
        if (empty($this->factory)) {
            $this->initFactory();
        }
        return $this->factory;
    }

    protected function initFactory() {
        $this->factory = static::newViewFactory()
            ->set('page', $this)
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
            ->where('site_id', $this->page->site_id)->all());
        // 加载页面模块
        $this->addWeight($this->page->weights);
    }

    /**
     * @param $parent_id
     * @param null $ext
     * @return PageWeightModel[]
     */
    public function getWeightList($parent_id, $ext = null) {
        $this->boot();
        $data = [];
        foreach ($this->weights as $weight) {
            if ($weight->parent_id != $parent_id) {
                continue;
            }
            if (!is_null($ext) && $weight->hasExtInfo($ext)) {
                continue;
            }
            $data[] = $weight;
        }
        return $data;
    }

    public function weight($parent_id, $ext = null) {
        $args = [];
        foreach ($this->getWeightList($parent_id, $ext) as $weight) {
            $args[] = $this->renderWeight($weight);
        }
        // 修正当前的文件夹
        $this->getFactory()->setDirectory($this->directory);
        $html = implode(PHP_EOL, $args);
        if ($this->isEditMode) {
            return <<<HTML
<div class="weight-row" data-id="{$parent_id}" data-ext="{$ext}">
{$html}
</div>
HTML;
        }
        return $html;
    }

    public function renderWeight(PageWeightModel $model) {
        return (new Weight($model, $this))->render($this->isEditMode);
    }

    public function render() {
        $this->boot();
        if ($this->isEditMode) {
            $this->getFactory()
                ->registerCssFile('@template.css');
        }
        return $this->getFactory()->render(Str::lastReplace($this->page->page->path, self::EXT), [
            'title' => $this->page->title,
            'keywords' => $this->page->keywords,
            'description' => $this->page->description,
        ]);
    }

    public function renderWithNewRoot(Directory $root, $name, array $data = []) {
        $directory = $this->getFactory()->getDirectory();
        $html = $this->getFactory()
            ->setDirectory($root)
            ->render($name, $data);
        $this->getFactory()
            ->setDirectory($directory);
        return $html;
    }

    public function template() {
        return $this->render();
    }

    public function converterUrl($item) {
        if ($item['type'] === 'url') {
            return [
                'uri' => url($item['uri']),
                'target' => $item['target']
            ];
        }
        if ($item['type'] === 'target') {
            return [
                'uri' => sprintf('javascript:ajaxWeight(%d, \'%s\');', $item['target'], $item['uri']),
                'target' => '',
            ];
        }
        if ($item['type'] === 'page') {
            return [
                'uri' => url('./page', ['name' => PageModel::where('id', $item['id'])->value('name')]),
                'target' => $item['target']
            ];
        }
        return [
            'uri' => 'javascript:;',
            'target' => '',
        ];
    }

    public function __toString() {
        return $this->render();
    }

    public static function newViewFactory() {
        $factory = new ViewFactory();
        $factory->setEngine(ParserCompiler::class)
            ->setConfigs([
                'suffix' => self::EXT
            ])
            ->getEngine()->registerFunc('weight', '<?=$page->weight(%s)?>');
        return $factory;
    }
}