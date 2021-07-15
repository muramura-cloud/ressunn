<?php

/**
 * 箱
 *  大きさ 10~20
 *  空き容量1以下で発送
 *  100個
 *
 * 荷物
 *  大きさ 1~5
 *  無限で出てくる
 *
 * ストック場
 *  荷物が入らない場合、ここに置く
 *  箱に入れられる荷物がある場合は、最初にここから入れられるだけ入れる
 *
 * 終了条件
 *  発送数100
 *
 */

$boxes = [];
for ($i = 0; $i < 100; $i++) {
    $boxes[] = [
        'size'     => rand(10, 20),
        'contents' => [],
    ];
}

$stock_field = [];

foreach ($boxes as $box_index => $box) {
    $box_free_space = $box['size'];

    if (count($stock_field) > 0) {
        foreach ($stock_field as $stock_baggage_index => $stock_baggage) {
            if ($box_free_space >= $stock_baggage) {
                $boxes[$box_index]['contents'][] = $stock_baggage;
                $box_free_space                 -= $stock_baggage;
                unset($stock_field[$stock_baggage_index]);
                if ($box_free_space <= 1) {
                    continue 2;
                }
            }
        }
    }

    while ($box_free_space > 1) {
        $baggage = rand(1, 5);

        if ($box_free_space >= $baggage) {
            $boxes[$box_index]['contents'][] = $baggage;
            $box_free_space                 -= $baggage;
        } else {
            $stock_field[] = $baggage;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パッキング</title>
  </head>

  <body>
    <h1>パッキング</h1>
    <?php foreach ($boxes as $box) : ?>
      <table border="1" cellpadding="2">
        <tbody>
          <tr>
            <th>箱のサイズ</th>
            <td><?php echo $box['size'] ?></td>
          </tr>
        </tbody>
      </table>
      <table>
      <table border="1" cellpadding="2">
        <tbody>
          <tr>
            <th>荷物のサイズ</th>
            <?php foreach ($box['contents'] as $baggage) : ?>
              <td><?php echo $baggage ?></td>
            <?php endforeach ?>
          </tr>
        </tbody>
      </table>
      <br>
    <?php endforeach ?>
  </body>

</html>
