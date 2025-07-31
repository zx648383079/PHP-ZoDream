<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service;

use Module\ResourceStore\Domain\Repositories\CategoryRepository;
use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class HomeController extends Controller {

    public function indexAction(
        array|string $sort = 'new', int $category = 0, string $keywords = '',
        int $user = 0,
        string $tag = '', int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $post_list  = ResourceRepository::getList($keywords, $category, $user, '', $sort, $tag);
        $cat_list = CategoryRepository::levelTree();
        $cat = null;
        if ($category > 0) {
            $cat = CategoryRepository::get($category);
        }
        return $this->show(compact('post_list',
            'cat_list', 'sort', 'category', 'keywords',
            'cat', 'tag'));
    }

    public function detailAction(int $id) {
        try {
            $post = ResourceRepository::getFull($id);
            $tags = $post['tags'];
        } catch (\Exception) {
            return $this->redirect('./');
        }
        return $this->show('detail', compact('post', 'tags'));
    }

    public function catalogAction(int $id) {
        $files = ResourceRepository::getCatalog($id);
        $this->layout = '';
        return $this->show(compact('files'));
    }

    public function suggestAction(string $keywords) {
        $data = ResourceRepository::suggestion($keywords);
        foreach($data as &$item) {
            $item['url'] = url('./', ['id' => $item['id']]);
        }
        unset($item);
        return $this->renderData($data);
    }
}