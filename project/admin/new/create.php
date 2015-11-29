<?php
    // notes:
    // the base template probably should have a panel/container and the MessageAgent

    set_include_path(get_include_path() . PATH_SEPARATOR . '../');

    // if project exists, fail, this is temp as we need a responsive design
    // that will push back errors
    if( empty($_REQUEST['site']) ) {
        header('Location: /admin/?error=empty'); // redirect
        exit;
    }

    // create the folder structure
    // create base widget file 
    $currentProject = @$_REQUEST['site'];
    $absPathCurrentProject = $_SERVER['DOCUMENT_ROOT']. "/" . $currentProject;
    $message = "Existing";

    if ( ! is_dir( $absPathCurrentProject ) ) {
        $tar = "{$currentProject}.tar";
        $phar = new PharData($tar);
        $result = true;
        try {
            $message = "ImageFailed";
            $phar->buildFromDirectory( __DIR__ . '/skeleton' );
            $message = "GenerationFailed";
            $result = $phar->extractTo( $absPathCurrentProject );
            if( $result ) {
                $message="success";
                unset($phar);
                unlink($tar);
                header("Location: /admin/?site={$currentProject}");
                exit;
            } else {
                throw new Exception("Failed to create tar {$result}");
            }
        } catch ( Exception $e ) {
            unset($phar);
            unlink( $tar );
            unlink( $absPathCurrentProject );
        }
    }

    header("Location: /admin/?error={$message}"); // redirect
    exit;