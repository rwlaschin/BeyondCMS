<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../');

// need to load all of the widget configs and display at positions
// if I'm in admin mode we'll be showing the images for the widgets
// and these widgets won't be 'active'.  Containers will display
// with the elements in them.  Also menus/tabs should also work
// to show the behavior.
?>
        <div id='root_application'> <!-- this is here so the child element can be exported -->
            <div class='bcms-application bcms-container-widget'> 
                <!-- this is where all of the applications elements will go -->
            </div>
        </div>