<?php

namespace UltimateBackend\lib\interfaces;

interface Application
{
    public function __construct();

    public function render($content);

    public function execute();
}