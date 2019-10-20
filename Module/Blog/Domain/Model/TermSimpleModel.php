<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\TermEntity;


/**
 * Class TermModel
 * @property integer $id
 * @property string $name
 */
class TermSimpleModel extends TermEntity {
    const SIMPLE_MODE = ['id', 'name'];

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }
}