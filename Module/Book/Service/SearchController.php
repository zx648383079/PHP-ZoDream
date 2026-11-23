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
        $click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $month_click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $week_click_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $month_recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $week_recommend_bang = BookModel::ofClassify()->isOpen()->orderBy('click_count', SORT_DESC)->take(10)->get();
        $size_bang = BookModel::ofClassify()->isOpen()->orderBy('size', SORT_DESC)->take(10)->get();

        $new_book = BookModel::ofClassify()->isOpen()->where('size < 50000')->orderBy('created_at', SORT_DESC)->orderBy('click_count', SORT_DESC)->take(10)->get();
        $over_book = BookModel::ofClassify()->isOpen()->where('over_at > 0')->orderBy('click_count', SORT_DESC)->take(10)->get();


        return $this->show(compact('click_bang', 'month_click_bang', 'week_click_bang', 'recommend_bang', 'month_recommend_bang', 'week_recommend_bang', 'size_bang', 'new_book', 'over_book'));
    }

}