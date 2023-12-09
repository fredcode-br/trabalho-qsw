<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    session_start();

    require_once 'app/core/Core.php';

    require_once 'lib/Database/Connection.php';

    require_once 'app/controller/LoginController.php';
    require_once 'app/controller/RegisterController.php';
    require_once 'app/controller/HomeController.php';
    require_once 'app/controller/EnrollmentController.php';

    
    require_once 'app/model/User.php';
    require_once 'app/model/Section.php';
    require_once 'app/model/Enrollment.php';
    require_once 'app/model/Wait.php';


    require_once 'vendor/autoload.php';
    

    $core = new Core;
	echo $core->start($_GET);