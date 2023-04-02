<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Repositories\LocalizeRepository;
use Module\SEO\Domain\Model\AgreementModel;

class AgreementRepository{

    public static function getList(string $keywords = '') {
        return AgreementModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'title']);
        })->orderBy('created_at', 'desc')->page();
    }

    public static function get(int $id) {
        return AgreementModel::findOrThrow($id, __('Service agreement does not exist'));
    }

    public static function detail(int $id) {
        $model = static::get($id);
        $data = $model->toArray();
        $data['languages'] = LocalizeRepository::formatLanguageList(AgreementModel::where('name', $model->name)
            ->where('status', $model->status)
            ->orderBy('created_at', 'asc')->asArray()
            ->get('id', LocalizeRepository::LANGUAGE_COLUMN_KEY)
            , false);
        return $data;
    }

    public static function getByName(string $name) {
        $model = LocalizeRepository::getByKey(
            AgreementModel::where('status', 1)
                ->orderBy('id', 'desc'),
            'name',
            $name
        );
        if (empty($model)) {
            throw new \Exception(__('Service agreement does not exist'));
        }
        return $model;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AgreementModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if ($model->status > 0) {
            AgreementModel::where('name', $model->name)
                ->where(LocalizeRepository::LANGUAGE_COLUMN_KEY, $model->language)
                ->where('id', '<>', $model->id)->update([
                    'status' => 0
                ]);
        }
        return $model;
    }

    public static function remove(int $id) {
        AgreementModel::where('id', $id)->delete();
    }
}