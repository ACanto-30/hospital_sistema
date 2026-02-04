<?php
declare(strict_types=1);

namespace Pay\Controller;

use Pay\Controller\AppController;

/**
 * Payments Controller
 *
 * @property \Pay\Model\Table\PaymentsTable $Payments
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class PaymentsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authorization.Authorization');
    }

    public function index()
    {
        $query = $this->Payments->find();
        $query = $this->Authorization->applyScope($query);
        $payments = $this->paginate($query);

        $this->set(compact('payments'));
    }

    public function view($id = null)
    {
        $payment = $this->Payments->get($id, contain: []);
        $this->Authorization->authorize($payment);

        $this->set(compact('payment'));
    }

    public function add()
    {
        $payment = $this->Payments->newEmptyEntity();
        $this->Authorization->authorize($payment);

        if ($this->request->is('post')) {
            $payment = $this->Payments->patchEntity(
                $payment,
                $this->request->getData()
            );

            if ($this->Payments->save($payment)) {
                $this->Flash->success(__('The payment has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }

        $this->set(compact('payment'));
    }

    public function edit($id = null)
    {
        $payment = $this->Payments->get($id, contain: []);
        $this->Authorization->authorize($payment);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $payment = $this->Payments->patchEntity(
                $payment,
                $this->request->getData()
            );

            if ($this->Payments->save($payment)) {
                $this->Flash->success(__('The payment has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }

        $this->set(compact('payment'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $payment = $this->Payments->get($id);
        $this->Authorization->authorize($payment);

        if ($this->Payments->delete($payment)) {
            $this->Flash->success(__('The payment has been deleted.'));
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function dashboardCashier()
    {
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('dashboard');

        $user = $this->request->getAttribute('identity') ?? null;

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
