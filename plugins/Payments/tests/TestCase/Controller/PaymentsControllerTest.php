<?php
declare(strict_types=1);

namespace Payments\Test\TestCase\Controller;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Entity;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use Cake\View\ViewBuilder;
use Payments\Controller\PaymentsController;
use Cake\Database\FunctionsBuilder;

class TestPaymentsController extends \Payments\Controller\PaymentsController
{
    public $Payments;
    public $Authorization;
    public $Flash;
}

class PaymentsTableContainStub
{
    public function __construct(private EntityInterface $entityToReturn) {}

    public function get(int $id, array $contain = []): EntityInterface
    {
        return $this->entityToReturn;
    }
}

final class PaymentsControllerTest extends TestCase
{
    private function makeController(ServerRequest $request, ?Response $response = null, array $onlyMethods = []): PaymentsController
    {
        $response ??= $this->createMock(Response::class);

        $defaultOnly = [
            'paginate',
            'redirect',
            'referer',
            'fetchTable',
            'viewBuilder',
            'set',
        ];
        $onlyMethods = $onlyMethods ?: $defaultOnly;

        /** @var PaymentsController $controller */
        $controller = $this->getMockBuilder(TestPaymentsController::class)
            ->onlyMethods($onlyMethods)
            ->setConstructorArgs([$request, $response])
            ->getMock();

        $controller->setRequest($request);
        $controller->setResponse($response);

        return $controller;
    }

    private function entity(array $data = []): EntityInterface
    {
        return new Entity($data);
    }

    private function tableMock(array $methods): Table
    {
        /** @var Table $tbl */
        $tbl = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();

        return $tbl;
    }

    private function selectQueryMock(array $items = [], mixed $first = null): SelectQuery
    {
        /** @var SelectQuery $q */
        $q = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select','where','contain','order','orderBy','count','first','func','toArray','getIterator'])
            ->getMock();

        $q->method('select')->willReturn($q);
        $q->method('where')->willReturn($q);
        $q->method('contain')->willReturn($q);
        $q->method('order')->willReturn($q);
        $q->method('orderBy')->willReturn($q);

        $q->method('toArray')->willReturn($items);
        $q->method('count')->willReturn(count($items));
        $q->method('first')->willReturn($first);

        // ResultSet real (no mock) para que Cake no se queje con tipos
        $q->method('getIterator')->willReturn(new ResultSet($items));

        // FunctionsBuilder real (no mock) evita errores de return types
        $q->method('func')->willReturn(new FunctionsBuilder());

        return $q;
    }

    public function testIndexSetsPayments(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $paymentsTable = $this->tableMock(['find']);
        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['applyScope'])->getMock();

        $query = $this->selectQueryMock();
        $scoped = $this->selectQueryMock();

        $paymentsTable->expects($this->once())->method('find')->willReturn($query);

        $authorization->expects($this->once())
            ->method('applyScope')
            ->with($query)
            ->willReturn($scoped);

        $paginated = $this->createMock(PaginatedInterface::class);
        $controller->expects($this->once())->method('paginate')->with($scoped)->willReturn($paginated);

        $viewVars = [];
        $controller->expects($this->once())->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->Payments = $paymentsTable;
        $controller->Authorization = $authorization;

        $controller->index();

        $this->assertSame($paginated, $viewVars['payments']);
    }

    public function testViewAuthorizes(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $payment = $this->entity(['id' => 9]);
        $controller->Payments = new PaymentsTableContainStub($payment);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($payment);

        $viewVars = [];
        $controller->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->Authorization = $authorization;

        $controller->view(9);

        $this->assertSame($payment, $viewVars['payment']);
    }

    public function testAddPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody(['amount' => '10.00']);

        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => null]);

        $paymentsTable = $this->tableMock(['newEmptyEntity', 'patchEntity', 'save']);
        $paymentsTable->method('newEmptyEntity')->willReturn($entity);
        $paymentsTable->expects($this->once())->method('patchEntity')->with($entity, ['amount' => '10.00'])->willReturn($entity);
        $paymentsTable->expects($this->once())->method('save')->with($entity)->willReturn($entity);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Payments = $paymentsTable;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->add();
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testEditPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody(['amount' => '25.00']);

        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => 3]);

        $paymentsStub = new class($entity) extends PaymentsTableContainStub {
            public function patchEntity($e, $data) { return $e; }
            public function save($e) { return $e; }
        };

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Payments = $paymentsStub;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->edit(3);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testDeleteRedirects(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]);
        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => 7]);

        $paymentsTable = $this->tableMock(['get', 'delete']);
        $paymentsTable->expects($this->once())->method('get')->with(7)->willReturn($entity);
        $paymentsTable->expects($this->once())->method('delete')->with($entity)->willReturn(true);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Payments = $paymentsTable;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->delete(7);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testPayWithoutIdentityRedirectsToLogin(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $vb = $this->createMock(ViewBuilder::class);
        $vb->expects($this->once())->method('setLayout')->with('dashboard');
        $controller->expects($this->once())->method('viewBuilder')->willReturn($vb);

        $controller->expects($this->once())->method('redirect')->with([
            'plugin' => 'Users',
            'controller' => 'Users',
            'action' => 'login',
        ])->willReturn($this->createMock(Response::class));

        $res = $controller->pay();
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testPayPostInvalidDebtShowsErrorAndNoRedirect(): void
    {
        $identity = new class { public function getIdentifier() { return 123; } };

        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withAttribute('identity', $identity)
            ->withParsedBody([
                'payment_id' => 999,
                'amount' => '5.00',
            ]);

        $controller = $this->makeController($request);

        $vb = $this->createMock(ViewBuilder::class);
        $controller->method('viewBuilder')->willReturn($vb);

        $assoc = $this->entity(['id' => 1]);

        $associatesTable = $this->tableMock(['find']);
        $qAssoc = $this->selectQueryMock([], $assoc);
        $associatesTable->method('find')->willReturn($qAssoc);

        $pmTable = $this->tableMock(['find']);
        $qPm = $this->selectQueryMock([1 => 'Efectivo']);
        $pmTable->method('find')->willReturn($qPm);

        $pdTable = $this->tableMock(['newEmptyEntity']);
        $pdTable->method('newEmptyEntity')->willReturn($this->entity());

        $controller->method('fetchTable')->willReturnCallback(
            function (string $alias) use ($associatesTable, $pmTable, $pdTable): Table {
                return match ($alias) {
                    'Associates.Associates' => $associatesTable,
                    'Payments.PaymentMethods' => $pmTable,
                    'Payments.PaymentDetails' => $pdTable,
                    default => throw new \RuntimeException("Unexpected table: $alias"),
                };
            }
        );

        $paymentsTable = $this->tableMock(['find']);
        $qDebts = $this->selectQueryMock([]); // debts vacías
        $paymentsTable->method('find')->willReturn($qDebts);
        $controller->Payments = $paymentsTable;

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->atLeastOnce())->method('error');
        $controller->Flash = $flash;

        $viewVars = [];
        $controller->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->pay();

        $this->assertArrayHasKey('debts', $viewVars);
        $this->assertSame([], $viewVars['debts']);
    }

    public function testDashboardCashierSetsResumenAndPagos(): void
    {
        $identity = new class { public function getIdentifier() { return 50; } };

        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]))
            ->withAttribute('identity', $identity);

        $controller = $this->makeController($request);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['skipAuthorization'])->getMock();
        $authorization->expects($this->once())->method('skipAuthorization');
        $controller->Authorization = $authorization;

        $vb = $this->createMock(ViewBuilder::class);
        $vb->expects($this->once())->method('setLayout')->with('dashboard');
        $controller->expects($this->once())->method('viewBuilder')->willReturn($vb);

        $paymentDetailsTable = $this->tableMock(['find']);

        $qSum = $this->selectQueryMock([], (object)['total' => 100]);
        $qHoy = $this->selectQueryMock([1,2]);
        $qPend = $this->selectQueryMock([1,2,3]);
        $qMain = $this->selectQueryMock([]);

        $paymentDetailsTable->method('find')->willReturnOnConsecutiveCalls($qSum, $qHoy, $qPend, $qMain);

        $statusTable = $this->tableMock(['find']);
        $qStatus = $this->selectQueryMock([1 => 'Pendiente', 2 => 'Aprobado']);
        $statusTable->method('find')->willReturn($qStatus);

        $controller->method('fetchTable')->willReturnCallback(function ($alias) use ($paymentDetailsTable, $statusTable): Table {
            return match ($alias) {
                'Payments.PaymentDetails' => $paymentDetailsTable,
                'Payments.PaymentStatuses' => $statusTable,
                default => throw new \RuntimeException("Unexpected table: $alias"),
            };
        });

        $paginated = $this->createMock(PaginatedInterface::class);
        $controller->expects($this->once())->method('paginate')->willReturn($paginated);

        $viewVars = [];
        $controller->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->dashboardCashier();

        $this->assertSame($paginated, $viewVars['pagos']);
        $this->assertSame(100.0, $viewVars['resumen']['cobrado_hoy']);
        $this->assertSame(2, $viewVars['resumen']['pagos_procesados_hoy']);
        $this->assertSame(3, $viewVars['resumen']['pendientes_totales']);
    }

    public function testProcessPaymentMissingIdRedirectsBack(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]);
        $controller = $this->makeController($request);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['error', 'success'])->getMock();
        $flash->expects($this->once())->method('error')->with('ID de pago no válido.');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('referer')->willReturn('/back');
        $controller->expects($this->once())->method('redirect')->with('/back')->willReturn($this->createMock(Response::class));

        $res = $controller->processPayment(null);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testProcessPaymentNoDataRedirectsBack(): void
    {
        $identity = new class { public function getIdentifier() { return 10; } };

        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withAttribute('identity', $identity)
            ->withParsedBody([]);

        $controller = $this->makeController($request);

        $payment = $this->entity(['id' => 1]);
        $detail  = $this->entity(['id' => 5, 'payment' => $payment, 'payment_id' => 1]);

        $pdTable = $this->tableMock(['get']);
        $pdTable->method('get')->willReturn($detail);

        $controller->method('fetchTable')->with('Payments.PaymentDetails')->willReturn($pdTable);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['error', 'success'])->getMock();
        $flash->expects($this->once())->method('error')->with('No se recibieron datos para actualizar.');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('referer')->willReturn('/back');
        $controller->expects($this->once())->method('redirect')->with('/back')->willReturn($this->createMock(Response::class));

        $res = $controller->processPayment(5);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testProcessPaymentSuccessSavesAndRedirectsBack(): void
    {
        $identity = new class { public function getIdentifier() { return 99; } };

        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withAttribute('identity', $identity)
            ->withParsedBody([
                'payment_status_id' => '2',
                'amount' => '15.50',
            ]);

        $controller = $this->makeController($request);

        $paymentParent = $this->entity(['id' => 1, 'amount' => 15.50, 'is_paid' => false]);
        $detail = $this->entity(['id' => 11, 'payment' => $paymentParent, 'payment_id' => 1]);

        $pdTable = $this->tableMock(['get', 'patchEntity', 'save', 'find']);
        $pdTable->method('get')->willReturn($detail);
        $pdTable->method('patchEntity')->willReturn($detail);
        $pdTable->method('save')->willReturn($detail);

        $qSum = $this->selectQueryMock([], (object)['total' => 15.50]);
        $pdTable->method('find')->willReturn($qSum);

        $paymentsTable = $this->tableMock(['get', 'save']);
        $paymentsTable->method('get')->with(1)->willReturn($paymentParent);
        $paymentsTable->method('save')->willReturn($paymentParent);
        $controller->Payments = $paymentsTable;

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($paymentParent, 'processPayment');
        $controller->Authorization = $authorization;

        $controller->method('fetchTable')->with('Payments.PaymentDetails')->willReturn($pdTable);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['error', 'success'])->getMock();
        $flash->expects($this->once())->method('success');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('referer')->willReturn('/back');
        $controller->expects($this->once())->method('redirect')->with('/back')->willReturn($this->createMock(Response::class));

        $res = $controller->processPayment(11);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testServeReceiptWithoutProofThrowsNotFound(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request, null, ['fetchTable']);

        $detail = $this->entity([
            'proof_image' => null,
            'payment' => $this->entity(['id' => 1]),
        ]);

        $pdTable = $this->tableMock(['get']);
        $pdTable->method('get')->willReturn($detail);

        $controller->method('fetchTable')->with('Payments.PaymentDetails')->willReturn($pdTable);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($detail->get('payment'), 'seeReceipt');
        $controller->Authorization = $authorization;

        $this->expectException(\Cake\Http\Exception\NotFoundException::class);
        $controller->serveReceipt(1);
    }
}