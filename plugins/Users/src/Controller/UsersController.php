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
        $roles = $this->Users->Roles->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order'      => ['Roles.nombre_rol' => 'ASC'],
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

        $roles = $this->Users->Roles->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order'      => ['Roles.nombre_rol' => 'ASC'],
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

        $roles = $this->Users->Roles->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order'      => ['Roles.nombre_rol' => 'ASC'],
        ])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['estado_usuario'])) {
                $data['estado_usuario'] = 'activo';
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario registrado correctamente.');
                return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
            }

            $this->Flash->error('No se pudo registrar el usuario.');
        }

        $this->set(compact('user', 'roles'));
    }

    public function login()
    {
        // Si ya está logueado, lo mando al dashboard
        if ($this->Authentication->getIdentity()) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
        }

        $this->viewBuilder()->setLayout('auth');

        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {
                return $this->redirect(
                    $this->Authentication->getLoginRedirect() ?? [
                        'plugin' => 'Users',
                        'controller' => 'Users',
                        'action' => 'dashboard'
                    ]
                );
            }
            
            $correo = $this->request->getData('correo');
            $pass   = $this->request->getData('contrasena_hash');

            if ($correo && $pass) {
                $user = $this->Users->find()->where(['correo' => $correo])->first();

                if ($user) {
                    $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();

                    if ($hasher->check($pass, $user->contrasena_hash)) {
                        $this->Authentication->setIdentity($user);

                        return $this->redirect([
                            'plugin' => 'Users',
                            'controller' => 'Users',
                            'action' => 'dashboard'
                        ]);
                    }
                }
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
         * Dashboard general.
         */
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
        }

        $user = $this->Users->get($identity->getIdentifier(), ['contain' => ['Roles']]);

        // Ajusta el 1 si tu rol admin tiene otro ID
        if (!empty($user->id_rol) && ((string)$user->id_rol === '1')) {
            return $this->redirect([
                'plugin' => 'Users',
                'controller' => 'Users',
                'action' => 'administratorDashboard'
            ]);
        }

        $this->set(compact('user'));
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
        if (empty($currentUser->id_rol) || ((string)$currentUser->id_rol !== '1')) {
            $this->Flash->error('No tienes permisos para entrar a este módulo.');
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
        }

        // Listado de usuarios con roles, ordenados por fecha de creación
        $query = $this->Users->find()
            ->contain(['Roles'])
            ->orderBy(['Users.fecha_creacion' => 'DESC']);

        $users = $this->paginate($query, [
            'limit' => 10,
        ]);

        $this->set(compact('users', 'currentUser'));
    }
}
