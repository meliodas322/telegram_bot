<?php
	include('vendor/autoload.php');
	include('TelegramBot.php');
	include('dataBase.php');
	include('keyboard.php');
    include ('Answers.php');
    include('dataBaseId.php');
    $bot = new \TelegramBot\Api\Client('495486838:AAFHlbUVP3hOfVIEkrQTQEAEWc43pyEkvmk',null);
    //$bot = new \TelegramBot\Api\BotApi('495486838:AAFHlbUVP3hOfVIEkrQTQEAEWc43pyEkvmk');
	//подключится к базе
    $db = new dataBase();
    $kb = new keyboard();
    $ans = new Answers();
    $dbid = new dataBaseId();
    $i=1;
    $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Оформить заказ")), true);
    $keyboardChoice = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Да","Нет")), true);
    $keyboard2 = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
    [
        [
            ['text' => 'ввести название товара', 'url'=>'vk.com']
        ]
    ]
);
    $keyboardReg = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Начислить баллы","Начислить комплименты","Начислить баллы и комплименты","Ничего не начислять")), true);
    $dbinfo = array(
        'object_id'=> null,
        'card_number'=>null,
        'phone'=>null,
        'email'=>null,
        'product_cost'=>null,
        'product_name'=>null,
        'reward_type'=>null

    );
    $mass = array();
    $type = null;
    $id = null;
    $id2 = null;
    $telegramApi = new TelegramBot();
    while (true) {
        sleep(1);
        $updates = $telegramApi->getUpdates();
        foreach ($updates as $update) {
            $result = null;
            switch ($update->message->text) {
                case '/menu':
                    $bot->sendMessage($update->message->chat->id, "Меню", null, false, null, $keyboard);
                    var_dump($dbid->queryCard($update->message->chat->id));
                    var_dump($dbid->queryUpdate($update->message->chat->id,1));
                    //$dbid->execute("INSERT INTO `data_id` SET `telegram_id`=".$update->message->chat->id." ");
                    break;
                case 'АЛМИ':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 3) {
                        $key = 0;
                        $type = $update->message->text;
                        $dbinfo["card_number"] = $mass[0]["card_number"];
                        for ($key = 0; $key < count($mass); $key++) {
                            if ($update->message->text == $mass[$key]["card_type"]) {
                                $ans->sendProductName(3, $bot, $update);
                                $dbinfo["phone"] = $mass[$key]["phone_number"];
                                $dbinfo["email"] = $mass[$key]["email"];
                                echo $key;
                                var_dump($dbinfo);
                                $i = $i + 1;
                                $dbid->queryUpdate($update->message->chat->id,4);
                            }
                        }
                    }
                    break;
                case 'Евроопт':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 3) {
                        $key = 0;
                        $type = $update->message->text;
                        $dbinfo["card_number"] = $mass[0]["card_number"];
                        for ($key = 0; $key < count($mass); $key++) {
                            if ($update->message->text == $mass[$key]["card_type"]) {
                                $ans->sendProductName(3, $bot, $update);
                                $dbinfo["phone"] = $mass[$key]["phone_number"];
                                $dbinfo["email"] = $mass[$key]["email"];
                                echo $key;
                                var_dump($dbinfo);
                                $i = $i + 1;
                                $dbid->queryUpdate($update->message->chat->id,4);
                            }
                        }
                    }
                    break;
                case 'Да':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 7 && $db->call2($id[0]["object_id"],$dbinfo["email"], $dbinfo["product_name"], $dbinfo["product_cost"], $dbinfo["reward_type"])=="success") {
                        $bot->sendMessage($update->message->chat->id,'Регистрация заказа прошла упешно.');
                        $bot->sendMessage($update->message->chat->id, "Меню", null, false, null, $keyboard);
                        $i=1;
                    }
                    break;
                case 'Нет':
                    if ($i == 7) {
                        $bot->sendMessage($update->message->chat->id, "Меню", null, false, null, $keyboard);
                        $i=1;
                    }
                    break;
            }
            switch ($dbid->getState($update->message->chat->id)[0]["state"]) {
                case '1':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 1 && $update->message->text == 'Оформить заказ') {
                        $ans->sendCard(1, $bot, $update, $dbinfo);
                        $i = $i + 1;
                        $dbid->queryUpdate($update->message->chat->id,2);
                        var_dump($dbid->getState($update->message->chat->id));
                    }
                    break;
                case '2':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 2) {
                        $mass = $db->call($update->message->text);
                        $mass[0]["card_type"];
                        var_dump($mass);
                        var_dump(count($mass));
                        $c = count(($mass));
                        $array = array();
                        for ($e = 0; $e < $c; $e++) {
                            $array[$e] = array($mass[$e]["card_type"]);
                            # добавить телефоны
                        }
                        var_dump($array);
                        if ($update->message->text == $mass[0]["card_number"]) {
                            $keyboardr = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($array, true);
                            $bot->sendMessage($update->message->chat->id, "Доступные карты", null, false, null, $keyboardr);
                            $i = $i + 1;
                            $dbid->queryUpdate($update->message->chat->id,3);
                        } else {
                            $bot->sendMessage($update->message->chat->id, 'Пользователя с таким номером карты не обнаружено');
                            $bot->sendMessage($update->message->chat->id, "Меню", null, false, null, $keyboard);
                            $i=1;
                        };
                    }
                    break;
                case '4':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 4 && $dbinfo["product_name"]!=null) {
                        $dbinfo["product_name"] = $update->message->text;
                        $ans->sendProductCost(4, $bot, $update);
                        $i = $i + 1;
                        $dbid->queryUpdate($update->message->chat->id,5);
                    }
                    //file_put_contents("test.txt",$update->message->chat->id );
                    break;
                case '5':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 5) {
                        $dbinfo["product_cost"] = $update->message->text;
                        $bot->sendMessage($update->message->chat->id, "Выберите способ регистрации", null, false, null, $keyboardReg);
                        $i = $i + 1;
                        $dbid->queryUpdate($update->message->chat->id,6);
                        var_dump($dbinfo);
                    }
                    break;
                case '6':
                    if ($dbid->getState($update->message->chat->id)[0]["state"] == 6) {
                        $dbinfo["reward_type"] = $update->message->text;
                        var_dump('array dbinfo  ', $dbinfo);
                        $text =
                            "В заказе вы указали следующие данные:
                            номер карты - ".$dbinfo["card_number"]."
                            тип карты - $type
                            email - ".$dbinfo["email"]."
                            название товара - ".$dbinfo["product_name"]."
                            цена товара - ".$dbinfo["product_cost"]."
                            тип регистрации - ".$dbinfo["reward_type"]."
                            Если хотите оформить заказ на эти данные нажмите Да.
                            Если хотите вернуться к началу нажмите Нет";
                        $bot->sendMessage($update->message->chat->id, $text, null, false, null, $keyboardChoice);
                        $i=$i+1;
                        $dbid->queryUpdate($update->message->chat->id,7);
                    }

                    break;
                case '8':

                    break;
            }
        }
    }

?>
375298849413
andrejkaniushock@gmail.com

$dbid->execute("INSERT INTO `data` SET `telegram_id`=".$update->message->chat->id." ");
{

}
$db->call($i,$dbinfo);
if ($i == 4 && $update->message->text != 'Оформить заказ'&& $db->query('CALL CheckAltCode('.$dbinfo[$i].')')){

}
$db->execute("INSERT INTO `data` SET `object_id`='1',`product_name`='food',`product_cost`='2.354',`phone`='3453132',`email`='ainz@mail.ru'");
$bot->sendMessage($update->message->chat->id, 'ссылка', null, false, null, $keyboard2);
for ($i = 1; $i<7;$i++)
{
switch ($i)
{
case '1':
$ans->getProductName($bot,$update);
break;
case '2':
$dbinfo[1]=$update->message->text;
echo $dbinfo[1];
$k=3;
echo $k;
$bot->sendMessage($update->message->chat->id,'Введите цену товара');
break;
case '3':
$dbinfo[2]=$update->message->text;
echo $dbinfo[2];
$k = 4;
$bot->sendMessage($update->message->chat->id,'Введите телефон покупателя');
exit;
case '4':
$dbinfo[3]=$update->message->text;
echo $dbinfo[3];
$k = 5;
$bot->sendMessage($update->message->chat->id,'Введите email покупателя');
exit;
case '5':
$dbinfo[4]=$update->message->text;
echo $dbinfo[4];
$k=6;
$bot->sendMessage($update->message->chat->id, "Введите тип регистрации", null, false, null, $keyboardReg);
exit;
case '6':
if ($update->message->text!='Ничего не начислять'){
$dbinfo[5] = $update->message->text;
} else $dbinfo[5]=null;
echo $dbinfo[5];
break;
}
}

















