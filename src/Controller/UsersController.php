<?php
declare(strict_types=1);

namespace App\Controller;

use Firebase\JWT\JWT;
use Cake\Http\Cookie\Cookie;

class UsersController extends AppController
{
    /**
     * Registro de usuarios
     */
    public function register()
    {
        // 1) Crear una entidad vacía
        $user = $this->Users->newEmptyEntity();

        // 2) Validar que sea un POST
        if ($this->request->is('post')) {

            // 3) Parchear la entidad con los datos del formulario
            $user = $this->Users->patchEntity(
                $user,
                $this->request->getData()
            );

            // 4) Intentar guardar el usuario
            if ($this->Users->save($user)) {
                $this->Flash->success(
                    'El usuario fue registrado correctamente.'
                );

                // Redirige al login después de registrarse
                return $this->redirect(['action' => 'login']);
            }

            // 5) Si falla el guardado
            $this->Flash->error(
                'No se pudo registrar el usuario. Verifique los datos ingresados.'
            );
        }

        // 6) Enviar la entidad a la vista
        $this->set(compact('user'));
    }

    /**
     * Dashboard del usuario autenticado
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
     * Genera y asigna la cookie JWT segura
     */
    private function _setJwtCookie($user): void
    {
        $keyPath = CONFIG . 'jwt.key';

        // Lógica defensiva
        if (!file_exists($keyPath)) {
            throw new \RuntimeException('Archivo jwt.key no encontrado');
        }

        $secretKey = file_get_contents($keyPath);

        $payload = [
            'sub'   => $user->id,
            'email'=> $user->email,
            'role' => $user->role->name ?? null,
            'iat'  => time(),
            'exp'  => time() + 3600
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

