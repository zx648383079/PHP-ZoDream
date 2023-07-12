<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\BookRepository;

class SearchController extends Controller {

    public function indexAction(string $keywords = '', int $cat_id = 0,
                                int $author_id = 0,
                                bool $top = false,
                                int $status = 0) {
        $book_list = BookRepository::getList([], $cat_id, $keywords, $top, $status, $author_id);
        return $this->show(compact('book_list', 'keywords', 'cat_id'));
    }

    public function topAction() {
        $click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $month_click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $week_click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $month_recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $week_recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', 'desc')->limit(10)->all();
        $size_bang = BookModel::ofClassify()->isOpen()->orderBy('size', 'desc')->limit(10)->all();

        $new_book = BookModel::ofClassify()->isOpen()->where('size < 50000')->orderBy('created_at', 'desc')->orderBy('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::ofClassify()->isOpen()->where('over_at > 0')->orderBy('click_count', 'desc')->limit(10)->all();


        return $this->show(compact('click_bang', 'month_click_bang', 'week_click_bang', 'recommend_bang', 'month_recommend_bang', 'week_recommend_bang', 'size_bang', 'new_book', 'over_book'));
    }

}