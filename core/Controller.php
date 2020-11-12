<?php namespace core;

abstract class Controller
{
//    @param string $name is th path of viewfile inside "views" folder
    protected function renderViewFile(string $name, array $variables = [])
    {
        $file = "views/$name.php";
        if (!file_exists($file)) echo "Error: view file $name does not exist";

        foreach ($variables as $name => $value) {
            // These local variables will be accessible in $file.
            $$name = $value;
        }
        require $file;
    }

    protected function saveErrorsAndGoBack(string $cookieName, array $errors)
    {
        (new FlashCookies($cookieName))->saveData($errors);
        (new Router)->goBack();
    }

    protected function loadErrors(string $cookieName): array
    {
        return (new FlashCookies($cookieName))->getData();
    }
}
