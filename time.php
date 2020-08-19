<?php
echo "<pre>";

$list = array();

$time[] = time();
$list[] = date('Y-m-d H:m:s', time());

sleep(5);

$time[] = time();
$list[] = date('Y-m-d H:m:s', time());

sleep(3);

$time[] = time();
$list[] = date('Y-m-d H:m:s', time());

print_r($list);
print_r($time);

function dumpTimeStamps($time)
{
    $first_stamp = array_kshift($time);
    foreach ($first_stamp as $loc => $stamp) {
        echo '<span class="label3">' . $loc . '</span>' . date(' Y-m-d H:m:s ', $stamp) . (0) . '<br>';
        $previous = $first_stamp[$loc];
    }
    foreach ($time as $loc => $stamp) {
        echo '<span class="label3">' . $loc . '</span>' . date(' Y-m-d H:m:s ', $stamp) . ($stamp - $previous) . '<br>';
        $previous = $stamp;
    }
}
dumpTimeStamps($time);
