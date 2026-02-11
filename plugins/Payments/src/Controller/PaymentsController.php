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
        $statusFilter = $this->request->getQuery('status', 'pending');

        // Resumen Estadístico (Dashboard Header)
        $today = date('Y-m-d');

        // 1. Total Cobrado Hoy (Solo Aprobados)
        $querySum = $this->Payments->find();
        $sumResult = $querySum->select(['total' => $querySum->func()->sum('amount')])
            ->where(['DATE(payment_date)' => $today, 'payment_status_id' => 2])
            ->first();

        // 2. Pagos Procesados Hoy (Aprobados o Rechazados)
        $procesadosHoy = $this->Payments->find()
            ->where([
                'DATE(payment_date)' => $today,
                'payment_status_id IN' => [2, 3]
            ])
            ->count();

        // 3. Pendientes Totales (No solo hoy)
        $pendientesTotales = $this->Payments->find()
            ->where(['payment_status_id' => 1])
            ->count();

        $resumen = [
            'cobrado_hoy' => $sumResult ? (float) $sumResult->total : 0,
            'pagos_procesados_hoy' => $procesadosHoy,
            'pendientes_totales' => $pendientesTotales,
        ];

        // Lógica de Filtrado para la Tabla
        $query = $this->Payments->find()
            ->contain(['Associates', 'PaymentMethods', 'PaymentStatuses']);

        if ($statusFilter === 'pending') {
            $query->where(['Payments.payment_status_id' => 1]);
        } elseif ($statusFilter === 'processed') {
            $query->where(['Payments.payment_status_id IN' => [2, 3]]);
        }
        // Si es 'all', no aplicamos filtro de status

        $query->orderBy(['Payments.payment_date' => 'DESC']);

        $pagos = $this->paginate($query, ['limit' => 20]);
        $paymentStatuses = $this->fetchTable('Payments.PaymentStatuses')->find('list')->toArray();

        $this->set(compact('user', 'resumen', 'pagos', 'statusFilter', 'paymentStatuses'));
    }

    /**
     * Procesa un pago: cambia monto, estado y asigna el cajero responsable.
     */
    public function processPayment($id = null)
    {
        \Cake\Log\Log::debug("[ProcessPayment] Iniciando procesamiento para ID: $id");
        
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

            // Obtener el pago
            try {
                $payment = $this->Payments->get($id);
                \Cake\Log\Log::debug("[ProcessPayment] Pago encontrado: ID=$id, Estado actual=" . $payment->payment_status_id . ", Monto actual=" . $payment->amount);
            } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                \Cake\Log\Log::error("[ProcessPayment] Pago no encontrado con ID: $id - " . $e->getMessage());
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

            // Autorizar usando la Policy
            try {
                $this->Authorization->authorize($payment);
                \Cake\Log\Log::debug("[ProcessPayment] Autorización exitosa");
            } catch (\Exception $e) {
                \Cake\Log\Log::error("[ProcessPayment] Error de autorización: " . $e->getMessage());
                $this->Flash->error('No tiene permisos para procesar este pago.');
                return $this->redirect($this->referer(['action' => 'dashboardCashier']));
            }

            // Parchear la entidad con los nuevos datos
            // Usar ['accessibleFields' => ['*' => true]] para asegurar que todos los campos se parcheen
            $payment = $this->Payments->patchEntity($payment, $data, [
                'accessibleFields' => [
                    'payment_status_id' => true,
                    'amount' => true,
                    'processed_by_user_id' => true
                ]
            ]);
            
            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - payment_status_id: " . $payment->payment_status_id . " (tipo: " . gettype($payment->payment_status_id) . ")");
            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - amount: " . $payment->amount);
            \Cake\Log\Log::debug("[ProcessPayment] Después de patchEntity - processed_by_user_id: " . $payment->processed_by_user_id);
            \Cake\Log\Log::debug("[ProcessPayment] ¿La entidad está dirty? " . ($payment->isDirty('payment_status_id') ? 'SÍ' : 'NO'));
            \Cake\Log\Log::debug("[ProcessPayment] Campos dirty: " . implode(', ', $payment->getDirty()));
            
            // Verificar errores de validación
            if ($payment->hasErrors()) {
                $errors = $payment->getErrors();
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
            \Cake\Log\Log::debug("[ProcessPayment] Valores antes de guardar - payment_status_id: {$payment->payment_status_id}, amount: {$payment->amount}");

            // Guardar el pago
            $saved = $this->Payments->save($payment);
            
            if ($saved) {
                // Verificar que realmente se guardó
                $paymentAfterSave = $this->Payments->get($id);
                \Cake\Log\Log::info("[ProcessPayment] Pago #$id actualizado exitosamente por usuario $userId");
                \Cake\Log\Log::debug("[ProcessPayment] Valores después de guardar - payment_status_id: {$paymentAfterSave->payment_status_id}, amount: {$paymentAfterSave->amount}");
                $this->Flash->success('El pago #' . $id . ' ha sido actualizado correctamente.');
            } else {
                $errors = $payment->getErrors();
                \Cake\Log\Log::error("[ProcessPayment] Error al guardar pago #$id: " . json_encode($errors));
                
                // Mostrar errores específicos si existen
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
     * Sirve el archivo del comprobante de forma segura desde /resources o /webroot/uploads.
     * Solo accesible para usuarios autorizados vía Policy.
     */
    public function serveReceipt($id = null)
    {
        // Cargamos el pago con su asociado para que la Policy pueda validar la propiedad si fuera necesario
        /** @var \Payments\Model\Entity\Payment $payment */
        $payment = $this->Payments->get($id, contain: ['Associates']);

        \Cake\Log\Log::debug("[ServeReceipt] Request for ID: $id. DB Path: " . $payment->proof_image);

        // Log to a separate file for easy access
        file_put_contents(ROOT . DS . 'debug_serve.txt', date('[Y-m-d H:i:s] ') . "Request ID: $id - DB Path: " . $payment->proof_image . PHP_EOL, FILE_APPEND);

        // Verificar permiso usando la Policy (seeReceipt)
        try {
            $this->Authorization->authorize($payment, 'seeReceipt');
        } catch (\Exception $e) {
            file_put_contents(ROOT . DS . 'debug_serve.txt', "  AUTHORIZATION FAILED: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            throw $e;
        }

        if (!$payment->proof_image) {
            \Cake\Log\Log::error("[ServeReceipt] Payment #$id has no proof_image string in DB.");
            file_put_contents(ROOT . DS . 'debug_serve.txt', "  ERROR: No proof_image in DB" . PHP_EOL, FILE_APPEND);
            throw new \Cake\Http\Exception\NotFoundException("Este pago no tiene comprobante.");
        }

        // Limpieza de ruta: Extraer solo el nombre del archivo
        $filename = basename(str_replace(['\\', '/'], DS, (string) $payment->proof_image));

        // Intentar encontrarlo en las carpetas estándar
        $possiblePaths = [
            ROOT . DS . 'resources' . DS . 'receipts' . DS . $filename,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            \Cake\Log\Log::debug("[ServeReceipt] Checking existence of: $path");
            $exists = file_exists($path);
            file_put_contents(ROOT . DS . 'debug_serve.txt', "  Checking: $path - " . ($exists ? "EXISTS" : "NOT FOUND") . PHP_EOL, FILE_APPEND);
            if ($exists) {
                $filePath = $path;
                \Cake\Log\Log::debug("[ServeReceipt] FOUND file at: $path");
                break;
            }
        }

        if (!$filePath) {
            \Cake\Log\Log::error("[ServeReceipt] File not found for Payment #$id. Tested: " . implode(', ', $possiblePaths));
            file_put_contents(ROOT . DS . 'debug_serve.txt', "  ERROR: File not found on disk" . PHP_EOL, FILE_APPEND);
            throw new \Cake\Http\Exception\NotFoundException("Archivo físico no encontrado.");
        }

        \Cake\Log\Log::debug("[ServeReceipt] Serving file: $filePath");
        file_put_contents(ROOT . DS . 'debug_serve.txt', "  SUCCESS: Serving $filePath" . PHP_EOL, FILE_APPEND);
        return $this->response->withFile($filePath);
    }
}
