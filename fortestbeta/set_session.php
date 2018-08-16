<?php
// 把 session 的生命週期調到你想要的時間
ini_set('session.gc_maxlifetime', 864000);

// 打開垃圾回收，1 表示有 1% 的機會進行垃圾回收
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);

// 設定自己的 session path 以避開 Debian 的自動清除
session_save_path('/var/www' . '/sessions');

// 都設定好之後再啟動 session
session_start();
?>