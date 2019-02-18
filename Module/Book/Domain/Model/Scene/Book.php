<?php
namespace Module\Book\Domain\Model\Scene;


use Module\Book\Domain\Model\BookModel;

class Book extends BookModel {
    protected $append = ['category', 'author'];
}