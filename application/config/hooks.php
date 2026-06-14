<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$hook['pre_controller'][] = [
    'class'    => 'Cors',
    'function' => 'set_headers',
    'filename' => 'Cors.php',
    'filepath' => 'hooks'
];
