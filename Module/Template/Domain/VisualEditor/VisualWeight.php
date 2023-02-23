<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Zodream\Helpers\Str;
use Zodream\Template\ViewFactory;

class VisualWeight implements IVisualEngine {

    const DATA_KEY = 'model';
    const PROPERTY_KEY = 'property';

    /**
     * @var ViewFactory
     */
    protected mixed $factory = null;
    /**
     * @var ThemeWeightModel
     */
    protected ThemeWeightModel $weight;

    protected SiteWeightModel $model;

    protected bool $editable = false;

    protected bool $asyncable = true;

    protected VisualWeightProperty $property;

    public function __construct(
        protected PageWeightModel $pageWeight,
        protected ?IVisualEngine $engine = null) {
        $this->model = VisualFactory::getOrSet(ThemeWeightModel::class,
            $this->pageWeight->weight_id, function () {
                return SiteWeightModel::where('id', $this->pageWeight->weight_id)->first();
            });
        $this->weight = VisualFactory::getOrSet(ThemeWeightModel::class,
            $this->model->theme_weight_id, function () {
                return ThemeWeightModel::where('id', $this->model->theme_weight_id)->first();
            });
        $this->property = VisualFactory::getOrSet(VisualWeightProperty::class,
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
        $items = PageWeightModel::where('parent_id', $parent_id)
            ->where('page_id', $this->pageId())
            ->where('parent_index', $index)->get();
        return VisualPage::renderAnyWeight($this, $items, $parent_id, $index);
    }

    /**
     * @return BaseWeight
     */
    public function newWeight(): mixed {
        $path = $this->weight->path;
        if (class_exists($path)) {
            return new $path;
        }
        if (!file_exists($path)) {
            $path = (string)VisualFactory::templateFolder($path);
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
            return $this->renderAsync();
        }
        $this->renderer()->set('is_dev', $editable);
        $html = $this->createWeight()
            ->render($this->model);
        if (!$editable) {
            return $this->renderHtml($html);
        }
        return $this->renderEdit($html);
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

    private function renderHtml(string $html) {
        $id = $this->property->weightId();
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

    private function renderEdit(string $html) {
        $editHtml = $this->weight->editable ? '<a class="edit">编辑</a>' : '';
        return <<<HTML
<div class="weight-edit-grid" data-type="weight" data-id="{$this->rowId()}">
    <div class="weight-action">
        <a class="refresh">刷新</a>
        {$editHtml}
        <a class="property">属性</a>
        <a class="drag">拖拽</a>
        <a class="del">删除</a>
    </div>
    <div class="weight-view">
        {$html}
    </div>
</div>
HTML;
    }

    private function renderAsync() {
        $url = url('./lazy', ['id' => $this->rowId()]);
        return <<<HTML
<div class="template-lazy" data-url="{$url}">
</div>
HTML;

    }
}