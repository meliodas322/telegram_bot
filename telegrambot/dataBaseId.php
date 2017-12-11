<?php
class dataBaseId
{
    private $link;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $config = require_once 'ConfigDBid.php';
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];
        $this->link = new  PDO($dsn, $config['username'], $config['password']);
        return $this;
    }

    public function execute($sql)
    {
        $sth = $this->link->prepare($sql);
        return $sth->execute();
    }

    public function query($sql)
    {
        $sth = $this->link->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public function queryCard($id){
        $sth = $this->link->prepare("SELECT `object_id` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdate($id,$state){

        $sth = $this->link->prepare("UPDATE data_id SET state=".$state." where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getState($id){
        $sth = $this->link->prepare("SELECT `state` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdateEmail($id,$email){

        $sth = $this->link->prepare("UPDATE data_id SET email='$email'where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getEmail($id){
        $sth = $this->link->prepare("SELECT `email` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdateCardType($id,$card_type){

        $sth = $this->link->prepare("UPDATE data_id SET card_type='$card_type' where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getCardType($id){
    $sth = $this->link->prepare("SELECT `card_type` FROM `data_id` where `telegram_id`=".$id."");
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    if ($result === false) {
        return [];
    }
    return $result;
    }
    public  function queryUpdateProductCost($id,$product_cost){

        $sth = $this->link->prepare("UPDATE data_id SET product_cost=".$product_cost." where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getProductCost($id){
        $sth = $this->link->prepare("SELECT `product_cost` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdateProductName($id,$product_name){

        $sth = $this->link->prepare("UPDATE data_id SET product_name='$product_name' where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getProductName($id){
        $sth = $this->link->prepare("SELECT `product_name` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdateRewardType($id,$reward_type){

        $sth = $this->link->prepare("UPDATE data_id SET reward_type='$reward_type' where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getRewardType($id){
        $sth = $this->link->prepare("SELECT `reward_type` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdatePhone($id,$phone){

        $sth = $this->link->prepare("UPDATE data_id SET phone=".$phone." where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getPhone($id){
        $sth = $this->link->prepare("SELECT `phone` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function queryUpdateCardNumber($id,$card_number){

        $sth = $this->link->prepare("UPDATE data_id SET card_number='$card_number' where telegram_id=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
    public  function getCardNumber($id){
        $sth = $this->link->prepare("SELECT `card_number` FROM `data_id` where `telegram_id`=".$id."");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
}