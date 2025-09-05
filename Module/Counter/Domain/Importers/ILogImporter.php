<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;

interface ILogImporter
{
    public function read(mixed $file, callable $callback): void;
}