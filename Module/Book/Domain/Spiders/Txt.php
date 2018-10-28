<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterModel;
use Zodream\Debugger\Domain\Log;
use Zodream\Disk\Stream;

class Txt {

    public function isTitle(string $line): bool {
        return preg_match('/^第.{1,10}[章|集]\s*.{1,20}$/', trim($line));
        //return preg_match('/^(正文)?\s*第.{1,10}[章|节|回|部|卷|集]\s*.{1,50}$/', trim($line));
        //return preg_match('/^(\d|\d{2}|1\d{0,2})$/', trim($line));
    }

    public function save(string $title, $content) {
        if (is_array($content)) {
            $content = implode(PHP_EOL, $content);
        }
        Log::info($title);
        $model =  new BookChapterModel([
            'title' => trim($title),
            'content' => $content,
            'book_id' => 100
        ]);
        $model->save();
    }

    public function invoke($file) {
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
            $this->save($title, $lines);
            $title = $line;
            $lines = [];
        }
        if (!empty($title)) {
            $i ++;
            $this->save($title, $lines);
        }
        Log::notice(sprintf('成功导入%s章', $i));
        $stream->close();
    }
}