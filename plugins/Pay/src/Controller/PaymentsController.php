<?php
declare(strict_types=1);

namespace Pay\Controller;

use Pay\Controller\AppController;

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
            $payment = $this->Payments->patchEntity($payment, $this->request->getData());

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
            $payment = $this->Payments->patchEntity($payment, $this->request->getData());

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

 
    public function pay()
    {
    
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('dashboard');
        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            $this->Flash->error('Debe iniciar sesión.');
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
        }

       
        if (!$this->request->is('post')) {
            return;
        }

        $data = (array)$this->request->getData();
        $file = $data['comprobante'] ?? null;

        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            $this->Flash->error('No se pudo leer el comprobante. Intente nuevamente.');
            return;
        }

      
        $maxBytes = 5 * 1024 * 1024;
        if ($file->getSize() > $maxBytes) {
            $this->Flash->error('El comprobante excede 5MB.');
            return;
        }

        $clientName = (string)$file->getClientFilename();
        $ext = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
        $allowed = ['png', 'jpg', 'jpeg'];

        if (!in_array($ext, $allowed, true)) {
            $this->Flash->error('Formato no permitido. Use .png, .jpg o .jpeg.');
            return;
        }

        $targetDir = WWW_ROOT . 'uploads' . DS . 'comprobantes' . DS;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

      
        $safeName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $targetPath = $targetDir . $safeName;

        try {
            $file->moveTo($targetPath);
        } catch (\Throwable $e) {
            $this->Flash->error('No se pudo guardar el comprobante.');
            return;
        }

        $this->Flash->success('Comprobante enviado. Será verificado manualmente.');
        return $this->redirect(['action' => 'dashboardCashier']);
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
                'metodo' => 'Transferencia',
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
