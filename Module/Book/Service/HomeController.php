<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookModel;
use Zodream\Infrastructure\Http\Request;

class HomeController extends Controller {

    public function indexAction() {
        if (app('request')->isMobile()) {
            return $this->redirect('./mobile');
        }
        $recommend_book = BookModel::ofClassify()->limit(4)->all();
        $hot_book = BookModel::ofClassify()->order('click_count', 'desc')->limit(10)->all();

        $new_recommend_book = BookModel::ofClassify()->where('size', '<', 50000)->order('created_at', 'desc')->order('recommend_count', 'desc')->limit(12)->all();
        $week_click_book = BookModel::ofClassify()->order('click_count', 'desc')->limit(5)->all();
        $week_recommend_book = BookModel::ofClassify()->order('recommend_count', 'desc')->limit(5)->all();
        $best_recommend_book = BookModel::ofClassify()->order('recommend_count', 'desc')->limit(12)->all();

        $month_click_book = BookModel::ofClassify()->order('click_count', 'desc')->limit(5)->all();
        $month_recommend_book = BookModel::ofClassify()->order('recommend_count', 'desc')->limit(5)->all();

        $click_bang = BookModel::ofClassify()->order('click_count', 'desc')->limit(15)->all();
        $recommend_bang = BookModel::ofClassify()->order('click_count', 'desc')->limit(15)->all();
        $size_bang = BookModel::ofClassify()->order('size', 'desc')->limit(15)->all();

        $book_list = BookModel::ofClassify()->order('updated_at', 'desc')->limit(20)->all();
        $new_book = BookModel::ofClassify()->where('size < 50000')->order('created_at', 'desc')->order('click_count', 'desc')->limit(10)->all();
        $over_book = BookModel::ofClassify()->where('over_at > 0')->order('click_count', 'desc')->limit(10)->all();
        $hot_author = [];
        $book = BookModel::ofClassify()->one();
        return $this->show(compact('recommend_book', 'book',
            'hot_book', 'book_list', 'new_book', 'over_book', 'click_bang', 'recommend_bang',
            'size_bang', 'hot_author',
            'new_recommend_book', 'week_click_book', 'week_recommend_book', 'best_recommend_book', 'month_click_book', 'month_recommend_book'));
    }
}