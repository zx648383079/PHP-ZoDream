<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\AnalysisRepository;
use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Domain\Upload\BaseUpload;
use Zodream\Domain\Upload\Upload;
use Zodream\Html\Page;
use Zodream\Infrastructure\Contracts\Http\Input;

class TrendController extends Controller {
    public function indexAction() {
        return $this->renderPage(StateRepository::currentStay());
    }

    public function analysisAction() {
        $items = new Page(0);
        return $this->renderPage($items);
    }

    public function logAction(string $keywords = '',
                              string $start_at = '',
                              string $end_at = '',
                              string $ip = '',
                              string $goto = '',
                              int $page = 1,
                              int $per_page = 20)
    {
        return $this->renderPage(AnalysisRepository::logList($keywords, $start_at, $end_at, $ip, $goto, $page, $per_page));
    }

    public function logImportAction(string $engine = 'iis', string $hostname = '', string $field_names = '')
    {
        $upload = new Upload();
        $upload->setDirectory(app_path()->directory('data/cache'));
        $upload->upload('file');
        if (!$upload->save()) {
            return $this->renderFailure('上传失败');
        }
        $upload->each(function (BaseUpload $file) use ($engine, $field_names, $hostname) {
            $hostname = $file->getName();
            AnalysisRepository::logImport($hostname, $engine, $field_names, $file->getFile());
        });
        return $this->renderData(true);
    }

    public function analysisMaskAction(Input $input)
    {
        try {
            $model = AnalysisRepository::mask($input->validate([
                'type' => 'required|int',
                'value' => 'required',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}