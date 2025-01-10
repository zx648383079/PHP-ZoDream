<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Module\Template\Domain\Model\ThemeComponentModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileObject;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Json;
use Zodream\Html\Page;

final class ThemeRepository {

    public static function getList(string $keywords = '') {
        return new Page(0);
    }


    public static function assetFile(string $folder, string $file): File {
        $dir = VisualFactory::templateFolder($folder);
        if (is_bool($dir)) {
            throw new \Exception('not found');
        }
        if ($dir->isFile() && $dir->getExtension() === 'html') {
            $dir = $dir->getDirectory();
        }
        $file = $dir->isFile() ? $dir : $dir->file($file);
        if (!$file->exist()) {
            throw new \Exception('not found');
        }
        return $file;
    }


    /**
     * 遍历所有文件夹
     * @param Directory $dir
     * @param callable $cb
     * @return void
     */
    protected static function mapFolder(Directory $dir, callable $cb) {
        $item = $cb($dir);
        if ($item === false) {
            return;
        }
        $dir->mapDeep(function (FileObject $file) use ($cb) {
            if (!($file instanceof Directory)) {
                return true;
            }
            return $cb($file);
        });
    }

    protected static function mapFolderFile(Directory $dir, callable $cb) {
        $dir->mapDeep(function (FileObject $file) use ($cb) {
            if (!($file instanceof Directory)) {
                $cb($file);
            }
        });
    }

    /***
     * 根据文件记录导入
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public static function importFileLog(array $data) {
        $baseRoot = ComponentRepository::root();
        $folder = $baseRoot->directory(sprintf('file_%s', $data['id']));
        if ($folder->exist()) {
            // 标识以前加过
            return;
        }
        $data = self::load($data['name'], $data['file'], $folder);
        foreach ($data as $item) {
            if ($item['type'] > 0) {
                self::installWeight($item);
            } else {
                self::installPage($item);
            }
        }
    }

    public static function import(string $title, File $file) {
        $data = self::load($title, $file);
        foreach ($data as $item) {
            if ($item['type'] > 0) {
                self::installWeight($item);
            } else {
                self::installPage($item);
            }
        }
    }

    /**
     * 获取模块数据
     * @param string $title
     * @param File $file
     * @param Directory|null $workFolder 实际工作目录，或解压路径
     * @return array[]
     * @throws \Exception
     */
    public static function load(string $title, File $file, Directory|null $workFolder = null): array {
        $args = pathinfo($title);
        $name = $args['basename'];
        $extension = $args['extension'] ?? '';
        $baseRoot = ComponentRepository::root();
        if (empty($workFolder)) {
            $workFolder = $baseRoot->directory(sprintf('%s__%s', auth()->id(), time()));
        }
        $workFolder->create();
        if (str_starts_with($extension, 'htm')) {
            $distFile = $workFolder->file(sprintf('weight.%s', $extension));
            $file->copy($distFile);
            return [
                [
                    'type' => 0,
                    'name' => $name,
                    'entry' => $distFile->getRelative($baseRoot)
                ]
            ];
        }
        if (str_starts_with($extension, 'php')) {
            $distFile = $workFolder->file(sprintf('weight.%s', $extension));
            $file->copy($distFile);
            return [
                [
                    'type' => 1,
                    'name' => $name,
                    'entry' => $distFile->getRelative($baseRoot)
                ]
            ];
        }
        (new ZipStream($file))->extractTo($workFolder);
        $jsonFile = $workFolder->file('theme.json');
        if ($jsonFile->exist()) {
            return self::loadThemeFile($baseRoot, $jsonFile);
        }
        $jsonFile = $workFolder->file('weight.json');
        if ($jsonFile->exist()) {
            return self::loadWeightFile($baseRoot, $jsonFile);
        }
        $data = [];
        $entryFile = $workFolder->file('weight.php');
        if ($entryFile->exist()) {
            $data[] = [
                'type' => 1,
                'name' => $name,
                'entry' => $entryFile->getRelative($baseRoot)
            ];
        }
        $entryFile = $workFolder->file('index.html');
        if ($entryFile->exist()) {
            $data[] = [
                'type' => 0,
                'name' => $name,
                'entry' => $entryFile->getRelative($baseRoot)
            ];
        }
        return $data;
    }

    private static function loadThemeFile(Directory $baseRoot, File $jsonFile): array {
        $data = Json::decode($jsonFile->read());
        $folder = $jsonFile->getDirectory();
        $shared = [];
        foreach ([
                     'author',
                    'since',
                    'version',
                    'copyright',
                    'email',
                    'url',
                 ] as $key) {
            if (isset($data[$key])) {
                $shared[$key] = $data[$key];
            }
        }
        if (isset($data['assets'])) {
            self::installAsset($data['assets'], $folder);
        }
        $items = [];
        if (isset($data['pages'])) {
            if (is_array($data['pages'])) {
                foreach ($data['pages'] as $item) {
                    $items = array_merge($items,
                        self::loadWeightData(array_merge($shared, $item), $baseRoot, $folder));
                }
            } else {
                self::mapFolderFile($folder->directory((string)$data['pages']),
                    function (File $file) use ($baseRoot, $shared, &$items) {
                    $items[] = array_merge($shared, [
                        'type' => 0,
                        'name' => $file->getNameWithoutExtension(),
                        'entry' => $file->getRelative($baseRoot)
                    ]);
                });
            }
        }
        if (isset($data['weights'])) {
            if (is_array($data['weights'])) {
                foreach ($data['weights'] as $item) {
                    $items = array_merge($items,
                        self::loadWeightData(array_merge($shared, $item), $baseRoot, $folder));
                }
            } else {
                self::mapFolder($folder->directory((string)$data['weights']),
                    function (Directory $folder) use ($baseRoot, $shared, &$items) {
                    $jsonFile = $folder->file('weight.json');
                    if (!$jsonFile->exist()) {
                        return;
                    }
                    $items = array_merge($items, self::loadWeightFile($baseRoot, $jsonFile, $shared));
                });
            }
        }
        return $items;
    }



    private static function loadWeightFile(Directory $baseRoot, File $jsonFile, array $shared = []): array {
        $data = array_merge($shared, Json::decode($jsonFile->read()));
        $folder = $jsonFile->getDirectory();
        return self::loadWeightData($data, $baseRoot, $folder);
    }

    private static function installAsset(array $data, Directory $folder) {
        if(empty($data)) {
            return;
        }
        $baseRoot = ComponentRepository::root();
        $assetRoot = ComponentRepository::assetRoot();
        $assetRoot->create();
        foreach ($data as $item) {
            $file = $folder->child($item);
            if (!$file->exist()) {
                continue;
            }
            $relative = $file->getRelative($baseRoot);
            if ($file->isFile()) {
                $file->copy($assetRoot->file($relative));
            } else {
                $file->copy($assetRoot->directory($relative));
            }
        }
    }

    private static function loadWeightData(array $data, Directory $baseRoot, Directory $folder): array {
        $isPage = false;
        if (!isset($data['entry'])) {
            $entryFile = $folder->file('weight.php');
            if ($entryFile->exist()) {
                $data['entry'] = $entryFile->getRelative($baseRoot);
            } else {
                $isPage = true;
                $entryFile = $folder->file('index.html');
                if ($entryFile->exist()) {
                    $data['entry'] = $entryFile->getRelative($baseRoot);
                } else {
                    return [];
                }
            }
        } else {
            $entryFile = $folder->file($data['entry']);
            if ($entryFile->getExtension() !== 'php') {
                $isPage = true;
            }
            $data['entry'] = $entryFile->getRelative($baseRoot);
        }
        if (isset($data['thumb'])) {
            $data['thumb'] = $folder->file($data['thumb'])->getRelative($baseRoot);
        }
        if (isset($data['assets'])) {
            self::installAsset($data['assets'], $folder);
        }
        if (isset($data['dependencies'])) {
            $data['dependencies'] = array_map(function (string $item) use ($folder, $baseRoot) {
                if (str_starts_with($item, '@')) {
                    return $item;
                }
                return '/assets/themes/'.$folder->combine($item)->getRelative($baseRoot);
            }, $data['dependencies']);
        }
        if (isset($data['styles'])) {
            $data['styles'] = array_map(function (array $style) use ($folder, $baseRoot) {
                if (isset($style['thumb'])) {
                    $style['thumb'] = $folder->file($style['thumb'])->getRelative($baseRoot);
                }
                $style['entry'] = $folder->file( $style['entry'])->getRelative($baseRoot);
                return $style;
            }, $data['styles']);
        }
        unset($data['default'], $data['assets']);
        $data['type'] = $isPage ? 0 : 1;
        return [$data];
    }

    private static function installWeight(array $data)
    {
         $model = ThemeComponentModel::create([
            'name' => $data['name'],
            'alias_name' => $data['name'],
            'description' => $data['description'] ?? '',
            'thumb' => self::copyAssetFile($data['thumb'] ?? ''),
            'cat_id' => 2,
            'user_id' => auth()->id(),
            'price' => 0,
            'type' => 1,
            'author' => $data['author'] ?? '',
            'version' => $data['version'] ?? '',
            'editable' => isset($data['editable']) && $data['editable'] ? 1 : 0,
            'path' => $data['entry'],
            'dependencies' => $data['dependencies'] ?? [],
        ]);
         if (empty($model) || !isset($data['styles'])) {
             return;
         }
         foreach ($data['styles'] as $item) {
             ThemeStyleModel::create([
                 'component_id' => $model->id,
                 'name' => $item['name'],
                 'description' => $item['description'] ?? '',
                 'thumb' => self::copyAssetFile($item['thumb'] ?? ''),
                 'path' => $item['entry'],
             ]);
         }
    }

    private static function installPage(array $data)
    {
        ThemeComponentModel::create([
            'name' => $data['name'],
            'alias_name' => $data['name'],
            'description' => $data['description'] ?? '',
            'thumb' => self::copyAssetFile($data['thumb'] ?? ''),
            'cat_id' => 1,
            'user_id' => auth()->id(),
            'price' => 0,
            'type' => 0,
            'author' => $data['author'] ?? '',
            'version' => $data['version'] ?? '',
            'path' => $data['entry'],
            'dependencies' => $data['dependencies'] ?? [],
        ]);
    }

    /***
     * 复制文件，并返回可用的前台url
     * @param string $path
     * @return string
     */
    private static function copyAssetFile(string $path): string {
        if (empty($path)) {
            return '';
        }
        $baseRoot = ComponentRepository::root();
        $assetRoot = ComponentRepository::assetRoot();
        $file = $assetRoot->file($path);
        if ($file->exist()) {
            return $file->getRelative(public_path());
        }
        $sourceFile = $baseRoot->file($path);
        if (!$sourceFile->exist()) {
            return '';
        }
        $sourceFile->copy($file);
        if ($file->exist()) {
            return $file->getRelative(public_path());
        }
        return '';
    }

}