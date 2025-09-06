<?php
declare(strict_types = 1);
namespace Module\Counter\Domain\Importers;

interface ILogImporter
{
    public function read(array $fields, mixed $file, callable $callback): void;

    public function parseFields(string $line): array;
}