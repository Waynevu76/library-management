<?php
class Router {
    private $routes = [];

    // Thêm route vào danh sách
    public function add($route, $controller, $method) {
        // Thay {param} thành regex (\d+ cho số, [^/]+ cho chuỗi)
        $routeRegex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route);
        $this->routes[$routeRegex] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    // Xử lý route
    public function dispatch($url) {
        foreach ($this->routes as $route => $info) {
            if (preg_match("#^$route$#", $url, $matches)) {
                $controller = $info['controller'];
                $method = $info['method'];

                require_once __DIR__ . '/../app/controllers/' . $controller . '.php';
                $controllerInstance = new $controller();

                // Lấy tham số động
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controllerInstance, $method], $params);
                return;
            }
        }
        echo '404 - Not Found';
    }
}
?>
