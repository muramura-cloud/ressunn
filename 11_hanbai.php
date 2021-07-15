<?php

/**
 * カテゴリがn個あります
 * 各カテゴリはn個の商品を持ちます
 * 商品は月ごとに在庫を確保します
 * 2017/11月 item_a の在庫は100個、とか
 * 商品は日々n個売れたり売れなかったりします
 * 次月の初日、以下のとおりに在庫を確保します
 *   前月に90%以上売れた場合、120%にする
 *   前月に80%以上売れた場合、100%にする（前月の在庫数が100個の場合は100個にする）
 *   前月に60%以上売れた場合、前月頭の20%分を仕入れる
 *   前月に40%以上売れた場合、前月頭の12%分を仕入れる
 *   前月に20%以上売れた場合、前月頭の5%分を仕入れる
 *
 * 結果表示分
 *   4ヶ月分
 *   月ごと
 *     カテゴリごと
 *       商品ごと
 *         在庫数（繰越数と新規確保数）と
 *         1日ごとの売れた数
 *         その月に何個・何%売れたか
 *       何円売れたか
 *     何円売れたか
 *
 * 前月の初期在庫量 = 当初の在庫 + 入荷量
 *  新規入荷量 = 前月の初期在庫量 * 消化率に応じた%
 */

$categories = [
    1 => '食べ物',
    2 => '飲み物',
];

$products = [
    1 => [
        'name'        => 'カレー',
        'category_id' => 1,
        'price'       => 400,
        'stock'       => 100,
    ],
    2 => [
        'name'        => 'ラーメン',
        'category_id' => 1,
        'price'       => 500,
        'stock'       => 100,
    ],
    3 => [
        'name'        => 'コーヒー',
        'category_id' => 2,
        'price'       => 400,
        'stock'       => 100,
    ],
    4 => [
        'name'        => '炭酸飲料',
        'category_id' => 2,
        'price'       => 500,
        'stock'       => 100,
    ],
];
$sorted_product_ids = [];
foreach ($categories as $category_id => $category) {
    $sorted_product_ids[$category_id] = [];
    foreach ($products as $product_id => $product) {
        if ($product['category_id'] === $category_id) {
            $sorted_product_ids[$category_id][] = $product_id;
        }
    }
}

$year  = 2020;
$month = 12;

$daily_sold_counts    = [];
$monthly_sold_results = [];
$category_sold_prices = [];
$total_sold_prices    = [];

for ($i = 0; $i < 4; $i++) {
    if (!isset($daily_sold_counts[$year])) {
        $daily_sold_counts[$year]    = [];
        $monthly_sold_results[$year] = [];
        $category_sold_prices[$year] = [];
        $total_sold_prices[$year]    = [];
    }

    $daily_sold_counts[$year][$month]    = [];
    $monthly_sold_results[$year][$month] = [];
    $category_sold_prices[$year][$month] = [];
    $total_sold_prices[$year][$month]    = 0;
    foreach ($categories as $category_id => $category) {
        $category_sold_prices[$year][$month][$category_id] = 0;
    }

    foreach ($products as $product_id => $product) {
        $monthly_sold_results[$year][$month][$product_id] = [
            'carryover_stock'  => $product['stock'],
            'first_day_stock'  => $product['stock'],
            'total_sold_count' => 0,
            'total_sold_price' => 0,
        ];
    }

    if ($i > 0) {
        $last_month_year = $year;
        $last_month      = $month - 1;
        if ($month === 1) {
            $last_month_year = $year - 1;
            $last_month      = 12;
        }

        foreach ($products as $product_id => $product) {
            $last_month_sold_count      = $monthly_sold_results[$last_month_year][$last_month][$product_id]['total_sold_count'];
            $last_month_first_day_stock = $monthly_sold_results[$last_month_year][$last_month][$product_id]['first_day_stock'];
            $sold_rate                  = $last_month_sold_count / $last_month_first_day_stock;
            $carryover_stock            = $monthly_sold_results[$year][$month][$product_id]['carryover_stock'];

            if ($sold_rate >= 0.9) {
                $products[$product_id]['stock'] += (int) round($last_month_first_day_stock * 1.2) - $carryover_stock;
            } elseif ($sold_rate >= 0.8) {
                $products[$product_id]['stock'] += (int) round($last_month_first_day_stock * 1.0) - $carryover_stock;
            } elseif ($sold_rate >= 0.6) {
                $products[$product_id]['stock'] += (int) round($last_month_first_day_stock * 0.2);
            } elseif ($sold_rate >= 0.4) {
                $products[$product_id]['stock'] += (int) round($last_month_first_day_stock * 0.12);
            } elseif ($sold_rate >= 0.2) {
                $products[$product_id]['stock'] += (int) round($last_month_first_day_stock * 0.05);
            }

            $monthly_sold_results[$year][$month][$product_id]['first_day_stock'] = $products[$product_id]['stock'];
        }
    }

    $days = date('t', strtotime("{$year}-{$month}-01"));
    for ($day = 1; $day <= $days; $day++) {
        $daily_sold_counts[$year][$month][$day] = [];
        foreach ($products as $product_id => $product) {
            if ($product['stock'] === 0 || rand(0, 1) === 0) {
                $daily_sold_counts[$year][$month][$day][$product_id] = 0;
                continue;
            }

            $purchase_limit_count  = 5;
            $max_purchasable_count = min($product['stock'], $purchase_limit_count);
            $sold_count            = rand(1, $max_purchasable_count);

            $products[$product_id]['stock'] -= $sold_count;

            $sold_price                                                            = $sold_count * $product['price'];
            $daily_sold_counts[$year][$month][$day][$product_id]                   = $sold_count;
            $monthly_sold_results[$year][$month][$product_id]['total_sold_count'] += $sold_count;
            $monthly_sold_results[$year][$month][$product_id]['total_sold_price'] += $sold_price;
            $category_sold_prices[$year][$month][$product['category_id']]         += $sold_price;
            $total_sold_prices[$year][$month]                                     += $sold_price;
        }
    }

    if ($month <= 11) {
        $month++;
    } else {
        $month = 1;
        $year++;
    }
}

?>

<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題11 販売</title>
  </head>

  <body>
  <h1>課題11 販売</h1>
  <?php foreach ($monthly_sold_results as $year => $year_sold_result) : ?>
    <?php foreach ($year_sold_result as $month => $month_sold_result) : ?>
      <table border="1" cellpadding="2">
        <tbody>
          <tr>
            <th colspan="2">年</th>
            <td colspan=<?php echo count($products) ?>><?php echo $year ?>年</td>
          </tr>
          <tr>
            <th colspan="2">月</th>
            <td colspan=<?php echo count($products) ?>><?php echo $month ?>月</td>
          </tr>
          <tr>
            <th colspan="2">カテゴリー</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <td colspan=<?php echo count($category_product_ids) ?>><?php echo $categories[$category_id] ?></td>
            <?php endforeach ?>
          </tr>
          <tr>
            <th colspan="2">商品名</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $products[$product_id]['name'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th rowspan="3">在庫数</th>
            <th>繰越数</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $month_sold_result[$product_id]['carryover_stock'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th>新規確保数</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $month_sold_result[$product_id]['first_day_stock'] - $month_sold_result[$product_id]['carryover_stock'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th>合計</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $month_sold_result[$product_id]['first_day_stock'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <?php foreach ($daily_sold_counts[$year][$month] as $date => $sold_product) : ?>
            <tr>
              <th colspan="2"><?php echo $date ?>日</th>
              <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
                <?php foreach ($category_product_ids as $product_id) : ?>
                  <td><?php echo $sold_product[$product_id] ?></td>
                <?php endforeach ?>
              <?php endforeach ?>
            </tr>
          <?php endforeach ?>
          <tr>
            <th rowspan="2">売上情報</th>
            <th>割合</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo round(($month_sold_result[$product_id]['total_sold_count'] / $month_sold_result[$product_id]['first_day_stock']) * 100) ?>%</td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th>個数</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $month_sold_result[$product_id]['total_sold_count'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th colspan="2">単価</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo $products[$product_id]['price'] ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th colspan="2">商品別売上金額</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <?php foreach ($category_product_ids as $product_id) : ?>
                <td><?php echo number_format($month_sold_result[$product_id]['total_sold_price']) ?></td>
              <?php endforeach ?>
            <?php endforeach ?>
          </tr>
          <tr>
            <th colspan="2">カテゴリー別売上金額</th>
            <?php foreach ($sorted_product_ids as $category_id => $category_product_ids) : ?>
              <td colspan=<?php echo count($category_product_ids) ?>><?php echo number_format($category_sold_prices[$year][$month][$category_id]) ?></td>
            <?php endforeach ?>
          </tr>
          <tr>
            <th colspan="2">合計売上金額</th>
            <td colspan=<?php echo count($products) ?>><?php echo number_format($total_sold_prices[$year][$month]) ?></td>
          </tr>
        </tbody>
      </table>
      <br>
    <?php endforeach ?>
  <?php endforeach ?>
  </body>

</html>
