<?php
declare(strict_types=1);

namespace App\Controller;
use League\OAuth2\Client\Provider\Google;
use Cake\Core\Configure;
use Firebase\JWT\JWT;
use Cake\Http\Cookie\Cookie;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize(): void {
        parent::initialize();
    }

    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'register']);
    }

    public function login() {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $result->getData();
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'display', 'home'
            ]);
            return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }
    

    public function register() {

        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $result->getData();
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'display', 'home'
            ]);
            return $this->redirect($redirect);
        }
        $userToCreate = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $userToCreate = $this->Users->patchEntity($userToCreate, $data);
    
            if (!$data || !$data['confirm_password'] || !$data['password'] || $data['confirm_password'] != $data['password']) {

            } else {
                if ($this->Users->save($userToCreate)) {
                    
                    $this->Flash->success(__('Registration successful.'));

                    // Log the user in
                    $result = $this->Authentication->getResult();
                    if (!$result->isValid()) {
                        // Use the user data to build the identity data and set the user as logged in
                        $this->Authentication->setIdentity($userToCreate);

                        // Redirect to the desired location after registration and login
                        return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
                    }
                }
            }
            $this->Flash->error('Ei voitu luoda käyttäjää');
        }
    
        $this->set('userToCreate', $userToCreate);
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        // Regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
            $expiredCookie = new \Cake\Http\Cookie\Cookie(
                'jwt', // Name of the cookie
                '', // Empty value
                new \DateTime('-1 day'), // Setting expiration to 1 day in the past
                '/', // Path
                ''  // Domain
            );
            $this->response = $this->response->withCookie($expiredCookie);
            // Redirect to any URL after logout
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function account() {
        
    }

    public function additionalInfo() {
        $user = $this->Authentication->getIdentity();
        if ($user['username']) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            if (!$username) {
                $this->Flash->error('Käyttäjänimen tallennus epäonnistui');
                return;
            }
            $username = trim($username);
            $user = $this->Users->get($user->id);
            $user->username = $username;
            $users = $this->Users->find()
                ->where([
                    'username' => $username
                ])
                ->enableHydration(false)
                ->toArray();
            if (!empty($users)) {
                $this->set('error', 'Käyttäjänimi on jo olemassa');
                return;
            }
            
            if ($this->Users->save($user)) {
                // $this->Flash->success('');
                $this->Authentication->setIdentity($user);
                return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
            }
            
            $this->Flash->error('Käyttäjänimen tallennus epäonnistui');
        }
    }
    
}
