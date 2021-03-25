<?php
declare(strict_types=1);
namespace Module\Disk\Domain;

use Zodream\Disk\Stream;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

class WebVTT implements ExportObject {

    protected string $newLine = "\n";
    protected string $newBlock = "\n\n";

    public function __construct(
        protected string $name,
        protected array $cues = [],
    )
    {
    }

    public function render(): string {
        $blocks = [$this->renderHeader()];
        $cues = $this->getCues();
        foreach ($cues as $cue) {
            $blocks[] = $this->renderCue(...$cue);
        }
        return implode($this->newBlock, $blocks);
    }

    /**
     *
     * @return array 示例 [[startTime, endTime, text]]
     */
    protected function getCues(): array {
        return array_filter($this->cues, function ($cue) {
            return !empty($cue);
        });
    }

    protected function renderHeader(): string {
        return 'WEBVTT';
    }

    /**
     * @param string|int|float $startTime
     * @param string|int|float $endTime
     * @param array|string $text 示例 Like a <00:19.000>big-a <00:19.500>pizza <00:20.000>pie
     * @return string
     */
    protected function renderCue(string|int|float $startTime, string|int|float $endTime, array|string $text): string {
        $lines = [sprintf('%s --> %s', $this->renderTime($startTime), $this->renderTime($endTime))];
        foreach ((array)$text as $item) {
            $lines[] = $this->renderText($item);
        }
        return implode($this->newLine, $lines);
    }

    protected function renderText(string $text): string {
        return htmlspecialchars($text);
    }

    protected function renderTime(string|int|float $time): string {
        if (!is_numeric($time)) {
            $args = array_reverse(explode(':', $time));
            $time = 0;
            foreach ($args as $i => $arg) {
                $time += $arg * pow(60, $i);
            }
        }
        return sprintf('%s:%s:%s.%s',
            str_pad((string)floor($time / 3600), 2, '0', STR_PAD_LEFT),
            str_pad((string)floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT),
            str_pad((string)floor($time % 60), 2, '0', STR_PAD_LEFT),
            str_pad((string)floor($time * 1000 % 1000), 3, '0', STR_PAD_LEFT)
        );
    }

    public function getName(): string
    {
        return $this->name. '.vtt';
    }

    public function getType(): string
    {
        return 'text/vtt';
    }

    public function send()
    {
        echo $this->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public static function parseCuesFromFile($file, string $ext): array {
        $stream = new Stream($file);
        $stream->openRead();
        $cues = [];
        while (!$stream->isEnd()) {
            $cue = static::parseCueLine($stream->readLine().'', $ext);
            if (!empty($cue)) {
                $cues[] = $cue;
            }
        }
        $stream->close();
        $cues[] = static::parseCueLine('', $ext);
        return $cues;
    }

    public static function parseCues(string $content, string $ext): array {
        $lines = explode(PHP_EOL, $content);
        $count = count($lines);
        $i = 0;
        $cues = [];
        while ($i < $count) {
            $cue = static::parseCueLine($lines[$i], $ext);
            if (!empty($cue)) {
                $cues[] = $cue;
            }
            $i ++;
        }
        $cues[] = static::parseCueLine('', $ext);
        return $cues;
    }

    public static function parseCueLine(string $content, string $ext): array {
        static $cache = [];
        if (empty($content)) {
            list($cache, $ret) = [[], $cache];
            return $ret;
        }
        $regItems = [
            'ssa' => '/^Dialogue:\s*Marked=\d+,([\d\:\.]+),([\d\:\.]+),Default,.+?,!Effect,(.+)/',
            'ass' => '/^Dialogue:\s*\d+,([\d\:\.]+),([\d\:\.]+),Default,.+,([^,]+)/',
        ];
        if (isset($regItems[$ext])) {
            if (preg_match($regItems[$ext], $content, $match)) {
                return [
                  $match[1],
                  $match[2],
                  trim($match[3])
                ];
            }
            return [];
        }
        if ($ext === 'srt') {
            if (preg_match('/^([\d\:\.,]+)\s*-->\s*([\d\:\.,]+)/', $content, $match)) {
                $cache = [
                    str_replace(',', '.', $match[1]),
                    str_replace(',', '.', $match[2]),
                    [],
                ];
                return [];
            }
            if (empty($cache)) {
                return [];
            }
            $cache[2][] = trim($content);
            return [];
        }
        return [];
    }
}
