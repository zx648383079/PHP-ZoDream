<?php
namespace Module\LogView\Domain\Parser;

use Zodream\Disk\File;
use Zodream\Disk\Stream;
use Zodream\Domain\Debug\Log;
use Exception;

class IIS {
    /**
     *      [0] date
     *      [1] time
     *      [2] s-ip
     *      [3] cs-method
     *      [4] cs-uri-stem
     *      [5] cs-uri-query
     *      [6] s-port
     *      [7] cs-username
     *      [8] c-ip
     *      [9] cs(User-Agent)
     *     [10] cs(Referer)
     *     [11] sc-status
     *     [12] sc-substatus
     *     [13] sc-win32-status
     *     [14] time-taken
     */

    /**
     * @param Stream| File $file
     * @param callable $callback
     */
    public function parser($file, callable $callback) {
        if (!$file instanceof Stream) {
            $file = new Stream($file);
        }
        $file->openRead();
        $headers = null;
        $index = -1;
        while (!$file->isEnd()) {
            $index ++;
            $line = $file->readLine();
            if (empty($line)) {
                continue;
            }
            if (strpos($line, '#Fields') === 0) {
                $headers = $this->parserHeaders($line);
                Log::notice($line);
                continue;
            }
            if (empty($headers)
                || substr($line, 0, 1) === '#') {
                continue;
            }
            $data = $this->parserItem($line, $headers);
            if (empty($data)) {
                continue;
            }
            try {
                $callback($data);
                Log::notice(sprintf('line %s success!', $index));
            } catch (Exception $ex) {
                Log::error(sprintf('line %s error!', $index));
            }
        }
        $file->close();
    }

    public function parserItem($line, array $headers) {
        $args = explode(' ', $line);
        if (count($headers) != count($args)) {
            return [];
        }
        return array_combine($headers, $args);
    }

    public function parserHeaders($line) {
        list(, $line) = explode(':', $line);
        $data = [];
        $args = explode(' ', $line);
        foreach ($args as $arg) {
            $arg = trim($arg);
            if (empty($arg)) {
                continue;
            }
            $arg = str_replace(['-', '(', ')'], ['_', '_', ''], $arg);
            $data[] = strtolower($arg);
        }
        return $data;
    }
}