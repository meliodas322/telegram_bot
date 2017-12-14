<?php
require_once('vendor/autoload.php');
require "telegrambot.php"; // подключаем telegrambot.php
require "Answers.php";
require "dataBase.php";
require "dataBaseId.php";
use GuzzleHttp\Client as HttpClient;
use RobbieP\ZbarQrdecoder\ZbarDecoder;
$ZbarDecoder = new RobbieP\ZbarQrdecoder\ZbarDecoder();
$dbid = new dataBaseId(); //переменнтая для получения позиции и object_id
$bot = new BOT(); // в переменную $bot создаем экземпляр нашего класса BOT
$ans = new Answers();
$db = new dataBase();
$token = 'ТОКЕН';
$botApi = new \TelegramBot\Api\BotApi($token);
############################################################################
$output         = json_decode(file_get_contents('php://input'), true);  // Получим то, что передано скрипту ботом в POST-сообщении и распарсим

$chat_id        = @$output['message']['chat']['id'];                    // идентификатор чата
$user_id        = @$output['message']['from']['id'];                    // идентификатор пользователя
$username       = @$output['message']['from']['username'];              // username пользователя
$first_name     = @$output['message']['chat']['first_name'];            // имя собеседника
$last_name      = @$output['message']['chat']['last_name'];             // фамилию собеседника
$chat_time      = @$output['message']['date'];                          // дата сообщения
$message        = @$output['message']['text'];                          // Выделим сообщение собеседника (регистр по умолчанию)
$photo          = $output['message']['photo'];
$msg            = mb_strtolower(@$output['message']['text'], "utf8");   // Выделим сообщение собеседника (нижний регистр)

$callback_query = @$output["callback_query"];                           // callback запросы
$data           = $callback_query['data'];                              // callback данные для обработки inline кнопок

$message_id     = $callback_query['message']['message_id'];             // идентификатор последнего сообщения
$chat_id_in     = $callback_query['message']['chat']['id'];             // идентификатор чата
############################################################################
switch ($dbid->getState($user_id)[0]["state"]) { // в переменной $message содержится сообщение, которое мы отправляем боту.
    case '0':
        $bot->sendMessage($user_id, "Здравствуйте ".$first_name);
        $dbid->queryUpdate($chat_id, 1);
        break;
    case '1':
        if($message == '/menu'){
        $bot->sendMessage($user_id,"Меню",[['Оформить заказ']]);
        $dbid->queryUpdate($chat_id, 2);
        }
        break;
    case '2':
        if($message == 'Оформить заказ'){
        $ans->sendCard(1, $bot, $user_id);
        $dbid->queryUpdate($chat_id, 3);
        }
        break;
    case '3':
        if ($photo!= null){
                        $image3=$photo;
                        $image=$photo[count($photo) - 1];
                        $a=$botApi->getFileUrl();
                        $file = $image3[0]['file_id'];
                        $fileName = $image3[0]['file_name'];
                        $b = '/';
                        $b1 = '/photos/';
                        $a1 = $a.$b.$file;
                        $a2 = $a.$b1;
                        $url = $a1;
                        //$dir=('tmp/');
                        $open=fopen(sys_get_temp_dir(),"w+"); //на всякий случай открываем тмп папку для чтения и записи
                        $write=fwrite($open,$url); //
                        $tmp = tempnam(sys_get_temp_dir(), 'img'); //первый способ добавления файла
                        //$botApi->getFile($file); //второй способ
                        //$botApi->downloadFile($file); //добавления файла
                        //$bot->sendMessage($user_id, "file ".$fileId);
                        //$content = file_get_contents($url); //третий способ
                        //tempnam(file_put_contents(sys_get_temp_dir().$url,$content),'mpx');//добавления файла
                        //$bot->sendMessage($user_id, "url ".$url);
                       // $parse->parse($url);
                        //$bot->sendMessage($user_id, "parse ".$parse[text]);
                       /* $qrcode = new QrReader($a1);
                        $text = $qrcode->text();       //  рабочая обработка qr-кода
                        $bot->sendMessage($user_id,"qr ".$text);*/
                        try {
                            $bot->sendMessage($user_id, "tmp ".$tmp);
                            (new HttpClient())->request('GET', $url, ['sink' => $tmp]);
                            $result = $ans->getContainer()->make(ZbarDecoder::class)->make($tmp);
                        } catch (Exception $e){
                            $bot->sendMessage($user_id, "catch ".$e->getMessage());
                            $bot->sendMessage($user_id, "catch ".$result);
                        } finally {
                            $dbid->queryUpdate($chat_id,4);
                            unlink($tmp); //после всех действий удаляем файл
                        }
                        if (isset($result) && $ans->isValidBarcodeScanResult($result)) {
                            $bot->sendMessage($user_id, "расшифровка прошла");
                            $bot->sendMessage($user_id, "результат ".$resalt);
                        }*/
        } else {
            $mass = $db->call($message);
            $c = count(($mass));
            $array = array();
            for ($e = 0; $e < $c-1; $e++) {
                $array[$e] = array($mass[$e]["card_type"]);
                            # добавить телефоны
                }
            if ($message == $mass[0]["card_number"]) {
                $dbid->queryUpdateCardNumber($chat_id,$message);
                $keyboardr = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($array, true);
                $botApi->sendMessage($chat_id, "Доступные карты", null, false, null, $keyboardr);
                $dbid->queryUpdate($chat_id,4);
                } else {
                $bot->sendMessage($chat_id, "Пользователя с таким номером карты не обнаружено");
                $bot->sendMessage($user_id,"Меню",[['Оформить заказ']]);
                $dbid->queryUpdate($chat_id,1);
                };
        }
        break;
        case '4':
            if ($message=='АЛМИ')
            {
                $card = $dbid->getCardNumber($chat_id);
                $mass = $db->call($card[0]['card_number']);
                $c = count(($mass));
                for ($key = 0; $key < count($mass)-1; $key++) {
                    if ($message == $mass[$key]["card_type"]) {
                        $ans->sendProductName(3, $bot, $chat_id);
                        $dbid->queryUpdatePhone($chat_id,$mass[$key]["phone_number"]);
                        $dbid->queryUpdateCardType($chat_id,$mass[$key]["card_type"]);
                        $dbid->queryUpdateEmail($chat_id,$mass[$key]["email"]);
                        $dbid->queryUpdate($chat_id,5);
                    }
                }
            }elseif($message=='Евроопт'){
                $card = $dbid->getCardNumber($chat_id);
                $mass = $db->call($card[0]['card_number']);
                $c = count(($mass));
                for ($key = 0; $key < count($mass)-1; $key++) {
                    if ($message == $mass[$key]["card_type"]) {
                        $ans->sendProductName(3, $bot, $chat_id);
                        $dbid->queryUpdatePhone($chat_id,$mass[$key]["phone_number"]);
                        $dbid->queryUpdateCardType($chat_id,$mass[$key]["card_type"]);
                        $dbid->queryUpdateEmail($chat_id,$mass[$key]["email"]);
                        $dbid->queryUpdate($chat_id,5);
                    }
                }
            }
            break;
        case '5':
            $dbid->queryUpdateProductName($chat_id,$message);
            $ans->sendProductCost(4, $bot, $chat_id);
            $dbid->queryUpdate($chat_id,6);
            break;
        case '6':
            $dbid->queryUpdateProductCost($chat_id,$message);
            $bot->sendMessage($user_id,"Выберите способ регистрации",[['Начислить баллы','Начислить комплименты'],['Начислить баллы и комплименты','Ничего не начислять']]);
            $dbid->queryUpdate($chat_id,7);
            break;
        case '7':
            $dbid->queryUpdateRewardType($chat_id,$message);
            $text ="В заказе вы указали следующие данные:
            номер карты - ".$dbid->getCardNumber($chat_id)[0]['card_number']."
            тип карты - ".$dbid->getCardType($chat_id)[0]['card_type']."
            email - ".$dbid->getEmail($chat_id)[0]['email']."
            название товара - ".$dbid->getProductName($chat_id)[0]['product_name']."
            цена товара - ".$dbid->getProductCost($chat_id)[0]['product_cost']."
            тип регистрации - ".$dbid->getRewardType($chat_id)[0]['reward_type']."
            Если хотите оформить заказ на эти данные нажмите Да.
            Если хотите вернуться к началу нажмите Нет";
            $bot->sendMessage($user_id,$text);
            $bot->sendMessage($user_id,"Подтвердить заказ?",[['Да'],['Нет']]);
            $dbid->queryUpdate($chat_id,8);
            break;
        case '8':
            if($message == 'Да'){
                $dbinfo = array(
                    'object_id'=> $dbid->queryCard($chat_id)[0]['object_id'],
                    'card_number'=>$dbid->getCardNumber($chat_id)[0]['card_number'],
                    'phone'=>$dbid->getPhone($chat_id)[0]['phone'],
                    'email'=>$dbid->getEmail($chat_id)[0]['email'],
                    'product_cost'=>$dbid->getProductCost($chat_id)[0]['product_cost'],
                    'product_name'=>$dbid->getProductName($chat_id)[0]['product_name'],
                    'reward_type'=>$dbid->getRewardType($chat_id)[0]['reward_type']
                );
                $result = $db->call2($dbinfo["object_id"],$dbinfo["email"], $dbinfo["product_name"], $dbinfo["product_cost"], $dbinfo["reward_type"]);
                if($result=='success')
                {
                    $bot->sendMessage($user_id,"Заказ успешно зарегистрирован");
                    $bot->sendMessage($user_id,"Меню",[['Оформить заказ']]);
                    $dbid->queryUpdate($chat_id, 2);
                } else{
                    $bot->sendMessage($user_id,"Сбой регистрации, попробуйте заного");
                    $bot->sendMessage($user_id,"Меню",[['Оформить заказ']]);
                    $dbid->queryUpdate($chat_id, 2);
                }
            } elseif($message == 'Нет'){
                $bot->sendMessage($user_id,"Меню",[['Оформить заказ']]);
                $dbid->queryUpdate($chat_id, 2);
            }
            break;
    default: $bot->sendMessage($user_id, "Неизвестная команда");
}
?>
