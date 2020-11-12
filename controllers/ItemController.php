<?php namespace controllers;

use core\Controller;
use models\TodoItemModel;
use core\Auth;

class ItemController extends Controller
{
    const ADD_ERRORS_COOKIE = 'add-errors';

    public function actionAdd()
    {
        $item = new TodoItemModel;
        $item->workName = $_POST['work_name'] ?? '';
        $item->startDate = $_POST['start_date'] ?? '';
        $item->endDate = $_POST['end_date'] ?? '';
        $item->status = $_POST['status'] ?? 'Planning';

        $errors = $item->addItemFromPost($_POST);
        if (!empty($errors)) $this->saveErrorsAndGoBack(self::ADD_ERRORS_COOKIE, $errors);

        // There are no errors, then save ERROR_ALL_OK for callback.
        $this->saveErrorsAndGoBack(self::ADD_ERRORS_COOKIE, [TodoItemModel::ERROR_ALL_OK]);
    }

    public function actionEdit(int $id)
    {
        if (!(new Auth)->isLogged()) {
            http_response_code(403);
            return;
        }

        $model = new TodoItemModel;
        if (($status = $_POST['status'] ?? null) !== null)
            $model->updateStatus($id, $status);

        if (($newText = $_POST['work_name'] ?? null) !== null)
            $model->updateText($id, $newText);
    }

    public function actionDelete(int $id)
    {
        if (!(new Auth)->isLogged()) {
            http_response_code(403);
            return;
        }

        $model = new TodoItemModel;
        $model->deleteItem($id);
    }
}