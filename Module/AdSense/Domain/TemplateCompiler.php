<?php
declare(strict_types=1);
namespace Module\AdSense\Domain;

use Module\AdSense\Domain\Repositories\AdRepository;
use Zodream\Database\Model\Model;
use Zodream\Infrastructure\Support\Html;
use function PHPUnit\Framework\returnArgument;

class TemplateCompiler {

    const EACH_BEGIN = '{each}';
    const EACH_END = '{/each}';

    public static function renderAd(array|Model $data, array $size = []): string {
        return match (intval($data['type'])) {
            AdRepository::TYPE_TEXT => Html::a(htmlspecialchars($data['content']), $data['url']),
            AdRepository::TYPE_IMAGE => Html::a(Html::img($data['content'], [
                'style' => $size
            ]), $data['url']),
            AdRepository::TYPE_VIDEO => Html::a(Html::tag('embed', '',
                [
                    'src' => $data['content'],
                    'style' => $size
                ]), $data['url']),
            default => htmlspecialchars_decode($data['content']),
        };
    }

    public static function render(string $template, array $items): string {
        $blockItems = explode(static::EACH_BEGIN, $template);
        $lineItems = [];
        for ($i = 0; $i < count($blockItems); $i++) {
            if ($i < 1) {
                $lineItems[] = $blockItems[$i];
                continue;
            }
            $split = explode(static::EACH_END, $blockItems[$i], 2);
            $lineItems[] = static::renderEach($split[0], $items);
            if (count($split) > 1) {
                $lineItems[] = $split[1];
            }
        }
        return sprintf('<div class="ad-container">%s</div>', implode('', $lineItems));
    }

    protected static function renderEach(string $content, array $items): string {
        $res = [];
        $i = 0;
        foreach ($items as $item) {
            $res[] = static::renderEachItem($content, $item, $i ++);
        }
        return implode('', $res);
    }

    protected static function renderEachItem(string $content, mixed $data, int $index = 0): string {
        return preg_replace_callback('/\{\.(\S+?)\}/', function ($match) use ($data, $index) {
            if ($match[1] === '$') {
                return $index;
            }
            return is_array($data) && !array_key_exists($match[1], $data) ? '' : $data[$match[1]];
        }, $content);
    }

    protected static function replaceEach(string $template,
                                          int $begin, int|false $end, string $content): string
    {
        if ($end === false || $end < $begin) {
            return substr($template, 0, $begin).$content;
        }
        return substr($template, 0, $begin).$content.substr($template,
                $end + strlen(static::EACH_END));
    }

    protected static function subEach(string $content, int $begin, int|false $end): string {
        $begin += strlen(static::EACH_BEGIN);
        if ($end === false || $end < $begin) {
            return substr($content, $begin);
        }
        return substr($content, $begin,
            $end - $begin);
    }
}