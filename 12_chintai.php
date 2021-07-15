<?php

/**
 * ワンフロアあたりの部屋数と階数は決める
 * 物件を適当に何件か定義する
 * 適当に入居済みの部屋を決める
 * 物件はランダムで空き室がある
 * 1Fのベースの金額を決めて階数が上がってくごとに家賃が1000円上がる
 *
 * 物件は値引き可能な物件とそうでない物件がある
 * 例えば値引き可能額2000円とか
 * 値引き可能物件はオーナーの気分でもうちょい値引きできる
 * 例えば値引き可能額2000円の場合、気分次第で倍の4000円まで値引きできる
 * ランダムで二重値引き (気分で不動産が引いて、更にオーナーが引く感じ)
 *
 * 借り主を適当に定義する
 * 借り主は支払い可能額を持つ（私は10万円まで）
 *
 * 物件・部屋に対して複数の申し込みがあった場合は適当に抽選
 *
 * 結果
 * 物件・部屋に対して申し込んだ人
 * 家賃
 * 誰が借りたか
 * 最終的に物件が見つからなかった人はホームレス
 * ホームレスの一覧も出す
 */

$applicants = [];
$apartments = [
    1 => [
        'floor_count'      => 2,
        'floor_room_count' => 5,
        'base_rent'        => 100000,
        'discount_price'   => 2000,
        'rooms'            => [],
    ],
    2 => [
        'floor_count'      => 2,
        'floor_room_count' => 5,
        'base_rent'        => 100000,
        'discount_price'   => 0,
        'rooms'            => [],
    ],
];

foreach ($apartments as $apartment_id => $apartment) {
    $applicants[$apartment_id] = [];
    for ($floor = 1; $floor <= $apartment['floor_count']; $floor++) {
        $apartments[$apartment_id]['rooms'][$floor] = [];
        $applicants[$apartment_id][$floor]          = [];
        for ($room_num = 1; $room_num <= $apartment['floor_room_count']; $room_num++) {
            $room_name                                               = $floor . sprintf('%02d', $room_num);
            $applicants[$apartment_id][$floor][$room_name]           = [];
            $apartments[$apartment_id]['rooms'][$floor][$room_name]  = [
                'apartment_id'         => $apartment_id,
                'floor'                => $floor,
                'room_name'            => $room_name,
                'rent'                 => $apartment['base_rent'] + (($floor - 1) * 1000),
                'empty'                => (rand(0, 1) === 0),
                'contracted_person_id' => null,
                'contracted_rent'      => null,
            ];
        }
    }
}

$persons = [
    1 => [
        'name'                    => '村田',
        'payable_amount'          => 110000,
        'expected_discount_price' => 1000,
    ],
    2 => [
        'name'                    => '田中',
        'payable_amount'          => 200000,
        'expected_discount_price' => 2000,
    ],
    3 => [
        'name'                    => '中村',
        'payable_amount'          => 150000,
        'expected_discount_price' => 5000,
    ],
    4 => [
        'name'                    => '小林',
        'payable_amount'          => 10000,
        'expected_discount_price' => 3000,
    ],
];

$homeless_person_ids  = [];
$searching_person_ids = array_keys($persons);

while (count($searching_person_ids) > 0) {
    foreach ($searching_person_ids as $searching_person_id) {
        $candidate_apply_rooms = [];
        foreach ($apartments as $apartment_id => $apartment) {
            foreach ($apartment['rooms'] as $floor => $floor_rooms) {
                foreach ($floor_rooms as $room_name => $room) {
                    if ($room['empty'] && rand(0, 1) === 1) {
                        $expected_after_discount_rent = $room['rent'] - $persons[$searching_person_id]['expected_discount_price'];
                        if ($expected_after_discount_rent <= $persons[$searching_person_id]['payable_amount']) {
                            $candidate_apply_rooms[] = $room;
                        }
                    }
                }
            }
        }

        while (true) {
            if (count($candidate_apply_rooms) === 0) {
                $homeless_person_ids[] = $searching_person_id;
                break;
            }

            $want_apply_room_index = array_rand($candidate_apply_rooms);
            $want_apply_room       = $candidate_apply_rooms[$want_apply_room_index];
            $want_apply_room_rent  = $want_apply_room['rent'];
            $discount_price        = $apartments[$want_apply_room['apartment_id']]['discount_price'];

            if ($discount_price > 0) {
                if (rand(0, 1) === 1) {
                    $want_apply_room_rent -= $discount_price;
                    if (rand(0, 1) === 1) {
                        $want_apply_room_rent -= $discount_price;
                    }
                }
            }

            if ($want_apply_room_rent < $persons[$searching_person_id]['payable_amount']) {
                $applicants[$want_apply_room['apartment_id']][$want_apply_room['floor']][$want_apply_room['room_name']][$searching_person_id] = $want_apply_room_rent;
                break;
            }

            unset($candidate_apply_rooms[$want_apply_room_index]);
        }
    }

    foreach ($apartments as $apartment_id => $apartment) {
        foreach ($apartment['rooms'] as $floor => $floor_rooms) {
            foreach ($floor_rooms as $room_name => $room) {
                if (count($applicants[$apartment_id][$floor][$room_name]) > 0 && $room['contracted_person_id'] === null) {
                    $contract_person_id = array_rand($applicants[$apartment_id][$floor][$room_name]);

                    $apartments[$apartment_id]['rooms'][$floor][$room_name]['contracted_person_id'] = $contract_person_id;
                    $apartments[$apartment_id]['rooms'][$floor][$room_name]['contracted_rent']      = $applicants[$apartment_id][$floor][$room_name][$contract_person_id];
                    $apartments[$apartment_id]['rooms'][$floor][$room_name]['empty']                = false;

                    $searching_person_ids = array_diff($searching_person_ids, [$contract_person_id]);
                }
            }
        }
    }

    foreach ($searching_person_ids as $searching_person_index => $searching_person_id) {
        if (in_array($searching_person_id, $homeless_person_ids, true)) {
            unset($searching_person_ids[$searching_person_index]);
        }
    }
}

?>

<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題12 賃貸</title>
  </head>

  <body>
    <h1>課題12 賃貸</h1>
    <h2>物件情報</h2>
    <?php foreach ($apartments as $apartment_id => $apartment) : ?>
      <h3>アパート<?php echo $apartment_id ?></h3>
      <table border="1" cellpadding="2">
        <tbody>
          <?php for ($floor = $apartments[$apartment_id]['floor_count']; $floor >= 1; $floor--) : ?>
            <tr>
              <?php foreach ($apartment['rooms'][$floor] as $room_name => $room) : ?>
                <td>
                  <p>部屋番号:<?php echo $room_name ?></p>
                  <?php if (count($applicants[$apartment_id][$floor][$room_name]) > 0) : ?>
                    <?php foreach ($applicants[$apartment_id][$floor][$room_name] as $applicant_id => $applicant) : ?>
                      <li><?php echo $persons[$applicant_id]['name'] ?></li>
                    <?php endforeach ?>
                  <?php else : ?>
                    <p>申込無し</p>
                  <?php endif ?>
                  <p>家賃:
                    <?php if ($room['contracted_rent'] !== null) : ?>
                      <?php echo number_format($room['contracted_rent']) ?>
                    <?php else : ?>
                      <?php echo number_format($room['rent']) ?>
                    <?php endif ?>
                    円</p>
                  <?php if (!$room['empty']) : ?>
                    <?php if ($room['contracted_person_id'] !== null) : ?>
                      <p>契約者:<?php echo $persons[$room['contracted_person_id']]['name'] ?></p>
                    <?php else : ?>
                      <p>入居済み</p>
                    <?php endif ?>
                  <?php else : ?>
                    <p>空き部屋</p>
                  <?php endif ?>
                </td>
              <?php endforeach ?>
            </tr>
          <?php endfor ?>
        </tbody>
      </table>
    <?php endforeach ?>
    <h2>ホームレス</h2>
    <?php foreach ($homeless_person_ids as $homeless_person_id) : ?>
      <li><?php echo $persons[$homeless_person_id]['name'] ?></li>
    <?php endforeach ?>
  </body>

</html>
