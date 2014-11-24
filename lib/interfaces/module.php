<?php

namespace UltimateBackend\lib\interfaces;

/**
 * Interface Module
 * @package UltimateBackend\lib\interfaces
 */
interface Module
{
    public function __construct($props);

    public function render();
}