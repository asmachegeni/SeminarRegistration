<?php

session_start();
$token = md5(uniqid(mt_rand(), TRUE));
$_SESSION['csrf_token'] = $token;


?>

<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <title>ثبت نام</title>
    <link rel="stylesheet" href="./styles/reset.css"/>
    <link rel="stylesheet" href="./styles/fontFaces.css"/>
    <link rel="stylesheet" href="./styles/main.css"/>
    <link rel="icon" href="./assets/images/logo.png"/>
</head>
<body>
<div class="main">
    <form action="Controller/registerController.php" method="post">
            <span id="title">ثبت نام در سمینار</span>
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <input type="text" name="name" placeholder="نام نام خانوادگی" id="namein"/>
            <span class="error" id="name">
                <?php
                if (isset($_SESSION['ERROR.type'])) {
                    if ($_SESSION['ERROR.type'] == 'global') {
                        echo $_SESSION['ERROR.message'];
                    } else {
                        echo " نام وارد شده معتبر نمی باشد.";
                    }
                    unset($_SESSION['ERROR.type']);
                } else {
                    echo " نام وارد شده معتبر نمی باشد.";
                }

                ?>
               </span>
            <input type="text" name="ssn" placeholder="کد ملی" id="nationcodein"/>
            <span class="error" id="nationcode"
            >کد ملی وارد شده معتبر نمی باشد.</span
            >
            <input type="text" name="stn" placeholder="شماره دانشجویی" id="stunumberin"/>
            <span class="error" id="stunumber"
            >شماره دانشجویی وارد شده معتبر نمی باشد.</span
            >
            <input type="text" name="phone" placeholder="َشماره تلفن همراه" id="tellin" />
            <span class="error" id="tell"
            >شماره تلفن وارد شده معتبر نمی باشد.</span
            >
        <span id="erroMessage">شما قبلا ثبت نام کرده اید.</span>
            <div class="btn">
                <input type="submit" value="ثبت نام و پرداخت"/>
                <span id="price">مبلغ: 20,000 تومان</span>
            </div>
        </form>
    <div class="infoContainer">
        <canvas></canvas>
        <div class="content">
            <img src="./assets/images/logo.png" width="183px" height="240px"/>
            <h2>سمینار برنامه نویسی‌ فرانت اند و بک اند</h2>
            <p>
                سمیناری برای دانشجویان علاقه مند به حوزه برنامه‌نویسی وب کاری از
                انجمن مهندسی کامپیوتر دانشگاه بوعلی‌سینا
            </p>
        </div>
    </div>
</div>
<div class="footer">
    <div id="bgFooter1-border"></div>
    <div id="bgFooter1"></div>
    <div id="bgFooter2-border"></div>
    <div id="bgFooter2"></div>
    <div class="creator">
        <span> توسعه داده شده توسط: </span>
        <a href="https://t.me/Ali_zne">علی زین الدینی</a>
        <span> ، </span>
        <a href="https://t.me/ascchh">اسماء چگنی</a>
    </div>

</div>
<script src="./scripts/script.js"></script>
<script src="./scripts/main.js"></script>
<script>
    <?php
    if (isset($_SESSION['ERROR.type'])) {
    switch ($_SESSION['ERROR.type']) {
    case 'csrf':
        echo "درخواست نامعتبر است!";
        unset($_SESSION['ERROR.type']);
        break;
    case 'name':
    unset($_SESSION['ERROR.type']);

    ?>
    showNameError();
    <?php
    break;
    case 'phone':
    unset($_SESSION['ERROR.type']);
    ?>
    showTellError();
    <?php
    unset($_SESSION['ERROR.type']);
    break;
    case 'ssn':
    ?>
    showNationCodeError();
    <?php
    unset($_SESSION['ERROR.type']);
    break;
    case 'stn':
    ?>
    showStuNumberError();
    <?php
    unset($_SESSION['ERROR.type']);
    break;
    case 'global':
    ?>
    showNameError();
    <?php
    unset($_SESSION['ERROR.type']);
    break;
    }
    }
    ?>
</script>
</body>
</html>
