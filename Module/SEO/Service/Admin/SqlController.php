<?php
namespace Module\SEO\Service\Admin;

use Zodream\Module\Gzo\Domain\GenerateModel;
use Zodream\Service\Factory;

class SqlController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function backUpAction() {
        $root = Factory::root()->directory('data/sql');
        $root->create();
        $file = $root->file(date('Y-m-d').'.sql');
        set_time_limit(0);
        if ((!$file->exist() || $file->modifyTime() < (time() - 60))
            && !GenerateModel::schema()
                ->export($file)) {
            return $this->jsonFailure('导出失败！');
        }
        return $this->jsonSuccess(null, '备份完成');
    }

    public function clearAction() {
        Factory::root()->directory('data/sql')->delete();
        return $this->jsonSuccess(null, '已删除所有备份');
    }
}