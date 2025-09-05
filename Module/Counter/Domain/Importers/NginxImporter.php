<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;


use Exception;
use Module\Counter\Domain\Model\LogModel;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Stream;

final class NginxImporter implements ILogImporter
{
    /**
     * "remote_addr", "remote_user", "time_local", "request", "status",
     * "body_bytes_sent", "http_referer", "http_user_agent", "request_time",
     * "upstream_response_time", "http_x_forwarded_for"
     */

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
            $data = self::parseValues($line);
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
                case 'time_local':
                    $res->created_at = strtotime($value);
                case 'remote_addr':
                    $res->ip = $value;
                    break;
                case 'request':
                    $args = explode(' ', $value);
                    $res->method = strtoupper($args[0]);
                    $args = explode('?', $args[1], 2);
                    $res->pathname = $args[0];
                    $res->queries = $args[1] ?? '';
                    break;
                case 'http_user_agent':
                    $res->user_agent = $value;
                    break;
                case 'http_referer':
                    $res->referrer_hostname = parse_url($value, PHP_URL_HOST);
                    $res->referrer_pathname = parse_url($value, PHP_URL_PATH);
                    break;
                case 'status':
                    $res->status_code = $value;
                    break;
            }
        }
        return $res;
    }
    public static function parseValues(string $line): array {
        $res = [];
        $i = 0;
        while ($i < strlen($line))
        {
            $next = match ($line[$i]) {
                '"' => strpos($line, '"', $i ++),
                '[' => strpos($line, ']', $i ++),
                '<' => strpos($line, '>', $i ++),
                default => strpos($line, ' ', $i)
            };
            if ($next < $i)
            {
                $res[] = substr($line, $i);
                break;
            }
            $res[] = substr($line, $i, $next - $i);
            $i = $next + 1;
            while ($i < strlen($line) && $line[$i] == ' ')
            {
                $i ++;
            }
        }
        return $res;
    }

    private function parserFields(string $line): array {
        if (preg_match_all('/\$([a-z_]+)/', $line, $matches, PREG_SET_ORDER))
        {
            return $matches[1];
        }
        return [];
    }
}