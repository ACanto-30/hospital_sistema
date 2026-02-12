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

        $this->Authentication->allowUnauthenticated(['login', 'register', 'doctorDashboard']);
    }

    /**
     * beforeFilter unificado (CORREGIDO)
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Evita autorización en doctorDashboard
        if ($this->request->getParam('action') === 'doctorDashboard') {
            if (property_exists($this, 'Authorization') && $this->Authorization) {
                $this->Authorization->skipAuthorization();
            }
        }

        // Normalizar email en POST
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            if ($email) {
                $cleanEmail = strtolower(trim((string)$email));
                $this->request = $this->request->withParsedBody(array_merge(
                    (array)$this->request->getParsedBody(),
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

        if ((int)$currentUser->role_id !== 1) {
            $this->Flash->error('No tienes permisos para entrar a este módulo.');
            return $this->redirect(['action' => 'dashboard']);
        }

        $query = $this->Users->find()
            ->contain(['Roles'])
            ->order(['Users.created_at' => 'DESC']);

        $data = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('data', 'currentUser'));
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

        $this->set(compact('doctorName', 'stats'));
    }
}
