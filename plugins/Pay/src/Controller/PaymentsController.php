<?php
declare(strict_types=1);

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
            $data['card_last4'] = substr((string)$data['card_number'], -4);
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

    public function dashboardCashier()
    {
        // Evita el Forbidden del plugin Authorization (por ahora es UI estática)
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()->setLayout('dashboard');

        $user = $this->request->getAttribute('identity') ?? null;

        // Datos estáticos
        $resumen = [
            'cobrado_hoy' => 425.50,
            'pagos_hoy' => 7,
            'pendientes' => 3,
        ];

        $pagos = [
            [
                'recibo' => 'RC-1001',
                'miembro' => 'Juan Pérez',
                'concepto' => 'Mensualidad Enero',
                'monto' => 35.00,
                'metodo' => 'Efectivo',
                'estado' => 'Pagado',
                'fecha' => '2026-02-03',
            ],
            [
                'recibo' => 'RC-1002',
                'miembro' => 'María González',
                'concepto' => 'Consulta General',
                'monto' => 20.00,
                'metodo' => 'Tarjeta',
                'estado' => 'Pendiente',
                'fecha' => '2026-02-03',
            ],
            [
                'recibo' => 'RC-1003',
                'miembro' => 'Carlos Rodríguez',
                'concepto' => 'Laboratorio',
                'monto' => 55.00,
                'metodo' => 'Transferencia',
                'estado' => 'Pagado',
                'fecha' => '2026-02-02',
            ],
            [
                'recibo' => 'RC-1004',
                'miembro' => 'Ana Martínez',
                'concepto' => 'Mensualidad Febrero',
                'monto' => 35.00,
                'metodo' => 'Yappy',
                'estado' => 'Pendiente',
                'fecha' => '2026-02-02',
            ],
        ];

        $this->set(compact('user', 'resumen', 'pagos'));
    }
}
