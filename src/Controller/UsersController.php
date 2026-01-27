<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * UsersController (App)
 *
 * Maneja:
 * - CRUD de usuarios
 * - register
 * - login
 * - logout
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Permite acceso sin login a estas acciones
        $this->Authentication->allowUnauthenticated(['login', 'register']);
    }

    public function index()
    {
        $users = $this->paginate(
            $this->Users->find()->contain(['Roles'])
        );

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

        $roles = $this->Users->Roles->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order' => ['Roles.nombre_rol' => 'ASC'],
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

        $roles = $this->Users->Roles->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order' => ['Roles.nombre_rol' => 'ASC'],
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

    /**
     * Register
     *
     * Cumple la consigna:
     * - validar POST
     * - crear entidad vacía
     * - patchEntity
     * - save
     * - Flash error si falla
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();

        $roles = $this->Users->Roles->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre_rol',
            'conditions' => ['Roles.activo' => 1],
            'order' => ['Roles.nombre_rol' => 'ASC'],
        ])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['estado_usuario'])) {
                $data['estado_usuario'] = 'activo';
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('Usuario registrado correctamente.');
                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error('No se pudo registrar el usuario.');
        }

        $this->set(compact('user', 'roles'));
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {
                return $this->redirect([
                    'controller' => 'Pages',
                    'action' => 'display',
                    'home'
                ]);
            }

            $this->Flash->error('Usuario o contraseña incorrectos.');
        }
    }

    public function logout()
    {
        $this->Authentication->logout();
        $this->request->getSession()->destroy();

        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }
}
