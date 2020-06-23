<?php
namespace Module\Demo\Service;

use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Repositories\PostRepository;
use Zodream\Service\Factory;

class DownloadController extends Controller {
    public function indexAction($id) {
        $post = PostModel::find($id);
        $file = PostRepository::file($post);
        return Factory::response()
            ->file($file);
    }

}