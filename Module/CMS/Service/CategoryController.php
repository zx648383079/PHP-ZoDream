<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;

class CategoryController extends Controller {

    public function indexAction($id) {
        FuncHelper::$current['channel'] = $id;
        $cat = FuncHelper::channel($id, true);
        $page = null;
        if ($cat->type < 1) {
            $page = FuncHelper::contents([]);
        }
        return $this->show($cat->category_template,
            compact('cat', 'page'));
    }

    public function listAction($id, $keywords = null) {
        FuncHelper::$current['channel'] = $id;
        $cat = FuncHelper::channel($id, true);
        $page = FuncHelper::contents(compact('keywords'));
        return $this->show($cat->list_template,
            compact('cat', 'page'));
    }
}