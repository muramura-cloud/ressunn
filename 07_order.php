<?php

/**
 * 製品A
 *   部品A2個と部品B1個からできています。
 * 製品B
 *   部品C3個と部品D2個からできています。
 * 製品C
 *   部品B1個と部品D1個からできています。
 *
 * 製品Aと製品Bと製品Cをランダムで発注します。
 * 部品にはそれぞれ在庫があり製品が作れなくなるまで製造をします。
 *
 * 最後に以下を出力します。
 *
 * 製造前の各部品の在庫数
 * 製品の発注数
 * 製造した製品の個数
 * 製造後の各部品の在庫数
 */

function get_producible_product_ids($parts_stocks, $products) {
    $producible_product_ids = [];
    foreach ($products as $product_id => $product) {
        foreach ($product['need_parts_counts'] as $need_parts_id => $need_parts_count) {
            if ($parts_stocks[$need_parts_id]['stock'] < $need_parts_count) {
                continue 2;
            }
        }
        $producible_product_ids[] = $product_id;
    }

    return $producible_product_ids;
}


$parts_stocks = [
    1 => [
        'name'  => 'A',
        'stock' => 10,
    ],
    2 => [
        'name'  => 'B',
        'stock' => 10,
    ],
    3 => [
        'name'  => 'C',
        'stock' => 10,
    ],
    4 => [
        'name'  => 'D',
        'stock' => 10,
    ],
];

$products = [
    1 => [
        'name'              => 'A',
        'need_parts_counts' => [
            1 => 2,
            2 => 1,
        ],
    ],
    2 => [
        'name'              => 'B',
        'need_parts_counts' => [
            3 => 3,
            4 => 2,
        ],
    ],
    3 => [
        'name'              => 'C',
        'need_parts_counts' => [
            2 => 1,
            4 => 1,
        ],
    ],
];

$before_production_parts = $parts_stocks;

$ordered_product_counts  = [];
$produced_product_counts = [];
foreach ($products as $product_id => $product) {
    $produced_product_counts[$product_id] = 0;
    $ordered_product_counts[$product_id]  = 0;
}

$producible_product_ids = get_producible_product_ids($parts_stocks, $products);

while (count($producible_product_ids) > 0) {
    $ordered_product_id                           = array_rand($products);
    $ordered_product_count                        = rand(1, 5);
    $ordered_product_counts[$ordered_product_id] += $ordered_product_count;

    if (in_array($ordered_product_id, $producible_product_ids)) {
        $need_parts_counts      = $products[$ordered_product_id]['need_parts_counts'];
        $produced_product_count = 0;

        for ($i = 0; $i < $ordered_product_count; $i++) {
            foreach ($need_parts_counts as $need_parts_id => $need_parts_count) {
                if ($parts_stocks[$need_parts_id]['stock'] < $need_parts_count) {
                    break 2;
                }
            }
            foreach ($need_parts_counts as $need_parts_id => $need_parts_count) {
                $parts_stocks[$need_parts_id]['stock'] -= $need_parts_count;
            }
            $produced_product_count++;
        }

        $produced_product_counts[$ordered_product_id] += $produced_product_count;
    }

    $producible_product_ids = get_producible_product_ids($parts_stocks, $products);
}

?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>オーダー</title>
  </head>

  <body>
    <h1>オーダー</h1>
    <h2>製造前の各部品の在庫数</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <?php foreach ($before_production_parts as $value) : ?>
          <tr>
            <th>部品<?php echo $value['name'] ?></th>
            <td><?php echo $value['stock'] ?>個</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <br>
    <h2>発注した製品の個数</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <?php foreach ($ordered_product_counts as $name => $count) : ?>
          <tr>
            <th>製品<?php echo $name ?></th>
            <td><?php echo $count ?>個</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <br>
    <h2>製造した製品の個数</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <?php foreach ($produced_product_counts as $name => $count) : ?>
          <tr>
            <th>製品<?php echo $name ?></th>
            <td><?php echo $count ?>個</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <br>
    <h2>製造後の各部品の在庫数</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <?php foreach ($parts_stocks as $value) : ?>
          <tr>
            <th>部品<?php echo $value['name'] ?></th>
            <td><?php echo $value['stock'] ?>個</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </body>

</html>
