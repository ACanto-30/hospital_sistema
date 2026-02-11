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

        // Login, register y doctorDashboard públicos (temporal por diseño estático)
        $this->Authentication->allowUnauthenticated(['login', 'register', 'doctorDashboard']);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Evita que Authorization exija autorización en esta acción (temporal)
        if ($this->request->getParam('action') === 'doctorDashboard') {
            if (property_exists($this, 'Authorization') && $this->Authorization) {
                $this->Authorization->skipAuthorization();
            }
        }
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // Normalizar correo en peticiones POST para login o registro
        // Esto asegura que 'Test@Gmail.com' sea igual a 'test@gmail.com'
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

    public function index()
    {
        // Listado general con roles
        $query = $this->Users->find()
            ->contain(['Roles'])
            ->orderBy(['Users.fecha_creacion' => 'DESC']);

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

        // Roles activos para el select
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
                    if ((int)$user->role_id === 4) {
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

    public function login()
    {
        $this->viewBuilder()->setLayout('auth');
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            \Cake\Log\Log::info('Login Attempt for: ' . ($data['email'] ?? 'NO EMAIL'));
        }

        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            \Cake\Log\Log::info('Login Success for: ' . ($this->Authentication->getIdentity()->email ?? 'unknown'));
            $redirect = $this->Authentication->getLoginRedirect() ?? '/dashboard';
            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && !$result->isValid()) {

    \Cake\Log\Log::error('Login Failed Status: ' . $result->getStatus());

    $email = $this->request->getData('email');
    \Cake\Log\Log::error("[LoginFailure] Intento fallido para: $email. Código de error: " . $result->getStatus());

    // Diagnóstico Exhaustivo
    $dbUser = $this->Users->find()->where(['email' => $email])->first();
    if ($dbUser) {
        $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
        $passInput = (string) $this->request->getData('password');
        $match = $hasher->check($passInput, $dbUser->password);

        \Cake\Log\Log::debug("[LoginDebug] Usuario encontrado: " . $dbUser->email);
        \Cake\Log\Log::debug("[LoginDebug] ¿Contraseña coincide manualmente?: " . ($match ? 'SÍ' : 'NO'));
        \Cake\Log\Log::debug("[LoginDebug] Longitud pass ingresada: " . strlen($passInput));
        \Cake\Log\Log::debug("[LoginDebug] Hash en BD empieza con: " . substr($dbUser->password, 0, 10));
        \Cake\Log\Log::debug("[LoginDebug] Estatus del usuario: " . ($dbUser->status ?? 'N/A'));
    } else {
        \Cake\Log\Log::debug("[LoginDebug] El correo $email NO existe en la base de datos.");
    }

    $this->Flash->error('Usuario o contraseña incorrectos.');
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

    public function dashboard()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }

    public function administratorDashboard()
    {
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
        }

        $currentUser = $this->Users->get($identity->getIdentifier(), ['contain' => ['Roles']]);

        if (empty($currentUser->role_id) || ((int)$currentUser->role_id !== 1)) {
            $this->Flash->error('No tienes permisos para entrar a este módulo.');
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
        }

        $listType = $this->request->getQuery('type', 'users');

        if ($listType === 'associates') {
            $associatesTable = $this->fetchTable('Associates.Associates');
            $query = $associatesTable->find()
                ->contain(['Users', 'InsurancePlans'])
                ->orderBy(['Associates.registered_at' => 'DESC']);
            $data = $this->paginate($query, [
                'limit' => 10,
                'scope' => 'associates'
            ]);
        } else {
            $query = $this->Users->find()
                ->contain(['Roles'])
                ->orderBy(['Users.created_at' => 'DESC']);
            $data = $this->paginate($query, [
                'limit' => 10,
                'scope' => 'users'
            ]);
        }

        $insurancePlans = $this->fetchTable('Associates.InsurancePlans')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->toArray();

        $this->set(compact('data', 'currentUser', 'listType', 'insurancePlans'));
    }

    
    public function doctorDashboard()
    {
        
        if (property_exists($this, 'Authorization') && $this->Authorization) {
            $this->Authorization->skipAuthorization();
        }

        $this->viewBuilder()->setLayout('dashboard');

        $doctorName = 'Dr. Demo';

        $stats = [
            'pacientes_hoy' => 12,
            'citas_pendientes' => 5,
            'emergencias' => 1,
        ];

        $agendaHoy = [
            ['hora' => '08:00', 'paciente' => 'María González', 'motivo' => 'Consulta general', 'estado' => 'Confirmada'],
            ['hora' => '09:30', 'paciente' => 'Carlos Pérez', 'motivo' => 'Control', 'estado' => 'Pendiente'],
        ];

        $enEspera = [
            ['paciente' => 'José Herrera', 'prioridad' => 'Media', 'tiempo' => '12 min'],
        ];

        $this->set(compact('doctorName', 'stats', 'agendaHoy', 'enEspera'));
    }

    public function editAssociatePlan($associateId = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $identity = $this->Authentication->getIdentity();

        $associatesTable = $this->fetchTable('Associates.Associates');
        $associate = $associatesTable->get($associateId);

        $data = $this->request->getData();
        $oldPlanId = $associate->plan_id;
        $newPlanId = (int)($data['plan_id'] ?? $oldPlanId);
        $reason = $data['reason'] ?? '';

        try {
            $associatesTable->getConnection()->transactional(function () use ($associatesTable, $associate, $oldPlanId, $newPlanId, $reason, $identity, $data) {
                $associate = $associatesTable->patchEntity($associate, [
                    'first_name' => $data['first_name'] ?? $associate->first_name,
                    'last_name' => $data['last_name'] ?? $associate->last_name,
                    'phone' => $data['phone'] ?? $associate->phone,
                    'address' => $data['address'] ?? $associate->address,
                    'plan_id' => $newPlanId
                ]);

                if (!$associatesTable->save($associate)) {
                    throw new \Exception('Error al actualizar los datos del asociado.');
                }

                if ($oldPlanId !== $newPlanId) {
                    $planChangesTable = $this->fetchTable('Associates.AssociatePlanChanges');
                    $change = $planChangesTable->newEntity([
                        'associate_id' => $associate->id,
                        'old_plan_id' => $oldPlanId,
                        'new_plan_id' => $newPlanId,
                        'reason' => $reason,
                        'change_by_user_id' => $identity->getIdentifier(),
                        'change_date' => date('Y-m-d')
                    ]);

                    if (!$planChangesTable->save($change)) {
                        throw new \Exception('Error al registrar la trazabilidad del cambio de plan.');
                    }
                }
            });

            $this->Flash->success('Los datos se actualizaron correctamente.');
        } catch (\Exception $e) {
            $this->Flash->error('No se pudo realizar la actualización: ' . $e->getMessage());
        }

        return $this->redirect($this->referer());
    }

    public function toggleUserStatus($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);

        $newStatus = ($user->status === 'activo') ? 'inactivo' : 'activo';
        $user->status = $newStatus;

        if ($this->Users->save($user)) {
            $this->Flash->success("Usuario " . ($newStatus === 'activo' ? 'activado' : 'desactivado') . " correctamente.");
        } else {
            $this->Flash->error('No se pudo cambiar el estado del usuario.');
        }

        return $this->redirect($this->referer());
    }
}
