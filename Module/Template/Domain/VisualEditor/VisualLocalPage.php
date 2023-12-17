<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\SiteWeightModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Helpers\Json;
use Zodream\Http\MIME;
use Zodream\Template\ViewFactory;

/***
 * 本地调试版
 */
class VisualLocalPage implements IVisualEngine {

    /**
     * @var ViewFactory
     */
    protected mixed $factory;

    /**
     * @var Directory
     */
    protected Directory $directory;
    protected Directory $pageDirectory;
    protected Directory $baseDirectory;

    protected array $pageData;
    protected array $weightItems = [];

    public function __construct(
        string $pagePath,
        string $weightPath,
        protected bool $editable = false) {
        $this->baseDirectory = VisualFactory::templateFolder();
        $this->pageData = $this->loadWeight($pagePath);
        $this->pageDirectory = $this->directory = (new File($this->pageData['entry']))->getDirectory();
        $this->weightItems[] = $this->loadWeight($weightPath);
    }

    protected function loadWeight(string $path): array {
        $folder = $this->baseDirectory->child($path);
        if ($folder === false) {
            throw new \Exception('page file error');
        }
        if ($folder instanceof File) {
            return [
                'name' => '',
                'entry' => $folder->getFullName()
            ];
        }
        /** @var Directory $folder */
        $jsonFile = $folder->file('weight.json');
        $entryFile = $folder->file('index.html');
        if (!$entryFile->exist()) {
            $entryFile = $folder->file('weight.php');
        }
        if (!$jsonFile->exist()) {
            if (!$entryFile->exist()) {
                throw new \Exception('not found entry');
            }
            return [
                'name' => '',
                'entry' => $entryFile->getFullName()
            ];
        }
        $data = Json::decode($jsonFile->read());
        if (isset($data['entry'])) {
            $entryFile = $folder->file($data['entry']);
            if (!$entryFile->exist())  {
                throw new \Exception('not found entry');
            }
            $data['entry'] = $entryFile->getFullName();
        } else {
            $data['entry'] = $entryFile->getFullName();
        }
        return $data;
    }

    public function editable(): bool
    {
        return $this->editable;
    }

    public function asyncable(): bool {
        return false;
    }

    public function rowId(): int
    {
        return 1;
    }

    public function pageId(): int
    {
        return 1;
    }

    public function renderer(): ViewFactory {
        if (empty($this->factory)) {
            $this->initFactory();
        }
        $this->factory->set(VisualPage::ENGINE_KEY, $this);
        return $this->factory;
    }

    public function resetRenderer() {
        $this->directory = $this->pageDirectory;
        $this->renderer()->setDirectory($this->directory);
    }

    protected function initFactory() {
        $this->factory = VisualFactory::newViewFactory(false)
            ->setDirectory($this->directory);
        $this->factory->getEngine()
            ->registerFunc('asset', function (string $val) {
                $file = $this->directory->file(trim($val, '\''));
                if (!$file->exist()) {
                    return $file->getFullName();
                }

                if ($file->size() === 0) {
                    return '';
                }
                return sprintf('data:%s;base64,%s', MIME::get($file->getExtension()), base64_encode($file->read()));
            });
    }

    public function weight(int $index): string
    {
        return $this->renderRow(0, $index);
    }

    public function renderRow(int $parent_id, int $index = 0): string {
        VisualFactory::lock($parent_id, $index);
        $args = [];
        $renderer = $this->renderer();
        foreach ($this->weightItems as $weight) {
            if (isset($weight['dependencies']) && is_array($weight['dependencies'])) {
                foreach ($weight['dependencies'] as $dependency) {
                    if (str_ends_with($dependency, '.js')) {
                        $renderer->registerJsFile($dependency);
                    } else {
                        $renderer->registerCssFile($dependency);
                    }
                }
            }
            $this->directory = (new File($weight['entry']))->getDirectory();
            $args[] = $this->renderWeight($weight);
        }
        // 修正当前的文件夹
        $this->resetRenderer();
        $html = implode(PHP_EOL, $args);
        return VisualPage::renderRowHtml($this->editable(), $parent_id, $index, $html);
    }

    protected function renderWeight(array $data): string {
        $def = $data['default'] ?? [];
        $model = new SiteWeightModel($def);
        $model->id = $this->rowId();
        $property = VisualWeightProperty::create($model);
        if (array_key_exists('style_id', $def) && isset($data['styles'][$def['style_id']])) {
            $styleData = $data['styles'][$def['style_id']];
            VisualFactory::newStyle($styleData['name'],
                (string)$this->directory->file($styleData['entry']))
            ->render($property);
        }
        $renderer = $this->renderer();
        $renderer->set(VisualWeight::DATA_KEY, $model)
            ->set(VisualWeight::PROPERTY_KEY, $property);
        $html = empty($data['name']) ? file_get_contents($data['entry'])
            : VisualFactory::newWeight($data['name'], $data['entry'])
            ->setEngine($this)
            ->render($model);
        if (!$this->editable) {
            return VisualWeight::renderHtml('weight-1', $html);
        }
        return VisualWeight::renderEditHtml(isset($data['editable']), 1, $html);
    }

    public function render() {
        $renderer = $this->renderer();
        $renderer->set('IS_DEV', $this->editable);
        if ($this->editable) {
            $renderer->registerCssFile('@template_edit.min.css');
        }
        return $renderer->render($this->pageData['entry'], [
            'title' => 'Local Test',
            'keywords' => '',
            'description' => '',
        ]);
    }
}