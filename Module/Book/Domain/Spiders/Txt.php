<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\File;
use Zodream\Disk\Stream;

class Txt {

    public function isTitle(string $line): bool {
        return preg_match('/^第.{1,10}[章|集]\s*.{1,20}$/', trim($line));
        //return preg_match('/^(正文)?\s*第.{1,10}[章|节|回|部|卷|集]\s*.{1,50}$/', trim($line));
        //return preg_match('/^(\d|\d{2}|1\d{0,2})$/', trim($line));
    }

    public function save(BookModel $book, string $title, $content) {
        if (is_array($content)) {
            $content = implode(PHP_EOL, $content);
        }
        Console::info($title);
        $model =  new BookChapterModel([
            'title' => trim($title),
            'content' => $content,
            'book_id' => $book->id
        ]);
        $model->save();
    }

    public function invoke($file, $book = null) {
        if (!$file instanceof File) {
            $file = new File($file);
        }
        if (!$file->exist()) {
            return;
        }
        if (!$book instanceof BookModel) {
            $book = BookModel::findOrDefault(intval($book), [
                'name' => $file->getNameWithoutExtension()
            ]);
            if ($book->isNewRecord) {
                $book->save();
            }
        }
        if (empty($book)) {
            return;
        }
        $stream = new Stream($file);
        $title = '';
        $lines = [];
        $i = 0;
        $stream->openRead();
        while (!$stream->isEnd()) {
            $line = $stream->readLine();
            if (!$this->isTitle($line)) {
                $lines[] = $line;
                continue;
            }
            $i ++;
            $this->save($book, $title, $lines);
            $title = $line;
            $lines = [];
        }
        if (!empty($title)) {
            $i ++;
            $this->save($book, $title, $lines);
        }
        Console::notice(sprintf('成功导入%s章', $i));
        $stream->close();
        $ids = BookChapterModel::where('book_id', $book->id)->pluck('id');
        $book->size = BookChapterBodyModel::whereIn('id', $ids)->sum('char_length(content)');
        $book->save();
    }
}