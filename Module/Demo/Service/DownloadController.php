<?php
namespace Module\Demo\Service;

use Module\Demo\Domain\Model\LogModel;
use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Repositories\PostRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;

class DownloadController extends Controller {
    public function indexAction(Request $request, $id, Output $output) {
        $post = PostModel::find($id);
        if (empty($post)) {
            return $this->redirect('./');
        }
        LogModel::create([
            'item_type' => LogModel::TYPE_POST,
            'item_id' => $id,
            'user_id' => auth()->id(),
            'action' => LogModel::ACTION_DOWNLOAD,
            'ip' => $request->ip(),
            'created_at' => time(),
        ]);
        $post->download_count ++;
        $post->save();
        $file = PostRepository::file($post);
        return $output
            ->file($file);
    }

}