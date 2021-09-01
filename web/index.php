<?php
/** 项目环境 */
$env = getenv("PHP_RUN_ENV");
empty($env) ? define('YII_ENV', 'dev') : define('YII_ENV', $env);

/** 项目根目录 */
define('APP_PATH', dirname(__DIR__));

/** 解析环境对应的 config.ini 文件 */
$cfg_ini = parse_ini_file(APP_PATH . '/config/' . YII_ENV . '/config.ini', TRUE);
define('YII_DEBUG', $cfg_ini['debug']);

/** application id */
define('APP_ID', 'thesis_api');

date_default_timezone_set('Asia/Shanghai');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
