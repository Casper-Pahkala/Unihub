<?php
namespace App\Controller\Api;
use Cake\Controller\Controller;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;
use Firebase\JWT\JWT;
use Cake\Core\Configure;
use Firebase\JWT\Key;
class UsersController extends AppController
{
    public function updateKideEmail() {
        $error = true;
        $message = '';
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (!$this->validateEmail($data['kide_email'])) {
                $message = 'Invalid email';
                return;
            } else {
                $user = JWT::decode($data['token'], new Key(Configure::read('JWT.SecretKey'), 'HS256'));
                $this->loadModel('Users');
                $user = $this->Users->get($user->id);
                if ($user) {
                    $user->kide_email = $data['kide_email'];
                    if ($this->Users->save($user)) {
                        $error = false;
                        $message = 'Saved Kide email succesfully';
                    }
                } else {
                    $message = 'User not found';
                }
            }
        }
        $responseData = [
            'error' => $error,
            'message' => $message
        ];
        return $this->response->withType('application/json')->withStringBody(json_encode($responseData));
    }

    private function validateEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}
