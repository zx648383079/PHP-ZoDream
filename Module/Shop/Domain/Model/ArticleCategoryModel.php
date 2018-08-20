<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class ArticleCategoryModel extends Model {
    public static function tableName() {
        return 'shop_article_category';
    }
}