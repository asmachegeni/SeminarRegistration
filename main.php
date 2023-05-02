<?php

session_start();
$token = md5(uniqid(mt_rand(), TRUE));
$_SESSION['csrf_token'] = $token;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="Controller/registerController.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <input type="text" name="name">
    <input type="text" name="ssn">
    <input type="text" name="phone">
    <input type="text" name="stn">
    <input type="submit">
</form>
</body>
</html>
