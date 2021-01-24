# 网盘系统

## 主要功能（待实现）

    1.文件、文件夹上传

    2.文件秒传

    3.分享

    4.视频转码、视频流播放

    5.文档在线编辑

    6.在线解压

    7.API 调用上传

## 配置

配置文件路径 `Service\config\disk.php`

```php
<?php

return [
    'driver' => \Module\Disk\Domain\Adapters\Location::class,
    'cache' => 'data/disk/cache/',    // 缓存路径
    'disk' => 'D:\zodream',  // 文件保存位置
    'ffmpeg' => 'D:\ffmpeg\bin\ffmpeg.exe', // iis 下必须配置完整路径
];
```