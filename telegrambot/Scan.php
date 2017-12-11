<?php
    use TelegramBot\Api\BotApi;
    use Telegram\Bot\Objects\PhotoSize;
    use GuzzleHttp\Client as HttpClient;
    use RobbieP\ZbarQrdecoder\ZbarDecoder;
    

class Scan{
    protected $validBarcodes = [];
    
    public $ZbarDecoder = new RobbieP\ZbarQrdecoder\ZbarDecoder();
    
    public function scanBarcode($images)
    {

        $image = $images[count($images) - 1];
        $file = $this->getTelegram()->getFile(['file_id' => $image['file_id']]);
        $url = 'https://api.telegram.org/file/bot'.$this->getTelegram()->getAccessToken().'/'.$file->getFilePath();

        $tmp = tempnam(sys_get_temp_dir(), 'img');
        try {
            (new HttpClient())->request('GET', $url, ['sink' => $tmp]);
            $result = $this->getContainer()->make(ZbarDecoder::class)->make($tmp);
        } finally {
            unlink($tmp);
        }

        if (isset($result) && $this->isValidBarcodeScanResult($result)) {
            return $result;
        }

        return false;
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