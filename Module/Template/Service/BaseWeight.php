<?php
namespace Module\Template\Service;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Page;
use Zodream\Disk\Directory;
use Exception;
use Zodream\Html\Dark\Theme;


abstract class BaseWeight {
    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Directory
     */
    protected $directory;

    /**
     * @param Page $page
     * @return BaseWeight
     */
    public function setPage($page) {
        $this->page = $page;
        return $this;
    }

    public function setDirectory($path) {
        $this->directory = new Directory($path);
        return $this;
    }

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     */
    abstract public function render(PageWeightModel $model);

    /**
     * 获取生成的配置视图
     * @param PageWeightModel $model
     * @return mixed
     */
    public function renderConfig(PageWeightModel $model) {
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

    public function parseConfigs() {
        return app('request')->get();
    }

    /**
     * 传递数据
     *
     * @param string|array $key 要传的数组或关键字
     * @param string $value 要传的值
     * @return static
     * @throws \Exception
     */
    public function send($key, $value = null) {
        $this->page->getFactory()->set($key, $value);
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
    public function show($name = null, $data = []) {
        $data['page'] = $this->page;
        if (!empty($this->page)) {
            return $this->page->renderWithNewRoot($this->directory, $name, $data);
        }
        return Page::newViewFactory()->setDirectory($this->directory)
            ->render($name, $data);
    }

    public function __call($name, $arguments) {
        if (empty($this->page) || !method_exists($this->page, $name)) {
            throw new Exception(
                __('{method} not found!', [
                    'method' => $name
                ])
            );
        }
        return $this->page->{$name}(...$arguments);
    }
}