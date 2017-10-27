<?php

class Route {

    public static function start()
    {
        // контроллер и действие по умолчанию
        session_start();
        $url = $_SERVER['REQUEST_URI'];
        //Прверяю авторизован ли пользователь. Если нет - отправляю на авторизацию
        if (! isset($_SESSION['user'])) {
            $url = "/login";
        }
        session_write_close();

        //Разбираю URL что бы получить контроллер/экшен
        $routes = explode('/', $url);
        $controller_name = (!empty($routes[1]))?$routes[1]:'Main';
        $action_name = (!empty($routes[2]))?$routes[2]:'index';

        $model_name = 'Model_' . ucfirst($controller_name);
        $controller_name = 'Controller_' . ucfirst($controller_name);
        $action_name = 'action_' . ucfirst($action_name);

        $model_file = $model_name . '.php';

        $controller_file = $controller_name . '.php';
        $controller_path = __DIR__ . "/../controllers/" . $controller_file;

        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            Route::ErrorPage404();
        }

        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;

        if (method_exists($controller, $action)) {
            include __DIR__ .  "/../models/" . $model_file;
            $controller->model = new $model_name;
            $controller->$action();
        }
    }

    /**
     * 404 Redirect
     */
    public static function ErrorPage404()
    {
        header('Location: /main/404');
        exit();
    }
}
