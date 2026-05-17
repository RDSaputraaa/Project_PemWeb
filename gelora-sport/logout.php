<?php
require_once __DIR__ . '/config/helper.php';
session_destroy();
redirect('/login.php');
