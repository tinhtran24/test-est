<?php

use models\TodoItemModel;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/styles.css">
    <title>Est Test</title>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item <?= $currentPage > 1 ? '' : 'disabled' ?>">
                        <a class="page-link" href="?sort=<?= $sortField ?>&order=<?= $sortOrder ?>&p=1" tabindex="-1"
                           aria-disabled="true">&laquo;</a>
                    </li>
                    <?php if ($currentPage - 1 > 0) : ?>
                        <li class="page-item"><a class="page-link"
                                                 href="?sort=<?= $sortField ?>&order=<?= $sortOrder ?>&p=<?= $currentPage - 1 ?>"><?= $currentPage - 1 ?></a>
                        </li>
                    <?php endif ?>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#"><?= $currentPage ?></a>
                    </li>
                    <?php if ($currentPage + 1 <= $lastPage) : ?>
                        <li class="page-item"><a class="page-link"
                                                 href="?sort=<?= $sortField ?>&order=<?= $sortOrder ?>&p=<?= $currentPage + 1 ?>"><?= $currentPage + 1 ?></a>
                        </li>
                    <?php endif ?>
                    <li class="page-item <?= $currentPage < $lastPage ? '' : 'disabled' ?>">
                        <a class="page-link" href="?sort=<?= $sortField ?>&order=<?= $sortOrder ?>&p=<?= $lastPage ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php if ($isAdmin) : ?>
            <div class="col-auto"><a href="/logout" class="btn btn-primary">Logout</a></div>
        <?php else : ?>
            <div class="col-auto"><a href="/login" class="btn btn-primary">Login</a></div>
        <?php endif ?>
    </div>

    <h4>List Todo</h4>
    <table class="table">
        <thead>
        <tr>
            <th>
                <a href="?sort=username&order=<?= $sortField === 'work_name'
                    ? ($sortOrder === 'asc' ? 'desc' : 'asc') : $sortOrder
                ?>&p=<?= $currentPage ?>" class="table-header-nowrap">Work Name
                    <?php if ($sortField === 'work_name') : ?>
                        <i class="fa fa-caret-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                    <?php endif ?>
                </a>
            </th>
            <th>
                <a href="?sort=email&order=<?= $sortField === 'start_date'
                    ? ($sortOrder === 'asc' ? 'desc' : 'asc')
                    : $sortOrder ?>&p=<?= $currentPage ?>"
                >StartDate
                    <?php if ($sortField === 'start_date') : ?>
                        <i class="fa fa-caret-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                    <?php endif ?>
                </a>
            </th>
            <th>
                <a href="?sort=email&order=<?= $sortField === 'end_date'
                    ? ($sortOrder === 'asc' ? 'desc' : 'asc')
                    : $sortOrder ?>&p=<?= $currentPage ?>"
                >StartDate
                    <?php if ($sortField === 'end_date') : ?>
                        <i class="fa fa-caret-<?= $sortOrder === 'asc' ? 'up' : 'down' ?>"></i>
                    <?php endif ?>
                </a>
            </th>
            <th>
                <a href="?sort=status&order=<?= $sortOrder === 'asc'
                    ? 'desc' : 'asc' ?>&p=<?= $currentPage ?>"
                >Status
                    <?php if ($sortField === 'status') : ?>
                        <i class="fa fa-caret-<?= $sortOrder === 'asc' ? 'down' : 'up' ?>"></i>
                    <?php endif ?>
                </a>
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item) : ?>
            <tr data-item-id="<?= $item['id'] ?>">
                <td class="align-middle"><span class="todo-item-text"><?= $item['work_name'] ?></span>
                    <?php if ($isAdmin) : ?>
                        <a href="#" class="todo-item-text-edit"><i class="fa fa-pencil"></i></a>
                    <?php endif ?></td>
                <td class="align-middle">
                    <span class="todo-item-text"><?= $item['start_date'] ?></span>
                    <?php if ($isAdmin) : ?>
                        <a href="#" class="todo-item-text-edit"><i class="fa fa-pencil"></i></a>
                    <?php endif ?>
                </td>
                <td class="align-middle">
                    <span class="todo-item-text"><?= $item['end_date'] ?></span>
                    <?php if ($isAdmin) : ?>
                        <a href="#" class="todo-item-text-edit"><i class="fa fa-pencil"></i></a>
                    <?php endif ?>
                </td>
                <td class="align-middle">
                    <div class="form-check form-check-inline">
                        <?php if ($isAdmin) : ?>
                            <select name="Status">
                                <option value="" disabled selected>Choose option</option>
                                <option value="Planning">Planning</option>
                                <option value="Doing">Doing</option>
                                <option value="Complete">Complete</option>
                            </select>
                        <?php endif ?>
                        <span class="form-check-label badge badge-success"><?= $item['status'] ?></span>
                    </div>
                </td>
                <td class="align-middle">
                    <?php if ($isAdmin) : ?>
                        <a href="#" class="todo-item-text-remove">Delete</a>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <div id="todo-tbody"></div>

    <h4>Add task</h4>
    <?php if (in_array(TodoItemModel::ERROR_ALL_OK, $errors)) : ?>
        <div class="alert alert-success" role="alert">Task added successfully</div>
    <?php else : ?>
        <?php if (in_array(TodoItemModel::ERROR_TEXT_EMPTY, $errors)) : ?>
            <div class="alert alert-danger" role="alert">Work name not enter</div>
        <?php endif ?>
        <?php if (in_array(TodoItemModel::ERROR_END_DATE_EMPTY, $errors)) : ?>
            <div class="alert alert-danger" role="alert">Start date not entered</div>
        <?php endif ?>
        <?php if (in_array(TodoItemModel::ERROR_START_DATE_EMPTY, $errors)) : ?>
            <div class="alert alert-danger" role="alert">End date not entered</div>
        <?php endif ?>
    <?php endif ?>

    <form action="/item/add" method="post">
        <div class="form-row align-items-center">
            <div class="col-2">
                <input type="text" name="work_name" class="form-control mb-2" placeholder="Work Name">
            </div>
            <div class="col-2">
                <input type="text" name="start_date" class="form-control mb-2" placeholder="Start Date">
            </div>
            <div class="col-md">
                <input type="text" name="end_date" class="form-control mb-2" placeholder="End date">
            </div>
            <div class="col-md">
                <select name="Status">
                    <option value="" disabled selected>Choose option</option>
                    <option value="Planning">Planning</option>
                    <option value="Doing">Doing</option>
                    <option value="Complete">Complete</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('.todo-status-checkbox').change(function () {
            var isChecked = $(this).is(':checked');
            var statusLabel = $(this).next();

            // this checkbox is located as tr > td > div > input
            var itemId = $(this).parent().parent().parent().data('item-id');

            if (isChecked) {
                statusLabel.removeClass('badge-light');
                statusLabel.addClass('badge-success');
                statusLabel.text('Выполнено');
            } else {
                statusLabel.removeClass('badge-success');
                statusLabel.addClass('badge-light');
                statusLabel.text('Не выполнено');
            }
            $.ajax({
                url: '/item/edit?id=' + itemId,
                method: 'post',
                data: {status: isChecked ? '1' : '0'},
                statusCode: {
                    403: function () {
                        window.location.replace("/login");
                    }
                }
            })
        });
        var itemDelete = function (textInput) {
            var tableCell = $(this).parent();
            var itemId = tableCell.parent().data('item-id');
            $.ajax({
                url: '/item/delete?id=' + itemId,
                method: 'post',
                statusCode: {
                    403: function () {
                        window.location.replace("/login");
                    },
                    200: function () {
                        window.location.replace("/");
                    }
                }
            })
        }

        var startItemTextEditing = function () {
            var tableCell = $(this).parent();
            var tableCellHtml = tableCell.html();
            var oldText = tableCell.find('.todo-item-text').first().text();
            var itemId = tableCell.parent().data('item-id');

            var updateText = function (textInput) {
                $.ajax({
                    url: '/item/edit?id=' + itemId,
                    method: 'post',
                    data: {text: textInput.value},
                    statusCode: {
                        403: function () {
                            window.location.replace("/login");
                        }
                    }
                })
            }

            var hideInput = function (textInput) {
                tableCell.html(tableCellHtml);
                tableCell.find('.todo-item-text').first().text(textInput.value);

                if (textInput.value !== oldText) updateText(textInput);

                // Since in the new html appears new edit button, it does not have this
                // callback, so we need to set it again.
                tableCell.find('.todo-item-text-edit').click(startItemTextEditing);
            }
            var showInput = function () {
                var textInput = document.createElement('input');
                textInput.type = 'text';
                textInput.className = 'form-control';
                textInput.value = oldText;
                $(tableCell).html(textInput);

                // Catch event when editing is finished and the text IS changed.
                $(textInput).change(function () {
                    hideInput(textInput)
                });

                // Catch event when editing is finished and the text IS NOT changed.
                $(textInput).keypress(function () {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13' && textInput.value === oldText) {
                        // Enter pressed, so we need to hide input.
                        hideInput(textInput);
                    }

                    // Stop the event from propogation to other handlers
                    // If this line will be removed, then keypress event handler attached
                    // at document level will also be triggered
                    event.stopPropagation();
                })
                textInput.focus();
            }
            showInput();
        };
        $('.todo-item-text-edit').click(startItemTextEditing);
        $('.todo-item-text-remove').click(itemDelete);
    })
</script>
</body>

</html>