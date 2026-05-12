<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\SceneInterface;
use Zodream\Database\Contracts\EntityCreator;
use Zodream\Database\Model\DefaultEntityCreator;
use Zodream\Database\Contracts\SqlBuilder;

abstract class BaseContext implements SiteContextInterface {

    public function __construct(
        private readonly SiteModel $site
    ) {
        $this->id = intval($this->site->id);
        $locale = intval($this->site->locale_group_id);
        $this->tableId = $locale > 0 ? $locale : $this->id;
        $this->channel = new DefaultEntityCreator(CategoryModel::class, 'cms_category_'.$this->tableId);
    }

    public readonly int $id;
    public readonly int $tableId;
    public readonly EntityCreator $channel;

    public function id(): int {
        return $this->id;
    }

    public function tableId(): int {
        return $this->tableId;
    }

    public function source(): SiteModel {
        return $this->site;
    }

    public function theme(): string {
        return $this->site->theme ?? '';
    }

    public function language(): string {
        return $this->site->language ?? '';
    }

    public function isOwer(): bool {
        return $this->id() === $this->tableId();
    }

    public function options(array|null $data = null): array {
        if (is_array($data)) {
            $this->site->saveOption($data);
        }
        return (array)$this->site->options;
    }

    public function channelTableName(): string {
        return 'cms_category_'.$this->tableId;
    }
    public function logTableName(): string {
        return 'cms_log_'.$this->tableId;
    }

    public function channelBuilder(): SqlBuilder {
        return $this->channel->builder();
    }

    public function channelSave(CategoryEntity|array $model): CategoryEntity {
        if (is_array($model)) {
            $model = new CategoryModel($model);
        }
        return $this->channel->save($model, !$model->isNewRecord);
    }

    public function scene(): SceneInterface {
        $instance = app(SceneInterface::class);
        if (empty($instance)) {
            (new \Module\CMS\Module())->boot();
            $instance = app(SceneInterface::class);
        }
        return $instance->binding($this);
    }
}