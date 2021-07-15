<?php

/**
 * ある商品群があります
 * 商品は名前と金額
 *
 * ある買う人がいます
 * 名前と都道府県
 *
 * その人が商品をN個買います
 * 買う商品はランダムで購入数もランダム 1個以上
 *
 * 送料は500円です
 * ただ、購入数が5以上の場合は1000円になります
 * また、都道府県が沖縄県と北海道はプラス1000円になります
 *
 * 買った商品ごとの商品名、個数と、金額
 * 小計、消費税、送料（消費税かからない）、合計金額を表示してください
 *
 * 消費税が変わる事を考慮しましょう
 */

const TAX_RATE = 0.1;

$customers = [
    [
        'name'       => '村田 陸',
        'prefecture' => '千葉県',
    ],
    [
        'name'       => '田中 太郎',
        'prefecture' => '埼玉県',
    ],
    [
        'name'       => '小林 はるか',
        'prefecture' => '千葉県',
    ],
    [
        'name'       => '永井 圭',
        'prefecture' => '沖縄県',
    ],
    [
        'name'       => '佐藤 雄介',
        'prefecture' => '北海道',
    ],
];

$products = [
    [
        'name'  => '机',
        'price' => 1000,
        'stock' => 10,
    ],
    [
        'name'  => 'ベッド',
        'price' => 2000,
        'stock' => 10,
    ],
    [
        'name'  => '照明',
        'price' => 2500,
        'stock' => 10,
    ],
    [
        'name'  => 'タンス',
        'price' => 5000,
        'stock' => 10,
    ],
    [
        'name'  => 'テレビ',
        'price' => 7000,
        'stock' => 10,
    ],
    [
        'name'  => 'パソコン',
        'price' => 3000,
        'stock' => 10,
    ],
];

$additional_delivery_fee_prefectures = ['北海道', '沖縄県'];

$bought_counts = [];
$subtotal      = 0;
while (count($bought_counts) === 0) {
    $selectable_product_indexes = [];
    foreach ($products as $product_index => $product) {
        if ($product['stock'] > 0) {
            $selectable_product_indexes[] = $product_index;
        }
    }
    if (count($selectable_product_indexes) === 0) {
        break;
    }
    foreach ($selectable_product_indexes as $selectable_products_index) {
        if (rand(0, 1) === 0) {
            continue;
        } else {
            $bought_count                                   = rand(1, $products[$selectable_products_index]['stock']);
            $bought_counts[$selectable_products_index]      = $bought_count;
            $subtotal                                      += $products[$selectable_products_index]['price'] * $bought_count;
            $products[$selectable_products_index]['stock'] -= $bought_count;
        }
    }
}

$delivery_fee          = 500;
$total_bought_count    = array_sum($bought_counts);
$random_customer_index = array_rand($customers);
if ($total_bought_count >= 5) {
    $delivery_fee = 1000;
}
if (in_array($customers[$random_customer_index]['prefecture'], $additional_delivery_fee_prefectures)) {
    $delivery_fee += 1000;
}

$tax_price   = round($subtotal * TAX_RATE);
$total_price = ($subtotal + $tax_price + $delivery_fee);

?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショッピング</title>
  </head>

  <body>
    <h1>ショッピング</h1>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>購入者</th>
        </tr>
        <tr>
          <td><?php echo $customers[$random_customer_index]['prefecture'] . ' ' . $customers[$random_customer_index]['name'] ?></td>
        </tr>
      </tbody>
    </table>
    <br>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>商品名</th>
          <th>個数</th>
          <th>単価</th>
          <th>合計金額</th>
        </tr>
        <?php foreach ($bought_counts as $index => $bought_count) : ?>
          <tr>
            <td><?php echo $products[$index]['name'] ?></td>
            <td><?php echo $bought_count ?></td>
            <td><?php echo number_format($products[$index]['price']) ?></td>
            <td><?php echo number_format($products[$index]['price'] * $bought_count) ?></td>
          </tr>
       <?php endforeach ?>
      </tbody>
    </table>
    <br>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>小計</th>
          <th>消費税</th>
          <th>送料</th>
        </tr>
        <tr>
          <td><?php echo number_format($subtotal) ?></td>
          <td><?php echo number_format($tax_price) ?></td>
          <td><?php echo number_format($delivery_fee) ?></td>
        </tr>
      </tbody>
    </table>
    <br>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>合計金額</th>
        </tr>
        <tr>
          <td><?php echo number_format($total_price) ?></td>
        </tr>
      </tbody>
    </table>
  </body>

</html>
