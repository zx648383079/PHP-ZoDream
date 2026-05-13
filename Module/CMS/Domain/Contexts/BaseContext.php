<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Contexts;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
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
    }

    private readonly int $id;
    private readonly int $tableId;
    private EntityCreator|null $_channel = null;
    private EntityCreator|null $_article = null;

    protected function lazyChannel(): EntityCreator {
        if (empty($this->_channel)) {
            $this->_channel = new DefaultEntityCreator(CategoryModel::class, $this->channelTableName());
        }
        return $this->_channel;
    }

    protected function lazyArticle(): EntityCreator {
        if (empty($this->_article)) {
            $this->_article = new DefaultEntityCreator(ContentModel::class, $this->articleTableName());
        }
        return $this->_article;
    }

    public function id(): int {
        return $this->id;
    }

    public function tableId(): int {
        return $this->tableId;
    }

    public function source(): SiteModel {
        return $this->site;
    }

    public function isLocale(): bool {
        return $this->site->locale_group_id > 0;
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

    public function articleTableName(): string {
        return 'cms_content_'.$this->tableId;
    }

    public function articleBuilder(): SqlBuilder {
        return $this->lazyArticle()->builder();
    }

    public function channelBuilder(): SqlBuilder {
        return $this->lazyChannel()->builder();
    }

    public function channelSave(CategoryEntity|array $model): CategoryEntity {
        if (is_array($model)) {
            $model = new CategoryModel($model);
        }
        return $this->lazyChannel()->save($model, !$model->isNewRecord);
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