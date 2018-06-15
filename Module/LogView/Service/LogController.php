<?php
namespace Module\LogView\Service;


use Module\LogView\Domain\Model\FileModel;
use Module\LogView\Domain\Model\LogModel;
use Module\LogView\Domain\Parser\IIS;
use Module\LogView\Domain\Tag;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

class LogController extends Controller {

    public function indexAction($id, $keywords = null, $operator = null, $name = null, $sort = 'id', $order = 'desc') {
        $file = FileModel::find($id);
        $log_list = LogModel::ofType($name, $operator, $keywords)
            ->sortOrder($sort, $order)
            ->where('file_id', $id)
            ->page();
        $file_list = FileModel::all();
        return $this->show(compact('file', 'log_list', 'file_list'));
    }

    public function createAction() {
        $file_list = FileModel::all();
        return $this->show(compact('file_list'));
    }

    public function saveAction() {
        $upload = new Upload();
        if (!$upload->upload('file')) {
            return $this->redirectWithMessage('./', '请选择文件！');
        }
        if (!$upload->checkType(['log'])) {
            return $this->redirectWithMessage('./', '文件不支持！');
        }
        $folder = Factory::root()->directory('data/upload');
        $folder->create();
        $upload->setDirectory($folder);
        if (!$upload->save()) {
            return $this->redirectWithMessage('./',  end($upload->getError()));
        }
        $name = Request::get('name');
        if (empty($name)) {
            $name = $upload->get()->getName();
        }
        $md5 = $upload->get()->getFile()->md5();
        $model = FileModel::where('md5', $md5)->one();
        if (!empty($model)) {
            return $this->redirectWithMessage(['./log', 'id' => $model->id],  '上传成功！');
        }
        $model = FileModel::create([
            'name' => $name,
            'md5' => $md5
        ]);
        if (empty($model)) {
            return $this->redirectWithMessage('./',  '保存失败！');
        }
        set_time_limit(0);
        $parser = new IIS();
        $upload->each(function (BaseUpload $file) use ($parser, $model) {
            $parser->parser($file->getFile(), function ($item) use ($model) {
                $item['file_id'] = $model->id;
                LogModel::create($item);
            });
        });
        return $this->redirectWithMessage(['./log', 'id' => $model->id],  '上传成功！');
    }

    public function tagAction($name) {
        $value = Request::request('value');
        Tag::toggle($name, $value);
        return $this->jsonSuccess();
    }
}