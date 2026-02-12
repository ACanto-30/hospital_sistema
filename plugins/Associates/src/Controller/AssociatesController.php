<?php
declare(strict_types=1);

namespace Associates\Controller;

use App\Controller\AppController;

class AssociatesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

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
            // Fallback o mensaje si no tiene perfil de asociado (ej: admin)
            $this->Flash->error('No se encontró un perfil de asociado vinculado a esta cuenta.');
        }

        $this->set(compact('associate'));
        $this->render('dashboard_associates');
    }
}
