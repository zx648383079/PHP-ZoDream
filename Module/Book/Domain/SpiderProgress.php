<?php
namespace Module\Book\Domain;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Spiders\GetBookInterface;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use Zodream\Html\Progress;
use Exception;

class SpiderProgress extends Progress {

    /**
     * @return GetBookInterface
     */
    protected function getSpider() {
        return $this->options['spider'];
    }

    public function init() {
        $book = $this->options['book'];
        $spider = $this->getSpider();
        if (!isset($book['chapters'])) {
            $book =
                $spider->book($spider instanceof ZhiShuShenQi
                    ? $book['id'] : $book['url']);
        }
        if (empty($book)) {
            throw new Exception('书籍信息错误');
        }
        $this->data = $book['chapters'];
        $model = BookModel::where('name', $book['name'])->first();
        list($book_id, $start) = empty($model) ? self::asyncCreate($book)
            : self::asyncUpdate($model, $book);
        $this->options['book'] = $book_id;
        $this->setStart($start);
    }

    public static function createBook(array $book) {
        return new BookModel([
            'name' => $book['name'],
            'cover' => isset($book['cover']) ? $book['cover'] : '',
            'description' => isset($book['description']) ? $book['description'] : '',
            'author_id' => BookAuthorModel::findIdByName(isset($book['author'])
                ? $book['author'] : ''),
            'cat_id' => BookCategoryModel::findIdByName(isset($book['category'])
                ? $book['category'] : ''),
            'classify' => 1,
            'size' => isset($book['size']) ? intval($book['size']) : 0
        ]);
    }

    /**
     * @param array $book
     * @return array
     * @throws Exception
     */
    public static function asyncCreate(array $book) {
        $model = static::createBook($book);
        if (!$model->save()) {
            throw new Exception('导入失败');
        }
        return [
            $model->id, 0
        ];
    }

    public static function asyncUpdate(
        BookModel $model, array $book) {
        return [
            $model->id, self::getStartChapter($model, $book['chapters'])
        ];
    }

    public static function getStartChapter(BookModel $model, array $chapters) {
        $last_chapter = $model->last_chapter;
        if (empty($last_chapter)) {
            return 0;
        }
        $count = $model->chapter_count;
        for ($i = count($chapters) - 1; $i >= 0; $i --) {
            if (strlen($last_chapter['title']) > strlen($chapters[$i]['title'])) {
                if (strpos($last_chapter['title'], $chapters[$i]['title']) >= 0 &&
                    $i - $count > -15) {
                    return $i + 1;
                }
                continue;
            }
            if (strpos($chapters[$i]['title'], $last_chapter['title']) >= 0 &&
                $i - $count > -15) {
                return $i + 1;
            }
        }
        return 0;
    }

    public function play($item) {
        static::asyncChapters($this->getSpider(), $this->options['book'], $item);
    }

    public static function asyncChapters(
        GetBookInterface $spider, $book_id, $chapter) {
        $item = $spider->chapter($chapter['url']);
        if (empty($item)) {
            $model = new BookChapterModel([
                'title' => $chapter['title'],
                'content' => $chapter['url'],
                'book_id' => $book_id
            ]);
        } else {
            $model = new BookChapterModel([
                'title' => $item['title'],
                'content' => $item['content'],
                'book_id' => $book_id
            ]);
        }
        $model->save();
        return true;
    }
}