<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;


use Exception;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Stream;

final class NginxImporter implements ILogImporter
{
    /**
     * "remote_addr", "remote_user", "time_local", "request", "status",
     * "body_bytes_sent", "http_referer", "http_user_agent", "request_time",
     * "upstream_response_time", "http_x_forwarded_for"
     */

    public function read(array $fields, mixed $file, callable $callback): void
    {
        if (!$file instanceof Stream) {
            $file = new Stream($file);
        }
        $file->openRead();
        if (empty($fields)) {
            $fields = ['remote_addr', 'remote_user', 'time_local', 'request', 'status',
                'body_bytes_sent', 'http_referer', 'http_user_agent', 'request_time',
                'upstream_response_time', 'http_x_forwarded_for'];
        }
        $index = -1;
        while (!$file->isEnd()) {
            $index ++;
            $line = $file->readLine();
            if (empty($line)) {
                continue;
            }
            $data = self::parseValues($line);
            if (empty($data) || count($data) !== count($fields)) {
                continue;
            }
            try {
                $res = $callback($this->parseLog(array_combine($fields, $data)));
                Console::notice(sprintf('line %s %s!',
                    $index, $res === false ? 'skip' : 'success'));
            } catch (Exception $ex) {
                Console::error(sprintf('line %s error!', $index));
            }
        }
        $file->close();
    }

    private function parseLog(array $data): array
    {
        $res = [
            'pathname' => '/',
            'queries' => '',
        ];
        foreach ($data as $field => $value)
        {
            switch ($field)
            {
                case 'time_local':
                    $res['created_at'] = strtotime($value);
                    break;
                case 'remote_addr':
                    $res['ip'] = $value;
                    break;
                case 'request':
                    $args = explode(' ', $value);
                    $res['method'] = strtoupper($args[0]);
                    $args = parse_url($args[1]);
                    $res['hostname'] = $args['host'] ?? '';
                    $res['pathname'] = $args['path'] ?? '';
                    $res['queries'] = $args['query'] ?? '';
                    break;
                case 'http_user_agent':
                    $res['user_agent'] = $value;
                    break;
                case 'http_referer':
                    $args = parse_url($value);
                    $res['referrer_hostname'] = $args['host'] ?? '';
                    $res['referrer_pathname'] = self::combinePathQueries($args['path'] ?? '',
                        $args['query'] ?? '');
                    break;
                case 'status':
                    $res['status_code'] = $value;
                    break;
            }
        }
        return $res;
    }
    public static function parseValues(string $line): array {
        $line = trim($line);
        $res = [];
        $i = 0;
        while ($i < strlen($line))
        {
            $next = match ($line[$i]) {
                '"' => strpos($line, '"',  ++$i),
                '[' => strpos($line, ']',  ++$i),
                '<' => strpos($line, '>',  ++$i),
                default => strpos($line, ' ', $i)
            };
            if ($next < $i)
            {
                $res[] = substr($line, $i);
                break;
            }
            $val = substr($line, $i, $next - $i);
            $res[] = $val === '-' ? '' : $val;
            $i = $next + 1;
            while ($i < strlen($line) && $line[$i] === ' ')
            {
                $i ++;
            }
        }
        return $res;
    }

    public function parseFields(string $line): array {
        if (preg_match_all('/\$([a-z_]+)/', $line, $matches))
        {
            return $matches[1];
        }
        return [];
    }

    public static function combinePathQueries(string $path, string $query) : string
    {
        if ($query === '')
        {
            return $path;
        }
        return sprintf('%s?%s', $path, $query);
    }
}