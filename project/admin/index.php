<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../');

    $site = @$_REQUEST['site'];

    $setupPage = true;
    $currentProject = "admin/new/load.php";
    $currentProjectSite = "$site/load.php";

    if( ! empty($site) && file_exists( $_SERVER['DOCUMENT_ROOT'] . "/" . $currentProjectSite ) ) {
        $setupPage = false;
        $currentProject = $currentProjectSite;
    }

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/admin/css/bcmsBase.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
<?php if( $setupPage == false ) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.js"></script>
        <!-- script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.0-rc1/angular-material.min.js"></script -->
        <script src="/admin/js/adminApp.js"></script>
        <script src="/admin/js/adminTools.js"></script>
<?php } ?>
    </head>
    <body ng-app='bcms' class='bcms-admin'>
<?php if ($setupPage == false ) { ?>
        <div class='admin-tools admin-configuration-tools'>
            <div class="bcms-admin-toolbar">
                <div class="bcms-panel">
                    <div class="bcms-admin-panel">
                        <!-- this is for settings options for when widgets are selected -->
                        <form class='bcms-admin-settings-default'>
                            <label for="bcmsSiteSettingCss">Css File (placed in css directory)</label><br/>
                            <input type="text" name="bcmsSiteSettingCss" class="bcms-settings-textbox" value="default.css"/>
                            <input type="button" class="bcms-upload-button" value="..." title="Upload css file"/>
                        </form>
                        <form class='bcms-admin-settings-default'>
                            <label for="bcmsSiteSettingWidth">Site Width</label><br/>
                            <input type="text" name="bcmsSiteSettingWidth" class="bcms-settings-textbox" value="100%"/><br/>
                            <label for="bcmsSiteSettingHeight">Site Height</label><br/>
                            <input type="text" name="bcmsSiteSettingHeight" class="bcms-settings-textbox" value="infinite"/><br/>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
<?php } ?>
        <!-- this can only be bootstrap as we have to combine/insert pieces to generate the code we want -->
        <?php include( $currentProject ); echo PHP_EOL;?>
<?php if ($setupPage == true ) { ?>
        <script>
            $('input[name="site"]').val( "<?php echo $site;?>" );
        </script>
<?php } ?>
<?php if ($setupPage == false ) { ?>
        <div class='admin-tools'> <!-- this is where all of the applications that should not be exported will go -->
            <div class="bcms-admin-toolbar">
                <div class="bcms-panel">
                    <div ng-controller="bcmsAdminPanel" class="bcms-admin-panel">
                        <form novalidate>
                            <input type="button" class="bcms-save-button" value="Save"/>
                            <input type="button" class="bcms-cancel-button" value="Cancel"/>
                        </form>
                    </div>
                </div>
                <div ng-controller="bcmsAdminWidgetSelector" class="bcms-panel bcms-admin-widget-selector">
                    <div>Widget Selector</div>
                    <div>
                        <input ng-repeat="widgetType in widgetTypes" ng-hide="true"
                                    type="radio" name="bcms-selector-control widget-selector-panel" value="{{widgetType}}"
                                    checked="{{ $index == 0 ? checked : _ }}" ng-model="widgetPanelSelection"/>
                        <table style="width:100%">
                            <tr>
                                <td class="bcms-vertical-buttons" valign='top'>
                                    <input ng-repeat="widgetType in widgetTypes" ng-click="updateWidgetControl()"
                                    type="button" value="{{widgetType}}"/>
                                </td>
                                <td class="bcms-tab-panel" valign='top'>
                                    <div ng-repeat="widgetType in widgetTypes" 
                                    ng-class="{ 'bcms-tab-panel-show' : show[widgetType] }" class="bcms-tab-panel-default"
                                    >
                                        <div ng-repeat="def in widgets | filter:widgetType" ng-grabbable="grabOptions"
                                                class="bcms-panel bcms-widget bcms-{{def.type}}" style="background-image:url('{{def.image}}');" name="{{def.widget}}" title="{{def.widget}}&#13;{{def.description}}"></div>
                                    </div>
                                </td>
                        </table>
                    </div>
                </div>
            </div>
            <div id="bcms_widget_placeholder_grab_object">
                <!-- This is a fake object that will be modified to match the selected object
                     It will be populated with the selected icons image and data and dragged 
                     with the mouse -->
            </div>
        </div>
<?php } ?>
    </body>
</html>