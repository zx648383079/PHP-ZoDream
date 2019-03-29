<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;

class ContentController extends Controller {

    public function indexAction($id, $category) {
        FuncHelper::$current['channel'] = $category;
        FuncHelper::$current['content'] = $id;
        $cat = FuncHelper::channel($category, true);
        $data = FuncHelper::content($id, true);
        return $this->show($cat->show_template,
            compact('cat', 'data'));
    }
}