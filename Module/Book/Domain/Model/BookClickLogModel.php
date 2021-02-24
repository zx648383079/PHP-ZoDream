<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Html\Page;

/**
 * Class BookLogModel
 * @property integer $id
 * @property integer $book_id
 * @property integer $hits
 * @property string $created_at
 */
class BookClickLogModel extends Model {

    public static function tableName() {
        return 'book_click_log';
    }

    protected function rules() {
        return [
            'book_id' => 'required|int',
            'hits' => 'int',
            'created_at' => 'required',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'hits' => 'Hits',
            'created_at' => 'Created At',
        ];
    }

    public static function logBook($id) {
        $time = date('Y-m-d');
        $model = static::where('book_id', $id)->where('created_at', $time)->first();
        if (empty($model)) {
            $model = new static([
                'book_id' => $id,
                'created_at' => $time,
                'hits' => 0
            ]);
        }
        $model->hits ++;
        if (!$model->save()) {
            return;
        }
        BookModel::query()->where('id', $id)->updateIncrement('click_count');
    }

    public static function getLogs($type) {
        return cache()->getOrSet('book_top_'.$type, function () use ($type) {
            return static::when($type == 'month', function ($query) {
                $query->where('created_at', '>=', date('Y-m-01'));
            })->when($type == 'week', function ($query) {
                $query->where('created_at', '>=', date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));
            })->groupBy('book_id')->select('book_id, SUM(hits) as hits')
                ->orderBy('hits', 'desc')
                ->asArray()->get();
        }, 3600);
    }

    public static function getPage(Builder $query, $type, $page = 1, $per_page = 20) {
        $logs = static::getLogs($type);
        $pager = new Page(count($logs), $per_page, $page);
        if (empty($logs) || $pager->getTotal() < $pager->getStart()) {
            return $pager->setPage([]);
        }
        $logs = array_splice($logs, $pager->getStart(), $pager->getPageSize());
        if (empty($logs)) {
            return $pager->setPage([]);
        }
        $logs = array_column($logs, 'hits', 'book_id');
        $book_list = $query->whereIn('id', array_keys($logs))->get();
        foreach ($book_list as $item) {
            $item->click_count = $logs[$item->id];
        }
        usort($book_list, function (BookModel $a, BookModel $b) {
            if ($a->click_count > $b->click_count) {
                return 1;
            }
            if ($a->click_count == $b->click_count) {
                return 0;
            }
            return -1;
        });
        return $pager->setPage($book_list);
    }
}