<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Map extends BaseField {

    public function options(bool $isJson = false): array|string {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function alterColumn(Column $column): void {
        $column->string()->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'map',
                'value' => $value
            ];
        }
        return (string)Theme::text($this->controlName(), $value, $this->controlLabel(), '',
            $this->isRequired());
    }
}