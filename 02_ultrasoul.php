<?php

/**
 * ウルトラソウル
 *
 * ウル / トラ / ソウル の3つの中からランダムに出力しつづける
 * もし、ウルトラソウルの3つが続いたら「ハイ！」と出力する
 * おわり
 */

$call_strings       = ['ウル', 'トラ', 'ソウル'];
$count_call_strings = count($call_strings);
$random_strings     = [];
$is_same            = false;

while ($random_strings !== $call_strings) {
    $output_string = $call_strings[array_rand($call_strings)];
    echo $output_string . PHP_EOL;
    $random_strings[] = $output_string;
    if (count($random_strings) > $count_call_strings) {
        array_shift($random_strings);
    }
}

echo 'ハイ！';