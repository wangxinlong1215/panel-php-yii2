<?php
$where = [
    'status' => \app\models\Menu::STATUS_USE,
    'system' => 1
];
$order = [
    'parent' => SORT_ASC,
    'order' => SORT_ASC
];
$list = (new \app\models\Menu())->find()->where($where)->orderBy($order)->asArray()->all();
$menu = [];
if (!empty($list)) {
    $admin = \app\modules\panel\services\AdminService::getInstance();
    foreach ($list as $item) {
        if (!empty($item['route'])) {
            $route = ltrim($item['route'], "/");
            $result = $admin->checkVisit($route);
            if (!$result) {
                continue;
            }
        }

        if ($item['parent'] == 0) {
            $menu[$item['id']] = [
                'name' => $item['name'],
                'icon' => $item['icon']
            ];
            continue;
        }
        $menu[$item['parent']]['list'][] = [
            'name' => $item['name'],
            'route' => $item['route'],
            'icon' => $item['icon']
        ];
    }
}
?>
<?php foreach ($menu as $val): ?>
    <?php if (!empty($val['list'])): ?>
        <li>
            <a href="javascript:;">
                <i class="iconfont left-nav-li" lay-tips="<?php echo \yii\helpers\ArrayHelper::getValue($val, 'name', ''); ?>">&#xe6b8;</i>
                <cite><?php echo \yii\helpers\ArrayHelper::getValue($val, 'name', ''); ?></cite>
                <i class="iconfont nav_right"><?php echo \yii\helpers\ArrayHelper::getValue($val, 'icon', ''); ?></i></a>
            <ul class="sub-menu">
                <?php
                $list = \yii\helpers\ArrayHelper::getValue($val, 'list', []);
                ?>

                <?php foreach ($list as $v): ?>
                    <li>
                        <a onclick='xadmin.add_tab("<?php echo $v['name']; ?>","<?php echo $v['route']; ?>")'>
                            <i class="iconfont"><?php echo \yii\helpers\ArrayHelper::getValue($v, 'icon', ''); ?></i>
                            <cite><?php echo $v['name']; ?></cite></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endif; ?>
<?php endforeach; ?>