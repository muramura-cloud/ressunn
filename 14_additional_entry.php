<?php

/**
 * 撮影会申し込み管理システム
 *
 * 撮影会は複数
 * 撮影会は、撮影会名と日付をもつ
 * 撮影会には1人以上のモデルが出演
 * 各モデルは、1日に1～3ステージ出演
 *
 * 1ステージに参加可能なカメラマンは3人まで（枠数3）
 *
 * カメラマンは、撮影会・モデル・ステージを指定して申し込みをする
 * 申し込みをした時点で、枠数内であればそのステージは確定
 * 申し込みをした時点で、枠数を超えていればそのステージはキャンセル待ち
 *
 * 結果
 *
 * 撮影会ごとに、
 *   出演モデル
 *   出演モデルごとの売上（各モデルは、1ステージあたりの料金が決まっている）
 *   出演モデルのステージごとのカメラマン（確定分のみ）
 *
 * 申込者ごとに、
 *   申し込んだ撮影会とモデルとステージ
 *   ステージごとに確定かキャンセル待ちか
 *   料金合計
 */

const ONE_STAGE_PHOTOGRAPHER_COUNT = 3;

$photo_sessions = [
    1 => [
        'name'   => '撮影会A',
        'date'   => '8/15',
        'models' => [],
    ],
    2 => [
        'name'   => '撮影会B',
        'date'   => '8/16',
        'models' => [],
    ],
    3 => [
        'name'   => '撮影会C',
        'date'   => '8/17',
        'models' => [],
    ],
];

$models = [
    1 => [
        'name'           => 'A子',
        'appearance_fee' => 9000,
    ],
    2 => [
        'name'           => 'B子',
        'appearance_fee' => 8000,
    ],
    3 => [
        'name'           => 'C子',
        'appearance_fee' => 7000,
    ],
    4 => [
        'name'           => 'D子',
        'appearance_fee' => 6000,
    ],
];

$stage_confirm_photographer_ids = [];
foreach ($photo_sessions as $photo_session_id => $photo_session) {
    $stage_confirm_photographer_ids[$photo_session_id] = [];
    $appearance_model_ids                              = array_rand($models, rand(1, count($models)));
    foreach ((array) $appearance_model_ids as $model_id) {
        $stage_confirm_photographer_ids[$photo_session_id][$model_id] = [];
        $photo_sessions[$photo_session_id]['models'][$model_id]       = [];
        $model_appearance_stage_count                                 = rand(1, 3);
        for ($stage_num = 1; $stage_num <= $model_appearance_stage_count; $stage_num++) {
            $stage_confirm_photographer_ids[$photo_session_id][$model_id][$stage_num] = [];
            $photo_sessions[$photo_session_id]['models'][$model_id][]                 = $stage_num;
        }
    }
}

$photographers = [
    1 => [
        'name'               => '村田',
        'total_shooting_fee' => 0,
        'applied_stages'     => [],
    ],
    2 => [
        'name'               => '小林',
        'total_shooting_fee' => 0,
        'applied_stages'     => [],
    ],
    3 => [
        'name'               => '田中',
        'total_shooting_fee' => 0,
        'applied_stages'     => [],
    ],
    4 => [
        'name'               => '小川',
        'total_shooting_fee' => 0,
        'applied_stages'     => [],
    ],
];

foreach ($photographers as $photographer_id => $photographer) {
    $photographer_apply_photo_sessions = [];
    foreach ($photo_sessions as $photo_session_id => $photo_session) {
        foreach ($photo_session['models'] as $model_id => $stage_nums) {
            foreach ($stage_nums as $stage_num) {
                $is_apply = (rand(0, 1) === 0);

                if (!$is_apply) {
                    continue;
                }

                if (!isset($photographer_apply_photo_sessions[$photo_session_id])) {
                    $photographer_apply_photo_sessions[$photo_session_id] = [];
                }

                $is_applied = isset($photographer_apply_photo_sessions[$photo_session_id][$stage_num]);
                $is_change  = (rand(0, 1) === 1);

                if (!$is_applied || ($is_applied && $is_change)) {
                    $photographer_apply_photo_sessions[$photo_session_id][$stage_num] = $model_id;
                }
            }
        }
    }

    foreach ($photographer_apply_photo_sessions as $photo_session_id => $stage_nums) {
        foreach ($stage_nums as $stage_num => $model_id) {
            $is_confirm = false;

            if (count($stage_confirm_photographer_ids[$photo_session_id][$model_id][$stage_num]) < ONE_STAGE_PHOTOGRAPHER_COUNT) {
                $stage_confirm_photographer_ids[$photo_session_id][$model_id][$stage_num][] = $photographer_id;
                $photographers[$photographer_id]['total_shooting_fee']                     += $models[$model_id]['appearance_fee'];
                $is_confirm                                                                 = true;
            }

            $photographers[$photographer_id]['applied_stages'][] = [
                'photo_session_id' => $photo_session_id,
                'model_id'         => $model_id,
                'stage_num'        => $stage_num,
                'is_confirm'       => $is_confirm,
            ];
        }
    }
}

$photo_session_model_profits = [];
foreach ($stage_confirm_photographer_ids as $photo_session_id => $photo_session_models) {
    $photo_session_model_profits[$photo_session_id] = [];
    foreach ($photo_session_models as $model_id => $stage_nums) {
        $photo_session_model_profits[$photo_session_id][$model_id] = 0;
        foreach ($stage_nums as $stage_num => $photographer_ids) {
            $photo_session_model_profits[$photo_session_id][$model_id] += count($photographer_ids) * $models[$model_id]['appearance_fee'];
        }
    }
}

?>

<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題14 撮影会申し込み管理システム</title>
  </head>

  <body>
    <h1>課題14 撮影会申し込み管理システム</h1>

    <h2>撮影会情報</h2>
    <?php foreach ($photo_sessions as $photo_session_id => $photo_session) : ?>
      <h3> <?php echo $photo_session['date'] ?>&nbsp;<?php $photo_session['name'] ?></h3>
      <table border="1" cellpadding="2">
        <tbody>
          <tr>
            <th>出演モデル</th>
            <th>売り上げ</th>
            <th>カメラマン</th>
          </tr>
          <?php foreach ($photo_session['models'] as $model_id => $stages) : ?>
            <tr>
              <td><?php echo $models[$model_id]['name'] ?></td>
              <td><?php echo number_format($photo_session_model_profits[$photo_session_id][$model_id]) ?>円</td>
              <td>
                <?php foreach ($stage_confirm_photographer_ids[$photo_session_id][$model_id] as $stage_num => $photographer_ids) : ?>
                  <p>ステージ:<?php echo $stage_num ?></p>
                  <?php if (count($photographer_ids) > 0) : ?>
                    <?php foreach ($photographer_ids as $photographer_id) : ?>
                      <li><?php echo $photographers[$photographer_id]['name'] ?></li>
                    <?php endforeach ?>
                  <?php else : ?>
                    <p>カメラマン無し</p>
                  <?php endif ?>
                <?php endforeach ?>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <br>
    <?php endforeach ?>

    <h2>申込者情報</h2>
    <?php foreach ($photographers as $photographer_id => $photographer) : ?>
      <h3><?php echo $photographer['name'] ?></h3>
      <?php if (count($photographer['applied_stages']) > 0) : ?>
        <table border="1" cellpadding="2">
          <tbody>
            <tr>
              <th>撮影会</th>
              <th>モデル</th>
              <th>ステージ</th>
              <th>申込状況</th>
            </tr>
            <?php foreach ($photographer['applied_stages'] as $application) : ?>
              <tr>
                <td><?php echo $photo_sessions[$application['photo_session_id']]['name'] ?></td>
                <td><?php echo $models[$application['model_id']]['name'] ?></td>
                <td><?php echo $application['stage_num'] ?></td>
                <?php if ($application['is_confirm']) : ?>
                  <td>確定</td>
                <?php else : ?>
                  <td>キャンセル待ち</td>
                <?php endif ?>
              </tr>
            <?php endforeach ?>
            <tr>
              <th>合計料金</th>
              <td colspan="3"><?php echo number_format($photographer['total_shooting_fee']) ?>円</td>
            </tr>
          </tbody>
        </table>
      <?php else : ?>
        <p>申込なし</p>
      <?php endif ?>
    <?php endforeach ?>
  </body>

</html>
