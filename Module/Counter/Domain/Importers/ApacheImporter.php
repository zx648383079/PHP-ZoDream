<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;


use Exception;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Stream;

final class ApacheImporter implements ILogImporter
{

    public function read(array $fields, mixed $file, callable $callback): void
    {
        if (!$file instanceof Stream) {
            $file = new Stream($file);
        }
        $file->openRead();
        if (empty($fields)) {
            $fields = ['h', 'l', 'u', 't', 'r', '>s', 'b'];
        }
        $index = -1;
        while (!$file->isEnd()) {
            $index ++;
            $line = $file->readLine();
            if (empty($line)) {
                continue;
            }
            $data = NginxImporter::parseValues($line);
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

    private function parseLog(array $data) : array
    {
        $res = [
            'pathname' => '/',
            'queries' => '',
        ];
        foreach ($data as $field => $value)
        {
            switch ($field)
            {
                case 't':
                    $res['created_at'] = strtotime($value);
                    break;
                case 'a':
                case 'h':
                    $res['ip'] = $value;
                    break;
                case 'm':
                    $res['method'] = strtoupper($value);
                    break;
                case 'r':
                    $args = explode(' ', $value);
                    $res['method'] = strtoupper($args[0]);
                    $args = parse_url($args[1]);
                    $res['hostname'] = $args['host'] ?? '';
                    $res['pathname'] = $args['path'] ?? '';
                    $res['queries'] = $args['query'] ?? '';
                    break;
                case 'U':
                    $res['pathname'] = $value;
                    break;
                case 'q':
                    $res['queries'] = $value;
                    break;
                case '{User-Agent}i':
                    $res['user_agent'] = $value;
                    break;
                case '{Referer}i':
                    $args = parse_url($value);
                    $res['referrer_hostname'] = $args['host'] ?? '';
                    $res['referrer_pathname'] = NginxImporter::combinePathQueries($args['path'] ?? '',
                        $args['query'] ?? '');
                    break;
                case '>s':
                    $res['status_code'] = $value;
                    break;
            }
        }
        return $res;
    }

    public function parseFields(string $line): array {
        if (preg_match_all('/%([\{\}a-z_\->]+)/', $line, $matches))
        {
            return $matches[1];
        }
        return [];
    }
}