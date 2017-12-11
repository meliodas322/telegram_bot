<?php


class dataBase{
    private $link;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $config = require_once 'config.php';
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
        $this->link = new  PDO($dsn,$config['username'],$config['password']);
        return $this;
    }

    public function execute($sql)
    {
        $sth = $this->link->prepare($sql);
        return $sth->execute();
    }

    public function query($sql)
    {
        $exe = $this->execute($sql);
        $result = $exe->fetchAll(PDO::FETCH_ASSOC);
        if ($result===false){
            return [];
        }
        return $result;
    }
    public function call($code){
        $stmt = $this->link->prepare("CALL CheckAltCode($code)");
        $rs = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function call2($id,$email,$name,$cost,$type){
        $ch = curl_init('http://45.55.69.246/mega-website-v2/public/api/compliment/code/generate');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "object_id=".$id."&"
            .   "email=".$email."&"
            .   "product_name=".$name."&"
            .   "product_cost=".$cost."&"
            .   "reward_type=".$type."");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);
        return $result;
    }

}


     /*   "object_id=96&"
        .   "email=$perem1&"
        .   "product_cost=$perem2&"
        .   "reward_type=$perem3&"); */