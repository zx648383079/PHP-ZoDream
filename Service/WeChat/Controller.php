<?php
namespace Service\WeChat;

use Zodream\Domain\Controller\Controller as BaseController;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
    use AjaxTrait;
}