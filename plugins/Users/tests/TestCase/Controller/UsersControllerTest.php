<?php
declare(strict_types=1);

namespace Users\Test\TestCase\Controller;

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
use Users\Controller\UsersController;
use Authentication\Authenticator\ResultInterface;

class TestUsersController extends \Users\Controller\UsersController
{
    public $Users;
    public $Authentication;
    public $Flash;
}

class UsersTableStub
{
    public function __construct(private EntityInterface $entityToReturn)
    {
    }

    public function get(int $id, array $contain = []): EntityInterface
    {
        return $this->entityToReturn;
    }
}

final class UsersControllerTest extends TestCase
{
    private function makeController(ServerRequest $request, ?Response $response = null, array $onlyMethods = []): UsersController
    {
        $response ??= $this->createMock(Response::class);

        $defaultOnly = [
            'initialize',
            'paginate',
            'redirect',
            'referer',
            'fetchTable',
            'viewBuilder',
            'set',
        ];
        $onlyMethods = $onlyMethods ?: $defaultOnly;

        $controller = $this->getMockBuilder(TestUsersController::class)
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
        $tbl = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();

        return $tbl;
    }

    private function selectQueryMock(array $items = []): SelectQuery
    {
        $q = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'where', 'contain', 'order', 'orderBy', 'count', 'first', 'toArray', 'getIterator', 'matching', 'distinct'])
            ->getMock();

        $q->method('select')->willReturn($q);
        $q->method('where')->willReturn($q);
        $q->method('contain')->willReturn($q);
        $q->method('order')->willReturn($q);
        $q->method('orderBy')->willReturn($q);
        $q->method('matching')->willReturn($q);
        $q->method('distinct')->willReturn($q);

        $q->method('toArray')->willReturn($items);
        $q->method('count')->willReturn(count($items));
        $q->method('first')->willReturn($items[0] ?? null);
        $q->method('getIterator')->willReturn(new ResultSet($items));

        return $q;
    }

    public function testIndexSetsUsers(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $usersTable = $this->tableMock(['find']);
        $query = $this->selectQueryMock();

        $usersTable->expects($this->once())->method('find')->willReturn($query);

        $paginated = $this->createMock(PaginatedInterface::class);
        $controller->expects($this->once())->method('paginate')->with($query)->willReturn($paginated);

        $viewVars = [];
        $controller->expects($this->once())->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->Users = $usersTable;

        $controller->index();

        $this->assertSame($paginated, $viewVars['users']);
    }

    public function testViewSetsUser(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $user = $this->entity(['id' => 1]);
        $controller->Users = new UsersTableStub($user);

        $viewVars = [];
        $controller->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->view(1);

        $this->assertSame($user, $viewVars['user']);
    }

    public function testAddPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody(['email' => 'test@test.com', 'role_id' => 2]);

        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => null]);

        $usersTable = $this->tableMock(['newEmptyEntity', 'patchEntity', 'save']);
        $usersTable->method('newEmptyEntity')->willReturn($entity);
        $usersTable->expects($this->once())->method('patchEntity')->willReturn($entity);
        $usersTable->expects($this->once())->method('save')->with($entity)->willReturn($entity);

        $rolesTable = $this->tableMock(['find']);
        $qRoles = $this->selectQueryMock();
        $rolesTable->method('find')->willReturn($qRoles);

        $controller->method('fetchTable')->with('Users.Roles')->willReturn($rolesTable);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Users = $usersTable;

        $res = $controller->add();
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testEditPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody(['email' => 'edit@test.com']);

        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => 3]);

        $usersStub = new class ($entity) extends UsersTableStub {
            public function patchEntity($e, $data)
            {
                return $e; }
            public function save($e)
            {
                return $e; }
        };

        $rolesTable = $this->tableMock(['find']);
        $qRoles = $this->selectQueryMock();
        $rolesTable->method('find')->willReturn($qRoles);

        $controller->method('fetchTable')->with('Users.Roles')->willReturn($rolesTable);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Users = $usersStub;

        $res = $controller->edit(3);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testDeleteRedirects(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]);
        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => 7]);

        $usersTable = $this->tableMock(['get', 'delete']);
        $usersTable->expects($this->once())->method('get')->with(7)->willReturn($entity);
        $usersTable->expects($this->once())->method('delete')->with($entity)->willReturn(true);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Users = $usersTable;

        $res = $controller->delete(7);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testLoginGetSetsLayout(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $vb = $this->createMock(ViewBuilder::class);
        $vb->expects($this->once())->method('setLayout')->with('auth');
        $controller->expects($this->once())->method('viewBuilder')->willReturn($vb);

        $resultMock = $this->createMock(ResultInterface::class);
        $resultMock->method('isValid')->willReturn(false);

        $auth = $this->getMockBuilder(\stdClass::class)->addMethods(['getResult', 'allowUnauthenticated'])->getMock();
        $auth->expects($this->once())->method('getResult')->willReturn($resultMock);

        $controller->Authentication = $auth;

        $controller->login();
    }

    public function testLogoutRedirects(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $auth = $this->getMockBuilder(\stdClass::class)->addMethods(['logout'])->getMock();
        $auth->expects($this->once())->method('logout');
        $controller->Authentication = $auth;

        $controller->expects($this->once())->method('redirect')->willReturn($this->createMock(Response::class));

        $res = $controller->logout();
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testToggleUserStatusUpdatesAndRedirects(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]);
        $controller = $this->makeController($request);

        $entity = $this->entity(['id' => 2, 'status' => 'activo']);

        $usersTable = $this->tableMock(['get', 'save']);
        $usersTable->expects($this->once())->method('get')->with(2)->willReturn($entity);
        $usersTable->expects($this->once())->method('save')->with($entity)->willReturn($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');
        $controller->Flash = $flash;

        $controller->expects($this->once())->method('referer')->willReturn('/admin-dashboard');
        $controller->expects($this->once())->method('redirect')->with('/admin-dashboard')->willReturn($this->createMock(Response::class));

        $controller->Users = $usersTable;

        $res = $controller->toggleUserStatus(2);
        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals('inactivo', $entity->status);
    }
}
