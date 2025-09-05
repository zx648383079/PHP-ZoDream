<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;


use Exception;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Stream;

final class ApacheImporter implements ILogImporter
{

    public function read(mixed $file, callable $callback): void
    {
        if (!$file instanceof Stream) {
            $file = new Stream($file);
        }
        $file->openRead();
        $headers = ['remote_addr', 'remote_user', 'time_local', 'request', 'status',
            'body_bytes_sent', 'http_referer', 'http_user_agent', 'request_time',
            'upstream_response_time', 'http_x_forwarded_for'];
        $index = -1;
        while (!$file->isEnd()) {
            $index ++;
            $line = $file->readLine();
            if (empty($line)) {
                continue;
            }
            $data = NginxImporter::parseValues($line);
            if (empty($data) || count($data) !== count($headers)) {
                continue;
            }
            try {
                $callback($this->parseLog(array_combine($headers, $data)));
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
                case 't':
                    $res->created_at = strtotime($value);
                case 'a':
                    $res->ip = $value;
                    break;
                case 'm':
                    $res->method = strtoupper($value);
                    break;
                case 'U':
                    $res->pathname = $value;
                    break;
                case 'q':
                    $res->queries = $value;
                    break;
                case '{User-Agent}i':
                    $res->user_agent = $value;
                    break;
                case '{Referer}i':
                    $res->referrer_hostname = parse_url($value, PHP_URL_HOST);
                    $res->referrer_pathname = parse_url($value, PHP_URL_PATH);
                    break;
                case '>s':
                    $res->status_code = $value;
                    break;
            }
        }
        return $res;
    }

    private function parserFields(string $line): array {
        if (preg_match_all('/%([\{\}a-z_\->]+)/', $line, $matches, PREG_SET_ORDER))
        {
            return $matches[1];
        }
        return [];
    }
}