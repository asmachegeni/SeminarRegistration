<?php
session_start();

require_once '../Model/database.php';
require_once 'Controller.php';
require_once 'paymentController.php';

class registerController implements Controller
{
    private $arr = [];

    public function validation()
    {
        if ($_SESSION['csrf_token'] != $_POST['csrf_token']) {
            $_SESSION['ERROR.message'] = 'درخواست شما پذیرفته نشد!';
            $_SESSION['ERROR.type'] = 'csrf';
            header('location:../index.php');
            return false;
        }
        if (!$this->nameValidation()) {
            $_SESSION['ERROR.message'] = 'نام شما به درستی وارد نشده است!';
            $_SESSION['ERROR.type'] = 'name';
            header('location:../index.php');
            return false;
        }
        if (!$this->phoneValidation()) {
            $_SESSION['ERROR.message'] = 'تلفن شما به درستی وارد نشده است!';
            $_SESSION['ERROR.type'] = 'phone';
            header('location:../index.php');
            return false;
        }
        if (!$this->ssnValidation()) {
            $_SESSION['ERROR.message'] = 'شماره ملی شما به درستی وارد نشده است!';
            $_SESSION['ERROR.type'] = 'ssn';
            header('location:../index.php');
            return false;
        }
        if (!$this->stnValidation()) {
            $_SESSION['ERROR.message'] = 'شماره دانشجویی شما به درستی وارد نشده است!';
            $_SESSION['ERROR.type'] = 'stn';
            header('location:../index.php');
            return false;
        }
        return true;
    }

    public function prepare()
    {
        $_DB = new DB();

        if (!$this->validation()) {
            return false;
        }

        $prepare = $_DB->pdo->prepare("SELECT * FROM `user`, `pay` WHERE (ssn = '{$_POST['ssn']}' OR phone = '{$_POST['phone']}' OR stn = '{$_POST['stn']}') and pay.user_id = user.id and pay.status = 'accepted'");
        $prepare->execute();
        $result = $prepare->rowCount();

        if ($result != 0) {
            $_SESSION['ERROR.message'] = 'شما قبلا ثبت نام کرده اید';
            $_SESSION['ERROR.type'] = 'global';
            header('Location: ../index.php');
            return false;
        }

        $prepare = $_DB->pdo->prepare("SELECT * FROM `user` WHERE ssn = '{$_POST['ssn']}' OR phone = '{$_POST['phone']}' OR stn = '{$_POST['stn']}' ");
        $prepare->execute();
        $result = $prepare->rowCount();

        if ($result == 0) {
            $prepare = $_DB->pdo->prepare("INSERT INTO `user` (`name`, `ssn`, `phone`, `stn`)
            VALUES ('{$_POST['name']}', '{$_POST['ssn']}', '{$_POST['phone']}','{$_POST['stn']}')");
            $prepare->execute();
        }

        $orderId = $this->makeOrder();

        // bu ali computer student and check free register
        $stn = $_POST['stn'];
        if (strlen($stn) == 11) {
            if (preg_match("/^(\d\d\d)[1][2][3][5][8](\d\d\d)/", $stn)) {
                $prepare = $_DB->pdo->prepare("UPDATE `pay` set status = 'accepted', amount = '0' WHERE id = '{$orderId}'");
                $prepare->execute();

                $_SESSION['name'] = $_POST['name'];
                $_SESSION['stn'] = $_POST['stn'];

                header("location:../ticket.php");
                return true;
            }
        } else if (strlen($stn) == 10) {
            if (preg_match("/^(\d\d)[1][2][3][5][8](\d\d\d)/", $stn)) {
                $prepare = $_DB->pdo->prepare("UPDATE `pay` set status = 'accepted', amount = '0' WHERE id = '{$orderId}'");
                $prepare->execute();

                $_SESSION['name'] = $_POST['name'];
                $_SESSION['stn'] = $_POST['stn'];

                header("Location: ../ticket.php");

                return true;
            }
        }

        $payment = new paymentController();
        $token = $payment->tokenRequest($orderId);

        header("Location:https://nextpay.org/nx/gateway/payment/{$token}");
        return true;
    }

    private function makeOrder()
    {
        $_DB = new DB();

        $prepare = $_DB->pdo->prepare("SELECT * FROM `user` WHERE ssn ='{$_POST['ssn']}'");
        $prepare->execute();
        $result = $prepare->fetchAll();
        $user = $result[0];

        $prepare = $_DB->pdo->prepare("INSERT INTO `pay` 
            (`amount`, `status`, `created_at`, `user_id`)
            VALUES ('1000', 'pending', now(), '{$user['id']}')");
        $prepare->execute();
        return $_DB->pdo->lastInsertId();
    }

    private function nameValidation()
    {
        if (isset($_POST['name']) and is_string($_POST['name'])) {
            return true;
        }
        return false;
    }

    private function ssnValidation()
    {
        if (isset($_POST['ssn']) and strlen($_POST['ssn']) == 10) {
            return true;
        }
        return false;
    }

    private function phoneValidation()
    {
        if (isset($_POST['phone']) and
            strlen($_POST['phone']) == 11) {
            return true;
        }
        return false;
    }

    private function stnValidation()
    {
        if (isset($_POST['stn'])) {
            if (is_string($_POST['stn']) and strlen($_POST['stn']) <= 11 and strlen($_POST['stn']) >= 10) {
                return true;
            }
            return false;
        } else {
            return true;
        }
    }
}

$r = new registerController();
$r->prepare();
