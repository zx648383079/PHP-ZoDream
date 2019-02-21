<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TermModel;

class HomeController extends Controller {

    public function indexAction() {
        $term_count = TermModel::query()->count();
        $blog_id = BlogModel::where('user_id', auth()->id())->pluck('id');
        $blog_count = count($blog_id);
        $view_count = BlogModel::where('user_id', auth()->id())->sum('click_count');
        $comment_count = CommentModel::whereIn('blog_id', $blog_id)
            ->count();
        return $this->show(compact('term_count', 'blog_count', 'view_count', 'comment_count'));
    }
}