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
use League\OAuth2\Client\Provider\Google;
use Cake\Core\Configure;
use Firebase\JWT\JWT;
use Cake\ORM\Entity;

use Cake\Http\Cookie\Cookie;
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
        $this->loadComponent('Authentication.Authentication');
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

        // August
        $cachedMenu = Cache::read('august-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 3,
                    'name' => 'August',
                    'menu' => $cachedMenu,
                    'image' => '/img/august.png',
                    'link' => 'https://augustrestaurant.fi/'
                ];
            }
        } else {
            $menu = $this->getAugustMenu();
            if ($menu) {
                Cache::write('august-' . FrozenTime::now()->format('Y-m-d'), $menu, 'menus');
                $menus[] = [
                    'id' => 3,
                    'name' => 'August',
                    'menu' => $menu,
                    'image' => '/img/august.png',
                    'link' => 'https://augustrestaurant.fi/'
                ];
            } else {
                Cache::write('august-' . FrozenTime::now()->format('Y-m-d'), 'closed', 'menus');
            }
        }

        // Bacchus
        $cachedMenu = Cache::read('bacchus-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 5,
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
                    'id' => 5,
                    'name' => 'Bacchus',
                    'menu' => $bacchusMenu,
                    'image' => '/img/bacchus.jpg',
                    'link' => 'https://bacchus.fi/lounas-brunssi/'
                ];
            } else {
                Cache::write('bacchus-' . FrozenTime::now()->format('Y-m-d'), 'closed', 'menus');
            }
        }

        $cachedMenu = Cache::read('w33-' . FrozenTime::now()->format('Y-m-d'), 'menus');
        if ($cachedMenu) {
            if ($cachedMenu != 'closed') {
                $menus[] = [
                    'id' => 4,
                    'name' => 'W33',
                    'menu' => $cachedMenu,
                    'image' => '/img/w33.png',
                    'link' => 'https://www.restaurangw33.com/lunch'
                ];
            }
        } else {
            $w33Menu = $this->getW33Menu();
            if ($w33Menu) {
                Cache::write('w33-' . FrozenTime::now()->format('Y-m-d'), $w33Menu, 'menus');
                $menus[] = [
                    'id' => 4,
                    'name' => 'W33',
                    'menu' => $w33Menu,
                    'image' => '/img/w33.png',
                    'link' => 'https://www.restaurangw33.com/lunch'
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
            '3' => 0,
            '4' => 0,
            '5' => 0
        ];
        foreach ($restaurantCommends as $commend) {
            $commends[$commend['restaurant_id']] += 1;
        }
        arsort($commends);
        usort($menus, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });
        usort($menus, function($a, $b) use ($commends) {
            return $commends[$b['id']] <=> $commends[$a['id']];
        });
        if (!empty($menus)) {
            $topRestaurant = $menus[0]['id'];
        } else {
            $topRestaurant = null;
        }
        $user = $this->Authentication->getIdentity();
        if ($user) {
            $this->loadModel('Users');
            $dbUser = $this->Users->get($user->id);
            // $mergedUser = new Entity(array_merge(get_object_vars($user), $dbUser));
            $this->Authentication->setIdentity($dbUser);
            $user = $this->Authentication->getIdentity();
        }
        $token = $this->request->getCookie('jwt');
        if (!$token && $user) {
            $token = $this->addTokenCookie($user);
        }
        if (!$user) {
            $expiredCookie = new \Cake\Http\Cookie\Cookie(
                'jwt', // Name of the cookie
                '', // Empty value
                new \DateTime('-1 day'), // Setting expiration to 1 day in the past
                '/', // Path
                ''  // Domain
            );
            $this->response = $this->response->withCookie($expiredCookie);
        }

        $this->set(compact('menus', 'canCommend', 'topRestaurant', 'user', 'token'));

        $this->loadModel('Users');
        if ($user) {
            if (!$user['username']) {
                $controllerName = $this->request->getParam('controller');
                $actionName = $this->request->getParam('action');
                $dontShowNavigation = true;
                $onBoarding = true;
                $this->set(compact('dontShowNavigation', 'onBoarding'));
                if ($controllerName != 'Users' || $actionName != 'additionalInfo') {
                    $this->redirect(['controller' => 'Users', 'action' => 'additionalInfo']);
                }
            }
        }
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
                        if (!empty($menu) && !str_contains($menu, 'Viikon Vege') && $menu != '') {
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
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1 == $key) {
                    $menu = $menu->nodeValue;
                    $menus = explode("\n", $menu);
                    $realMenus = [];
                    $i = 0;
                    foreach ($menus as &$menu) {
                        $i++;
                        $menu = trim($menu);
                        if (!empty($menu)) {
                            $menu = preg_replace('/\xc2\xa0/', '', $menu);
                            if ($menu != '') {
                                $realMenus[] = $menu . (($i !== count($menus) - 1) ? ' 2,95€' : ' 4,50€');
                            }
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
                    $i = 0;
                    foreach ($itemnameElements as $itemnameElement) {
                        $i++;
                        $item = trim($itemnameElement->nodeValue);
                        $parts  = explode(' / ', $item);
                        $correctName = trim($parts[0]);
                        if (isset($parts[1])) {
                            $additionalParts = explode(" (", $parts[1], 2);
                            if (isset($additionalParts[1])) {
                                $additionalInfo = " (" . $additionalParts[1];
                                $correctName .= " " . trim($additionalInfo);
                            }
                        }
                        $item = trim($correctName);
                        if ($item != '') {
                            $realMenus[] = $item . (($i < count($itemnameElements)) ? ' 2,95€' : ' 5,60€');
                        }
                            
                    }
                    return $realMenus;
                }
            }

        }
        return false;
    }

    private function getAugustMenu() {
        $http = new Client();
        $response = $http->get('https://augustrestaurant.fi/');
        if ($response->isOk()) {
            $data = $response;
            $htmlString = $data->getStringBody();
            $doc = new \DOMDocument();
            @$doc->loadHTML($htmlString);
            $xpath = new \DOMXPath($doc);
            $tables = $xpath->query("//div[contains(@class, 'et_pb_text_inner')]//table");

            foreach ($tables as $key => $menu) {
                if (Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1 == $key) {
                    $rows = $xpath->query(".//tr", $menu);
                    $weekdays = [
                        'Maanantai',
                        'Tiistai',
                        'Keskiviikko',
                        'Torstai',
                        'Perjantai'
                    ];
                    $menus = [];
                    foreach ($rows as $row) {
                        $row = trim($row->nodeValue);
                        $row  = explode('/ ', $row)[0];
                        if ($row != '' && !in_array($row, $weekdays)) {
                            $menus[] = $row . ' ';
                        }
                    }
                    return $menus;
                }
            }
        }
        return false;
    }

    private function getW33Menu() {
        $http = new Client();
        $response = $http->get('https://www.restaurangw33.com/lunch');
        if ($response->isOk()) {
            $htmlString = $response->getStringBody();
            $doc = new \DOMDocument();
            @$doc->loadHTML($htmlString);
            $xpath = new \DOMXPath($doc);
            $menuMeshIds = [
                'comp-l7a9plg5inlineContent-gridContainer',
                'comp-l7a9so0rinlineContent-gridContainer',
                'comp-l7a9v32o1inlineContent-gridContainer',
                'comp-llwh166pinlineContent-gridContainer',
                'comp-l7a9z9bsinlineContent-gridContainer'
            ];
            $menuBody = $xpath->query('//div[@data-mesh-id="' . $menuMeshIds[Time::now()->setTimezone('Europe/Helsinki')->format('N') - 1] . '"]');
            $menuElements = $xpath->query('div[@data-testid="richTextElement"]', $menuBody[0]);

            $menuItems = [];
            foreach ($menuElements as $key => $menuItem) {
                $menuItems[] = $menuItem->nodeValue . ' 2,95€';
            }
            return $menuItems;
        }
        return false;
    }

    private function addTokenCookie($user) {
        $key = Configure::read('JWT.SecretKey'); // keep this secret and safe!
        $payload = [
            "id" => $user->id,
            "email" => $user->email,
            "exp" => time() + (60*60) // expires in 1 hour
        ];
        
        $token = JWT::encode($payload, $key, 'HS256');
        $cookie = new Cookie(
            'jwt',      // Name of the cookie
            $token,     // Value of the cookie
            new \DateTime('+1 hour'),  // Expiration time, you can adjust as needed
            '/',        // Path
            '',         // Domain 
            true,      // Secure (set to true if you're using HTTPS)
            true        // HttpOnly
        );
        $this->response = $this->response->withCookie($cookie);
        return $token;
    }
}
