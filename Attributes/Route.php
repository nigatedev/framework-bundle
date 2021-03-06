<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);

namespace Nigatedev\FrameworkBundle\Attributes;

use Attribute;
use Nigatedev\FrameworkBundle\Application\Configuration as Config;

/**
* Route Attribute
*
* @author Abass Ben Cheik <abass@todaysdev.com>
*/

#[Attribute]
class Route
{
    /**
     * @var string
     */
    private $path;
    
    /**
     * @var string
     */
    private $method;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * Route Attribute constructor
     *
     * @param string $path      Request URL (e.g: https://example.com/about)
     * @param string $name      The name is used to generate a url for this route.
     * @param string $method    HTTP request method (e.g: get|post|delete...)
     *
     * @return void
     */
    public function __construct(string $path, string $name = null, string $method = "get")
    {
        $this->path = $path;
        $this->method = $method;
        $this->name = $name;
    }
    
    /**
     * Get HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->method);
    }
    
    /**
     * Get the path
     *
     * @return string
     */
    public function getPath()
    {
        if (preg_match("/\d+$/", Config::getEnv("REQUEST_URI"), $id)) {
            $_ENV["_path_id"] = $id[0];
            $filterPath = preg_replace("/{id}/", $id[0], $this->path);
        } else {
            $filterPath = $this->path;
        }
        $_GET[$this->name] = $filterPath;
        return $filterPath;
    }
}
