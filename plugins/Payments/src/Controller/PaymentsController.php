<?php
declare(strict_types=1);

namespace Payments\Controller;

use Payments\Controller\AppController;

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
        $this->viewBuilder()->setLayout('dashboard');

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'login']);
        }

        // 1. Obtener el Asociado vinculado al Usuario actual
        $associatesTable = $this->fetchTable('Associates.Associates');
        $associate = $associatesTable->find()
            ->where(['user_id' => $identity->getIdentifier()])
            ->first();

        // 2. Obtener Métodos de Pago para el Dropdown
        $paymentMethods = $this->fetchTable('Payments.PaymentMethods')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->toArray();

        $payment = $this->Payments->newEmptyEntity();

        if ($this->request->is('post')) {
            // Si NO es asociado (ej: admin/cajero pagando por alguien más), se debería manejar diferente.
            // Por ahora, asumimos que el usuario QUE PAGA debe ser un asociado, a menos que el form envíe 'associate_id'.
            // Para simplificar según tu requerimiento, usaremos el asociado encontrado.

            if (!$associate) {
                // Opción B: Si no hay asociado ligado, quizás es un pago anónimo o admin.
                // PERO la BD exige associate_id NOT NULL.
                $this->Flash->error('Este usuario no tiene un perfil de asociado para asignar el pago.');
                return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
            }

            $data = $this->request->getData();
            $file = $data['comprobante'] ?? null;
            $saved = false;

            // Validación de archivo
            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                $this->Flash->error('Debe subir un comprobante válido.');
            } else {
                $filename = $file->getClientFilename();
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (!in_array($ext, ['png', 'jpg', 'jpeg', 'pdf'])) {
                    $this->Flash->error('Formato no permitido (solo imágenes o PDF).');
                } else {
                    $targetDir = ROOT . DS . 'resources' . DS . 'receipts' . DS;
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    $safeName = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $targetPath = $targetDir . $safeName;

                    try {
                        $file->moveTo($targetPath);

                        // Preparar datos para BD
                        $paymentData = [
                            'associate_id' => $associate->id,
                            'payment_method_id' => $data['payment_method_id'],
                            'amount' => $data['amount'],
                            'payment_date' => date('Y-m-d H:i:s'),
                            'payment_status_id' => 1, // Por Aprobar (Default)
                            'proof_image' => 'resources/receipts/' . $safeName,
                            'processed_by_user_id' => null
                        ];

                        $payment = $this->Payments->patchEntity($payment, $paymentData);

                        if ($this->Payments->save($payment)) {
                            $saved = true;
                        } else {
                            $this->Flash->error('Error al guardar el pago en BD.');
                        }

                    } catch (\Exception $e) {
                        $this->Flash->error('Error al procesar el archivo o guardar.');
                    }
                }
            }

            if ($saved) {
                $this->Flash->success('Pago registrado correctamente.');
                return $this->redirect(['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard']);
            }
        }

        $this->set(compact('payment', 'paymentMethods', 'associate'));
    }

    public function dashboardCashier()
    {
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('dashboard');

        $user = $this->request->getAttribute('identity') ?? null;
        $today = date('Y-m-d');

        // Resumen Real
        $querySum = $this->Payments->find();
        $sumResult = $querySum->select(['total' => $querySum->func()->sum('amount')])
            ->where(['DATE(payment_date)' => $today, 'payment_status_id' => 2])
            ->first();

        $resumen = [
            'cobrado_hoy' => $sumResult ? (float) $sumResult->total : 0,
            'pagos_hoy' => $this->Payments->find()
                ->where(['DATE(payment_date)' => $today])
                ->count(),
            'pendientes' => $this->Payments->find()
                ->where(['payment_status_id' => 1]) // 1 = Por Aprobar / Pendiente
                ->count(),
        ];

        // Pagos Reales
        $query = $this->Payments->find()
            ->contain([
                'Associates',
                'PaymentMethods',
                'PaymentStatuses'
            ])
            ->orderBy(['Payments.payment_date' => 'DESC'])
            ->limit(15);

        $pagos = $query->all();

        $this->set(compact('user', 'resumen', 'pagos'));
    }
}
