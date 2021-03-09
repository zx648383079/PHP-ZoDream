<?php
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Repositories\CMSRepository;

class CommentModel extends BaseModel {
    public static function tableName() {
        return 'cms_comment_'.CMSRepository::siteId();
    }

}