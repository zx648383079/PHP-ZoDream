<?php
declare(strict_types = 1);
namespace Module\Book\Domain\Spiders;


use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Http\Http;
use Zodream\Infrastructure\Error\Exception;

class ZhiShuShenQi implements GetBookInterface {

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

    public function book(string $id): array {
        $data = (new Http())->url('http://api.zhuishushenqi.com/book/'.$id)->json();
        if (array_key_exists('ok', $data) && $data['ok'] === false) {
            return [];
        }
        return [
            'name' => $data['title'],
            'author' => $data['author'],
            'category' => $data['cat'],
            'description' => $data['longIntro'],
            'cover' => urldecode(substr($data['cover'], 7)),
            'chapters' => $this->chapters($id),
            'size' => $data['wordCount']
        ];
    }

    public function chapters(string $book): array {
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

    public function chapter(string $url): array {
        $data = (new Http())->url('http://chapterup.zhuishushenqi.com/chapter/'.$url)->json();
        if (!$data['ok']) {
            return [];
        }
        return [
            'title' => $data['chapter']['title'],
            'content' => $data['chapter']['cpContent']
        ];
    }


}