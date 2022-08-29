<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api;

use Domain\Repositories\FileRepository;
use Exception;

class FileController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        try {
            $items = FileRepository::uploadFiles();
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if (is_array($_FILES['file']['name'])) {
            return $this->renderData($items);
        }
        return $this->render(current($items));
    }

    public function base64Action() {
        try {
            return $this->render(
                FileRepository::uploadBase64()
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function imageAction() {
        try {
            $items = FileRepository::uploadImages();
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if (is_array($_FILES['file']['name'])) {
            return $this->renderData($items);
        }
        return $this->render(current($items));
    }

    public function videoAction() {
        try {
            return $this->render(
                FileRepository::uploadVideo()
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function audioAction() {
        try {
            return $this->render(
                FileRepository::uploadAudio()
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function imagesAction() {
        return $this->renderPage(
            FileRepository::imageList()
        );
    }

    public function filesAction() {
        return $this->renderPage(
            FileRepository::fileList()
        );
    }


}