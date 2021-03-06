<?php
// Version
define('VERSION', '3.0.3.6');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Agmedia custom Configuration
if (is_file('env.php')) {
    require_once('env.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// VirtualQMOD
require_once('./vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

start('catalog');
