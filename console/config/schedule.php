<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

$schedule->exec("docker exec -it books_fpm_1 php yii parse/top-books")->everyThirtyMinutes();