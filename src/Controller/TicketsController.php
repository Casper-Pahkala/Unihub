<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TicketsController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }
   public function index() {
        $this->loadModel('Tickets');
        $tickets = $this->Tickets->find()
            ->where([
                'deleted' => 0,
                'sold' => 0
            ])
            ->enableHydration(false)
            ->toArray();

        $this->set(compact('tickets'));
   }

   public function sellTicket() {

   }

   public function myTickets() {
        $user = $this->Authentication->getIdentity();
        $this->loadModel('Tickets');
        $tickets = $this->Tickets->find()
            ->where([
                'deleted' => 0,
                'sold' => 0,
                'person_id' => $user->id
            ])
            ->enableHydration(false)
            ->toArray();

        $this->set(compact('tickets'));
   }
}
