<?php
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