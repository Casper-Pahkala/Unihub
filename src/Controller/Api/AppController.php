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
}
