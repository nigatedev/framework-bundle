<?php
/*
 * This file is part of the Nigatedev PHP framework package
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Nigatedev\FrameworkBundle\Controller;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Nigatedev\FrameworkBundle\Application\App;
use Nigatedev\FrameworkBundle\Http\Response;
use Nigatedev\FrameworkBundle\Http\Request;
use Nigatedev\FrameworkBundle\Controller\Exception\ControllerException;
use Nigatedev\Diyan\Diyan;
use Nigatedev\FrameworkBundle\Application\Configuration;

/**
 * Main abstract controller
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
abstract class AbstractController
{
    /**
     * @var Diyan
     */
    protected $diyan;
    
    /**
     * Construitor
     *
     * @return void
     */
    public function __construct()
    {
        $this->diyan = App::$app->router->diyan;
    }
     
    /**
     * @param string $view
     * @param string[] $params
     *
     * @return mixed
     */
    public function render(string $view, array $params = [])
    {
        $defaultTemplate = $this->getDefaultTemplate();

        if ($defaultTemplate === "diyan") {
            return $this->diyan->render($view, $params);
        } elseif ($defaultTemplate === "twig") {
            $loader = new FilesystemLoader(Configuration::getAppRoot()."/views");
            $twig = new Environment($loader, [
            'cache' => false,
            ]);
            $template = $twig->load("{$view}.twig");
            return $template->render($params);
        } else {
            throw new ControllerException("Bad template configuration!");
        }
    }
    
    /**
     * Redirect to route
     *
     * @return mixed
     */
    public function redirectToRoute(string $route)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ${route}");
        exit();
    }
    
    /**
     * Redirect to not found template and set the status code to 404
     *
     * @return mixed
     */
    public function redirectToNotFound()
    {
        return new Response(404, [], $this->diyan->render("errors/_404", []));
    }
    
    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return Configuration::getEntityManagerConfig();
    }
    
    /**
     * @return mixed
     */
    public function getDefaultTemplate()
    {
        return Configuration::getDefaultTemplateConfig();
    }
}
