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
    if (!$identity) {
        $this->Flash->error('Debes iniciar sesión.');
        return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
    }

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
            'phone',
            'address',
            'email',
        ];

        $data = $this->request->getData();

        
        if (isset($data['email'])) {
            $data['email'] = trim((string)$data['email']);
        }

        $associate = $associatesTable->patchEntity(
            $associate,
            $data,
            ['fields' => $allowedFields]
        );

        
        if (!empty($data['email']) && isset($associate->user) && $associate->user) {
            $associate->user->email = $data['email'];
        }

        if ($associatesTable->save($associate, ['associated' => ['Users']])) {
            $this->Flash->success('Perfil actualizado correctamente.');
            return $this->redirect(['controller' => 'Associates', 'action' => 'dashboard']);
        }

        $this->Flash->error('No se pudo actualizar el perfil. Revisa los datos e inténtalo de nuevo.');
    }

    $this->set(compact('associate'));
}
}