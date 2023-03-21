<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;


use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;

final class VisualController extends ModuleController {
    use CheckRole;

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $site, int $id) {

    }

    public function previewAction(int $site, int $id) {

    }
}