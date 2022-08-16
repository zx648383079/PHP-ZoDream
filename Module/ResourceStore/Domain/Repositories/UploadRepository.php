<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Module\ResourceStore\Domain\Models\ResourceFileModel;
use Module\ResourceStore\Domain\Models\ResourceMetaModel;
use Module\ResourceStore\Domain\Models\ResourceModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Infrastructure\Contracts\Http\Input;

final class UploadRepository {

    protected static function folder(): Directory {
        return app_path()->directory('data/demo');
    }

    public static function saveFile(Input $input) {
        return ResourceRepository::storage()->addFile($input->file('file'));
    }

    public static function file(ResourceFileModel $model) {
        return ResourceRepository::storage()->getFile($model->file);
    }

    public static function resourceFolder($id): Directory {
        if ($id instanceof ResourceModel) {
            $id = $id->id;
        } elseif ($id instanceof ResourceFileModel) {
            $id = $id->res_id;
        }
        return self::folder()->directory($id.'');
    }

    public static function previewFolder($id): Directory {
        if ($id instanceof ResourceModel) {
            $id = $id->id;
        } elseif ($id instanceof ResourceFileModel) {
            $id = $id->res_id;
        }
        return self::folder()->directory($id.'_preview');
    }

    public static function previewFile(int $id, string $file = ''): File {
        /** @var ResourceModel $model */
        $model = ResourceModel::where('id', $id)->first('preview_type');
        if ($model->preview_type < 1) {
            throw new \Exception('not preview');
        }
        $previewFile = ResourceMetaModel::where('res_id', $id)->where('name', 'preview_file')->value('content');
        if ($model->preview_type != 3) {
            return ResourceRepository::storage()->getFile($previewFile);
        }
        $folder = self::previewFolder($id);
        if (!$folder->exist()) {
            $folder->create();
            $zip = new ZipStream(ResourceRepository::storage()->getFile($previewFile));
            $zip->extractTo($folder);
        }
        if (!empty($file)) {
            return $folder->file($file);
        }
        $files = $folder->children();
        $file = null;
        foreach ($files as $item) {
            if (!($item instanceof File)) {
                continue;
            }
            if (in_array($item->getName(), ['index.html', 'index.htm', 'default.html', 'default.htm'])) {
                $file = $item;
                break;
            }
            if (!empty($file)) {
                continue;
            }
            if (strpos($item->getName(), '.htm') > 0) {
                $file = $item;
            }
        }
        if (empty($file)) {
            throw new \Exception('not found');
        }
        return $file;
    }

    public static function unzipFile(ResourceFileModel $model) {
        $folder = self::resourceFolder($model);
        if ($folder->exist()) {
            $folder->delete();
        }
        $folder->create();
        $file = self::file($model);
        $zip = new ZipStream($file);
        $zip->extractTo($folder);
    }

    public static function catalog(ResourceFileModel $model): array {
        $file = self::file($model);
        $items = [];
        try {
            $zip = new ZipStream($file);
            $zip->each(function (string $name, bool $isFolder) use (&$items) {
                $nodes = explode('/', $name);
                $parent = &$items;
                $last = count($nodes) - 1;
                for ($i = 0; $i <= $last; $i ++) {
                    $node = $nodes[$i];
                    $success = false;
                    foreach ($parent as &$item) {
                        if ($item['name'] === $node) {
                            $parent = &$item['children'];
                            $success = true;
                        }
                    }
                    unset($item);
                    if ($success) {
                        continue;
                    }
                    $isFile = !$isFolder && $last === $i;
                    if ($isFile) {
                        $parent[] = [
                            'name' => $node,
                            'type' => 1,
                            'icon' => 'fa-file'
                        ];
                        return;
                    }
                    $item = [
                        'name' => $node,
                        'type' => 0,
                        'icon' => 'fa-folder',
                        'children' => []
                    ];
                    $parent[] = $item;
                    $parent = &$parent[count($parent) - 1]['children'];;
                }
                unset($parent);
            });
            return $items;
        } catch (\Exception) {
            return $items;
        }
    }

    public static function fileMap(ResourceModel $model) {
        $folder = self::resourceFolder($model);
        return self::getFiles($folder);
    }

    public static function getFiles(Directory $folder) {
        $items = [];
        if (!$folder->exist()) {
            return $items;
        }
        $folder->map(function ($file) use (&$items) {
            if ($file instanceof File) {
                $items[] = [
                    'name' => $file->getName(),
                    'type' => 1,
                    'icon' => 'fa-file'
                ];
                return;
            }
            $items[] = [
                'name' => $file->getName(),
                'type' => 0,
                'icon' => 'fa-folder',
                'children' => self::getFiles($file)
            ];
        });
        return $items;
    }
}