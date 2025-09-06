<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;


use Exception;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Stream;

final class IISImporter implements ILogImporter
{
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

    public function read(array $fields, mixed $file, callable $callback): void
    {
        if (!$file instanceof Stream) {
            $file = new Stream($file);
        }
        $file->openRead();
        $fields = [];
        $index = -1;
        while (!$file->isEnd()) {
            $index ++;
            $line = $file->readLine();
            if (empty($line)) {
                continue;
            }
            if (str_starts_with($line, '#Fields')) {
                $fields = $this->parseFields($line);
                Console::notice($line);
                continue;
            }
            if (empty($fields)
                || str_starts_with($line, '#')) {
                continue;
            }
            $data = $this->parseValues($line);
            if (empty($data) || count($data) !== count($fields)) {
                continue;
            }
            try {
                $callback($this->parseLog(array_combine($fields, $data)));
                Console::notice(sprintf('line %s success!', $index));
            } catch (Exception $ex) {
                Console::error(sprintf('line %s error!', $index));
            }
        }
        $file->close();
    }

    private function parseLog(array $data) : LogModel
    {
        $res = new LogModel();
        foreach ($data as $field => $value)
        {
            switch ($field)
            {
                case 'date':
                    $res->created_at = strtotime(sprintf('%s %s', $value, $data['time']));
                    break;
                case 'c_ip':
                    $res->ip = $value;
                    break;
                case 'cs_method':
                    $res->method = strtoupper($value);
                    break;
                case 'cs_uri_stem':
                    $res->pathname = $value;
                    break;
                case 'cs_uri_query':
                    $res->queries = $value;
                    break;
                case 'cs_user_agent':
                    $res->user_agent = $value;
                    break;
                case 'cs_referer':
                    $args = parse_url($value);
                    $res->referrer_hostname = $args['host'] ?? '';
                    $res->referrer_pathname = NginxImporter::combinePathQueries($args['path'] ?? '',
                        $args['query'] ?? '');
                    break;
                case 'sc_status':
                    $res->status_code = $value;
                    break;
            }
        }
        return $res;
    }

    private function parseValues(string $line): array {
        return explode(' ', $line);
    }

    public function parseFields(string $line): array {
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