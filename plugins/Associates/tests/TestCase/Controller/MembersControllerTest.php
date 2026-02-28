<?php
declare(strict_types=1);

namespace Associates\Test\TestCase\Controller;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Entity;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use Associates\Controller\MembersController;

class TestMembersController extends \Associates\Controller\MembersController
{
    public $Members;
    public $Authorization;
    public $Flash;
}

class MembersTableContainStub
{
    public function __construct(private EntityInterface $entityToReturn) {}

    public function get(int $id, array $contain = []): EntityInterface
    {
        return $this->entityToReturn;
    }
}

final class MembersControllerTest extends TestCase
{
    private function makeController(ServerRequest $request, ?Response $response = null, array $onlyMethods = []): MembersController
    {
        $response ??= $this->createMock(Response::class);

        $defaultOnly = [
            'paginate',
            'redirect',
            'fetchTable',
            'set',
        ];
        $onlyMethods = $onlyMethods ?: $defaultOnly;

       
        $controller = $this->getMockBuilder(TestMembersController::class)
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
            ->onlyMethods(['select','where','contain','order','orderBy','count','first','toArray','getIterator'])
            ->getMock();

        $q->method('select')->willReturn($q);
        $q->method('where')->willReturn($q);
        $q->method('contain')->willReturn($q);
        $q->method('order')->willReturn($q);
        $q->method('orderBy')->willReturn($q);

        $q->method('toArray')->willReturn($items);
        $q->method('count')->willReturn(count($items));
        $q->method('first')->willReturn($items[0] ?? null);
        $q->method('getIterator')->willReturn(new ResultSet($items));

        return $q;
    }

    public function testIndexSetsMembers(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $membersTable = $this->tableMock(['find']);
        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['applyScope'])->getMock();

        $query = $this->selectQueryMock();
        $scoped = $this->selectQueryMock();

        $membersTable->expects($this->once())->method('find')->willReturn($query);

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

        $controller->Members = $membersTable;
        $controller->Authorization = $authorization;

        $controller->index();

        $this->assertSame($paginated, $viewVars['members']);
    }

    public function testViewAuthorizesAndSetsMember(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'GET']]);
        $controller = $this->makeController($request);

        $member = $this->entity(['member_id' => 1]);
        $controller->Members = new MembersTableContainStub($member);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($member);

        $viewVars = [];
        $controller->method('set')->willReturnCallback(function ($vars) use (&$viewVars) {
            $viewVars = array_merge($viewVars, $vars);
        });

        $controller->Authorization = $authorization;

        $controller->view(1);

        $this->assertSame($member, $viewVars['member']);
    }

    public function testAddPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody([
                'first_name' => 'Juan',
                'last_name' => 'Perez',
            ]);

        $controller = $this->makeController($request);

        $entity = $this->entity(['member_id' => null]);

        $membersTable = $this->tableMock(['newEmptyEntity', 'patchEntity', 'save']);
        $membersTable->method('newEmptyEntity')->willReturn($entity);
        $membersTable->expects($this->once())->method('patchEntity')->with($entity, $request->getParsedBody())->willReturn($entity);
        $membersTable->expects($this->once())->method('save')->with($entity)->willReturn($entity);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Members = $membersTable;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->add();
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testEditPostSuccessRedirects(): void
    {
        $request = (new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]))
            ->withParsedBody(['phone' => '6000-0000']);

        $controller = $this->makeController($request);

        $entity = $this->entity(['member_id' => 3]);

        $membersStub = new class($entity) extends MembersTableContainStub {
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

        $controller->Members = $membersStub;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->edit(3);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testDeleteRedirects(): void
    {
        $request = new ServerRequest(['environment' => ['REQUEST_METHOD' => 'POST']]);
        $controller = $this->makeController($request);

        $entity = $this->entity(['member_id' => 7]);

        $membersTable = $this->tableMock(['get', 'delete']);
        $membersTable->expects($this->once())->method('get')->with(7)->willReturn($entity);
        $membersTable->expects($this->once())->method('delete')->with($entity)->willReturn(true);

        $authorization = $this->getMockBuilder(\stdClass::class)->addMethods(['authorize'])->getMock();
        $authorization->expects($this->once())->method('authorize')->with($entity);

        $flash = $this->getMockBuilder(\stdClass::class)->addMethods(['success', 'error'])->getMock();
        $flash->expects($this->once())->method('success');

        $controller->expects($this->once())->method('redirect')
            ->with(['action' => 'index'])
            ->willReturn($this->createMock(Response::class));

        $controller->Members = $membersTable;
        $controller->Authorization = $authorization;
        $controller->Flash = $flash;

        $res = $controller->delete(7);
        $this->assertInstanceOf(Response::class, $res);
    }
}