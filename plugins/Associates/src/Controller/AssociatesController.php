<?php
declare(strict_types=1);

namespace Associates\Controller;

use App\Controller\AppController;

class AssociatesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

       
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
    }

    public function dashboard()
    {
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        $associatesTable = $this->fetchTable('Associates.Associates');

        $associate = $associatesTable->find()
            ->where(['user_id' => $identity->getIdentifier()])
            ->contain([
                'Users',
                'InsurancePlans',
                'Payments' => [
                    'PaymentDetails' => [
                        'PaymentMethods',
                        'PaymentStatuses'
                    ]
                ]
            ])
            ->first();

        if (!$associate) {
           
            $this->Flash->error('No se encontró un perfil de asociado vinculado a esta cuenta.');
        }

        $this->set(compact('associate'));
        $this->render('dashboard_associates');
    }

    
    public function editProfile()
    {
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        $associatesTable = $this->fetchTable('Associates.Associates');

        $associate = $associatesTable->find()
            ->where(['user_id' => $identity->getIdentifier()])
            ->contain(['InsurancePlans', 'Users'])
            ->first();

        if (!$associate) {
            $this->Flash->error('No se encontró un perfil de asociado vinculado a esta cuenta.');
            return $this->redirect(['controller' => 'Associates', 'action' => 'dashboard']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            
            $allowedFields = [
                'id_card',
                'first_name',
                'last_name',
                'phone',
                'email',
                'address',
                'birth_date',
            ];

            $associate = $associatesTable->patchEntity($associate, $this->request->getData(), [
                'fields' => $allowedFields
            ]);

            if ($associatesTable->save($associate)) {
                $this->Flash->success('Perfil actualizado correctamente.');
                return $this->redirect(['controller' => 'Associates', 'action' => 'dashboard']);
            }

            $this->Flash->error('No se pudo actualizar el perfil. Revisa los datos e inténtalo de nuevo.');
        }

        $this->set(compact('associate'));
        
    }
}
