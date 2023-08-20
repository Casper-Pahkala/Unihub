<?php
namespace App\Controller\Api;
use Cake\Controller\Controller;
use Cake\I18n\Time;
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
}
