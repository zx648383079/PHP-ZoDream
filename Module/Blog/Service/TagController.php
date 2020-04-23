<?php
namespace Module\Blog\Service;


use Module\Blog\Domain\Repositories\TagRepository;

class TagController extends Controller {

    public function indexAction() {
        $tag_list = TagRepository::get();
        return $this->show(compact('tag_list'));
    }
}