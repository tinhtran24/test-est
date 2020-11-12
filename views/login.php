<?php
use models\LoginForm;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/styles.css">
    <title>Sign In</title>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <?php if (in_array(LoginForm::ERROR_NO_LOGIN, $errors)) : ?>
                <div class="alert alert-danger" role="alert">Login success</div>
            <?php endif ?>
            <?php if (in_array(LoginForm::ERROR_NO_PASSWORD, $errors)) : ?>
                <div class="alert alert-danger" role="alert">Username/Password wrong</div>
            <?php endif ?>
            <?php if (in_array(LoginForm::ERROR_FAIL_ACCESS, $errors)) : ?>
                <div class="alert alert-danger" role="alert">Username/Password wrong</div>
            <?php endif ?>
            <form action="/login" method="post">
                <div class="form-group">
                    <label for="inputLogin">UserName</label>
                    <input type="text" name="login" class="form-control" id="inputLogin">
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword">
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
                <a href="/"><button type="button" class="btn btn-outline-secondary">Cancel</button></a>
            </form>
        </div>
    </div>
</div>
</body>

</html>