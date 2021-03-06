<?php

use Routing\Router;

class AdminController
{
    /**
     * @var \Routing\Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function mainAction()
    {
        global $cdle;
        echoheader($cdle['title'], $cdle['description']);
        echo self::render('main');
        echofooter();
    }

    public function settingsAction()
    {
        global $cdle;
        echoheader($cdle['title'], $cdle['description']);
        echo self::render('settings');
        echofooter();
    }

    public function error404Action()
    {
        global $cdle;
        header("HTTP/1.0 404 Not Found");
        echoheader($cdle['title'], $cdle['description']);
        echo self::render('error404');
        echofooter();
    }

    protected function request($data)
    {
        echo $data;
    }

    protected function render($template, array $vars = array())
    {
        $root = ADMINDIR . '/views/';

        $templatePath = $root . $template . '.php';

        if (!is_file($templatePath)) {
            throw new \InvalidArgumentException(sprintf('Template "%s" not found in "%s"', $template, $templatePath));
        }

        extract($vars);

        $router = $this->router;

        ob_start();
        ob_implicit_flush(0);

        try {
            require($templatePath);
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }
}