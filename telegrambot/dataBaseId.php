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
}