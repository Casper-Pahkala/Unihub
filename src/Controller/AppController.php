<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;
use Cake\Cache\Cache;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');

        $menus = [];

        // Åbo
        $cachedMenu = Cache::read('åbo-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 1,
                    'name' => 'Åbo Akademi',
                    'menu' => $cachedMenu,
                    'image' => '/img/åbo.png',
                    'link' => 'https://abo-academi.ravintolapalvelut.iss.fi/abo-academi/lounaslista'
                ];
            }
        } else {
            $menu = $this->getÅboMenu();
            if ($menu) {
                Cache::write('åbo-' . FrozenTime::now()->format('Y-m-d'), $menu, 'menus');
                $menus[] = [
                    'id' => 1,
                    'name' => 'Åbo Akademi',
                    'menu' => $menu,
                    'image' => '/img/åbo.png',
                    'link' => 'https://abo-academi.ravintolapalvelut.iss.fi/abo-academi/lounaslista'
                ];
            } else {
                Cache::write('åbo-' . FrozenTime::now()->format('Y-m-d'), 'closed', 'menus');
            }
        }

        // Åbo
        $cachedMenu = Cache::read('cotton-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 2,
                    'name' => 'Cotton Club',
                    'menu' => $cachedMenu,
                    'image' => '/img/cotton.png',
                    'link' => 'https://www.cotton-club.fi/ruokalista'
                ];
            }
        } else {
            $menu = $this->getCottonMenu();
            if ($menu) {
                Cache::write('cotton-' . FrozenTime::now()->format('Y-m-d'), $menu, 'menus');
                $menus[] = [
                    'id' => 2,
                    'name' => 'Cotton Club',
                    'menu' => $menu,
                    'image' => '/img/cotton.png',
                    'link' => 'https://www.cotton-club.fi/ruokalista'
                ];
            } else {
                Cache::write('cotton-' . FrozenTime::now()->format('Y-m-d'), 'closed', 'menus');
            }
        }

        // Bacchus
        $cachedMenu = Cache::read('bacchus-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 3,
                    'name' => 'Bacchus',
                    'menu' => $cachedMenu,
                    'image' => '/img/bacchus.jpg',
                    'link' => 'https://bacchus.fi/lounas-brunssi/'
                ];
            }
        } else {
            $bacchusMenu = $this->getBacchusMenu();
            if ($bacchusMenu) {
                Cache::write('bacchus-' . FrozenTime::now()->format('Y-m-d'), $bacchusMenu, 'menus');
                $menus[] = [
                    'id' => 3,
                    'name' => 'Bacchus',
                    'menu' => $bacchusMenu,
                    'image' => '/img/bacchus.jpg',
                    'link' => 'https://bacchus.fi/lounas-brunssi/'
                ];
            } else {
                Cache::write('bacchus-' . FrozenTime::now()->format('Y-m-d'), 'closed', 'menus');
            }
        }

        $ip = $_SERVER['REMOTE_ADDR'];

        $this->loadModel('RestaurantCommends');

        $query = $this->RestaurantCommends->find()
            ->where([
                'date' => FrozenTime::now()->format('Y-m-d'),
                'ip' => $ip
            ])
            ->first();
        
        $canCommend = empty($query);

        $restaurantCommends = $this->RestaurantCommends->find()
            ->select(['restaurant_id'])
            ->where(['date' => FrozenTime::now()->setTimezone('Europe/Helsinki')->format('Y-m-d')])
            ->enableHydration(false)
            ->toArray();

        $commends = [
            '1' => 0,
            '2' => 0,
            '3' => 0
        ];
        foreach ($restaurantCommends as $commend) {
            $commends[$commend['restaurant_id']] += 1;
        }
        arsort($commends);
        $topRestaurant = reset($commends);
        usort($menus, function($a, $b) use ($commends) {
            return $commends[$b['id']] <=> $commends[$a['id']];
        });
        $this->set(compact('menus', 'canCommend', 'topRestaurant'));
    }



    private function getBacchusMenu() {
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
                //Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1 == $key) {
                    $menu = $div->nodeValue;
                    // $menu = str_replace('p ( M G )', '', $menu);
                    $menu = str_replace("\n", "", $menu);
                    $menu = str_replace(")", ")\n", $menu);
                    // $menu = preg_replace('/\([^)]+\)/', '', $menu);
                    $menu = explode('Salaattipöytä – 12,70€', $menu)[1];
                    $menu = explode('Viikon vege', $menu)[0];
                    $menus = explode("\n", $menu);
                    $realMenus = [];
                    foreach ($menus as &$menu) {
                        $menu = trim($menu);
                        if (!empty($menu) && !str_contains($menu, 'Viikon Vege')) {
                            $menu = preg_replace('/\xc2\xa0/', '', $menu);
                            $realMenus[] = $menu;
                        }
                    }
                    return $realMenus;
                }
            }
        }
        return false;
    }

    private function getÅboMenu() {
        $http = new Client();
        $response = $http->get('https://abo-academi.ravintolapalvelut.iss.fi/abo-academi/lounaslista');
        if ($response->isOk()) {
            $data = $response;
            $htmlString = $data->getStringBody();
            $doc = new \DOMDocument();
            @$doc->loadHTML($htmlString);
            $xpath = new \DOMXPath($doc);
            $menuBody = $xpath->query("//div[contains(@class, 'article__body')]");
            $pElements = $xpath->query('.//p', $menuBody[0]);
            foreach ($pElements as $key => $menu) {
                //Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1 == $key) {
                    $menu = $menu->nodeValue;
                    // $menu = str_replace('p ( M G )', '', $menu);
                    // $menu = str_replace(")", ")\n", $menu);
                    // $menu = preg_replace('/\([^)]+\)/', '', $menu);
                    // $menu = explode('Salaattipöytä – 12,70€', $menu)[1];
                    // $menu = explode('Viikon vege', $menu)[0];
                    $menus = explode("\n", $menu);
                    $realMenus = [];
                    foreach ($menus as &$menu) {
                        $menu = trim($menu);
                        if (!empty($menu)) {
                            $menu = preg_replace('/\xc2\xa0/', '', $menu);
                            $realMenus[] = $menu;
                        }
                    }
                    return $realMenus;
                }
            }
        }
        return false;
    }

    private function getCottonMenu() {
        $http = new Client();
        $response = $http->get('https://www.cotton-club.fi/ruokalista');
        if ($response->isOk()) {
            $data = $response;
            $htmlString = $data->getStringBody();
            $doc = new \DOMDocument();
            @$doc->loadHTML($htmlString);
            $xpath = new \DOMXPath($doc);
            $priceLists = $xpath->query("//div[contains(@class, 'pricelist')]");

            foreach ($priceLists as $key => $priceList) {
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') == $key) {
                    $itemnameElements = $xpath->query("./*[2]//span[contains(@class, 'itemname')]", $priceList);
                    $realMenus = [];
                    foreach ($itemnameElements as $itemnameElement) {
                        $item = trim($itemnameElement->nodeValue);
                        // $lg = explode(' (', $item);
                        $parts  = explode(' / ', $item);
                        $correctName = trim($parts[0]);

                        // $item = $item . ' (' . $lg;
                        if (isset($parts[1])) {
                            $additionalParts = explode("(", $parts[1], 2);
                            if (isset($additionalParts[1])) {
                                $additionalInfo = "(" . $additionalParts[1];
                                $correctName .= " " . trim($additionalInfo);
                            }
                        }
                        $item = trim($correctName);
                        $realMenus[] = $item;
                    }
                    return $realMenus;
                }
            }

        }
        return false;
    }
}
