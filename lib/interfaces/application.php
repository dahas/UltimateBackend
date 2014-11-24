<?php

namespace UltimateBackend\lib\interfaces;

/**
 * Interface Application
 * @package UltimateBackend\lib\interfaces
 */
interface Application
{
    public function __construct();

    public function render($html, $additionalFiles=array());

    public function execute();
}