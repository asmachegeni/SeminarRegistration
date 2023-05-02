<?php
require_once 'Controller.php';
require_once '../Model/database.php';

class paymentController implements Controller
{
    private $callback_uri = "https://ssces.barfenow.ir/Controller/paymentController.php";

    private $trans_id;
    private $order_id;
    private $amount;

    private $res;


    public function tokenRequest($orderId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://nextpay.org/nx/gateway/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "api_key=65d94dfb-19d8-4357-bcf4-cf570abcf251&amount=1000&callback_uri={$this->callback_uri}&order_id={$orderId}",
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($response);

        if ($res->code == -1) {
            return $res->trans_id;
        }

        header("location:../index.php");
    }

    public function redirectToBank($token)
    {
        header("location:https://nextpay.org/nx/gateway/payment/{$token}");
    }

    public function validation()
    {
        if ($this->res->code != 0) {
            return false;
        }
        return true;
    }

    public function prepare()
    {
        if (!isset($_GET['trans_id']) or !isset($_GET['order_id']) or !isset($_GET['amount'])) {
            return false;
        }
        $this->trans_id = $_GET['trans_id'];
        $this->order_id = $_GET['order_id'];
        $this->amount = $_GET['amount'];


        $this->updateTransOfPay();

        $this->acceptTrans();

        if (!$this->validation()) {
            echo "پرداخت ناموفق بود :) لطفا دوباره تلاش کنید";
            die("پرداخت ناموفق بود. لطفا دوباره تلاش کنید!");
        }

        $_DB = new DB();

        $prepare = $_DB->pdo->prepare("UPDATE `pay` set card_holder = '{$this->res->card_holder}',
                 customer_phone = '{$this->res->customer_phone}',
                 Shaparak_Ref_Id = '{$this->res->Shaparak_Ref_Id}',
                 trans_created_at = '{$this->res->created_at}',
                 status = 'accepted' WHERE id = '{$this->order_id}'");
        $prepare->execute();

        $prepare = $_DB->pdo->prepare("SELECT name, stn FROM `user`, `pay` WHERE pay.id = '{$this->order_id}' and pay.user_id = user.id");
        $prepare->execute();
        $result = $prepare->fetchAll();

        $user = $result[0];

        $_SESSION['name'] = $user['name'];
        $_SESSION['stn'] = $user['stn'];

        header("location:https://ssces.barfenow.ir/ticket.php");


    }

    public function acceptTrans()
    {
        $_DB = new DB();

        $prepare = $_DB->pdo->prepare("SELECT * FROM `pay` WHERE id = '{$this->order_id}' and trans_id = '{$this->trans_id}'");
        $prepare->execute();
        $res = $prepare->fetchAll();

        $record = $res[0];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://nextpay.org/nx/gateway/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "api_key=65d94dfb-19d8-4357-bcf4-cf570abcf251&amount={$record['amount']}&trans_id={$record['trans_id']}",
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        $this->res = $response;

        return $response;
    }

    private function updateTransOfPay()
    {
        $_DB = new DB();

        $prepare = $_DB->pdo->prepare("UPDATE `pay` set trans_id = '{$this->trans_id}' WHERE id = '{$this->order_id}'");
        $prepare->execute();

    }
}

$exc = new paymentController();
$exc->prepare();
