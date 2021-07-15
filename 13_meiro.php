<?php

/**
 * 別ファイルにある map.txt から迷路データを読み込んで迷路を解いだけだよね。
 * map.txt は課題一覧から取得してください
 *
 * 「開」から「終」に向かって探索し、通ったマスは「＋」で埋めて、結果を出力してください
 *
 * なお、行き止まりの道へ進んだ場合に、その通ったマスが「＋」になっているのは構いません
 * また、「開」から「終」への経路が複数ある場合に、最短経路を求める必要はありません
 */

$filename = 'map.txt';
$rows     = file($filename, FILE_IGNORE_NEW_LINES);

$map = [];
foreach ($rows as $row_num => $row) {
    $map[$row_num] = preg_split('//u', $row, -1, PREG_SPLIT_NO_EMPTY);
}

$start_location = [];
$end_location   = [];
foreach ($map as $row_num => $row) {
    foreach ($row as $col_num => $map_contents) {
        if ($map_contents === '開') {
            $start_location = [
                'row_num' => $row_num,
                'col_num' => $col_num,
            ];
        }
        if ($map_contents === '終') {
            $end_location = [
                'row_num' => $row_num,
                'col_num' => $col_num,
            ];
        }
    }
}

if (empty($start_location) || empty($end_location)) {
    echo '開始地点もしくは終了地点が設定されていません。';
    exit;
}

$correct_map      = true;
$current_location = $start_location;
$branch_locations = [];

while (true) {
    $next_locations = [
        [
            'row_num' => $current_location['row_num'],
            'col_num' => $current_location['col_num'] - 1,
        ],
        [
            'row_num' => $current_location['row_num'],
            'col_num' => $current_location['col_num'] + 1,
        ],
        [
            'row_num' => $current_location['row_num'] - 1,
            'col_num' => $current_location['col_num'],
        ],
        [
            'row_num' => $current_location['row_num'] + 1,
            'col_num' => $current_location['col_num'],
        ],
    ];

    $movable_locations = [];
    foreach ($next_locations as $next_location) {
        if (isset($map[$next_location['row_num']][$next_location['col_num']])) {
            $map_contents = $map[$next_location['row_num']][$next_location['col_num']];
            if ($map_contents === '　') {
                $movable_locations[] = $next_location;
            }
            if ($map_contents === '終') {
                break 2;
            }
        }
    }

    if (count($movable_locations) >= 2) {
        $branch_locations[] = [
            'row_num' => $current_location['row_num'],
            'col_num' => $current_location['col_num'],
        ];
    }

    if (count($movable_locations) > 0) {
        $current_location                                                = $movable_locations[array_rand($movable_locations)];
        $map[$current_location['row_num']][$current_location['col_num']] = '＋';
        continue;
    }

    if (empty($branch_locations)) {
        $correct_map = false;
        break;
    }

    $current_location = array_pop($branch_locations);
}

?>

<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>課題13 迷路</title>
</head>

<body>
  <h1>課題13 迷路</h1>
  <?php if ($correct_map) : ?>
    <?php foreach ($map as $row) : ?>
      <?php foreach ($row as $map_contents) : ?>
        <?php echo $map_contents ?>
      <?php endforeach ?>
      <br>
    <?php endforeach ?>
  <?php else : ?>
    <p>迷路の「開」と「終」が正しく繋がっていません。</p>
  <?php endif ?>
</body>

</html>
