<?php
declare(strict_types=1);
namespace Module\Exam;

use Module\Exam\Domain\Migrations\CreateExamTables;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateExamTables();
    }

    public function openLinks(SiteMap $map) {
//        $map->add(url('./'), time());
    }
}