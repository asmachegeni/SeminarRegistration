<?php
require_once 'Controller.php';
require_once '../Model/database.php';

class stnController implements Controller
{

    public function validation()
    {
        if (!isset($_POST['stn'])) {
            return false;
        }
        if ($this->isRegister()) {
            return false;
        }
        if (!$this->isSSCESStudent()){
            return false;
        }
        return true;
    }

    public function prepare()
    {
        header("Access-Control-Allow-Origin:*");
        if(!$this->validation()){
            echo json_encode([
                'success' => 0,
            ]);
            return false;
        }

        echo json_encode([
            'success' => 1,
        ]);
        return true;
    }

    private function isRegister()
    {
        $_DB = new DB();

        $prepare = $_DB->pdo->prepare("SELECT * FROM `user`, `pay` WHERE stn = '{$_POST['stn']}' and pay.user_id =  user.id and pay.status = 'accepted'");
        $prepare->execute();

        $result = $prepare->fetchAll();

        if (count($result) != 0) {
            return true;
        }
        return false;
    }

    private function isSSCESStudent()
    {
        $stn = $_POST['stn'];
        if (strlen($stn) == 11) {
            if (preg_match("/^(\d\d\d)[1][2][3][5][8](\d\d\d)/", $stn)) {
                return true;
            }
            return false;
        } else if (strlen($stn) == 10) {
            if (preg_match("/^(\d\d)[1][2][3][5][8](\d\d\d)/", $stn)) {
                return true;
            }
            return false;
        }
        return false;
    }
}

$exe = new stnController();
$exe->prepare();
