<?php

namespace UltimateBackend\lib\interfaces;
use UltimateBackend\lib\Template;

/**
 * Interface Module
 * @package UltimateBackend\lib\interfaces
 */
interface Module
{
    public function __construct($props, Template $Tmpl = null);

    public function render();
}