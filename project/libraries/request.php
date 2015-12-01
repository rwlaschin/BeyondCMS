<?php
    // table format
    // { count : number, fields: [ <list of fields>], rows : [ { field: data, ... } ] }
    $root = $_SERVER['DOCUMENT_ROOT'];
    $widgetDefinitionsDir = "$root/admin/widgets";
    $templatesDir = "$root/admin/templates";
    $debug = ( array_key_exists('debug', $_REQUEST) && $_REQUEST['debug'] == "1" );

    // generic request interface
    // permits pulling widget information
    // from the server

    if( array_key_exists('widgets',$_REQUEST) || empty(@$_REQUEST['widget']) ) {
        $list = array( 'fields' => array( 'widget' ), 'rows' => array() );
        chdir($widgetDefinitionsDir);
        $widgetDefinitions = glob("*.ddf", GLOB_BRACE);
        if( $debug ) {
            $list['debug'] 
                = array( 'cwd' => getcwd(), 
                         'defs' => print_r($widgetDefinitions,true) 
                    );
        }
        foreach($widgetDefinitions as $file) {
          array_push($list['rows'], array( 'widget' => $file ) );
        }
        $list['count'] = count($list['rows']);
        // set response
        header(' ',true,200); // standard 200 response
        header('Content-Type: application/json');
        echo json_encode( $list );
        exit;
    } else if ( array_key_exists('widget',$_REQUEST) ) {
        // load widget by name
        $list = array( 'fields' => array( 'definition' ), 'rows' => array(), 'errors' => array() );
        chdir($widgetDefinitionsDir);
        $widgetFilePaths = preg_split("/\s*,\s*/",$_REQUEST['widget']);
        if( $debug ) {
            $list['debug'] 
                = array( 'cwd' => getcwd(), 
                         'widgets' => print_r($widgetFilePaths,true) 
                    );
        }
        foreach($widgetFilePaths as $widget ) {
            // load file, create table spec, return
            try {
                $definition = json_decode( preg_replace('!/\*.*?\*/!s', '', file_get_contents($widget)), true );
                if( !empty($definition) ) {
                    array_push($list['rows'], array( 'definition' => $definition ) );
                } else {
                    throw new Exception('Widget definition file was empty');
                }
            } catch (Exception $e) {
                array_push($list['errors'], array( 'widget' => $widget, 'message' => $e->getMessage() ));
            }
        }
        $list['count'] = count($list['rows']);
        // set response
        header(' ',true,200); // standard 200 response
        header('Content-Type: application/json');
        echo json_encode( $list );
        exit;
    } else if ( array_key_exists('template',$_REQUEST) && ! empty($_REQUEST['template']) ) {
        $template = $_REQUEST['template'];
        $templatesDir . "/{$template}.tpl";
    }