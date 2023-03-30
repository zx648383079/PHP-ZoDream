<?php
declare(strict_types=1);
namespace Module\AppStore\Service;

use Module\AppStore\Domain\Repositories\CategoryRepository;
use Module\AppStore\Domain\Repositories\AppRepository;

class HomeController extends Controller {

    public function indexAction(
        array|string $sort = 'new', int $category = 0, string $keywords = '', int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $app_list  = AppRepository::getList($keywords, $category, $sort);
        $cat_list = CategoryRepository::levelTree();
        $cat = null;
        if ($category > 0) {
            $cat = CategoryRepository::get($category);
        }
        return $this->show(compact('app_list',
            'cat_list', 'sort', 'category', 'keywords',
            'cat'));
    }

    public function detailAction(int $id, int $version = 0) {
        try {
            $model = AppRepository::getFull($id, $version);
        } catch (\Exception) {
            return $this->redirect('./');
        }
        return $this->show('detail', compact('model'));
    }

    public function suggestionAction(string $keywords) {
        $data = AppRepository::suggestion($keywords);
        foreach($data as &$item) {
            $item['url'] = url('./', ['id' => $item['id']]);
        }
        unset($item);
        return $this->renderData($data);
    }
}