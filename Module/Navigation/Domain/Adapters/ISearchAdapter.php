<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Adapters;

use Zodream\Html\Page;

interface ISearchAdapter {

    public function search(string $keywords): Page;

    public function get(int $id): array;

    public function create(array $data): array;

    public function update(int $id, array $data): void;

    public function remove(int $id): void;

}