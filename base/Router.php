<?php namespace base;

class Router
{
    public function getUrl(): string
    {
        $query = $_SERVER['QUERY_STRING'];
        return $_SERVER['REQUEST_URI'] . ($query ? "?$query" : '');
    }

    /**
     * @return array of three elements: [
     *  (string) controllerClass,
     *  (string) controllerActionMethod,
     *  (array) runParams
     * ]
     */
    public function findControllerRunConfig(): ?array
    {
        $url = $this->getUrl();
        $action = $this->parseControllerAction($url);
        if (!$action) return null;

        $controllerClass = $action[0];
        $controllerActionMethod = $action[1];

        try {
            $runParams = $this->parseControllerActionParams(
                $controllerClass,
                $controllerActionMethod,
                $url
            );
        } catch (\Exception $e) {
            return null;
        }

        return [$controllerClass, $controllerActionMethod, $runParams];
    }

    public function go(string $url)
    {
        header("Location: $url");
        exit;
    }

    public function goBack()
    {
        $this->go($_SERVER['HTTP_REFERER'] ?? '/');
    }

    /**
     * @return array of two elements: [
     *  (string) controllerClass,
     *  (string) controllerActionMethod
     * ]
     */
    private function parseControllerAction(string $url): ?array
    {
        $urlPath = trim(parse_url($url, PHP_URL_PATH), '/');
        $pathParts = explode('/', $urlPath);

        $controllerSubdir = implode('/', array_slice($pathParts, 0, -2));
        $controllerName = ucfirst($pathParts[count($pathParts) - 2] ?? 'Index');

        $controllerAction = ucfirst($pathParts[count($pathParts) - 1]);
        if (!$controllerAction) $controllerAction = 'Index';

        $controllerClass = 'controllers'
            . ($controllerSubdir ? "\\$controllerSubdir" : '')
            . '\\' . ($controllerName ? $controllerName : 'Index')
            . 'Controller';

        if (!class_exists($controllerClass)) {
            return null;
        }
        return [$controllerClass, "action$controllerAction"];
    }

    private function parseControllerActionParams(
        string $controllerClass,
        string $actionMethod,
        string $url
    ): array {
        parse_str(parse_url($url, PHP_URL_QUERY), $urlQueryArgs);

        $reflector = new \ReflectionClass($controllerClass);
        $methodParameters = $reflector->getMethod($actionMethod)->getParameters();

        $result = [];
        foreach ($methodParameters as $parameter) {
            if (!isset($urlQueryArgs[$parameter->name])) {
                // Try to get the default value if it is not recieved from the url.
                if (!$parameter->isDefaultValueAvailable())
                    throw new \Exception("URL arg {$parameter->name} is not recieved");
                $value = $parameter->getDefaultValue();
            } else $value = $urlQueryArgs[$parameter->name];

            // Typize the parameter
            $type = $parameter->getType();
            if ($type) settype($value, $type->getName());
            $result[] = $value;
        }
        return $result;
    }
}