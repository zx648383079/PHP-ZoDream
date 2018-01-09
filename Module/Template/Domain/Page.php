<?php
namespace Module\Template\Domain;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
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

    public function __construct($page) {
        $this->page = $page instanceof PageModel
            ? $page :
            PageModel::where('name', $page)->one();
    }

    /**
     * @param array $weights
     */
    public function setWeights($weights) {
        $this->weights = $weights;
    }

    public function getFactory() {
        if (empty($this->factory)) {
            $this->factory = new ViewFactory();
            $this->factory->setEngine(ParserCompiler::class)
                ->setConfigs([
                    'suffix' => '.html'
                ])
                ->set('page', $this)
                ->setDirectory('Module/Template/UserInterface/templates/default')
                ->getEngine()->registerFunc('weight', '<?=$page->weight(%s)?>');
        }
        return $this->factory;
    }

    public function addWeight($weight) {
        if (!is_array($weight)) {
            $weight = func_get_args();
        }
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
        return implode(PHP_EOL, $args);
    }

    public function renderWeight(PageWeightModel $model) {
        return $model->weight->getWeightInstance()
            ->setPage($this)
            ->render($model);
    }

    public function render() {
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