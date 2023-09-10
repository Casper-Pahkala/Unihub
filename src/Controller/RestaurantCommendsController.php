<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * RestaurantCommends Controller
 *
 * @method \App\Model\Entity\RestaurantCommend[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RestaurantCommendsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $restaurantCommends = $this->paginate($this->RestaurantCommends);

        $this->set(compact('restaurantCommends'));
    }

    /**
     * View method
     *
     * @param string|null $id Restaurant Commend id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $restaurantCommend = $this->RestaurantCommends->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('restaurantCommend'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $restaurantCommend = $this->RestaurantCommends->newEmptyEntity();
        if ($this->request->is('post')) {
            $restaurantCommend = $this->RestaurantCommends->patchEntity($restaurantCommend, $this->request->getData());
            if ($this->RestaurantCommends->save($restaurantCommend)) {
                $this->Flash->success(__('The restaurant commend has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The restaurant commend could not be saved. Please, try again.'));
        }
        $this->set(compact('restaurantCommend'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Restaurant Commend id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $restaurantCommend = $this->RestaurantCommends->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $restaurantCommend = $this->RestaurantCommends->patchEntity($restaurantCommend, $this->request->getData());
            if ($this->RestaurantCommends->save($restaurantCommend)) {
                $this->Flash->success(__('The restaurant commend has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The restaurant commend could not be saved. Please, try again.'));
        }
        $this->set(compact('restaurantCommend'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Restaurant Commend id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $restaurantCommend = $this->RestaurantCommends->get($id);
        if ($this->RestaurantCommends->delete($restaurantCommend)) {
            $this->Flash->success(__('The restaurant commend has been deleted.'));
        } else {
            $this->Flash->error(__('The restaurant commend could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
