<?

use  GuzzleHttp\Client;

class TelegramBot
{
    protected $token='495486838:AAFHlbUVP3hOfVIEkrQTQEAEWc43pyEkvmk';

    protected $updateId;

    protected function query($method,$params = [])
    {
        $url = "https://api.telegram.org/bot";

        $url .=$this->token;

        $url .= "/" .$method;

        if (!empty($params)){
            $url .= "?" . http_build_query($params);
        }

        $client = new Client([
            'base_uri' =>$url
        ]);

        $result = $client->request('GET');

        return json_decode($result->getBody());

    }

    public function getUpdates()
    {
        $response = $this->query('getUpdates',[
            'offset' => $this->updateId + 1
        ]);

        if (!empty($response->result)) {
            $this->updateId =$response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;

    }

    public function sendMessage($chat_id,$text)
    {

        $response = $this->query('SendMessage',[
            'text' => $text,
            'chat_id' => $chat_id
        ]);
        return $response;
    }

}


?>