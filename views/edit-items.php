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
                    <div class="col-auto"><a href="/" class="btn btn-primary">Home</a></div>
                </ul>
            </nav>
        </div>
        <?php if ($isAdmin) : ?>
            <div class="col-auto"><a href="/logout" class="btn btn-primary">Logout</a></div>|
        <?php else : ?>
            <div class="col-auto"><a href="/login" class="btn btn-primary">Login</a></div>
        <?php endif ?>
    </div>

    <div id="todo-tbody"></div>

    <h4>Edit task</h4>
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

    <form action="/item/update?id=<?= $item[0]['id'] ?>" method="post">
        <div class="form-row align-items-center">
            <div class="col-2">
                <input type="text" name="work_name" value=<?= $item[0]['work_name'] ?> class="form-control mb-2" placeholder="Work Name">
            </div>
            <div class="col-2">
                <input type="date" name="start_date" value=<?= $item[0]['start_date'] ?> class="form-control mb-2" placeholder="Start Date">
            </div>
            <div class="col-md">
                <input type="date" name="end_date" value=<?= $item[0]['end_date'] ?> class="form-control mb-2" placeholder="End date">
            </div>
            <div class="col-md">
                <select name="status">
                    <option value="<?= $item[0]['status'] ?>" disabled selected><?= $item[0]['status'] ?></option>
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
                        window.alert('Delete success')
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