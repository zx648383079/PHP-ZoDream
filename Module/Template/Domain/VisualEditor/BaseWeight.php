<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Exception;
use Module\Template\Domain\Model\PageWeightModel;
use Zodream\Disk\Directory;
use Zodream\Html\Dark\Theme;
use Zodream\Template\ViewFactory;

/**
 *
 * @method string weight(int $index)
 */
abstract class BaseWeight {
    /**
     * @var IVisualEngine
     */
    protected mixed $engine;

    /**
     * @var Directory
     */
    protected mixed $directory;

    /**
     * @param VisualWeight $engine
     * @return BaseWeight
     */
    public function setEngine(VisualWeight $engine) {
        $this->engine = $engine;
        return $this;
    }

    public function setDirectory(mixed $path) {
        $this->directory = new Directory($path);
        return $this;
    }

    public function renderer(): ViewFactory {
        return $this->engine->renderer();
    }

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return string
     */
    abstract public function render(PageWeightModel $model): string;

    /**
     * 获取生成的配置视图
     * @param PageWeightModel $model
     * @return mixed
     */
    public function renderForm(PageWeightModel $model): string {
        $html = Theme::text('title', $model->title, '标题');
        return <<<HTML
{$html}
<textarea id="editor-container" style="height: 400px;" name="content" placeholder="请输入内容">{$model->content}</textarea>
<script>
UE.delEditor('editor-container');
UE.getEditor('editor-container');
</script>
HTML;
    }

    public function parseForm(): array {
        return request()->get();
    }

    /**
     * 传递数据
     *
     * @param string|array $key 要传的数组或关键字
     * @param string $value 要传的值
     * @return static
     * @throws \Exception
     */
    public function send(mixed $key, mixed $value = null) {
        $this->renderer()->set($key, $value);
        return $this;
    }

    /**
     * 加载视图
     *
     * @param string $name 视图的文件名
     * @param array $data 要传的数据
     * @return string
     * @throws \Exception
     */
    public function show(mixed $name = null, array $data = []) {
        return $this->renderer()
            ->setDirectory($this->directory)
            ->render($name, $data);
    }

    public function __call(string $name, array $arguments) {
        if (empty($this->engine) || !method_exists($this->engine, $name)) {
            throw new Exception(
                __('{method} not found!', [
                    'method' => $name
                ])
            );
        }
        return $this->engine->{$name}(...$arguments);
    }
}