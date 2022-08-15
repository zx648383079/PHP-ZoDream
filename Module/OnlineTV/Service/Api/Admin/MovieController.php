<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\MovieRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MovieController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, int $area = 0,
                                  int $age = 0) {
        return $this->renderPage(
            MovieRepository::search($keywords, $category, $area, $age)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(MovieRepository::getEdit($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                MovieRepository::save($input->validate([
                    'id' => 'int',
                    'title' => 'required|string:0,255',
                    'film_title' => 'string:0,255',
                    'translation_title' => 'string:0,255',
                    'cover' => 'string:0,255',
                    'director' => 'string',
                    'leader' => 'string',
                    'screenwriter' => 'string',
                    'cat_id' => 'int',
                    'area_id' => 'int',
                    'age' => 'string:0,4',
                    'language' => 'string:0,10',
                    'release_date' => 'string:0,255',
                    'duration' => 'int',
                    'description' => 'string:0,255',
                    'content' => '',
                    'series_count' => 'int',
              ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            MovieRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function areaAction() {
        return $this->renderData(
            MovieRepository::areaList()
        );
    }

    public function areaSaveAction(Input $input) {
        try {
            return $this->render(
                MovieRepository::areaSave($input->validate([
                    'id' => 'int',
                    'name' => 'string:0,255',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function areaDeleteAction(int $id) {
        try {
            MovieRepository::areaRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function scoreAction(int $movie) {
        return $this->renderData(
            MovieRepository::scoreList($movie)
        );
    }

    public function scoreSaveAction(Input $input) {
        try {
            return $this->render(
                MovieRepository::scoreSave($input->validate([
                    'id' => 'int',
                    'movie_id' => 'required|int',
                    'name' => 'required|string:0,20',
                    'score' => 'required|string:0,10',
                    'url' => 'string:0,255',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function scoreDeleteAction(int $id) {
        try {
            MovieRepository::scoreRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function seriesAction(int $movie, string $keywords = '') {
        return $this->renderPage(
            MovieRepository::seriesList($movie, $keywords)
        );
    }

    public function seriesSaveAction(Input $input) {
        try {
            return $this->render(
                MovieRepository::seriesSave($input->validate([
                    'id' => 'int',
                    'movie_id' => 'required|int',
                    'episode' => 'required|int',
                    'title' => 'string:0,255',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function seriesDeleteAction(int $id) {
        try {
            MovieRepository::seriesRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function fileAction(int $movie, int $series = 0) {
        return $this->render(
            MovieRepository::fileList($movie, $series)
        );
    }

    public function fileSaveAction(Input $input) {
        try {
            return $this->render(
                MovieRepository::fileSave($input->validate([
                    'id' => 'int',
                    'movie_id' => 'required|int',
                    'series_id' => 'int',
                    'file_type' => 'int:0,127',
                    'definition' => 'int:0,127',
                    'file' => 'required|string:0,255',
                    'name' => 'required|string:0,255',
                    'size' => 'string:0,20',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fileDeleteAction(int $id) {
        try {
            MovieRepository::fileRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function uploadAction(Input $input) {
        try {
            return $this->render(TvRepository::storage()->addFile($input->file('file')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}