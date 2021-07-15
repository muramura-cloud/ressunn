<?php

function output($arg) {
  echo '<pre>';
  var_dump($arg);
  echo '</pre>';
}

// 改行について
// ブラウザはHTMLしか解釈できない
// サーバー（PHP）は、PHPコードを返すわけではない。出力結果（文字列（HTML））を返しているだけ。
// PHPプログラム上で改行してもそれはブラウザ上の表示に一切関係ない
// ブラウザ上の表示で改行したければ、HTMLとして改行しなければならない。（<br>タグを使う）
echo 'aiueo<br>kakikukeko';

// コーディング規約
// 同じ会社やチームにおいて、コードの書き方を統一しましょう。

// Coding Standards: オペレーターの前後は必ずスペースを入れる
$num1 = 10;
$num2 = 20;

output($num1 + $num2);
output(10 + $num2);
output(10 + 20);

$num1 = 0.9;
$num2 = 0.2;

output($num1 + $num2);

$num1 = 0.9;
$num2 = 0.2;

output($num1 - $num2);

$num1 = 0.9;
$num2 = 0.2;

output($num1 * $num2);

$num1 = 0.9;
$num2 = 0.2;

output($num1 / $num2);

$num = 10;
$num++;  // インクリメント演算子
output($num);

$num = 10;
$num++;
$num++;
$num++;
output($num);

$num = 10;
$num--;  // デクリメント演算子
output($num);

$num = 10;
$num--;
$num--;
$num--;
output($num);

$num = 10;
$num = $num + 1;
output($num);

$num = 10;
$num += 1;
output($num);

$num = 10;
$num -= 1;
output($num);

$num = 10;
$num *= 2;
output($num);

$num = 10;
$num /= 2;        // できるけどあまり見ないのでやらない方がいい
$num = $num / 2;  // わかりやすく書く
output($num);

// 座学（こういうこともできるよ、仕様）
$num = 10;
++$num;
output($num);

// 座学（こういうこともできるよ、仕様）
$num = 10;
$ans = $num++ + 10;
output($ans);

// 実際はこう書きましょう（わかりやすく）
$num = 10;
$ans = $num + 10;
$num++;
output($ans);

// 座学（こういうこともできるよ、仕様）
$num = 10;
$ans = ++$num + 10;
output($ans);

// 実際はこう書きましょう（わかりやすく）
$num = 10;
$num++;
$ans = $num + 10;
output($ans);

// 未満, より大きい（を超える）, 以下, 以上
// <, >, <=, >=
// Coding Standards: オペレーターの前後は必ずスペースを入れる
// Coding Standards: 制御構文の括弧の前後には必ずスペースを入れる
for ($i = 0; $i < 10; $i++) {
    output($i);
}

for ($i = 0; $i < 10; ++$i) {
    output($i);
}

for ($i = 0; $i < 10; $i = $i + 1) {
    output($i);
}

for ($i = 0; $i < 10; $i += 1) {
    output($i);
}

// 真偽値(boolean) 2種類しかない
$bool = true;

if ($bool) {
    output('$bool is true');
} else {
    output('$bool is false');
}

$bool = false;

if ($bool) {
    output('$bool is true');
} else {
    output('$bool is false');
}

/*
if (条件式) {
}

↓正確には

if (式) {
}
*/

$n1 = 10;         // 10を$n1に入れるという式         -> 10
$n2 = 20;         // 20を$n2に入れるという式         -> 20
$n3 = $n1 + $n2;  // $n1 + $n2 を$n3に入れるという式 -> 30

$r = $n1 == 10;
output($r);  // trueが入る

if (10) {
    output('true');
} else {
    output('false');
}

// 傾向として、LL（ライトウェイトランゲージ）では、こういう書き方ができる。
// そうじゃない、重たい言語（JAVAとか）では、こういう書き方はできない。（コンパイルエラーが起こる）
// JAVAの場合は、厳密に、ifの条件式の結果はtrueかfalseでなければならない

// では、何をもってtrueなのか？falseなのか？
// これは、言語仕様次第。
/*
if (10)
if ('test')
if ('aiueo')
if ('-1')
if (-1)
if (0)
if ('0')
*/
// こういうtrue, false以外を渡した（true, false以外の結果になった）時に、それをtrueとするかfalseとするかは言語次第ということ。
// http://php.net/manual/ja/types.comparisons.php

// JAVA
// if (trueかどうか）
// PHPとか
// if (trueを見なされるものかどうか）

// Coding Standards: 可能な限り、厳密な比較を用いましょう
// NG
$num = '10';
if ($num == 10) {
    output('$num is 10');
} else {
    output('$num is not 10');
}

// OK
$num = '10';
if ($num === 10) {
    output('$num is 10');
} else {
    output('$num is not 10');
}

// $numには、文字列なのか整数なのか分からないけど、必ず数字が入っているって分かっているという場合
// そういう場合は、キャストする。
// ただし、こういうのはケースバイケースなので、今後いろいろな場面で突っ込みを受けたりして学んでいきましょう。
$num = 10;
if ((int)$num === 10) {
    output('$num is 10');
} else {
    output('$num is not 10');
}

// Coding Standards: 文字列はシングルクオーテーションを使う
$str1 = 'Ebine';
$str2 = 'Yutaka';
output($str1 . $str2);
output('Ebine' . 'Yutaka');

$str1 = 'Ebine';
$str2 = 'Yutaka';
output($str1 . ' ' . $str2);

$last_name = 'Ebine';
$first_name = 'Yutaka';
output('こんにちは！' . $last_name . ' ' . $first_name . ' さん');

// 文字列展開
// Coding Standards: 文字列展開の場合は、このように書く。
output("こんにちは！{$last_name} {$first_name} さん");
// コーディング規約違反の例1
output("こんにちは！${last_name} ${first_name} さん");
// コーディング規約違反の例2
output("こんにちは！$last_name $first_name さん");

// Coding Standards: 配列の宣言は[]を使う
// $array = array(1, 2, 3);
// 何番目というのを、添字（てんじ）、添え字、インデックスという言い方をします
$array = [1, 2, 3, 4, 5];

output($array[0]);
output($array[1]);
output($array[2]);

for ($i = 0; $i < count($array); $i++) {
    output($array[$i]);
}

// 要素を追加する
$array[] = 6;
output($array[5]);

for ($i = count($array) - 1; $i >= 0; $i--) {
    output($array[$i]);
}

// 連想配列
// それぞれの項目に意味がある（意味のある名前をキーとする）
// 連想配列の場合はインデックスではなくキーと呼ぶ
$ebine = ['name' => 'Ebine', 'age' => 35, 'sex' => 'male'];

output($ebine['name']);
output($ebine['age']);
output($ebine['sex']);

// Coding Standards:
// このような書き方をする時は、=> の位置を揃える
// 最後の要素にもカンマを付ける
$ebine = [
    'name' => 'Ebine',
    'age'  => 35,
    'sex'  => 'male',
];

// 連想配列の場合はよく、foreach文を使います
foreach ($ebine as $value) {
    output($value);
}

foreach ($ebine as $key => $value) {
    output($key . ' ' . $value);
}

// 配列の中に連想配列を入れる
$employees = [];

$employees[] = [
    'name' => 'Ebine',
    'age'  => 35,
    'sex'  => 'male',
];

$employees[] = [
    'name' => 'Hamanaka',
    'age'  => 33,
    'sex'  => 'male',
];

for ($i = 0; $i < count($employees); $i++) {
    foreach ($employees[$i] as $key => $value) {
        output($key . ' ' . $value);
    }
}

$array = [
  [1, 2, 3],
  [4, 5, 6],
  [7, 8, 9],
];

output($array[1][1]);  // 5

$array = [
  [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
  ],
  [
    [10, 11, 12],
    [13, 14, 15],
    [16, 17, 18],
  ],
];

output($array[1][2][0]);  // 16

// ↓みたいなコードもできるわけで
// $hash['key1']['key2']['key3'][2]['key4']['key5'];

// 関数
// 何か特定の仕事をするもの
// 関数名は、その仕事をちゃんと表すものとする
function add($num1, $num2) {
    // $resultという変数に2つの変数の合計を代入している
    $result = $num1 + $num2;

    // $resultの値を戻り値として返している
    return $result;

    // 1行で書ける
    // return $num1 + $num2;
}

$num1 = 10;
$num2 = 20;

$result = add($num1, $num2);
output($result);

// 上の2行は、1行で書ける
output(add($num1, $num2));

// オプショナルの引数
// 「たいていの場合はこうだよね」というのはオプショナルの引数にしてしまう
// ほとんどの客はレシートいらないんだから、それをデフォルトにしちゃえばいい
function checkout($money, $return_receipt = false) {
    // 処理の詳細はどうでもいい
    // ...

    /*
    if ($return_receipt) {
        return レシート;
    }
    */
}

checkout(10000, false);  // レシートいらない
checkout(10000, true);   // レシートいる

checkout(10000);         // レシートいらないから書かなくていい
checkout(10000, true);   // レシートいる時だけ書けばいい

// メモ
// よくわからないインターネット上の素人のブログとかのコードを鵜呑みにしないようにしましょう
// 大事なこと。
// 本に書いてあるコードでさえ鵜呑みにしないようにしましょう
// 自分が100%理解できていないコードが含まれるのであれば、それは自分のコードじゃない。
// 100%理解するまで学びましょう。
// コピペした！動いた！やった！じゃ、プログラマーじゃないです。
// なので、PHPの公式ドキュメントをしっかり読めるようになりましょう。
// 例えば本やインターネットの記事で strpos() という関数を使っているコードを見ました。
// その関数がよくわからなければ、http://php.net/strpos のように関数名で一発で検索できます。
//
// まずは、
// http://php.net/manual/ja/
// 言語リファレンスの基本的な構文～関数までを読む
// 中にはえらい込み入った内容も書いてあるけど、あまり難しいところは100%理解できなくてもいいから、読み進める。
// 自分でコードコピペして動作の振る舞いを確認するのも非常によし。
// もちろん先輩に聞いてもよし。
//
// 文字列関数
// http://php.net/manual/ja/ref.strings.php
// 配列関数
// http://php.net/manual/ja/ref.array.php
//

