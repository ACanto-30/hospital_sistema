<?php
declare(strict_types=1);

namespace Users\Controller;

use App\Controller\AppController;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Dejo login y register públicos; el resto requiere sesión
        $this->Authentication->allowUnauthenticated(['login', 'register']);
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

            // Si no viene el estado, por defecto lo pongo activo
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
                $data['status'] = 'active';
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
                            'email' => $user->email, // Usamos el mismo email del usuario por consistencia
                            'address' => $data['address'] ?? null,
                            'plan_id' => $data['plan_id'] ?? null,
                            'member_status' => 'active',
                        ];

                        $associate = $associatesTable->patchEntity($associate, $associateData);
                        if (!$associatesTable->save($associate)) {
                            // Si falla el guardado del asociado, lanzamos error para el rollback
                            $errors = $associate->getErrors();
                            throw new \Exception('Error al guardar datos del asociado: ' . json_encode($errors));
                        }
                    }

                    return true;
                });

                if ($result) {
                    $this->Flash->success('Usuario registrado correctamente.');
                    return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
                } else {
                    $this->Flash->error('No se pudo registrar el usuario. Por favor, verifique los datos.');
                }

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

        // Si el usuario ya está autenticado (vía Form o Session), redirigir
        if ($result && $result->isValid()) {
            \Cake\Log\Log::info('Login Success for: ' . ($this->Authentication->getIdentity()->email ?? 'unknown'));
            $redirect = $this->Authentication->getLoginRedirect() ?? '/dashboard';

            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            \Cake\Log\Log::error('Login Failed Status: ' . $result->getStatus());

            // PRUEBA MANUAL DE DIAGNÓSTICO
            $email = $this->request->getData('email');
            $pass = $this->request->getData('password');
            $testUser = $this->Users->find()->where(['email' => $email])->first();

            if ($testUser) {
                $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
                $match = $hasher->check($pass, $testUser->password);
                \Cake\Log\Log::debug("[LoginDebug] Manual check for $email: " . ($match ? 'MATCHES!' : 'NO MATCH'));
                \Cake\Log\Log::debug("[LoginDebug] DB Hash: " . substr($testUser->password, 0, 10) . "...");
            } else {
                \Cake\Log\Log::debug("[LoginDebug] Manual check could not find user $email");
            }

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

    public function dashboard()
    {
        /**
         * Dashboard general / punto de redirección
         */
        $this->viewBuilder()->setLayout('dashboard');
    }

    public function administratorDashboard()
    {
        /**
         * Dashboard del Administrador
         */
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
        }

        // Usuario actual (admin) con rol
        $currentUser = $this->Users->get($identity->getIdentifier(), ['contain' => ['Roles']]);

        // Seguridad extra: si no es admin, lo mando al dashboard normal
        if (empty($currentUser->role_id) || ((int) $currentUser->role_id !== 1)) {
            $this->Flash->error('No tienes permisos para entrar a este módulo.');
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
        }

        // Listado de usuarios con roles, ordenados por fecha de creación
        $query = $this->Users->find()
            ->contain(['Roles'])
            ->orderBy(['Users.created_at' => 'DESC']);

        $users = $this->paginate($query, [
            'limit' => 10,
        ]);

        $this->set(compact('users', 'currentUser'));
    }
}
