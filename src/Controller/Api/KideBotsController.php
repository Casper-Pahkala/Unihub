<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\I18n\Time;
use App\Controller\FirebaseController;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
class KideBotsController extends AppController
{

    public function addBot() {
        $error = true;
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $this->loadModel('KideBots');
            $bot = $this->KideBots->newEntity($data);
            if ($this->KideBots->save($bot)) {
                $error = false;
            }
        }
        $json = json_encode([
            'error' => $error,
        ]);
        return $this->response->withType('application/json')->withStringBody($json);
    }

    public function getBots() {
        $error = false;
        $bots = [];
        if ($this->request->is('get')) {
            $this->loadModel('KideBots');
            $bots = $this->KideBots->find()
                ->enableHydration(false)
                ->toArray();

            foreach ($bots as $bot) {
                $now = new FrozenTime();
                $inTrade = true;
                if ($bot['trade_start_time']) {
                    $elapsedSeconds = $now->diffInSeconds($bot['trade_start_time']);
                    if ($elapsedSeconds >= Configure::read('Settings.TradeTimeOut')) {
                        $inTrade = false;
                    }
                } else {
                    $inTrade = false;
                }
                $databaseBot = $this->KideBots->get($bot['id']);
                $databaseBot->in_trade = $inTrade;
                if(!$this->KideBots->save($databaseBot)) {
                    $error = true;
                }
            }
        } else {
            $error = true;
        }
        $json = json_encode([
            'error' => $error,
            'bots' => $bots
        ]);
        return $this->response->withType('application/json')->withStringBody($json);
    }
}
