'use strict';

// Allows things to work ;)
function bcmsWidgetManager($scope,$http,$log) {
    bcmsBaseWidgetManager.call(this,$scope,$http,$log); // runs initialization for base class

    // on click update the EditPanel
    $scope.handleDisplayControls = new (function(){
        this.initialize = function(scope) {
            // call at the beginning off
        };
        this.registerEvents = function(scope) {

        };
    })();
}
bcmsWidgetManager.prototype = Object.create(bcmsBaseWidgetManager.prototype);
app.controller('bcmsWidgetManager',['$scope','$http', '$log', bcmsWidgetManager ] );