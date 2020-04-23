<?php
namespace Module\Blog\Service;


use Module\Blog\Domain\Repositories\BlogRepository;

class ArchivesController extends Controller {

    public function indexAction() {
        $blog_list = BlogRepository::getArchives();
        return $this->show(compact('blog_list'));
    }
}