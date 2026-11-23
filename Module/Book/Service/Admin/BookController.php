<?php
declare(strict_types=1);
namespace Module\Book\Service\Admin;


use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Repositories\BookRepository;

class BookController extends Controller {
    public function indexAction(string $keywords = '', int $cat_id = 0, int $author_id = 0, int $classify = 0) {
        $model_list = BookModel::with('category', 'author')
            //->withCount('chapter')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($author_id), function ($query) use ($author_id) {
                $query->where('author_id', intval($author_id));
            })->when(is_numeric($classify), function ($query) use ($classify) {
                $query->where('classify', intval($classify));
            })->orderBy('id', SORT_DESC)->page();
        $cat_list = BookCategoryModel::select('id', 'name')->get();
        $author_list = BookAuthorModel::select('id', 'name')->get();
        return $this->show(compact('model_list', 'cat_list', 'author_list', 'keywords', 'cat_id', 'classify', 'author_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = BookModel::findOrNew($id);
        $cat_list = BookCategoryModel::select('id', 'name')->get();
        $author_list = BookAuthorModel::select('id', 'name')->get();
        return $this->show('edit', compact('model', 'cat_list', 'author_list'));
    }

    public function saveAction() {
        $model = new BookModel();
        if (!$model->load()) {
            return $this->renderFailure('输入数据有误！');
        }
        if ($model->isExist()) {
            return $this->renderFailure('书籍已存在！');
        }
        $model->autoIsNew();
        $isNew = $model->isNewRecord;
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $isNew ? $this->getUrl('book') : -1
        ]);
    }

    public function deleteAction(int $id) {
        BookModel::where('id', $id)->delete();
        $ids = BookChapterModel::where('book_id', $id)->pluck('id');
        if (!empty($ids)) {
            BookChapterModel::where('book_id', $id)->delete();
            BookChapterBodyModel::whereIn('id', $ids)->delete();
        }
        return $this->renderData([
            'url' => $this->getUrl('book')
        ]);
    }

    public function chapterAction(int $book, string $keywords = '') {
        $book = BookModel::find($book);
        $model_list = BookChapterModel::where('book_id', $book->id)
            ->when(!empty($keywords), function ($query) {
                BookModel::searchWhere($query, 'title');
            })->orderBy('id', SORT_DESC)->page();
        return $this->show(compact('model_list',  'book', 'keywords'));
    }

    public function createChapterAction(int $book) {
        return $this->editChapterAction(0, $book);
    }

    public function editChapterAction(int $id, int $book = 0) {
        $model = BookChapterModel::findOrNew($id);
        if ($model->book_id < 1) {
            $model->book_id = intval($book);
        }
        return $this->show('editChapter', compact('model'));
    }

    public function saveChapterAction() {
        $model = new BookChapterModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('book/chapter', ['book' => $model->book_id])
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteChapterAction(int $id) {
        $model = BookChapterModel::find($id);
        $model->delete();
        BookChapterBodyModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('book/chapter', ['book' => $model->book_id])
        ]);
    }

    public function refreshAction() {
        BookRepository::refreshBook();
        return $this->renderData(true);
    }

    public function importAction() {
        return $this->show();
    }



}