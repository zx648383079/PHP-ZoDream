<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Upgrades;

use Module\CMS\Domain\Contexts\LiveSiteContext;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\SingleScene;
use Zodream\Database\Schema\Table;
use Zodream\Database\DB;

/**
 * 升级支持SEO版本
 */
class LocaleUpgrade {

    public function handle(): void {
        $modelItem = ModelModel::query()->where('type', 0)->get();
        $siteItems = SiteModel::query()->get();
        foreach ($siteItems as $site) {
            $this->upgradeSite($site, $modelItem);
        }

    }

    private function upgradeSite(SiteModel $site, array $modelItem): void {
        $context = new LiveSiteContext($site);
        $scene = $context->scene();
        $table = new Table(SiteModel::tableName());
        $localeField = $table->uint('locale_group_id')->default(0)->comment('把多个站点放到同一个组，实现多语言切换');
        $information = DB::information();
        if ($information->columnExist($table, $localeField)) {
            return;
        }
        CreateCmsTables::updateTable($table, [
            $localeField
        ]);
        $table = new Table($context->channelTableName());
        CreateCmsTables::updateTable($table, [
            $table->uint('site_id')->default(0),
            $table->uint('locale_group_id')->default(0)->comment('把多个站点放到同一个组，实现多语言切换'),
        ]);
        $context->channelBuilder()->update([
            'site_id' => $site->id
        ]);
        $table = new Table($context->articleTableName());
        if ($scene instanceof SingleScene) {
            CreateCmsTables::updateTable($table, [
                $table->uint('locale_group_id')->default(0)->comment('把多个站点放到同一个组，实现多语言切换'),
            ]);
            return;
        }
        CreateCmsTables::updateTable($context->articleTableName(), [
            $table->uint('site_id')->default(0),
        ]);
        $context->articleBuilder()->update([
            'site_id' => $site->id
        ]);
        foreach ($modelItem as $model) {
            $scene->setModel($model);
            $table = new Table($scene->getMainTable());
            CreateCmsTables::updateTable($table, [
                $table->uint('site_id')->default(0),
                $table->uint('locale_group_id')->default(0)->comment('把多个站点放到同一个组，实现多语言切换'),
            ]);
            $scene->query()->update([
                'site_id' => $site->id
            ]);
        }
    }
}