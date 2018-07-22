<?php
namespace Module\Template\Service;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Page;
use Zodream\Disk\Directory;
use Zodream\Infrastructure\Http\Request;

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
        return null;
    }

    public function parseConfigs() {
        return app('request')->request();
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
     * @return Response
     * @throws \Exception
     */
    public function show($name = null, $data = array()) {
        $data['page'] = $this->page;
        return $this->page->getFactory()
            ->setDirectory($this->directory)
            ->render($name, $data);
    }

    public function __call($name, $arguments) {
        return $this->page->{$name}(...$arguments);
    }
}