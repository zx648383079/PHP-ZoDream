<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;

class CategoryController extends Controller {

    public function indexAction($id) {
        FuncHelper::$current['channel'] = intval($id);
        $cat = FuncHelper::channel($id, true);
        if (empty($cat)) {
            return $this->redirect('./');
        }
        $page = null;
        if ($cat->type < 1) {
            $page = FuncHelper::contents([]);
        }
        $title = $cat['title'];
        return $this->show($cat->category_template,
            compact('cat', 'page', 'title'));
    }

    public function listAction($id, $keywords = null) {
        FuncHelper::$current['channel'] = $id;
        $cat = FuncHelper::channel($id, true);
        $page = FuncHelper::contents(compact('keywords'));
        $title = $cat['title'].'列表页';
        return $this->show($cat->list_template,
            compact('cat', 'page',  'title'));
    }
}