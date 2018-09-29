<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Class Autoloader
 *
 */

class Autoloader
{

    // File Extension
    private $ext = '.php';

    // Namespace Aliases Define Directory Mapping
    private $namespaces = [];

    // Namespace/Directory Separators
    private $separator = [
        'directory' => DIRECTORY_SEPARATOR,
        'namespace' => '\\'
    ];


    /**
     *  Register Class Autoloader
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }


    /**
     *  Unregisters Class Autoloader
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'loadClass']);
    }


    /**
     *  Normalize Prefix Then Add To Prefix Container
     *
     *  @param string $namespace
     *  @param string $directory
     */
    public function addNamespace(string $namespace, string $directory)
    {
        $namespace = trim($namespace, $this->separator['namespace']) . $this->separator['namespace'];
        $directory = $this->normalize($directory) . $this->separator['directory'];

        $this->namespaces[$namespace] = $directory;
    }


    /**
     *  Add Multiple Namespaces To Prefix Container
     *
     *  @see $this->addNamespace
     */
    public function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $key => $value) {
            $this->addNamespace($key, $value);
        }
    }


    /**
     *  Autoload Class
     *
     *  @param string $class
     */
    private function loadClass(string $class)
    {
        requireClass($this->normalize($class) . $this->ext);
    }


    /**
     *  Normalize Class/Directory
     *
     *  @param string $string
     *  @return string
     */
    private function normalize(string $string) : string
    {
        $string = rtrim(
            trim($string, $this->separator['namespace']),
            $this->separator['directory']
        );
        $string = str_replace(
            array_keys($this->namespaces),
            array_values($this->namespaces),
            $string
        );
        return str_replace($this->separator['namespace'], $this->separator['directory'], $string);
    }
}


/**
 *  Isolate Requiring Class
 *
 *  Taken From Composer; Prevents Access To '$this' From Required File
 */
function requireClass($class)
{
    if (file_exists($class)) {
        require_once $class;
    }
}


$autoloader = new Autoloader();
$autoloader->register();

return $autoloader;
