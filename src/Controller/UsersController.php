<?php
declare(strict_types=1);

namespace App\Controller;

use Firebase\JWT\JWT;
use Cake\Http\Cookie\Cookie;

class UsersController extends AppController
{
    /**
     * Registro de usuarios (PÚBLICO)
     */
    public function register()
    {
        // ✅ Evita excepción de Authorization en página pública
        $this->Authorization->skipAuthorization();

        // 🔒 Si ya está autenticado, no mostrar registro
        if ($this->request->getAttribute('identity')) {
            return $this->redirect(['action' => 'dashboard']);
        }

        $user = $this->Users->newEmptyEntity();

         if ($this->request->is('post')) {
            $user = $this->Users->patchEntity(
                $user,
                $this->request->getData()
            );

             if ($this->Users->save($user)) {
                $this->Flash->success(
                    'El usuario fue registrado correctamente.'
                );
                 return $this->redirect(['action' => 'login']);
            }

             $this->Flash->error(
                'No se pudo registrar el usuario. Verifique los datos ingresados.'
            );
        }

         $this->set(compact('user'));
    }

    /**
     * Login de usuarios (PÚBLICO)
     */
    public function login()
    {
        // ✅ Evita excepción de Authorization en página pública
        $this->Authorization->skipAuthorization();

        // 🔒 Si ya está autenticado, no mostrar login
        if ($this->request->getAttribute('identity')) {
            return $this->redirect(['action' => 'dashboard']);
        }

        if ($this->request->is('post')) {

            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {

                $user = $this->request->getAttribute('identity');

                // Crear cookie JWT
                $this->_setJwtCookie($user);

                return $this->redirect(['action' => 'dashboard']);
            }

            $this->Flash->error(
                'Usuario o contraseña incorrectos.'
            );
        }
    }

    /**
     * Dashboard del usuario autenticado (PROTEGIDO)
     */
    public function dashboard()
    {
        $identity = $this->request->getAttribute('identity');

        if (!$identity) {
            return $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->get($identity->id, [
            'contain' => ['Roles']
        ]);

        $this->set(compact('user'));
    }

    /**
     * Logout (PÚBLICO)
     */
    public function logout()
    {
        // ✅ No requiere autorización explícita
        $this->Authorization->skipAuthorization();

        $this->Authentication->logout();

        // Eliminar cookie JWT
        $this->response = $this->response->withExpiredCookie('jwt');

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Genera y asigna la cookie JWT segura
     */
    private function _setJwtCookie($user): void
    {
        $keyPath = CONFIG . 'jwt.key';

         if (!file_exists($keyPath)) {
            throw new \RuntimeException('Archivo jwt.key no encontrado');
        }

        $secretKey = file_get_contents($keyPath);

        $payload = [
            'sub'   => $user->id,
            'email' => $user->email,
            'role'  => $user->role->name ?? null,
            'iat'   => time(),
            'exp'   => time() + 3600
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        $cookie = new Cookie(
            'jwt',
            $jwt,
            time() + 3600,
            '/',
            null,
            true,   // secure
            true,   // httpOnly
            false,
            Cookie::SAMESITE_STRICT
        );

        $this->response = $this->response->withCookie($cookie);
    }
 }