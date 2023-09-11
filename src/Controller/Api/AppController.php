<?php
namespace App\Controller\Api;
use Cake\Controller\Controller;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;
use Firebase\JWT\JWT;
use Cake\Core\Configure;
use Firebase\JWT\Key;
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
            $ticket = $data['ticket'];
            $ticketId = $ticket['id'];
            $ticketModel = $this->Tickets->newEmptyEntity();
            $ticketModel->price = intval(trim($data['price']));
            $ticketApiUrl = "https://api.kide.app/api/views/wallet/product/$ticketId";
            
            $botAuthToken = $data['bot_auth_token'];
            $headers = [
                'Host' => 'api.kide.app',
                'Authorization' => "Bearer $botAuthToken",
            ];
            $responseData = ['message' => 'save failed', 'success' => false];
           
            $ticketInfo = $this->getTicketInfo($ticketId, $headers);
            if (!$ticketInfo) {
                $responseData = ['message' => 'ticket info fetch failed', 'success' => false, 'info' => $ticketId];
                return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
            }
            $eventId = $ticketInfo['product_id'];
            $ticketModel->event_id = $eventId;
            $ticketModel->person_id = $data['user']['id'];
            $ticketModel->event_url = $ticket['productType'] == 1 ? "https://kide.app/events/$eventId" : "https://kide.app/products/$eventId";
            $ticketModel->company_name = $ticketInfo['company_name'];
            $ticketModel->event_name = $ticketInfo['event_name'];
            $ticketModel->event_image = $ticketInfo['event_image'];
            $ticketModel->event_from = $ticketInfo['event_date_from'];
            $ticketModel->event_to = $ticketInfo['event_date_to'];
            $ticketModel->location = $ticketInfo['location'];
            $ticketModel->variant_id = $ticketInfo['variant_id'];
            $ticketModel->variant_name = $ticketInfo['variant_name'];
            $ticketModel->original_price = $ticketInfo['real_price'];
            $ticketModel->ticket_id = $ticketInfo['ticket_id'];
            $ticketModel->bot_id = $data['bot_id'];
            $ticket = $this->Tickets->find()
                ->where([
                    'variant_id' => $ticketInfo['variant_id'],
                    'deleted' => 0,
                    'sold' => 0,
                ])
                ->first();
            if ($ticket) {
                $responseData = ['message' => 'Ticket already exists', 'success' => false];
                return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
            }
            if ($this->Tickets->save($ticketModel)) {
                $responseData = ['message' => 'saved succesfully', 'success' => true];
            }
            return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
        }
    }


    private function getTicketInfo($ticketId, $headers) {
        $http = new Client();
        $ticketApiUrl = "https://api.kide.app/api/views/wallet/product/$ticketId";
        $response = $http->get($ticketApiUrl, [], [
            'headers' => $headers
        ]);
        $returnData = [];
        if ($response->isOk()) {
            $data = json_decode($response->getStringBody(), true)['model'][0];
            $returnData = [
                'product_id' => $data['productId'],
                'company_name' => $data['companyName'],
                'event_name' => $data['productName'],
                'event_image' => $data['productMediaFilename'],
                'event_date_from' => $data['dateActualFrom'],
                'event_date_to' => $data['dateActualUntil'],
                'location' => $data['place'] . ', ' . $data['streetAddress'],
                'variant_id' => $data['variantId'],
                'variant_name' => $data['variantName'],
                'real_price' => $data['pricePerItem'],
                'ticket_id' => $data['id']
            ];
        } else {
            return false;
        }
        return $returnData;
    }

    public function getUser($token) {
        $responseData = [
            'error' => true,
            'user' => null,
            'message' => ''
        ];
        try {
            $algorithms = ['HS256'];
            $decoded = JWT::decode($token, new Key(Configure::read('JWT.SecretKey'), 'HS256'));
            $responseData = [
                'error' => false,
                'user' => $decoded
            ];
            
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
    }

    public function sendTicketBackToUser() {
        

        $this->request->allowMethod(['post']);
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = JWT::decode($data['token'], new Key(Configure::read('JWT.SecretKey'), 'HS256'));
            $payload = [
                "variantId" => $data['variant_id'],
                "userInventoryId" => $data['ticket_id'],
                "recipientEmail" => 'casper.pahkala@gmail.com'
            ];

            $botAuthToken = $data['bot_auth_token'];
            $headers = [
                'Host' => 'api.kide.app',
                'Authorization' => "Bearer $botAuthToken",
                'Content-Type' => 'application/json'
            ];
            $http = new Client();
            $apiUrl = "https://api.kide.app/api/users/transferUserVariant";
            $response = $http->post($apiUrl, json_encode($payload), [
                'headers' => $headers
            ]);
            $returnData = [
                'error' => true
            ];
            if ($response->isOk()) {
                // $data = json_decode($response->getStringBody(), true)['model'][0];
                $this->loadModel('Tickets');
                $ticket = $this->Tickets->find()
                ->where([
                    'variant_id' => $data['variant_id'],
                    'deleted' => 0,
                    'sold' => 0,
                ])
                ->first();
                $ticket->deleted = true;
                if ($this->Tickets->save($ticket)) {
                    $returnData['error'] = false;
                }
            }
            return $this->response->withType('application/json')->withStringBody(json_encode($returnData));
        }
    }
}
