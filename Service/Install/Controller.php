<?php
namespace Service\Install;

use Zodream\Domain\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
    protected $canCSRFValidate = false;
}