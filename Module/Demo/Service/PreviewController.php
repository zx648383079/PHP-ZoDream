<?php
namespace Module\Demo\Service;

use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Repositories\PostRepository;
use Zodream\Disk\File;

class PreviewController extends Controller {
    public function indexAction($id) {
        $post = PostModel::find($id);
        return $this->show(compact('post'));
    }

    public function viewAction($id, $file = null) {
        $this->layout = false;
        $folder = PostRepository::folder($id);
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
            return;
        }
        return $this->showContent($file->read());
    }
}