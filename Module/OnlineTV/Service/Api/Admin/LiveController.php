<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\LiveRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Infrastructure\Contracts\Http\Input;

class LiveController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            LiveRepository::getList($keywords)
        );
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                LiveRepository::save($input->validate([
                    'id' => 'int',
                    'title' => 'required|string:0,255',
                    'thumb' => 'string:0,255',
                    'source' => 'required|string:0,255',
                    'status' => 'int:0,1'
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        LiveRepository::remove($id);
        return $this->renderData(true);
    }

    public function exportAction() {
        return response()->export(LiveRepository::export());
    }

    public function importAction() {
        $upload = new Upload();
        $upload->setDirectory(app_path()->directory('data/cache'));
        $upload->upload('file');
        if (!$upload->checkType(['csv', 'txt', 'dpl', 'm3u']) || !$upload->save()) {
            return $this->renderFailure('文件不支持，仅支持csv,txt,dpl,m3u文件');
        }
        $upload->each(function (BaseUpload $file) {
            LiveRepository::import($file->getFile(), $file->getName());
        });
        return $this->renderData(true);
    }
}