<?php
namespace Module\Book\Domain\Spiders;


use Zodream\Http\Http;

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
}