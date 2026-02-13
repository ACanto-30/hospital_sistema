<?php
declare(strict_types=1);

namespace Users\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Login, register públicos
        $this->Authentication->allowUnauthenticated(['login', 'register']);
    }



    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Normalizar email en POST
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            if ($email) {
                $cleanEmail = strtolower(trim((string) $email));
                $this->request = $this->request->withParsedBody(array_merge(
                    (array) $this->request->getParsedBody(),
                    ['email' => $cleanEmail]
                ));
            }
        }
    }

    /* ==========================
       CRUD BÁSICO
    ========================== */

    public function index()
    {
        $query = $this->Users->find()
            ->contain(['Roles'])
            ->order(['Users.fecha_creacion' => 'DESC']);

        $users = $this->paginate($query, ['limit' => 10]);
        $this->set(compact('users'));
    }

    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles'],
        ]);

        $this->set(compact('user'));
    }

    public function add()
    {
        $user = $this->Users->newEmptyEntity();

        $roles = $this->fetchTable('Users.Roles')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['Roles.active' => 1],
            'order' => ['Roles.name' => 'ASC'],
        ])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['estado_usuario'])) {
                $data['estado_usuario'] = 'activo';
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('El usuario se guardó correctamente.');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('No se pudo guardar el usuario.');
        }

        $this->set(compact('user', 'roles'));
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id);

        $roles = $this->fetchTable('Users.Roles')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['Roles.active' => 1],
            'order' => ['Roles.name' => 'ASC'],
        ])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (empty($data['estado_usuario'])) {
                $data['estado_usuario'] = 'activo';
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('El usuario se actualizó correctamente.');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('No se pudo actualizar el usuario.');
        }

        $this->set(compact('user', 'roles'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success('El usuario fue eliminado.');
        } else {
            $this->Flash->error('No se pudo eliminar el usuario.');
        }

        return $this->redirect(['action' => 'index']);
    }

    /* ==========================
       LOGIN / LOGOUT
    ========================== */

    public function login()
    {
        $this->viewBuilder()->setLayout('auth');
        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $redirect = $this->Authentication->getLoginRedirect() ?? '/dashboard';
            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error('Usuario o contraseña incorrectos.');
        }
    }

    public function logout()
    {
        $this->Authentication->logout();
        $this->request->getSession()->destroy();

        return $this->redirect([
            'plugin' => 'Users',
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }

    /* ==========================
       DASHBOARDS
    ========================== */

    public function dashboard()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }

    public function administratorDashboard()
    {
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect(['action' => 'login']);
        }

        $currentUser = $this->Users->get($identity->getIdentifier(), [
            'contain' => ['Roles']
        ]);

        if ((int) $currentUser->role_id !== 1) {
            $this->Flash->error('No tienes permisos para entrar a este módulo.');
            return $this->redirect(['action' => 'dashboard']);
        }

        // Determinar qué lista mostrar (users vs associates)
        $listType = $this->request->getQuery('type', 'users');
        $insurancePlans = [];

        if ($listType === 'associates') {

    $associatesTable = $this->fetchTable('Associates.Associates');

    $query = $associatesTable->find()
        ->contain(['InsurancePlans', 'Users']);

    // 🔎 Capturar filtros
    $search = $this->request->getQuery('search');
    $plan   = $this->request->getQuery('plan');
    $status = $this->request->getQuery('status');

    if (!empty($search)) {
        $query->where([
            'OR' => [
                'Associates.first_name LIKE' => "%$search%",
                'Associates.last_name LIKE'  => "%$search%",
                'Associates.id_card LIKE'    => "%$search%",
                'Associates.phone LIKE'      => "%$search%"
            ]
        ]);
    }

    if (!empty($plan)) {
        $query->where(['Associates.plan_id' => $plan]);
    }

    if (!empty($status)) {
        $query->where(['Associates.member_status' => $status]);
    }

    // Orden final
    $query->order(['Associates.id' => 'DESC']);

    // Cargar planes para filtro y edición
    $insurancePlans = $this->fetchTable('Associates.InsurancePlans')
        ->find('list', [
            'keyField'   => 'id',
            'valueField' => 'name'
        ])
        ->toArray();

} else {

    // Default: Users
    $query = $this->Users->find()
        ->contain(['Roles'])
        ->order(['Users.created_at' => 'DESC']);

    // Seguridad
    $listType = 'users';

    // Evita error si no es associates
    $insurancePlans = [];
}

try {
    $data = $this->paginate($query, ['limit' => 10]);
} catch (\Exception $e) {
    $data = [];
    $this->Flash->error('Error al cargar los datos: ' . $e->getMessage());
}

$this->set(compact('data', 'currentUser', 'listType', 'insurancePlans'));
}
    public function createDebt($associateId = null)
    {
        $this->request->allowMethod(['post']);

        // Debug: Log incoming ID
        \Cake\Log\Log::debug("createDebt called. associateId arg: " . var_export($associateId, true));

        // Fallback: Intentar obtener ID de la request si no llegó como argumento
        if (!$associateId) {
            $associateId = $this->request->getParam('id');
        }
        if (!$associateId) {
            $associateId = $this->request->getQuery('id');
        }
        if (!$associateId) {
            $associateId = $this->request->getData('associate_id');
        }

        \Cake\Log\Log::debug("createDebt final ID: " . var_export($associateId, true));

        // Auth Check (simular admin check como en dashboard)
        $identity = $this->Authentication->getIdentity();
        $currentUser = $this->Users->get($identity->getIdentifier());
        if ((int) $currentUser->role_id !== 1) {
            $this->Flash->error('No autorizado.');
            return $this->redirect(['action' => 'dashboard']);
        }

        $associatesTable = $this->fetchTable('Associates.Associates');
        try {
            $associate = $associatesTable->get($associateId);
        } catch (\Exception $e) {
            // Error detallado
            $this->Flash->error('Asociado no encontrado. ID recibido: ' . json_encode($associateId) . '. Detalles: ' . $e->getMessage());
            return $this->redirect(['action' => 'administratorDashboard', '?' => ['type' => 'associates']]);
        }

        $paymentsTable = $this->fetchTable('Payments.Payments');
        $payment = $paymentsTable->newEmptyEntity();

        $data = $this->request->getData();

        // Datos forzados
        $paymentData = [
            'associate_id' => $associate->id,
            'amount' => $data['amount'],
            // 'payment_method_id' removido
            'payment_status_id' => 1, // Pendiente
            'is_paid' => 0,
        ];

        $payment = $paymentsTable->patchEntity($payment, $paymentData);

        if ($paymentsTable->save($payment)) {
            $this->Flash->success('Cobro/Deuda registrada correctamente.');
        } else {
            $errors = $payment->getErrors();
            $this->Flash->error('Error al registrar: ' . json_encode($errors));
        }

        return $this->redirect(['action' => 'administratorDashboard', '?' => ['type' => 'associates']]);
    }

    public function doctorDashboard()
    {
        if (property_exists($this, 'Authorization') && $this->Authorization) {
            $this->Authorization->skipAuthorization();
        }

        $this->viewBuilder()->setLayout('dashboard');

        $user = $this->request->getAttribute('identity') ?? null;
        $doctorName = $user ? ($user->full_name ?? $user->username ?? $user->email ?? 'Dr. Usuario') : 'Dr. Usuario';

        $associatesTable = $this->fetchTable('Associates.Associates');
        $associates = $associatesTable->find()
            ->contain([
                'AssociatesConditions' => ['Conditions'],
                'MedicalRecords',
            ])
            ->order(['Associates.last_name' => 'ASC', 'Associates.first_name' => 'ASC']);

        $associates = $this->paginate($associates, ['limit' => 20]);

        $this->set(compact('doctorName', 'associates', 'user'));
    }

    public function register()
    {
        // Si ya está logueado, lo mando al dashboard
        if ($this->Authentication->getIdentity()) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
        }

        $this->viewBuilder()->setLayout('auth');

        $user = $this->Users->newEmptyEntity();

        $roles = $this->fetchTable('Users.Roles')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['Roles.active' => 1],
            'order' => ['Roles.name' => 'ASC'],
        ])->toArray();

        // Cargar Planes de Seguro para el dropdown
        $insurancePlans = $this->fetchTable('Associates.InsurancePlans')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
        ])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['status'])) {
                $data['status'] = 'activo';
            }

            $user = $this->Users->patchEntity($user, $data);

            try {
                $result = $this->Users->getConnection()->transactional(function () use ($user, $data) {
                    if (!$this->Users->save($user)) {
                        return false;
                    }

                    // Si el rol es 'Asociado' (ID 4), guardamos en la tabla associates
                    if ((int) $user->role_id === 4) {
                        $associatesTable = $this->fetchTable('Associates.Associates');
                        $associate = $associatesTable->newEmptyEntity();

                        $associateData = [
                            'user_id' => $user->id,
                            'id_card' => $data['id_card'] ?? '',
                            'first_name' => $data['first_name'] ?? '',
                            'last_name' => $data['last_name'] ?? '',
                            'phone' => $data['phone'] ?? null,
                            'email' => $user->email,
                            'address' => $data['address'] ?? null,
                            'plan_id' => $data['plan_id'] ?? null,
                            'birth_date' => $data['birth_date'] ?? null,
                            'member_status' => 'activo',
                        ];

                        $associate = $associatesTable->patchEntity($associate, $associateData);
                        if (!$associatesTable->save($associate)) {
                            $errors = $associate->getErrors();
                            throw new \Exception('Error al guardar datos del asociado: ' . json_encode($errors));
                        }
                    }

                    return true;
                });

                if ($result) {
                    $this->Flash->success('Usuario registrado correctamente.');
                    return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
                }

                $this->Flash->error('No se pudo registrar el usuario. Por favor, verifique los datos.');
            } catch (\Exception $e) {
                \Cake\Log\Log::error('Register Error: ' . $e->getMessage());
                $this->Flash->error('Error durante el registro: ' . $e->getMessage());
            }
        }

        $this->set(compact('user', 'roles', 'insurancePlans'));
    }

    public function toggleUserStatus($id = null)
    {
        $this->request->allowMethod(['post']);
        $user = $this->Users->get($id);

        $newStatus = ($user->status === 'activo') ? 'inactivo' : 'activo';
        $user->status = $newStatus;

        if ($this->Users->save($user)) {
            $this->Flash->success('El estado del usuario se ha actualizado a: ' . $newStatus);
        } else {
            $this->Flash->error('No se pudo actualizar el estado.');
        }

        return $this->redirect($this->referer(['action' => 'administratorDashboard']));
    }

    public function editAssociatePlan($associateId = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        $associatesTable = $this->fetchTable('Associates.Associates');
        try {
            $associate = $associatesTable->get($associateId);
        } catch (\Exception $e) {
            $this->Flash->error('Asociado no encontrado.');
            return $this->redirect(['action' => 'administratorDashboard', '?' => ['type' => 'associates']]);
        }

        $data = $this->request->getData();

        $associate = $associatesTable->patchEntity($associate, $data);

        if ($associatesTable->save($associate)) {
            $this->Flash->success('Datos del asociado actualizados.');
        } else {
            $errors = $associate->getErrors();
            $this->Flash->error('Error al actualizar: ' . json_encode($errors));
        }

        return $this->redirect(['action' => 'administratorDashboard', '?' => ['type' => 'associates']]);
    }
}
