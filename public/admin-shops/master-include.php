<?php // любой код на ваше усмотрение. Он будет добавлен в отдельный файл, который будет подключен к началу всех экранов
if ($_SESSION['user']['is_activated'] == 0) {
 unset($_SESSION['user']);
}

define('IS_SHOP_ADMIN_PANEL', true);
define('PANEL_VERSION', '1.1.5');






