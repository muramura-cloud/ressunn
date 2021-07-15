<?php
const MAX_NUM = 9;
?>

<!-- 九九の表をHTMLで作ってください。 -->
<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>九九表</title>
  </head>

  <body>
    <h1>九九表</h1>
    <table border="1" cellpadding="2">
      <tbody>
        <tr>
          <th></th>
          <?php for ($i = 1; $i <= MAX_NUM; $i++) : ?>
            <th><?php echo $i ?></th>
          <?php endfor ?>
        </tr>
        <?php for ($i = 1; $i <= MAX_NUM; $i++) : ?>
          <tr>
            <th><?php echo $i ?></th>
            <?php for ($j = 1; $j <= MAX_NUM; $j++) : ?>
              <td><?php echo $i * $j ?></td>
            <?php endfor ?>
          </tr>
        <?php endfor ?>
      </tbody>
    </table>
  </body>

</html>
