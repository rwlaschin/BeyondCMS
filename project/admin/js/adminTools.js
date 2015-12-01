'use strict';

// Allows things to work ;)
function bcmsWidgetManager($scope,$http,$log) {
    bcmsBaseWidgetManager.call(this,$scope,$http,$log); // runs initialization for base class

    // define variables and functions here.
        $scope.defineMouseOver = {

    }
}
bcmsWidgetManager.prototype = Object.create(bcmsBaseWidgetManager.prototype);
app.controller('bcmsWidgetManager',['$scope','$http', '$log', bcmsWidgetManager ] );