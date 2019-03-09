<?php
namespace Module\Book\Domain\Spiders;


use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Http\Http;
use Zodream\Infrastructure\Error\Exception;

class ZhiShuShenQi {

    public function search($keywords) {
            $data = (new Http())->url('http://api.zhuishushenqi.com/book/fuzzy-search', [
                'query' => $keywords
            ])->json();
            if (!$data['ok']) {
                return [];
            }
            return array_map(function ($item) {
                return [
                    'id' => $item['_id'],
                    'name' => $item['title'],
                    'author' => $item['author'],
                    'description' => $item['shortIntro'],
                    'last_chapter' => $item['lastChapter'],
                    'size' => $item['wordCount']
                ];
            }, $data['books']);
    }

    public function book($id) {
        return (new Http())->url('http://api.zhuishushenqi.com/book/'.$id)->json();
    }

    public function chapters($book) {
        $data = (new Http())->url('http://api.zhuishushenqi.com/mix-atoc/'.$book, [
            'view' => 'chapters'
        ])->json();
        if (!$data['ok']) {
            return [];
        }
        return array_map(function ($item) {
            return [
                'title' => $item['title'],
                'url' => $item['link'],
            ];
        }, $data['mixToc']['chapters']);
    }

    public function chapter($url) {
        $data = (new Http())->url('http://chapterup.zhuishushenqi.com/chapter/'.$url)->json();
        if (!$data['ok']) {
            return [];
        }
        return [
            'title' => $data['chapter']['title'],
            'content' => $data['chapter']['cpContent']
        ];
    }

    public function async($id, $name, $description = null, $size = 0) {
        $chapters = $this->chapters($id);
        if (empty($chapters)) {
            throw new Exception('目录为空');
        }
        $model = BookModel::where('name', $name)->first();
        if (empty($model)) {
            return $this->asyncCreate($chapters, $name, $description, $size);
        }
        return $this->asyncUpdate($model, $chapters, $size);
    }

    public function asyncCreate(array $chapters, $name, $description, $size) {
        $model = BookModel::create([
            'name' => $name,
            'description' => $description,
            'size' => $size,
            'author_id' => 1,
            'cat_id' => 1,
            'classify' => 1
        ]);
        if (empty($model)) {
            throw new Exception('导入失败');
        }
        $this->asyncChapters($model, $chapters);
    }

    public function asyncUpdate(BookModel $model, array $chapters, $size) {
        $last_chapter = $model->last_chapter;
        if (empty($last_chapter)) {
            return $this->asyncChapters($model, $chapters);
        }
        $count = $model->chapter_count;
        for ($i = count($chapters) - 1; $i >= 0; $i --) {
            if (strlen($last_chapter['title']) > strlen($chapters[$i]['title'])) {
                if (strpos($last_chapter['title'], $chapters[$i]['title']) >= 0 &&
                    abs($i - $count) < 15) {
                    return $this->asyncChapters($model, $chapters, $i + 1);
                }
                continue;
            }
            if (strpos($chapters[$i]['title'], $last_chapter['title']) >= 0 &&
                abs($i - $count) < 15) {
                return $this->asyncChapters($model, $chapters, $i + 1);
            }
        }
        return $this->asyncChapters($model, $chapters);
    }

    public function asyncChapters(BookModel $model, array $chapters, $start = 0) {
        for (; $start < count($chapters); $start ++) {
            $item = $this->chapter($chapters[$start]['url']);
            if (empty($item)) {
                $chapter = new BookChapterModel([
                    'title' => $chapters[$start]['title'],
                    'content' => $chapters[$start]['url'],
                    'book_id' => $model->id
                ]);
            } else {
                $chapter = new BookChapterModel([
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'book_id' => $model->id
                ]);
            }
            $chapter->save();
        }
    }
}