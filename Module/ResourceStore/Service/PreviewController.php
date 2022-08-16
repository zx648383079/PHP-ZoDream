<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class PreviewController extends Controller {
    public function indexAction(int $id) {
        $post = ResourceRepository::get($id);
        return $this->show(compact('post'));
    }

    public function viewAction(Output $output, int $id, string $file = '') {
        $this->layout = '';
        try {
            $file = UploadRepository::previewFile($id, $file);
        } catch (\Exception $ex) {
            return $this->showContent($ex->getMessage());
        }
        $output->header->setContentType($file->getExtension());
        return $output->setParameter($file);
    }
}