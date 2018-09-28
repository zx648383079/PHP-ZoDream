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
            ->withCount('chapter')
            ->when(!empty($keywords), function ($query) {
                BookModel::search($query, 'name');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($author_id), function ($query) use ($author_id) {
                $query->where('author_id', intval($author_id));
            })->orderBy('id', 'desc')->page();
        $cat_list = BookCategoryModel::select('id', 'name')->all();
        $author_list = BookAuthorModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'cat_list', 'author_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BookModel::findOrNew($id);
        $cat_list = BookCategoryModel::select('id', 'name')->all();
        $author_list = BookAuthorModel::select('id', 'name')->all();
        return $this->show(compact('model', 'cat_list', 'author_list'));
    }

    public function saveAction() {
        $model = new BookModel();
        if (!$model->load()) {
            return $this->jsonFailure('输入数据有误！');
        }
        if ($model->isExist()) {
            return $this->jsonFailure('书籍已存在！');
        }
        if ($model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('book')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookModel::where('id', $id)->delete();
        $ids = BookChapterModel::where('book_id', $id)->pluck('id');
        if (!empty($ids)) {
            BookChapterModel::where('book_id', $id)->delete();
            BookChapterBodyModel::whereIn('id', $ids)->delete();
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('book')
        ]);
    }

    public function chapterAction($book, $keywords = null) {
        $book = BookModel::find($book);
        $model_list = BookChapterModel::where('book_id', $book->id)
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    BookModel::search($query, 'title');
                });
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list',  'book', 'keywords'));
    }

    public function createChapterAction($book) {
        return $this->runMethodNotProcess('editChapter', ['id' => null, 'book' => $book]);
    }

    public function editChapterAction($id, $book = 0) {
        $model = BookChapterModel::findOrNew($id);
        if ($model->book_id < 1) {
            $model->book_id = intval($book);
        }
        return $this->show(compact('model'));
    }

    public function saveChapterAction() {
        $model = new BookChapterModel();
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

    public function refreshAction() {
        $this->deleteNoBookChapter();
        $this->refreshBookSize();
        return $this->jsonSuccess();
    }

    protected function refreshBookSize() {
        $ids = BookModel::pluck('id');
        foreach ($ids as $id) {
            $ids = BookChapterModel::where('book_id', $id)->pluck('id');
            $length = BookChapterBodyModel::whereIn('id', $ids)->sum('char_length(content)');
            BookModel::where('id', $id)
                ->update([
                    'size' => $length
                ]);
        }
    }

    protected function deleteNoBookChapter(): void {
        $ids = BookChapterModel::query()->alias('c')
            ->left('book b', 'b.id', '=', 'c.book_id')
            ->where('b.id')
            ->select('c.id')
            ->pluck();
        if (!empty($ids)) {
            BookChapterModel::whereIn('id', $ids)->delete();
            BookChapterBodyModel::whereIn('id', $ids)->delete();
        }
    }


}