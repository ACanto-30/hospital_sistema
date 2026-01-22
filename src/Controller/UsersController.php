<?php
declare(strict_types=1);

namespace App\Controller;

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
}
