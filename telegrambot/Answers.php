<?php





include('vendor/autoload.php');
require_once 'constants.php';

class Answers
{
    protected $validBarcodes = [];
    public static function sendProductName($perem,$bot,$update){
        if ($perem == 3){
           $result=$bot->sendMessage($update, "Введите название товара");
        } else {$result=$bot->sendMessage($update, "Ошибка при заполении поля название товара, обратитесь к администратору");};
        return $result;
    }
    public function sendProductCost($perem,$bot,$update){
        if ($perem == 4){
            $result=$bot->sendMessage($update, "Введите цену товара");
        } else {$result=$bot->sendMessage($update, "Ошибка при заполении поля цена товара, обратитесь к администратору");};
        return $result;
    }
    public function sendEmail($perem,$bot,$update,$array){
        if ($perem == 4){
            $array[$perem-1] = $update->message->text;
           // echo $array[$perem-1];
            $result=$bot->sendMessage($update->message->chat->id, "Введите email");
        } else {$result=$bot->sendMessage($update->message->chat->id, "Ошибка при заполении поля email, обратитесь к администратору");};
        return $result;
    }
    public function sendCard($perem,$bot,$update){

        if ($perem == 1){
            $result=$bot->sendMessage($update, "Введите код карты");
        } else {$result=$bot->sendMessage($update, "Ошибка карта");};
        return $result;
    }
    public function choiceCard($bot,$update,$name){
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array($name)), true);
        $result=$bot->sendMessage($update, "Доступные карты", null, false, null, $keyboard);
        return $result;
    }
    function decode($variable, $hints=null){
        if(is_array($variable)){
            return $this->decodeImage($variable,$hints);
        }else{
            die('decode error Decoder.php');
        }


    }

    /**
     * <p>Convenience method that can decode a QR Code represented as a 2D array of booleans.
     * "true" is taken to mean a black module.</p>
     *
     * @param image booleans representing white/black QR Code modules
     * @param hints decoding hints that should be used to influence decoding
     * @return text and bytes encoded within the QR Code
     * @throws FormatException if the QR Code cannot be decoded
     * @throws ChecksumException if error correction fails
     */
    public function decodeImage($image, $hints=null)
    {
        $dimension = count($image);
        $bits = new BitMatrix($dimension);
        for ($i = 0; $i < $dimension; $i++) {
            for ($j = 0; $j < $dimension; $j++) {
                if ($image[$i][$j]) {
                    $bits->set($j, $i);
                }
            }
        }
        return $this->decode($bits, $hints);
    }
    public function getContainer()
    {
    return $this->getTelegram()->getContainer();
    }
    public function isValidBarcodeScanResult($result): bool
    {
        return ($result instanceof DecodeResult) &&
            (empty($this->validBarcodes) || in_array($result->format, $this->validBarcodes));
    }



}