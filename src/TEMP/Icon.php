<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Start Of Basic PHP Components
 *
 */

namespace TEMP;

use Exception;

class Icon
{

    // File Extention
    private $ext = '.svg';

    // Import Directory Path For SVG 
    private $import = '';


    public function __construct(string $import)
    {
        $this->import = $import;
    }


    /**
     *  Search Directory For SVG If Found Return Icon 'component' HTML
     *
     *  @param string $icon         Uses Dot Notation For Directory Namespacing
     */
    public function __invoke(string $icon)
    {
        $path = $this->path($icon);

        if (file_exists($path)) {
            return include $path;
        }

        throw new Exception("Icon Component Could Not Find Icon: {$icon} In Path: {$path}");
    }


    private function path(string $icon)
    {
        return $this->import . str_replace('.', '/', $icon) . $this->ext;
    }
}
