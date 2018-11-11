<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;

abstract class BaseField {


    abstract public function options(ModelFieldModel $field);

    abstract public function add($name, $options);
    abstract public function edit($name, $options);
    abstract public function drop($name);

    abstract public function set(ModelFieldModel $field);

    abstract public function get($name, ContentModel $model);

    abstract public function input();

    abstract public function output($value);

}