<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>دریافت بلیط</title>
    <link rel="stylesheet" href="./styles/reset.css"/>
    <link rel="stylesheet" href="./styles/fontFaces.css"/>
    <link rel="icon" href="./assets/images/logo.png"/>
    <link rel="stylesheet" href="./styles/ticket.css"/>
    <link rel="stylesheet" media="print" href="./styles/print.css"/>
</head>
<body>
<div class="ticketContainer">
    <div class="ticketHeader">
        <img src="./assets/images/logo.png" width="114px" height="150px"/>
        <div class="text">
            <span id="text1">بلیط حضور در سمینار</span>
            <span id="text2">سمینار تخصصی فرانت اند</span>
        </div>
    </div>
    <div class="ticketBody">
        <div class="field">
            <span class="title">نام</span><span class="value"><?php echo $_SESSION['name']; ?></span>
        </div>
        <div class="field">
          <span class="title">شماره دانشجویی</span
          ><span class="value"><?php echo $_SESSION['stn']; ?></span>
        </div>
        <div class="field">
          <span class="title">تاریخ برگزاری سمینار</span
          ><span class="value"> سه شنبه 13 دی ماه 1401</span>
        </div>
        <div class="field">
          <span class="title">محل برگزاری سمینار</span
          ><span class="value">دانشکده مهندسی، سالن آمفی تئاتر</span>
        </div>
    </div>
    <div class="ticketFooter">
        <div class="effect">
            <span class="circle"></span>
            <span class="line"></span>
            <span class="circle"></span>
        </div>
        <img id="barcode" src="./assets/images/barcode.svg"/>
    </div>
</div>
<button onclick="window.print()" id="printbtn">پرینت بلیط</button>
</body>
</html>
