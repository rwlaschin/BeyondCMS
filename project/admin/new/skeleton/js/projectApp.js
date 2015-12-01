'use strict';

// this is loaded first and mirrors the application in the 
// admin tool, however it won't define any of the admin variables
// which will cause
var app = angular.module('bcms', []);

// Base Controller this will be defined on the application space
// and enables all of the admin functionality
function bcmsBaseWidgetManager($scope, $http, $log) {
    $scope.adminEnabled = false;
}
