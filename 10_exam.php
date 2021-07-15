<?php

/**
 * n人の生徒がいます
 * 年にn回(3回)テストが開催されます
 * 教科はn個(算数、国語、理科、社会、英語)あります
 * 点数はランダムで0〜100点
 * ランクを適当に定義する（300〜250=A,249〜200=B,...など）
 *
 * 結果を表示してください
 * 各テストごとの生徒ごとの教科ごとの点数を表で表示
 * 生徒ごとの教科ごとの年間合計点数を表示
 * 上記年間合計点数によるランク表示も合わせて表示
 *
 * 表の形式はいい感じで（見たときに分かりやすく）
 */

$students    = ['村田', '小林', '田中', '中村'];
$subjects    = ['国語', '算数', '理科', '社会', '英語'];
$exam_count  = rand(1, 3);
$rank_scores = [
    'A' => 80 * $exam_count,
    'B' => 60 * $exam_count,
    'C' => 40 * $exam_count,
    'D' => 20 * $exam_count,
    'E' => 0,
];

$all_exam_scores = [];
foreach ($students as $student_index => $student) {
    $all_exam_scores[$student_index] = [];
    foreach ($subjects as $exam_subject) {
        $all_exam_scores[$student_index][$exam_subject] = [];
        for ($i = 1; $i <= $exam_count; $i++) {
            $all_exam_scores[$student_index][$exam_subject][$i] = rand(0, 100);
        }
    }
}

$exam_results = [];
foreach ($students as $student_index => $student) {
    $exam_results[$student_index] = [];
    foreach ($subjects as $subject) {
        $subject_total_score = array_sum($all_exam_scores[$student_index][$subject]);

        foreach ($rank_scores as $rank => $rank_score) {
            if ($subject_total_score >= $rank_score) {
                $subject_rank = $rank;
                break;
            }
        }

        $exam_results[$student_index][$subject] = [
            'total_score' => $subject_total_score,
            'rank'        => $subject_rank,
        ];
    }
}

?>

<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題10 試験</title>
  </head>

  <body>
    <h1>課題10 試験</h1>
    <h2>---試験結果---</h2>
    <?php foreach ($all_exam_scores as $student_index => $scores) : ?>
      <table border="1" cellpadding="2">
        <tbody>
          <tr>
            <th><?php echo $students[$student_index] ?></th>
            <?php for ($i = 1; $i <= $exam_count; $i++) : ?>
              <th><?php echo $i ?>回目</th>
            <?php endfor ?>
            <th>合計</th>
            <th>ランク</th>
          </tr>
          <?php foreach ($scores as $subject => $subject_scores) : ?>
            <tr>
              <th><?php echo $subject ?></th>
              <?php foreach ($subject_scores as $score) : ?>
                <td><?php echo $score ?></td>
              <?php endforeach ?>
              <td><?php echo $exam_results[$student_index][$subject]['total_score'] ?></td>
              <td><?php echo $exam_results[$student_index][$subject]['rank'] ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <br>
    <?php endforeach ?>
  </body>

</html>
