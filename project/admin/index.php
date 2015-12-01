<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../');

    $currentProject = @$_REQUEST['site'];
    if( empty($currentProject) ) {
        $setupPage = true;
        $currentProject = "admin/new/load.php";
    } else {
        $setupPage = false;
        $currentProject = "$currentProject/load.php";
    }

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/admin/css/bcmsBase.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
    </head>
    <body ng-app='bcms' class='bcms-admin'>
        <!-- this can only be bootstrap as we have to combine/insert pieces to generate the code we want -->
        <?php include( $currentProject ); echo PHP_EOL;?>
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
<?php if( $setupPage == false ) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.js"></script>
        <!-- script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.0-rc1/angular-material.min.js"></script -->
        <script src="/admin/js/adminApp.js"></script>
        <script src="/admin/js/adminTools.js"></script>
<?php } ?>
    </body>
</html>