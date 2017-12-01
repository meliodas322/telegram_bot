<?php

class keyboard
{
    public function sendKeyboard($chat_id)
    {
        $reply='Меню';
        $url = "https://api.telegram.org/bot495486838:AAFHlbUVP3hOfVIEkrQTQEAEWc43pyEkvmk/sendMessage";
        $keyboard = array(
            "keyboard" => /*array(*/array(array(array(
                "text" => "balls"
            ),
                array(
                    "text" => "Сделать заказ"
                ),
                array(
                    "text" => "awdsvzx"
                ),
                /*array(
                    "text" => "Ничего не начислять"
                )*/

            )),
            "one_time_keyboard" => true, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        );

        $postfields = array(
            'chat_id' => "$chat_id",
            'text' => "$reply",
            'reply_markup' => json_encode($keyboard)
        );

        print_r($postfields);
        if (!$curld = curl_init())
        {
            exit;
        }

        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curld);

        curl_close($curld);
        return ($postfields);
    }

}
