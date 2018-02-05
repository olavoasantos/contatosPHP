<!-- 
  Error.view.php
  View para apresentação de errors
 -->

<!DOCTYPE html>
<html lang="en" style="display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; padding: 0; height: 100%">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= $message ?> - <?= config('app', 'name') ?></title>
</head>
<body style="display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 0; padding: 0; height: 100%">
  <h3>Error</h3>
  <h1 style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
    <span><?= $code ?></span>
  </h1>

  <p>
    <?= $message ?>
  </p>
</body>
</html>