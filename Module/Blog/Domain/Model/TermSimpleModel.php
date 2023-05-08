<?php
namespace Module\Blog\Domain\Model;

use Domain\Repositories\LocalizeRepository;
use Module\Blog\Domain\Entities\TermEntity;


/**
 * Class TermModel
 * @property integer $id
 * @property string $name
 * @property string $styles
 */
class TermSimpleModel extends TermEntity {

    public static function query() {
        return parent::query()->select(['id', 'styles', ...LocalizeRepository::languageColumnsWidthPrefix('name')]);
    }
}