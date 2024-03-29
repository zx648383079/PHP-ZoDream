<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters;

enum ReplyType {
    case Text;
    case Url;
    case Template;
    case News;
    case Media;
}