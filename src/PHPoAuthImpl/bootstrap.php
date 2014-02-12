<?php
/**
 * This bootstraps the PHPoAuthImpl library
 *
 * PHP version 5.4
 *
 * @category   PHPoAuthImpl
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace PHPoAuthImpl;

use PHPoAuthImpl\Psr0\Autoloader;

/**
 * Setup the library's autoloader
 */
require_once __DIR__ . '/Psr0/Autoloader.php';

$autoloader = new Autoloader(__NAMESPACE__, dirname(__DIR__));
$autoloader->register();

/**
 * Bootstrap the PHPoAuthLib
 */
require __DIR__ . '/../../vendor/lusitanian/oauth/src/OAuth/bootstrap.php';
