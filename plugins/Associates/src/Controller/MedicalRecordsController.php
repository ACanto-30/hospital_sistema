<?php
declare(strict_types=1);

namespace Associates\Controller;

use Associates\Controller\AppController;

/**
 * MedicalRecords Controller
 *
 * @property \Associates\Model\Table\MedicalRecordsTable $MedicalRecords
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class MedicalRecordsController extends AppController
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->MedicalRecords->find();
        $query = $this->Authorization->applyScope($query);
        $medicalRecords = $this->paginate($query);

        $this->set(compact('medicalRecords'));
    }

    /**
     * View method
     *
     * @param string|null $id Medical Record id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $medicalRecord = $this->MedicalRecords->get($id, contain: []);
        $this->Authorization->authorize($medicalRecord);
        $this->set(compact('medicalRecord'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $medicalRecord = $this->MedicalRecords->newEmptyEntity();
        $this->Authorization->authorize($medicalRecord);
        if ($this->request->is('post')) {
            $medicalRecord = $this->MedicalRecords->patchEntity($medicalRecord, $this->request->getData());
            if ($this->MedicalRecords->save($medicalRecord)) {
                $this->Flash->success(__('The medical record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The medical record could not be saved. Please, try again.'));
        }
        $this->set(compact('medicalRecord'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Medical Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $medicalRecord = $this->MedicalRecords->get($id, contain: []);
        $this->Authorization->authorize($medicalRecord);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $medicalRecord = $this->MedicalRecords->patchEntity($medicalRecord, $this->request->getData());
            if ($this->MedicalRecords->save($medicalRecord)) {
                $this->Flash->success(__('The medical record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The medical record could not be saved. Please, try again.'));
        }
        $this->set(compact('medicalRecord'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Medical Record id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $medicalRecord = $this->MedicalRecords->get($id);
        $this->Authorization->authorize($medicalRecord);
        if ($this->MedicalRecords->delete($medicalRecord)) {
            $this->Flash->success(__('The medical record has been deleted.'));
        } else {
            $this->Flash->error(__('The medical record could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
