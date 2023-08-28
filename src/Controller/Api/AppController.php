<?php
namespace App\Controller\Api;
use Cake\Controller\Controller;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;

class AppController extends Controller

{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    public function getBacchusMenu() {
        $http = new Client();
        $response = $http->get('https://bacchus.fi/lounas-brunssi/');
        if ($response->isOk()) {
            $data = $response;
            $htmlString = $data->getStringBody();
            $doc = new \DOMDocument();
            @$doc->loadHTML($htmlString);
            $xpath = new \DOMXPath($doc);
            $divsWithClass = $xpath->query("//div[contains(@class, 'rest_box_lunch')]");
            foreach ($divsWithClass as $key => $div) {
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1 == $key) {
                    $menu = $div->nodeValue;
                    $menu = str_replace('p ( M G )', '', $menu);
                    $menu = str_replace("\n", "", $menu);
                    $menu = str_replace(")", ")\n", $menu);
                    $menu = preg_replace('/\([^)]+\)/', '', $menu);
                    $menu = explode('Salaattipöytä – 12,70€', $menu)[1];
                    $menu = explode('Viikon vege', $menu)[0];
                    $menus = explode("\n", $menu);
                    $realMenus = [];
                    foreach ($menus as &$menu) {
                        $menu = trim($menu);
                        if (!empty($menu)) {
                            $menu = preg_replace('/\xc2\xa0/', '', $menu);
                            $realMenus[] = $menu;
                        }
                    }
                    return $this->response->withType('application/json')->withStringBody(json_encode($realMenus));
                }
            }
        }
        return $this->response->withType('application/json')->withStringBody(json_encode(['message' => 'Bacchus is not open today']));
    }

    public function commendRestaurant() {
        $this->request->allowMethod(['post']);
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->loadModel('RestaurantCommends');
            $commendModel = $this->RestaurantCommends->newEmptyEntity();
            $commendModel->ip = $ip;
            $commendModel->restaurant_id = $data['id'];
            $commendModel->date = FrozenTime::now()->format('Y-m-d');
            $message = 'failed';
            $query = $this->RestaurantCommends->find()
            ->where([
                'date' => FrozenTime::now()->format('Y-m-d'),
                'ip' => $ip
            ])
            ->first();
        
            $canCommend = empty($query);
            if ($canCommend && $this->RestaurantCommends->save($commendModel)) {
                $message = 'success';
            }
            return $this->response->withType('application/json')->withStringBody(json_encode(['message' => $message]));
        }
        return $this->response->withType('application/json')->withStringBody(json_encode(['message' => 'invalid data']));
    }

    public function addTicketToSell() {
        $this->request->allowMethod(['post']);
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $this->loadModel('Tickets');
            $ticketId = $data['ticket']['id'];
            $ticketModel = $this->Tickets->newEmptyEntity();
            $ticketModel->price = intval(trim($data['price']));
            $ticketApiUrl = "https://api.kide.app/api/views/wallet/product/$ticketId";
            
            $http = new Client();
            $botAuthToken = $data['bot_auth_token'];
            $headers = [
                'Host' => 'api.kide.app',
                'Authorization' => "Bearer $botAuthToken",
            ];
            $response = $http->get($ticketApiUrl, [], [
                'headers' => $headers
            ]);
            $body = $response->getBody()->getContents();
            $responseData = json_decode($body)->model[0];
            $eventId = $responseData->productId;
            // dd($eventId);
            $ticketModel->event_id = strval($eventId);
            $ticketModel->person_id = 1;
            $ticketModel->event_url = "https://kide.app/events/$eventId";
            $responseData = ['message' => 'save failed', 'success' => false];
            $ticketInfo = $this->getTicketInfo($ticketId);
            $ticketModel->company_name = $ticketInfo['company_name'];
            $ticketModel->event_name = $ticketInfo['event_name'];
            $ticketModel->event_image = $ticketInfo['event_image'];
            $ticketModel->event_from = $ticketInfo['event_date_from'];
            $ticketModel->event_to = $ticketInfo['event_date_to'];
            $ticketModel->location = $ticketInfo['location'];
            $ticketModel->ticket_id = strval($ticketId);
            if ($this->Tickets->save($ticketModel)) {

                $responseData = ['message' => 'saved succesfully', 'success' => true];
            }
            return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
        }
    }


    private function getTicketInfo($ticketId) {
        $http = new Client();
        $response = $http->get("https://api.kide.app/api/products/$ticketId");
        $returnData = [];
        if ($response->isOk()) {
            $data = json_decode($response->getStringBody(), true)['model'];
            dd($data);
            $returnData = [
                'ticket_id' => '',
                'company_name' => $data['company']['name'],
                'event_name' => $data['product']['name'],
                'event_image' => $data['product']['mediaFilename'],
                'event_date_from' => $data['product']['dateActualFrom'],
                'event_date_to' => $data['product']['dateActualUntil'],
                'location' => $data['product']['place'] . ', ' . $data['product']['streetAddress']
            ];
        }
        return $returnData;
    }
}
