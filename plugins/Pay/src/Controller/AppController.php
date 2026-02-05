<?php
declare(strict_types=1);

namespace Pay\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;

class AppController extends BaseController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authorization.Authorization');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authorization->skipAuthorization(['pay', 'dashboardCashier']);
    }
}
