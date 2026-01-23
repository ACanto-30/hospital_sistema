<?php
declare(strict_types=1);

namespace Users\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 */
class UsersController extends AppController
{
    /**
     * Initialize controller
     */
    public function initialize(): void
    {
        parent::initialize();

        // Permitir acceso sin autenticación al login
        $this->Authentication->allowUnauthenticated(['login']);
    }

    /**
     * Index method
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     * Valida Authentication, obtiene el usuario y redirige
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {

            // 1️⃣ Obtener resultado de Authentication
            $result = $this->Authentication->getResult();

            // 2️⃣ Validar autenticación
            if ($result && $result->isValid()) {

                // 3️⃣ Obtener usuario autenticado
                $user = $this->Authentication->getIdentity();

                // 4️⃣ Guardar usuario en sesión
                $this->request->getSession()->write('Auth.User', $user);

                // 5️⃣ Redirigir
                $redirect = $this->Authentication->getLoginRedirect() ?? [
                    'controller' => 'Pages',
                    'action' => 'display',
                    'home'
                ];

                return $this->redirect($redirect);
            }

            // ❌ Credenciales incorrectas
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    /**
     * Logout method
     * Destruye sesión y cookie
     */
    public function logout()
    {
        // 1️⃣ Cerrar sesión con Authentication
        $this->Authentication->logout();

        // 2️⃣ Destruir sesión completamente
        $this->request->getSession()->destroy();

        // 3️⃣ Eliminar cookie de autenticación (si existe)
        if ($this->request->getCookie('Auth')) {
            $this->response = $this->response->withExpiredCookie('Auth');
        }

        // 4️⃣ Redirigir al login
        return $this->redirect([
            'plugin' => 'Users',
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }
}
