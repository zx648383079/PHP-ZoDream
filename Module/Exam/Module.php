<?php
namespace Module\Exam;

use Module\Exam\Domain\Migrations\CreateExamTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration()
    {
        return new CreateExamTables();
    }
}