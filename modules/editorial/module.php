<?php

$Module = array(
    'name' => 'editorial',
    'variable_params' => true
);

$ViewList = array();

$ViewList['dashboard'] = array(
    'functions' => array( 'view' ),
    'script' => 'dashboard.php',
    'default_navigation_part' => 'oweditorialnavigationpart',
);

$FunctionList['view'] = array();
