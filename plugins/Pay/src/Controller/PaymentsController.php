<?php
namespace Pay\Controller;

use Pay\Controller\AppController;

class PaymentsController extends AppController
{
    public function add()
    {
        $this->request->allowMethod(['post']);

        // Usuario autenticado (inyectado por middleware JWT)
        $authUser = $this->request->getAttribute('authUser');

        if (!$authUser) {
            $this->response = $this->response->withStatus(401);
            return;
        }

        $payment = $this->Payments->newEmptyEntity();
        $data = $this->request->getData();

        // Seguridad: user_id viene del token
        $data['user_id'] = $authUser['id'];

        // Guardar solo últimos 4 dígitos
        if (!empty($data['card_number'])) {
            $data['card_last4'] = substr($data['card_number'], -4);
            unset($data['card_number']);
        }

        $payment = $this->Payments->patchEntity($payment, $data);

        if ($this->Payments->save($payment)) {
            $this->set([
                'success' => true,
                'message' => 'Pago registrado correctamente'
            ]);
        } else {
            $this->set([
                'success' => false,
                'errors' => $payment->getErrors()
            ]);
        }

        $this->viewBuilder()->setOption('serialize', true);
    }
    public function pay()
    {
        // Renderiza templates/Payments/pay.php
    }
}
