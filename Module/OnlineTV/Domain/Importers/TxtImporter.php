<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class TxtImporter implements IImporter, ExportObject {

    public function is($resource, string $fileName): bool
    {
        return Str::endWith($fileName, ['.csv', '.txt']);
    }

    public function read($resource): array
    {
        $items = [];
        $this->readCallback($resource, function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    public function readCallback($resource, callable $cb): bool
    {
        while (($line = fgets($resource)) !== false) {
            $data = explode(',', $line, 2);
            if (count($data) !== 2 || empty($data[1])
                || !parse_url($data[1], PHP_URL_HOST)) {
                continue;
            }
            call_user_func($cb, [
                'title' => $data[0],
                'source' => $data[1]
            ]);
        }
        return true;
    }

    public function getName(): string
    {
        return 'live.txt';
    }

    public function getType(): string
    {
        return 'txt';
    }

    public function send()
    {
        $items = LiveModel::query()->get();
        foreach ($items as $item) {
            echo $item['title'], ',', $item['source'], "\n";
        }
    }
}