<?php

/**
 * エナジードリンク 150円
 * 炭酸飲料水 140円
 * スポーツドリンク 130円
 * 缶コーヒー 120円
 * ミネラルウォーター 110円
 *
 * 投入できるのは1000円札、500円硬貨、100円硬貨、50円硬貨、10円硬貨のみ
 * 10000円札、5000円札、2000円札、5円硬貨、1円硬貨は使用不可
 * 紙幣、硬貨の最大数はX枚とする(X > 0)
 *
 * ランダムで飲料を購入する
 * ただし、飲料の合計金額がNを超えてはならない
 * 各飲料の在庫数はY本とする(Y> 0)
 *
 * 任意の金額N円(1000,500,100,50,10円(の組み合わせで成立する額))を
 * 1回のみ自販機に投入して、
 * ランダムに何か買ってゆく。
 * それが何本でもいいし、何を買ってもいい。
 * まだ何か買えたとしても、どこで打ち切るかもランダム。
 *
 * 購入したら投入金額、各飲料の本数とその合計金額、全飲料の合計金額、おつりを表示する
 */

$drinks = [
    'エナジードリンク' => [
        'unit_price'  => 150,
        'stock_count' => 10,
    ],
    '炭酸飲料' => [
        'unit_price'  => 140,
        'stock_count' => 10,
    ],
    'スポーツドリンク' => [
        'unit_price'  => 130,
        'stock_count' => 10,
    ],
    '缶コーヒー' => [
        'unit_price'  => 120,
        'stock_count' => 10,
    ],
    'ミネラルウォーター' => [
        'unit_price'  => 110,
        'stock_count' => 10,
    ],
];
$prices            = [1000, 500, 100, 50, 10];
$price_input_count = rand(1, 10);
$input_price       = 0;
for ($i = 0; $i < $price_input_count; $i++) {
    $input_price += $prices[array_rand($prices)];
}
$first_input_price = $input_price;

$bought_drink_names = [];
while (true) {
    $selectable_drink_names = [];
    foreach ($drinks as $drink_name => $drink) {
        if ($input_price >= $drink['unit_price'] && $drink['stock_count'] > 0) {
            $selectable_drink_names[] = $drink_name;
        }
    }
    if (count($selectable_drink_names) === 0) {
        break;
    }
    $selected_drink_name = $selectable_drink_names[array_rand($selectable_drink_names)];
    $input_price        -= $drinks[$selected_drink_name]['unit_price'];
    $drinks[$selected_drink_name]['stock_count']--;
    $bought_drink_names[] = $selected_drink_name;
    if (rand(0, 1) === 0) {
        break;
    }
}

$bought_drink_counts          = array_count_values($bought_drink_names);
$bought_drink_total_prices    = [];
$all_bought_drink_total_price = 0;
foreach ($bought_drink_counts as $drink_name => $drink_count) {
    $drink_price                   = $drinks[$drink_name]['unit_price'] * $drink_count;
    $all_bought_drink_total_price += $drink_price;
    $bought_drink_total_prices    += [
        $drink_name => $drink_price,
    ];
}
?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自販機</title>
  </head>

  <body>
    <h1>自販機</h1>
    <h2>投入金額と残金</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>投入金額</th>
          <th>全飲料の合計金額</th>
          <th>残金</th>
       </tr>
        <tr>
          <td><?php echo $first_input_price ?>円</td>
          <td><?php echo $all_bought_drink_total_price ?>円</td>
          <td><?php echo $input_price ?>円</td>
        </tr>
      </tbody>
    </table>
    <h2>各ドリンクの明細</h2>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th>ドリンクの銘柄</th>
          <th>ドリンクの合計本数</th>
          <th>ドリンクの合計金額</th>
        </tr>
        <?php foreach ($bought_drink_counts as $drink_name => $drink_count) : ?>
          <tr>
            <td><?php echo $drink_name ?></td>
            <td><?php echo $drink_count ?>本</td>
            <td><?php echo  $bought_drink_total_prices[$drink_name] ?>円</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </body>

</html>
