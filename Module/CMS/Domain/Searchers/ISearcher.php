<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Searchers;

use Zodream\Html\Page;

interface ISearcher {

    public function index(string $id, array $data): void;

    public function search(string $keywords): Page;
}