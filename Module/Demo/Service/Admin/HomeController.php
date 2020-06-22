<?php
namespace Module\Demo\Service\Admin;

use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Model\CategoryModel;

class HomeController extends Controller {

    public function indexAction() {
        $cat_count = CategoryModel::query()->count();
        $post_count = PostModel::where('user_id', auth()->id())->count();
        $view_count = PostModel::where('user_id', auth()->id())->sum('click_count');
        $download_count = PostModel::where('user_id', auth()->id())->sum('download_count');
        return $this->show(compact('cat_count', 'post_count', 'view_count', 'download_count'));
    }
}