<?php
namespace Module\ResourceStore\Service\Admin;

use Module\ResourceStore\Domain\Models\PostModel;
use Module\ResourceStore\Domain\Models\CategoryModel;

class HomeController extends Controller {

    public function indexAction() {
        $cat_count = CategoryModel::query()->count();
        $post_count = PostModel::where('user_id', auth()->id())->count();
        $view_count = PostModel::where('user_id', auth()->id())->sum('click_count');
        $download_count = PostModel::where('user_id', auth()->id())->sum('download_count');
        return $this->show(compact('cat_count', 'post_count', 'view_count', 'download_count'));
    }
}