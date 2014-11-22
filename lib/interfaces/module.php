<?php

namespace UltimateBackend\lib\interfaces;


interface Module
{
    public function __construct($props);

    public function render();
}