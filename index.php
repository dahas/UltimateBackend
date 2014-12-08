<?php

/**
 * SOME SYNTAX RULES:
 * Variables are camelCaps starting with a small letter: e. g. $variable or $variAble or $this->variAble.
 * Constants are in capital letters: e. g. CONSTANT.
 * Objects are camelCaps starting with a capital letter: e. g. $Object or $ObJect or $this->ObJect.
 * Methods are camelCaps starting with a small letter: e. g. $this->method() or $this->methOd().
 */

error_reporting(E_ALL);

use UltimateBackend\app\php\App;

require_once('app/php/app.php');

$App = new App();
echo $App->execute();

die;