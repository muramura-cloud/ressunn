<?php

/**
 * 七並べをするプログラム
 *
 * トランプが48枚あります
 * 7はすべてゲーム開始時に並べられています
 *
 * 4人でプレイします(1人当たり手札は12枚)
 *
 * プレイヤーは順番にカードを並べていきます
 * 並べられるカードはすでに並べてあるカードの数字と隣り合う数字だけです
 *
 * カードが置けない場合は3回までスキップできます(4回目で失格)
 * 失格になったら手持ちのカードをすべて並べます
 *
 * ゲームを有利に進めるため、カードは7から遠い数字のものを優先的に置いていきます
 *
 * 手札がなくなったらゲームクリアです
 * クリアした順番を出力します
 *
 * 失格の場合は最下位です
 * 失格の時点で、その人の持っていたカードは全て場に置かれます
 * (ただし、7, 8, _, 10 と場にあった時に 11 は置けません)
 * もし、失格が2人以上いた場合は同率最下位です

 * 13の次に1はおけない
 */

$players = [
    [
        'name'       => '村田',
        'pass_count' => 0,
        'ranking'    => null,
        'cards'      => [],
    ],
    [
        'name'       => '小林',
        'pass_count' => 0,
        'ranking'    => null,
        'cards'      => [],
    ],
    [
        'name'       => '田中',
        'pass_count' => 0,
        'ranking'    => null,
        'cards'      => [],
    ],
    [
        'name'       => '中村',
        'pass_count' => 0,
        'ranking'    => null,
        'cards'      => [],
    ],
];

$marks = ['heart', 'spade', 'club', 'diamond'];

$trump = [];
for ($number = 1; $number <= 13; $number++) {
    if ($number === 7) {
        continue;
    }
    foreach ($marks as $mark) {
        $trump[] = [
            'number' => $number,
            'mark'   => $mark,
        ];
    }
}
shuffle($trump);

while (count($trump) > 0) {
    foreach ($players as $player_index => $player) {
        $players[$player_index]['cards'][] = array_shift($trump);
        if (count($trump) === 0) {
            break 2;
        }
    }
}

$game_field = [];
foreach ($marks as $mark) {
    for ($i = 1; $i <= 13; $i++) {
        if ($i === 7) {
            $game_field[$mark][$i] = true;
        } else {
            $game_field[$mark][$i] = false;
        }
    }
}

$playing_player_indexes = array_keys($players);
$current_ranking        = 1;
while (true) {
    foreach ($playing_player_indexes as $player_index) {
        if (count($playing_player_indexes) === 1) {
            $players[$player_index]['ranking'] = $current_ranking;
            break 2;
        }

        $placeable_cards = [];
        foreach ($game_field as $mark => $game_field_row) {
            for ($card_number = 8; $card_number <= 13; $card_number++) {
                if (!$game_field_row[$card_number]) {
                    $placeable_cards[] = [
                        'number' => $card_number,
                        'mark'   => $mark,
                    ];
                    break;
                }
            }
            for ($card_number = 6; $card_number >= 1; $card_number--) {
                if (!$game_field_row[$card_number]) {
                    $placeable_cards[] = [
                        'number' => $card_number,
                        'mark'   => $mark,
                    ];
                    break;
                }
            }
        }

        $player_placeable_cards = [];
        foreach ($placeable_cards as $placeable_card) {
            foreach ($players[$player_index]['cards'] as $player_card) {
                if ($placeable_card === $player_card) {
                    $player_placeable_cards[] = $player_card;
                }
            }
        }

        if (count($player_placeable_cards) === 0) {
            $players[$player_index]['pass_count']++;
            if ($players[$player_index]['pass_count'] === 4) {
                $players[$player_index]['ranking'] = count($players);
                foreach ($players[$player_index]['cards'] as $card_index => $card) {
                    $game_field[$card['mark']][$card['number']] = true;
                    unset($players[$player_index]['cards'][$card_index]);
                }
                unset($playing_player_indexes[$player_index]);
            }
            continue;
        }

        $best_cards = [];
        foreach ($player_placeable_cards as $player_placeable_card) {
            if (count($best_cards) === 0 || abs(7 - $best_cards[0]['number']) === abs(7 - $player_placeable_card['number'])) {
                $best_cards[] = $player_placeable_card;
            } elseif (abs(7 - $best_cards[0]['number']) < abs(7 - $player_placeable_card['number'])) {
                $best_cards   = [];
                $best_cards[] = $player_placeable_card;
            }
        }

        $best_card              = $best_cards[array_rand($best_cards)];
        $player_best_card_index = array_search($best_card,$players[$player_index]['cards']);
        $player_best_card       = $players[$player_index]['cards'][$player_best_card_index];

        $game_field[$player_best_card['mark']][$player_best_card['number']] = true;
        unset($players[$player_index]['cards'][$player_best_card_index]);

        if (count($players[$player_index]['cards']) === 0) {
            $players[$player_index]['ranking'] = $current_ranking;
            $current_ranking++;
            unset($playing_player_indexes[$player_index]);
        }
    }
}

array_multisort(array_column($players, 'ranking'), SORT_ASC, $players);

?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>七並べ</title>
  </head>

  <body>
    <h1>七並べ</h1>
    <h2>順位</h2>
    <table border="1" cellpadding="2">
      <tbody>
      <?php foreach ($players as $player) : ?>
        <tr>
          <th><?php echo $player['ranking'] ?>位</th>
          <td><?php echo $player['name'] ?>さん</td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </body>

</html>
