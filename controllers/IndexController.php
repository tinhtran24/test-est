<?php namespace controllers;

use core\Controller;
use core\FlashCookies;
use core\Paginator;
use core\Auth;
use core\Router;
use models\LoginForm;
use models\TodoItemModel;

class IndexController extends Controller
{
    public function actionIndex(int $p = 1, string $sort = 'id', string $order = 'asc')
    {
        $model = new TodoItemModel;
        $onPageLimit = 3;
        $paginator = new Paginator($p, $model->countAllItems(), $onPageLimit);
        $itemsOffset = $paginator->calcItemsOffset();

        $this->renderViewfile('index', [
            'items' => $model->loadPaginatedItems($itemsOffset, $onPageLimit, $sort, $order),
            'errors' => (new FlashCookies(ItemController::ADD_ERRORS_COOKIE))->getData(),
            'currentPage' => $p,
            'lastPage' => $paginator->getLastPage(),
            'sortField' => $sort,
            'sortOrder' => $order,
            'isAdmin' => (new Auth)->isLogged()
        ]);
    }

    public function actionLogin()
    {
        if (empty($_POST)) {
            $this->renderViewfile('login', [
                'errors' => $this->loadErrors('login-errors')
            ]);
            return;
        }

        $errors = (new LoginForm)->run($_POST);
        if (!empty($errors)) $this->saveErrorsAndGoBack('login-errors', $errors);
        (new Router)->go('/');
    }

    public function actionLogout()
    {
        (new Auth)->logout();
        (new Router)->go('/');
    }
}