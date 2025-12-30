<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Blog\Domain\Repositories\PublishRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PublishController extends Controller {

    public function methods()
    {
        return [
            'index' => ['POST', 'PUT', 'PATCH'],
            'detail' => ['GET', 'HEAD', 'OPTIONS'],
            'upload' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    public function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(Request $request, int $id = 0) {
        try {
            $model = PublishRepository::save($request->validate([
                'id' => 'int',
                'title' => 'required|string:0,200',
                'description' => 'string:0,255',
                'keywords' => 'string:0,255',
                'parent_id' => 'int',
                'programming_language' => 'string:0,20',
                'language' => '',
                'thumb' => 'string:0,255',
                'edit_type' => 'int:0,127',
                'content' => '',
                'user_id' => 'int',
                'term_id' => 'int',
                'type' => 'int:0,127',
                'recommend_count' => 'int',
                'comment_count' => 'int',
                'click_count' => 'int',
                'open_type' => 'int:0,127',
                'open_rule' => 'string:0,20',
                'publish_status' => 'int:0,127',
                'tags' => '',
                'is_hide' => '',
                'source_url' => '',
                'source_author' => '',
                'cc_license' => '',
                'weather' => '',
                'audio_url' => '',
                'video_url' => '',
                'comment_status' => '',
                'seo_link' => '',
                'seo_title' => '',
                'seo_description' => '',
            ]), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function pageAction(string $keywords = '', int $term = 0, int $status = 0, int $type = 0, string $language = '') {
        return $this->renderPage(
            PublishRepository::getList($keywords, $term, $status, $type, $language)
        );
    }

    public function saveDraftAction(Request $request, int $id = 0) {
        try {
            $model = PublishRepository::saveDraft($request->validate([
                'id' => 'int',
                'title' => 'required|string:0,200',
                'description' => 'string:0,255',
                'keywords' => 'string:0,255',
                'parent_id' => 'int',
                'programming_language' => 'string:0,20',
                'language' => '',
                'thumb' => 'string:0,255',
                'edit_type' => 'int:0,127',
                'content' => '',
                'user_id' => 'int',
                'term_id' => 'int',
                'type' => 'int:0,127',
                'recommend_count' => 'int',
                'comment_count' => 'int',
                'click_count' => 'int',
                'open_type' => 'int:0,127',
                'open_rule' => 'string:0,20',
                'tags' => '',
                'is_hide' => '',
                'source_url' => '',
                'source_author' => '',
                'cc_license' => '',
                'weather' => '',
                'audio_url' => '',
                'video_url' => '',
                'comment_status' => '',
                'seo_link' => '',
                'seo_title' => '',
                'seo_description' => '',
            ]), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id = 0, string $language = '') {
        try {
            $model = PublishRepository::get($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function uploadAction() {
        try {
            $res = FileRepository::uploadImage();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($res);
    }

    public function deleteAction(int $id) {
        try {
            PublishRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}