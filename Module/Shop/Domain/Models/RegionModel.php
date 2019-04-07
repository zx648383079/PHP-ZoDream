<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;
use Zodream\Disk\File;
use Zodream\Helpers\Json;
use Zodream\Html\Tree;

/**
 * Class RegionModel
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $full_name
 */
class RegionModel extends Model {

	public static function tableName() {
        return 'shop_region';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'parent_id' => 'int',
            'full_name' => 'string:0,100',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
            'full_name' => 'Full Name',
        ];
    }

    /**
     * @return Tree
     * @throws \Exception
     */
	public static function tree() {
        return new Tree(static::query()->select('id', 'name', 'parent_id')->asArray()->all());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function cacheTree() {
	    return cache()->getOrSet('shop_region_tree', function () {
	        return self::tree()->makeIdTree();
        });
    }

    /**
     * 请导入以下文件
     * @url https://github.com/modood/Administrative-divisions-of-China  dist/pcas.json
     * @param File $file
     * @throws \Exception
     */
    public static function import(File $file) {
	    $data = Json::decode($file->read());
	    $args = [];
	    $i = 0;
        $j = 0;
	    foreach ($data as $prov => $cities) {
	        $i ++;
	        $args[] = [
	            'id' => $i,
	            'name' => $prov,
                'parent_id' => $j,
                'full_name' => $prov
            ];
        }
        foreach ($data as $prov => $cities) {
            $j ++;
            foreach ($cities as $city => $dists) {
                $i ++;
                $args[] = [
                    'id' => $i,
                    'name' => $city,
                    'parent_id' => $j,
                    'full_name' => sprintf('%s %s', $prov, $city)
                ];
            }
        }
        foreach ($data as $prov => $cities) {
            foreach ($cities as $city => $dists) {
                $j ++;
                foreach ($dists as $dist => $streets) {
                    $i ++;
                    $args[] = [
                        'id' => $i,
                        'name' => $dist,
                        'parent_id' => $j,
                        'full_name' => sprintf('%s %s %s', $prov, $city, $dist)
                    ];
                }
            }
        }
        foreach ($data as $prov => $cities) {
            foreach ($cities as $city => $dists) {
                foreach ($dists as $dist => $streets) {
                    $j ++;
                    foreach ($streets as $street) {
                        $i ++;
                        $args[] = [
                            'id' => $i,
                            'name' => $street,
                            'parent_id' => $j,
                            'full_name' => sprintf('%s %s %s %s', $prov, $city, $dist, $street)
                        ];
                    }
                }
            }
        }
        self::query()->insert([
            'id',
            'name',
            'parent_id',
            'full_name'
        ], $args);
	    cache()->delete('shop_region_tree');
	    static::cacheTree();
    }
}