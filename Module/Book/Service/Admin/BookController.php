<?php
namespace Module\Book\Service\Admin;


use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;

class BookController extends Controller {
    public function indexAction($keywords = null, $cat_id = null, $author_id = null) {
        $model_list = BookModel::with('category', 'author')
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($author_id), function ($query) use ($author_id) {
                $query->where('author_id', intval($author_id));
            })->order('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BookModel::findOrNew($id);
        $cat_list = BookCategoryModel::select('id', 'name')->all();
        $author_list = BookAuthorModel::select('id', 'name')->all();
        return $this->show(compact('model', 'cat_list', 'author_list'));
    }

    public function saveAction() {
        $model = new BookModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('book')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('book')
        ]);
    }

    public function chapterAction($book, $keywords = null) {
        $model_list = BookChapterModel::where('book_id', $book)
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })->order('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createChapterAction($book) {
        return $this->runMethod('editChapter', ['id' => null, 'book' => $book]);
    }

    public function editChapterAction($id, $book = 0) {
        $model = BookChapterModel::findOrNew($id);
        if ($model->book_id < 1) {
            $model->book_id = intval($book);
        }
        return $this->show(compact('model'));
    }

    public function saveChapterAction() {
        $model = new BookModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('book/chapter', ['book' => $model->book_id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteChapterAction($id) {
        $model = BookChapterModel::find($id);
        $model->delete();
        BookChapterBodyModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('book/chapter', ['book' => $model->book_id])
        ]);
    }


}