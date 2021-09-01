<?php

return [
    'master' => [
        'class'               => 'yii\db\Connection',
        'dsn'                 => $cfg_ini['db.master']['dsn'],
        'username'            => $cfg_ini['db.master']['username'],
        'password'            => $cfg_ini['db.master']['password'],
        'charset'             => 'utf8',
        'tablePrefix'         => $cfg_ini['db.master']['tablePrefix'],
        'enableSlaves'        => (boolean)$cfg_ini['db.master']['enableSlaves'],
        'enableSchemaCache'   => (boolean)$cfg_ini['db.master']['enableSchemaCache'],
        'schemaCacheDuration' => $cfg_ini['db.master']['schemaCacheDuration'],
    ],
];
