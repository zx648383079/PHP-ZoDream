<?php
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Pager;

class PagerController extends Controller {

    public function indexAction($course, $type = 0) {
        $pager = Pager::create($course, intval($type));
        return $this->render($pager);
    }

    public function checkAction($question) {
        $pager = new Pager();
        foreach ($question as $id => $item) {
            $pager->append($id)
                ->answer(isset($item['answer']) ? $item['answer'] : '',
                    isset($item['dynamic']) ? $item['dynamic'] : null);
        }
        $pager->finish();
        return $this->render($pager);
    }

}