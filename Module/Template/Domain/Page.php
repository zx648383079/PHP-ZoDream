<?php
namespace Module\Template\Domain;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Zodream\Disk\Directory;
use Zodream\Infrastructure\Traits\Attributes;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Template\ViewFactory;

class Page {

    use Attributes;

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
        $this->directory = Factory::root()
            ->directory('Module/Template/UserInterface/templates/default');
        $this->setIsEditMode($isEditMode);

    }

    public function boot() {
        if ($this->booted) {
            return;
        }
        $this->booted = true;
        $this->factory = new ViewFactory();
        $this->factory->setEngine(ParserCompiler::class)
            ->setConfigs([
                'suffix' => '.html'
            ])
            ->set('page', $this)
            ->setDirectory($this->directory)
            ->getEngine()->registerFunc('weight', '<?=$page->weight(%s)?>');
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
        $this->boot();
        return $this->factory;
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
        $this->addWeight(PageWeightModel::where('is_share', 1)->all());
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
        $this->boot();
        $html = $model->weight
            ->getWeightInstance()
            ->setPage($this)
            ->setDirectory($model->weight->path)
            ->render($model);
        if ($this->isEditMode) {
            $editHtml = $model->weight->editable ? '<a class="edit">编辑</a>' : '';
            return <<<HTML
<div class="item weight-grid" data-type="weight" data-id="{$model->id}">
    <div class="action">
        {$editHtml}
        <a class="drag">拖拽</a>
        <a class="del">删除</a>
    </div>
    <div class="view">
        {$html}
    </div>
</div>
HTML;
        }
        return $html;
    }

    public function render() {
        $this->boot();
        return $this->getFactory()->render($this->page->template, [
            'keywords' => $this->page->keywords,
            'description' => $this->page->description,
        ]);
    }

    public function template() {
        return $this->render();
    }

    public function __toString() {
        return $this->render();
    }
}