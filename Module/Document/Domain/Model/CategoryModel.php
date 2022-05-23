<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;

class CategoryModel extends Model {

    public static function tableName() {
        return 'doc_category';
    }

}