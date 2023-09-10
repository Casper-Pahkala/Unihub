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
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $user = $result->getData();
            // $key = Configure::read('JWT.SecretKey'); // keep this secret and safe!
            // $payload = [
            //     "id" => $user->id,
            //     "email" => $user->email,
            //     "exp" => time() + (60*60) // expires in 1 hour
            // ];
            
            // $token = JWT::encode($payload, $key, 'HS256');
            
            // // You can set this token in a cookie, or send it in a response
            // // for the sake of simplicity, we're using a cookie
            // $cookie = new Cookie(
            //     'jwt',      // Name of the cookie
            //     $token,     // Value of the cookie
            //     new \DateTime('+1 hour'),  // Expiration time, you can adjust as needed
            //     '/',        // Path
            //     '',         // Domain 
            //     false,      // Secure (set to true if you're using HTTPS)
            //     true        // HttpOnly
            // );
            // $this->response = $this->response->withCookie($cookie);
            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'display', 'home'
            ]);

            return $this->redirect($redirect);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }
    

    public function register() {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
    
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Registration successful.'));
                return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
            }
            $this->Flash->error(__('Unable to register. Please try again.'));
        }
    
        $this->set('user', $user);
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
            return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }
    }
    
}
