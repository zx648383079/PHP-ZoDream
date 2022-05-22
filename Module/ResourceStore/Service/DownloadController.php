<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;

class DownloadController extends Controller {
    public function indexAction(int $id, Output $output) {
        $post = ResourceRepository::get($id);
        if (empty($post)) {
            return $this->redirect('./');
        }
        ResourceRepository::log()->insert([
            'item_type' => ResourceRepository::LOG_TYPE_RES,
            'item_id' => $id,
            'action' => ResourceRepository::LOG_ACTION_DOWNLOAD,
        ]);
        $post->download_count ++;
        $post->save();
        $file = UploadRepository::file($post);
        return $output
            ->file($file);
    }

}