<?php

/**
 * プレイヤーはN人(N>1)
 * トランプ52枚を順番に2枚ずつめくる
 * 同じ数字だったらもう一度めくる
 * めくるカードが無くなったら終了
 * 違う数字だったら次の人
 * N人目の次の人は1人目
 * J,Q,kは11,12,13でいい
 * 2枚めくって同じ数字だったらめくる対象から除外する
 * 各プレイヤーが取った組の数を出力する
 *
 * 神経衰弱です
 */

const PLAYER_COUNT = 4;

$players_points = [];
for ($i = 0; $i < PLAYER_COUNT; $i++) {
    $players_points["player{$i}"] = 0;
}

$cards = [];

$marks = ['heart', 'spade', 'club', 'diamond'];
for ($number = 1; $number <= 13; $number++) {
    foreach ($marks as $mark) {
        $cards[] = [
            'number' => $number,
            'mark'   => $mark,
        ];
    }
}

while (count($cards) > 0) {
    for ($i = 0; $i < PLAYER_COUNT; $i++) {
        while (true) {
            $selected_cards_indexes = array_rand($cards, 2);
            if ($cards[$selected_cards_indexes[0]]['number'] === $cards[$selected_cards_indexes[1]]['number']) {
                $players_points["player{$i}"]++;
                unset($cards[$selected_cards_indexes[0]]);
                unset($cards[$selected_cards_indexes[1]]);
            } else {
                break;
            }
            if (count($cards) === 0) {
                break 2;
            }
        }
    }
}

foreach ($players_points as $player_name => $point) {
    echo $player_name . ':' . $point . PHP_EOL;
}
