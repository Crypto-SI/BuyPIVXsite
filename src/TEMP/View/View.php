<?php

/**
 *------------------------------------------------------------------------------
 *
 *  eSportspCMS View/Template Rendering
 *
 */

namespace TEMP\View;

use Exception;
use TEMP\Helpers\Collections;
use TEMP\Helpers\Arr;

class View extends Collections {


    // Error Message
    private $error  = [
        'directory'     => 'Misconfigured Path: Directory Does Not Exist: ',
        'layout'        => 'Misconfigured Path: Layout File Does Not Exist: '
    ];

    // File Extention Used For Views/Templates
    private $ext    = '.php';

    // Default Layout To Use During Rendering
    private $layout = 'master';

    // Directory Paths
    private $paths  = [];


    /**
     *  Define Class Dependencies
     *
     *  @param object $array        Array Helper Instance
     *  @param array  $paths        View/Template Paths
     */
    public function __construct(Arr $array, $paths = []) {
        $this->array = $array;

        $this->setPaths($paths);
    }


    /**
     *  Set New Template/View/Layout Paths That Should Be Used During Rendering
     *
     *  @param array $paths         Assoc | Sequential Array Containing Paths
     */
    public function setPaths($paths = []) {
        if (!$paths) { return; }

        foreach ((array) $paths as $key => $path) {
            $this->paths[$key] = $this->validDirectory($path);
        }
    }


    /**
     *  Define Layout Being Used For Display ( Final Render Method )
     *
     *  @param string $layout       Layout Name
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }


    /**
     *  Validate Directory Paths
     *
     *  @param  string $paths       View, Template, Or Layout Directory
     *  @return string              Normalized Directory Path
     */
    private function validDirectory($path) {
        if (!file_exists($path = rtrim($path, '/') . '/')) {
            throw new Exception($this->error['directory'] . $path);
        }
        return $path;
    }


    /**
     *  Render View/Template File
     *
     *  @param  string $files       View/Template File(s)
     *  @param  array  $data        Additional Data To Be Passed To File
     *  @return string              The Rendered Template
     */
    public function render($files, $data = []) {
        extract(
            $data = array_merge($this->getData(), (array) $data)
        );

        ob_start();

        foreach ((array) $files as $file) {
            // Direct File Path Check
            if (file_exists($file)) {
                require $file;
                continue;
            }

            // Else Iterate Through Paths To Search For Template/View
            foreach ($this->paths as $key => $path) {
                $f = $path . $file . $this->ext;

                if (file_exists($f)) {
                    require $f;
                    break;
                }
            }
        }

        return ob_get_clean();
    }


    /**
     *  Render Full Page Layout
     *
     *  @see                        $this->render() Comments
     */
    public function display($page, $data = []) {
        $layout = $this->paths['layouts'] . $this->layout . $this->ext;

        if (!file_exists($layout)) {
            throw new Exception($this->error['layout'] . $layout);
        }

        return $this->render($layout, ['page' => $this->render($page, $data)]);
    }
}
