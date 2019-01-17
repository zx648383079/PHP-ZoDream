<?php
namespace Module\Template\Domain;


use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\WeightModel;
use Module\Template\Service\BaseWeight;
use Zodream\Helpers\Str;
use Zodream\Service\Factory;
use Zodream\Template\ViewFactory;

class Weight {

    /**
     * @var PageWeightModel
     */
    protected $model;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var ViewFactory
     */
    protected $factory;
    /**
     * @var WeightModel
     */
    protected $weight;

    public function __construct(PageWeightModel $model, Page $page = null) {
        $this->model = $model;
        $this->weight = $model->weight;
        $this->page = $page;
    }

    public function viewFactory() {
        if ($this->page) {
            return $this->page->getFactory();
        }
        if (!$this->factory) {
            $this->factory = Page::newViewFactory()
                ->set('weight', $this);
        }
        return $this->factory;
    }

    /**
     * @return BaseWeight
     */
    protected function newWeight() {
        $path = $this->weight->path;
        if (class_exists($path)) {
            return new $path;
        }
        if (!file_exists($path)) {
            $path = (string)Factory::root()->child($path);
        }
        if (is_dir($path)) {
            $path .= '/weight.php';
        }
        include_once $path;
        $name = Str::studly($this->weight->name).'Weight';
        return (new $name)->setDirectory(dirname($path));
    }

    /**
     * @return BaseWeight
     */
    public function createWeight() {
        return $this->newWeight()
            ->setPage($this->page);
    }

    public function render($isEditMode = false) {
        $html = $this->createWeight()
            ->render($this->model);
        if (!$isEditMode) {
            return $html;
        }
        $editHtml = $this->weight->editable ? '<a class="edit">编辑</a>' : '';
        return <<<HTML
<div class="weight-edit-grid" data-type="weight" data-id="{$this->model->id}">
    <div class="weight-action">
        <a class="refresh">刷新</a>
        {$editHtml}
        <a class="drag">拖拽</a>
        <a class="del">删除</a>
    </div>
    <div class="weight-view">
        {$html}
    </div>
</div>
HTML;
    }
}