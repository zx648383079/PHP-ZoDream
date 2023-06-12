<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Repositories\ComponentRepository;
use Zodream\Template\ViewFactory;

class VisualWeight implements IVisualEngine {

    const DATA_KEY = 'model';
    const PROPERTY_KEY = 'property';

    /**
     * @var ViewFactory
     */
    protected mixed $factory = null;
    /**
     * @var SiteComponentModel
     */
    protected SiteComponentModel $weight;

    protected SiteWeightModel $model;

    protected bool $editable = false;

    protected bool $asyncable = true;

    protected VisualWeightProperty $property;

    public function __construct(
        protected SitePageWeightModel $pageWeight,
        protected ?IVisualEngine $engine = null) {
        $this->model = VisualFactory::cache()->getOrSet(SiteWeightModel::class,
            $this->pageWeight->weight_id, function () {
                return SiteWeightModel::where('id', $this->pageWeight->weight_id)->first();
            });
        $this->weight = VisualFactory::cache()->getOrSet(SiteComponentModel::class,
            $this->model->component_id, function () {
                return SiteComponentModel::where('component_id', $this->model->component_id)
                    ->where('site_id', $this->model->site_id)
                    ->where('type', 1)->first();
            });
        $this->property = VisualFactory::cache()->getOrSet(VisualWeightProperty::class,
            $this->model->id, function () {
                return VisualWeightProperty::create($this->model);
            });
    }

    public function pageId(): int {
        return $this->pageWeight->page_id;
    }

    public function editable(): bool {
        return $this->editable;
    }

    public function asyncable(): bool {
        return $this->asyncable;
    }

    protected function isAsync(): bool {
        if (!$this->asyncable()) {
            return false;
        }
        if ($this->model->setting('lazy', false)) {
            return true;
        }
        return false;
    }

    public function rowId(): int {
        return $this->pageWeight->id;
    }

    public function renderer(): ViewFactory {
        if ($this->engine) {
            $renderer = $this->engine->renderer();
        } else {
            if (!$this->factory) {
                $this->factory = VisualFactory::newViewFactory();
            }
            $renderer = $this->factory;
        }
        $renderer->set(VisualPage::ENGINE_KEY, $this)
        ->set(static::DATA_KEY, $this->model)
        ->set(static::PROPERTY_KEY, $this->property);
        return $renderer;
    }

    public function resetRenderer() {
    }

    public function weight(int $index): string {
        return $this->renderRow($this->rowId(), $index);
    }

    public function renderRow(int $parent_id, int $index = 0): string {
        if ($this->engine) {
            return $this->engine->renderRow($parent_id, $index);
        }
        VisualFactory::lock($parent_id, $index);
        $items = SitePageWeightModel::where('parent_id', $parent_id)
            ->where('page_id', $this->pageId())
            ->where('parent_index', $index)->get();
        return VisualPage::renderAnyWeight($this, $items, $parent_id, $index);
    }



    /**
     * @return BaseWeight
     */
    public function createWeight() {
        return VisualFactory::newWeight($this->weight->alias_name,
            (string)ComponentRepository::root()->file($this->weight->path))
            ->setEngine($this);
    }

    public function renderForm() {
        return $this->createWeight()->renderForm($this->model);
    }

    public function parseForm() {
        return $this->createWeight()->parseForm();
    }

    public function render(bool $editable = false, bool $asyncable = true): string {
        $this->editable = $editable;
        $this->asyncable = $asyncable;
        if ($this->isAsync()) {
            return $this->renderAsync($this->property->weightId(), $this->rowId());
        }
        $this->renderer()->set('IS_DEV', $editable);
        $file = ComponentRepository::root()->file($this->weight->path);
        $html = $this->weight->alias_name ? $this->createWeight()
            ->render($this->model) : $file->read();
        if (!$editable) {
            return static::renderHtml($this->property->weightId(), $html);
        }
        return $this->renderEditHtml(!!$this->weight->editable, $this->rowId(), $html);
    }

    /**
     * 替换处理对当前控件的引用
     * @param string $id
     * @param string $html
     * @return string
     */
    public static function renderHtml(string $id, string $html) {
        $styleTag = '#'.$id;
        $nameTag = str_replace('-', '_', $id);
        $html = preg_replace_callback('#<style[\s\S]+?</style>#', function ($match) use($styleTag) {
            return str_replace(':host', $styleTag, $match[0]);
        }, $html);
        $html = preg_replace_callback('#<script(.+?)>([\s\S]+?)</script>#',
            function ($match) use($styleTag, $id, $nameTag) {
            $hasHost = str_contains($match[2], ':host');
            $hasHostEle = str_contains($match[2], '$host');
            if (!$hasHost && !$hasHostEle) {
                return $match[0];
            }
            $match[2] = str_replace(':host', $styleTag, $match[2]);
            if ($hasHostEle) {
                $match[2] = sprintf('var %s=document.getElementById("%s");%s', $nameTag, $id,
                    str_replace('$host', $nameTag, $match[2])
                );
            }
            return sprintf('<script%s>%s</script>', $match[1], $match[2]);
        }, $html);
        return <<<HTML
<div id="{$id}">
{$html}
</div>
HTML;

    }

    /**
     * 编辑模式下
     * @param bool $editable
     * @param int $rowId
     * @param string $html
     * @return string
     */
    public static function renderEditHtml(bool $editable, int $rowId, string $html) {
        $editHtml = $editable ? '<a class="edit">编辑</a>' : '';
        return <<<HTML
<div class="visual-edit-control" data-type="weight" data-id="{$rowId}">
    <div class="visual-action">
        <a class="refresh">刷新</a>
        {$editHtml}
        <a class="property">属性</a>
        <a class="drag">拖拽</a>
        <a class="del">删除</a>
    </div>
    <div class="visual-view">
        {$html}
    </div>
</div>
HTML;
    }

    /**
     * 延迟加载模式下
     * @param string $id
     * @param int $rowId
     * @return string
     * @throws \Exception
     */
    public static function renderAsync(string $id, int $rowId) {
        $url = url('./lazy', ['id' => $rowId]);
        return <<<HTML
<div id="{$id}" class="template-lazy" data-url="{$url}">
</div>
HTML;
    }
}