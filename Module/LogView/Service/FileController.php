<?php
declare(strict_types=1);
namespace Module\LogView\Service;

use Module\LogView\Domain\Model\FileModel;
use Module\LogView\Domain\Model\LogModel;
use Module\LogView\Domain\Parser\IIS;
use Module\LogView\Service\Controller;
use Zodream\Disk\File;

class FileController extends Controller {

    protected File|string $layout = '';

    public function rules() {
        return [
            '*' => 'cli'
        ];
    }

    public function indexAction(string $input) {
        $file = app_path()->getFile($input);
        if (!$file->exist()) {
            return $this->showContent('文件不存在！');
        }
        $md5 = $file->md5();
        $model = FileModel::where('md5', $md5)->one();
        if (!empty($model)) {
            return $this->showContent('文件已存在！');
        }
        $model = FileModel::create([
            'name' => $file->getName(),
            'md5' => $md5
        ]);
        $parser = new IIS();
        $parser->parser($file, function ($item) use ($model) {
            $item['file_id'] = $model->id;
            LogModel::create($item);
        });
        return $this->showContent('爬虫执行完成！');
    }
}