<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain;

use Infrastructure\LinkRule as BaseRule;
use Zodream\Infrastructure\Support\Html;

class LinkRule extends BaseRule {

    protected static function renderUser(array $rule): string
    {
        return Html::a($rule['s'], url('./', ['user' => $rule['u']]));
    }

    protected static function renderExtra(array $rule): string
    {
        if (isset($rule['t'])) {
            return Html::a($rule['s'], url('./', ['topic' => $rule['t']]));
        }
        return parent::renderExtra($rule);
    }

    public static function formatTopic(string $word, int $topic): array
    {
        return [
          's' => $word,
          't' => $topic
        ];
    }
}