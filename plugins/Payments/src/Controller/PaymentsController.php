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

        // 3. Buscar Deudas Pendientes (Payments padres con is_paid = false)
        // Calculamos cuánto falta por pagar de cada una
        $pendingPaymentsQuery = $this->Payments->find()
            ->where([
                'associate_id' => $associate->id,
                'is_paid' => false
            ])
            ->contain(['PaymentDetails']) // Para sumar lo ya abonado
            ->order(['payment_date' => 'DESC']);

        $pendingOptions = [];
        $debts = []; // Para validación JS/Backend (ID => Monto Restante)

        foreach ($pendingPaymentsQuery as $dept) {
            $totalAmount = (float) $dept->amount;

            // Sumar abonos APROBADOS (2) y PENDIENTES (1)
            $paidSoFar = 0;
            foreach ($dept->payment_details as $detail) {
                if (in_array($detail->payment_status_id, [1, 2])) {
                    $paidSoFar += (float) $detail->amount;
                }
            }

            $remaining = $totalAmount - $paidSoFar;

            if ($remaining > 0.01) { // Solo si queda deuda real (> 1 centavo)
                $label = "Cuota del " . ($dept->payment_date ? $dept->payment_date->format('d/m/Y') : 'Fecha Desc.') .
                    " | Deuda: B/. " . number_format($totalAmount, 2) .
                    " | Restante: B/. " . number_format($remaining, 2);

                $pendingOptions[$dept->id] = $label;
                $debts[$dept->id] = $remaining;
            }
        }

        // 4. Preparar entidad PaymentDetail
        $paymentDetailsTable = $this->fetchTable('Payments.PaymentDetails');
        $paymentDetail = $paymentDetailsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            if (!$associate) {
                $this->Flash->error('Este usuario no tiene un perfil de asociado para asignar el pago.');
                return $this->redirect(['plugin' => 'Users', 'controller' => 'Users', 'action' => 'dashboard']);
            }

            $data = $this->request->getData();

            // Validar que seleccionó una deuda válida
            $paymentId = $data['payment_id'] ?? null;
            if (!$paymentId || !isset($debts[$paymentId])) {
                $this->Flash->error('Debe seleccionar una deuda válida para abonar.');
                // Re-enviamos al form
            } else {
                // Validar monto
                $amountToPay = (float) ($data['amount'] ?? 0);
                $maxAllowed = $debts[$paymentId];

                // Margen de error pequeño por float conversion
                if ($amountToPay <= 0) {
                    $this->Flash->error('El monto a pagar debe ser mayor a 0.');
                } elseif ($amountToPay > ($maxAllowed + 0.01)) {
                    $this->Flash->error('El monto ingresado (B/. ' . number_format($amountToPay, 2) . ') supera la deuda restante (B/. ' . number_format($maxAllowed, 2) . ').');
                } else {
                    // Procesamiento de archivo y guardado
                    $file = $data['comprobante'] ?? null;
                    $saved = false;

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

                               
                                $detailData = [
                                    'payment_id' => $paymentId,
                                    'payment_method_id' => $data['payment_method_id'],
                                    'amount' => $amountToPay,
                                    'payment_date' => date('Y-m-d H:i:s'),
                                    'payment_status_id' => 1, 
                                    'proof_image' => 'resources/receipts/' . $safeName,
                                    'processed_by_user_id' => null
                                ];

                                $paymentDetail = $paymentDetailsTable->patchEntity($paymentDetail, $detailData);

                                if ($paymentDetailsTable->save($paymentDetail)) {
                                    $saved = true;
                                } else {
                                    $this->Flash->error('Error al guardar el abono en BD.');
                                }

                            } catch (\Exception $e) {
                                $this->Flash->error('Error al procesar el archivo o guardar: ' . $e->getMessage());
                            }
                        }
                    }

                    if ($saved) {
                        $this->Flash->success('Abono registrado correctamente. Pendiente de aprobación.');
                        return $this->redirect(['plugin' => 'Associates', 'controller' => 'Associates', 'action' => 'dashboard']);
                    }
                }
            }
        }

        // Pasamos paymentDetail a la vista en lugar de payment
        $this->set(compact('paymentDetail', 'paymentMethods', 'associate', 'pendingOptions', 'debts'));
    }

    public function dashboardCashier()
    {
        $this->Authorization->skipAuthorization();
        $this->viewBuilder()->setLayout('dashboard');

        $user = $this->request->getAttribute('identity') ?? null;
        $statusFilter = $this->request->getQuery('status', 'pending');

        $paymentDetailsTable = $this->fetchTable('Payments.PaymentDetails');

        // Resumen Estadístico (Dashboard Header)
        $today = date('Y-m-d');

        // 1. Total Cobrado Hoy (Solo Detalle Aprobados)
        $querySum = $paymentDetailsTable->find();
        $sumResult = $querySum->select(['total' => $querySum->func()->sum('amount')])
            ->where(['DATE(payment_date)' => $today, 'payment_status_id' => 2]) // 2 = Aprobado
            ->first();

        // 2. Pagos (Detalles) Procesados Hoy (Aprobados o Rechazados)
        $procesadosHoy = $paymentDetailsTable->find()
            ->where([
                'DATE(payment_date)' => $today,
                'payment_status_id IN' => [2, 3]
            ])
            ->count();

        // 3. Pendientes Totales (No solo hoy)
        $pendientesTotales = $paymentDetailsTable->find()
            ->where(['payment_status_id' => 1])
            ->count();

        $resumen = [
            'cobrado_hoy' => $sumResult ? (float) $sumResult->total : 0,
            'pagos_procesados_hoy' => $procesadosHoy,
            'pendientes_totales' => $pendientesTotales,
        ];

        // Lógica de Filtrado para la Tabla
        // Necesitamos contain Payments -> Associates para mostrar quién pagó
        $query = $paymentDetailsTable->find()
            ->contain([
                'Payments' => ['Associates'],
                'PaymentMethods',
                'PaymentStatuses'
            ]);

        if ($statusFilter === 'pending') {
            $query->where(['PaymentDetails.payment_status_id' => 1]);
        } elseif ($statusFilter === 'processed') {
            $query->where(['PaymentDetails.payment_status_id IN' => [2, 3]]);
        }
        // Si es 'all', no aplicamos filtro de status

        $query->orderBy(['PaymentDetails.payment_date' => 'DESC']);

        $pagos = $this->paginate($query, ['limit' => 20]);
        $paymentStatuses = $this->fetchTable('Payments.PaymentStatuses')->find('list')->toArray();

        $this->set(compact('user', 'resumen', 'pagos', 'statusFilter', 'paymentStatuses'));
    }

    /**
     * Procesa un abono (PaymentDetail): cambia monto, estado y asigna el cajero responsable.
     * El dashboard del cajero muestra PaymentDetails (abonos), por lo que el ID recibido
     * es el ID del PaymentDetail, no del Payment padre.
     */
    public function processPayment($id = null)
    {
        \Cake\Log\Log::debug("[ProcessPayment] Iniciando procesamiento para PaymentDetail ID: $id");

        try {
            // Validar método HTTP
            $this->request->allowMethod(['post', 'put', 'patch']);
            \Cake\Log\Log::debug("[ProcessPayment] Método HTTP válido: " . $this->request->getMethod());

            // Validar que el ID existe
            if (!$id) {
                \Cake\Log\Log::error("[ProcessPayment] ID no proporcionado");
                $this->Flash->error('ID de pago no válido.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Obtener el PaymentDetail (abono) - el dashboard envía el ID del detalle
            $paymentDetailsTable = $this->fetchTable('Payments.PaymentDetails');
            try {
                $paymentDetail = $paymentDetailsTable->get($id, [
                    'contain' => ['Payments']
                ]);
                \Cake\Log\Log::debug("[ProcessPayment] PaymentDetail encontrado: ID=$id, Estado actual=" . $paymentDetail->payment_status_id . ", Monto actual=" . $paymentDetail->amount);
            } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                \Cake\Log\Log::error("[ProcessPayment] PaymentDetail no encontrado con ID: $id - " . $e->getMessage());
                $this->Flash->error('Pago no encontrado.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Validar identidad del usuario
            $identity = $this->request->getAttribute('identity');
            if (!$identity) {
                \Cake\Log\Log::error("[ProcessPayment] Usuario no autenticado");
                $this->Flash->error('Debe estar autenticado para procesar pagos.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            $userId = $identity->getIdentifier();
            \Cake\Log\Log::debug("[ProcessPayment] Usuario procesando: $userId");

            // Obtener datos del formulario
            $data = $this->request->getData();
            \Cake\Log\Log::debug("[ProcessPayment] Datos recibidos del formulario (raw): " . json_encode($data));
            \Cake\Log\Log::debug("[ProcessPayment] Tipo de payment_status_id: " . gettype($data['payment_status_id'] ?? 'NO EXISTE'));
            \Cake\Log\Log::debug("[ProcessPayment] payment_status_id recibido: " . ($data['payment_status_id'] ?? 'NO ENVIADO'));
            \Cake\Log\Log::debug("[ProcessPayment] amount recibido: " . ($data['amount'] ?? 'NO ENVIADO'));

            // Validar que hay datos
            if (empty($data)) {
                \Cake\Log\Log::error("[ProcessPayment] No se recibieron datos del formulario");
                $this->Flash->error('No se recibieron datos para actualizar.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Validar que payment_status_id está presente
            if (!isset($data['payment_status_id'])) {
                \Cake\Log\Log::error("[ProcessPayment] payment_status_id NO está en los datos recibidos. Campos disponibles: " . implode(', ', array_keys($data)));
                $this->Flash->error('El estado del pago es requerido.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Asegurar que payment_status_id sea un entero
            $data['payment_status_id'] = (int) $data['payment_status_id'];
            $data['amount'] = (float) $data['amount'];

            // Asignar el usuario que procesa
            $data['processed_by_user_id'] = $userId;
            \Cake\Log\Log::debug("[ProcessPayment] Datos finales a guardar (después de conversión): " . json_encode($data));

            // Autorizar usando la Policy del Payment padre
            try {
                $this->Authorization->authorize($paymentDetail->payment, 'processPayment');
                \Cake\Log\Log::debug("[ProcessPayment] Autorización exitosa");
            } catch (\Exception $e) {
                \Cake\Log\Log::error("[ProcessPayment] Error de autorización: " . $e->getMessage());
                $this->Flash->error('No tiene permisos para procesar este pago.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Parchear el PaymentDetail con los nuevos datos
            $paymentDetail = $paymentDetailsTable->patchEntity($paymentDetail, $data, [
                'accessibleFields' => [
                    'payment_status_id' => true,
                    'amount' => true,
                    'processed_by_user_id' => true
                ]
            ]);

            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - payment_status_id: " . $paymentDetail->payment_status_id . " (tipo: " . gettype($paymentDetail->payment_status_id) . ")");
            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - amount: " . $paymentDetail->amount);
            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - processed_by_user_id: " . $paymentDetail->processed_by_user_id);

            // Verificar errores de validación
            if ($paymentDetail->hasErrors()) {
                $errors = $paymentDetail->getErrors();
                \Cake\Log\Log::error("[ProcessPayment] Errores de validación: " . json_encode($errors));
                $errorMessages = [];
                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $errorMessages[] = "$field: " . (is_array($error) ? implode(', ', $error) : $error);
                    }
                }
                $this->Flash->error('Errores de validación: ' . implode(' | ', $errorMessages));
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            \Cake\Log\Log::debug("[ProcessPayment] Entidad parcheada sin errores. Intentando guardar...");
            \Cake\Log\Log::debug("[ProcessPayment] Valores antes de guardar - payment_status_id: {$paymentDetail->payment_status_id}, amount: {$paymentDetail->amount}");

            // Guardar el PaymentDetail
            $saved = $paymentDetailsTable->save($paymentDetail);

            if ($saved) {
                \Cake\Log\Log::info("[ProcessPayment] PaymentDetail #$id actualizado exitosamente por usuario $userId");
                // Actualizar is_paid del Payment padre según el total de abonos aprobados
                $this->updateParentPaymentStatus($paymentDetail->payment_id);
                $this->Flash->success('El abono #' . $id . ' ha sido actualizado correctamente.');
            } else {
                $errors = $paymentDetail->getErrors();
                \Cake\Log\Log::error("[ProcessPayment] Error al guardar PaymentDetail #$id: " . json_encode($errors));

                if (!empty($errors)) {
                    $errorMessages = [];
                    foreach ($errors as $field => $fieldErrors) {
                        foreach ($fieldErrors as $error) {
                            $errorMessages[] = "$field: " . (is_array($error) ? implode(', ', $error) : $error);
                        }
                    }
                    $this->Flash->error('No se pudo actualizar el pago: ' . implode(' | ', $errorMessages));
                } else {
                    $this->Flash->error('No se pudo actualizar el pago. Intente de nuevo.');
                }
            }

        } catch (\Exception $e) {
            \Cake\Log\Log::error("[ProcessPayment] Excepción no manejada: " . $e->getMessage());
            \Cake\Log\Log::error("[ProcessPayment] Stack trace: " . $e->getTraceAsString());
            $this->Flash->error('Ocurrió un error inesperado al procesar el pago: ' . $e->getMessage());
        }

        return $this->redirect($this->referer(['action' => 'dashboardCashier']));
    }

    /**
     * Actualiza el is_paid del Payment padre según el total de abonos APROBADOS.
     * Solo los abonos con payment_status_id = 2 (Aprobado) cuentan para cubrir la deuda.
     * Si el total aprobado >= monto de la cuota → is_paid = true.
     * Si no, o si algún abono pasa de aprobado a pendiente/rechazado → is_paid = false.
     *
     * @param int $paymentId ID del Payment padre
     * @return void
     */
    private function updateParentPaymentStatus(int $paymentId): void
    {
        $payment = $this->Payments->get($paymentId);
        $totalDebt = (float) $payment->amount;

        $paymentDetailsTable = $this->fetchTable('Payments.PaymentDetails');
        $querySum = $paymentDetailsTable->find();
        $approvedSum = $querySum
            ->select(['total' => $querySum->func()->sum('amount')])
            ->where([
                'payment_id' => $paymentId,
                'payment_status_id' => 2, // Solo APROBADOS
            ])
            ->first();

        $totalApproved = $approvedSum && $approvedSum->total !== null
            ? (float) $approvedSum->total
            : 0.0;

        $isPaid = $totalApproved >= ($totalDebt - 0.01); // Margen por errores de float

        if ((bool) $payment->is_paid !== $isPaid) {
            $payment->is_paid = $isPaid;
            $this->Payments->save($payment);
            \Cake\Log\Log::info("[ProcessPayment] Payment #$paymentId actualizado: is_paid=" . ($isPaid ? 'true' : 'false') . " (abonos aprobados: B/. $totalApproved, deuda: B/. $totalDebt)");
        }
    }

    /**
     * Sirve el archivo del comprobante de forma segura desde /resources o /webroot/uploads.
     * Solo accesible para usuarios autorizados vía Policy.
     */
    public function serveReceipt($id = null)
    {
        // Modificado para buscar en PaymentDetails en lugar de Payments
        $paymentDetailsTable = $this->fetchTable('Payments.PaymentDetails');

        try {
            /** @var \Payments\Model\Entity\PaymentDetail $detail */
            $detail = $paymentDetailsTable->get($id, [
                'contain' => ['Payments'] // Necesitamos el Payment padre para autorización si se requiere
            ]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            throw new \Cake\Http\Exception\NotFoundException("Detalle de pago no encontrado.");
        }

        // Autorización: usamos el payment padre como proxy para permission 'seeReceipt'
        // Asumimos que la Policy de Payment aplica aquí
        try {
            $this->Authorization->authorize($detail->payment, 'seeReceipt');
        } catch (\Exception $e) {
            throw $e;
        }

        if (!$detail->proof_image) {
            throw new \Cake\Http\Exception\NotFoundException("Este pago no tiene comprobante.");
        }

        // Limpieza de ruta: Extraer solo el nombre del archivo
        $filename = basename(str_replace(['\\', '/'], DS, (string) $detail->proof_image));

        // Intentar encontrarlo en las carpetas estándar
        $possiblePaths = [
            ROOT . DS . 'resources' . DS . 'receipts' . DS . $filename,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            throw new \Cake\Http\Exception\NotFoundException("Archivo físico no encontrado.");
        }

        return $this->response->withFile($filePath);
    }
}
