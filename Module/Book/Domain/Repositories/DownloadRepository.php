<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Disk\File;
use Zodream\Disk\Stream;
use Zodream\Disk\ZipStream;

class DownloadRepository {

    public static function txt(int $id): File {
        $book = BookModel::find($id);
        return static::getFile($book);
    }

    public static function zip(int $id): File {
        $book = BookModel::find($id);
        $file = static::getFile($book);
        $zipFile = new File($file->getFullName().'.zip');
        $zip = new ZipStream($zipFile,\ZipArchive::CREATE);
        $zip->addFile(sprintf('%s.txt', $book->name), $file);
        $zip->close();
        return $zipFile;
    }

    protected static function getFile(BookModel $book) {
        $file = app_path()->file('data/cache/book_'. $book->id);
        if (!$file->exist() || $file->modifyTime() < time() - 3600) {
            $stream = new Stream($file);
            $stream->open('w');
            $chapters = BookRepository::chapters($book->id);
            foreach ($chapters as $item) {
                self::writeChapter($item, $stream);
                if ($item['type'] < 1 || !isset($item['children'])) {
                    continue;
                }
                foreach ($item['children'] as $it) {
                    self::writeChapter($it, $stream);
                }
            }
            $stream->close();
        }
        return $file;
    }

    private static function writeChapter(array|BookChapterModel $chapter, Stream $stream) {
        $stream->writeLine($chapter['title']);
        $stream->writeLine('');
        if ($chapter['type'] > 0) {
            return;
        }
        $content = BookChapterBodyModel::where('id', $chapter['id'])->value('content');
        if (!empty($content)) {
            $stream->writeLine($content);
        }
        $stream->writeLines([
            '',
            '',
        ]);
    }
}