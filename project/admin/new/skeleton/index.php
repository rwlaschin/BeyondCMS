<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../');

    $site_contents;
    ob_start();
        include( "load.php" );
        $site_contents = ob_get_contents();
    ob_end_clean();

    // if site has admin controls add in the admin checks
    // also filter the site_contents to remove anything that the 
    // user shouldn't see
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.0-rc1/angular-material.min.js"></script>
    </head>
    <body class='bcms-site'>
        <?php echo $site_contents,PHP_EOL; ?>
        <script src="js/projectApp.js"></script>
        <script src="js/projectTools.js"></script>
    </body>
</html>
