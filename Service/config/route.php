<?php
/**
* 配置文件模板
* 
* @author Jason
*/

return [
    'rewrite' => '.html',
    'not-found' => 'Service\Home\HomeController@notFoundAction',
    'modules' => [
        'gzo' => 'Zodream\Module\Gzo',
    ],
];