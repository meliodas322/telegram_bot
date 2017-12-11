<?php




use Telegram\Bot\Objects\PhotoSize;
use GuzzleHttp\Client as HttpClient;
use RobbieP\ZbarQrdecoder\ZbarDecoder;
use RobbieP\ZbarQrdecoder\Result\Result as DecodeResult;

/**
 * Trait ScansBarcode.
 *
 * @author Zer0
 */
trait ScansBarcode
{
    /**
     * Array of allowed barcode types.
     *
     * @var array
     */
    protected $validBarcodes = [];

    /**
     * Scan barcode from inbound photo message.
     *
     * @param  PhotoSize[]  $images        Photo from message.
     *
     * @return DecodeResult|false
     */
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

    /**
     * Check if barcode scan result is valid.
     *
     * @param  mixed  $result  Decoded result.
     *
     * @return boolean
     */
    protected function isValidBarcodeScanResult($result): bool
    {
        return ($result instanceof DecodeResult) &&
            (empty($this->validBarcodes) || in_array($result->format, $this->validBarcodes));
    }
}