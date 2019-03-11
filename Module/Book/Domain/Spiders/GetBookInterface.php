<?php
declare(strict_types = 1);
namespace Module\Book\Domain\Spiders;

interface GetBookInterface {
    public function book(string $input): array;

    public function chapter(string $input): array;
}