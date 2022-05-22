<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Disk\File;

class PreviewController extends Controller {
    public function indexAction(int $id) {
        $post = ResourceRepository::get($id);
        return $this->show(compact('post'));
    }

    public function viewAction(int $id, string $file = '') {
        $this->layout = '';
        $folder = UploadRepository::resourceFolder($id);
        if (!empty($file)) {
            $file = $folder->file($file);
            $response = app('response');
            $response->header->setContentType($file->getExtension());
            return $response->setParameter($file);
        }
        $files = $folder->children();
        $file = null;
        foreach ($files as $item) {
            if (!($item instanceof File)) {
                continue;
            }
            if (in_array($item->getName(), ['index.html', 'index.htm', 'default.html', 'default.htm'])) {
                $file = $item;
                break;
            }
            if (!empty($file)) {
                continue;
            }
            if (strpos($item->getName(), '.htm') > 0) {
                $file = $item;
            }
        }
        if (empty($file)) {
            return $this->showContent('');
        }
        return $this->showContent($file->read());
    }
}