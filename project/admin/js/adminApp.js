'use strict';

// this is loaded first
var app = angular.module('bcms', []);

// Base Controller this will be defined on the application space
// and enables all of the admin functionality
function bcmsBaseWidgetManager($scope, $http, $log) {
    $scope.adminEnabled = true;
}

////////////////////////////////////////////////////////////////////////////////////////////////
// Function that will be used for the save functionality

function _serializeDOM() {
    function getXMLParser() {
        if (typeof window.XMLSerializer != "undefined") {
            return (new window.XMLSerializer()).serializeToString;
        } else if (typeof document.body.xml != "undefined") {
            // closure
            return function(node) { node.xml };
        } else {
            alert("Browser not supported");
            throw "Browser not supported";
        }
    }
    var xmlStrings = [], xmlDump;
    var parseFunction;
    
    for ( child in document.body.children) {
        xmlStrings.push( parseFunction( child ) );
    }
}

///////////////////////////////////////////////////////////// Angular Controllers

/* Return Format request.php
    { count : number, fields : [ <list of fields> ], rows : [ { field: data}, ... ] }
*/

///////////////////////////////////////////////////////////// Angular Controllers

/* Return Format request.php
    { count : number, fields : [ <list of fields> ], rows : [ { field: data}, ... ] }
*/

/********************************************** Directives ************************************************/
app.directive('ngGrabbable', [ '$document', '$log', function($document,$log) {
    return {
        restrict : 'A',
        scope : {
            ngGrabbable : '='
        },
        link : function(scope, elem, attr) {
            function handleMouseDown (e) {
                // when mouse is clicked
                // copy over attribs
                // attach fake to mouse
                // attach listeners to dragging object
                // set dragging state

                ngGrabbable.dragging = true;

                $document.on('mouseup', handleMouseUp);
                $document.on('mousemove', handleMouseMove);
                ngGrabbable.placeholder.on('$destroy', handleCleanupCopy ); // mem leak protection

                var clone = ngGrabbable.placeholder;
                clone.addClass('bcms-widget-placeholder-grab-object-attached');
                clone.css( { 
                    'visibility':'hidden', 
                    'top' :  (elem.position().y + (elem.outerHeight() - clone.outerHeight() ) *.5) + 'px',
                    'left' : (elem.position().x + (elem.outerWidth() - clone.outerWidth() ) *.5) + 'px',
                    'background-image' : elem.css('background-image')
                });

                start.x = clone.outerWidth() * .5;
                start.y = clone.outerHeight() * .5;

                handleMouseMove(e);
                clone.css('visibility','visible');
            }

            function handleMouseUp (e) {
                // alert("I'm done");
                handleCleanupCopy(e);

                // create a new object place add it into container beneath
                // if action, adds to action bar
                // if view/container, adds in grid
            }
            function handleMouseMove(e) {
                var over;
                var hovers = $(".bcms-container-widget").filter( 
                        function(elm) {
                            var $this = $(this), pos = $this.position();
                            return (pos.top <= e.pageY && pos.left <= e.pageX && 
                                    e.pageY <= (pos.top+$this.outerHeight()) && 
                                    e.pageX <= (pos.left+$this.outerWidth()) );
                        }
                    );
                // only take the top most
                if( hovers.length > 0 ) {over = $(hovers[0]);}
                (function(e){ 
                        var $base = $('.bcms-application');
                        var dh = $base.position().top + ($base.innerHeight() + $base.outerHeight()) * 0.5,
                            dw = $base.position().left + ($base.innerWidth() + $base.outerWidth()) * 0.5;
                        if( e.pageY < start.y ) { e.pageY = 0;} else 
                        if( e.pageY >= dh - start.y ) { e.pageY = dh - ngGrabbable.placeholder.outerHeight();} 
                        else { e.pageY -= start.y; }
                        
                        if( e.pageX < start.x ) { e.pageX = 0;} else
                        if( e.pageX >= dw - start.x ) { e.pageX = dw - ngGrabbable.placeholder.outerWidth();}
                        else { e.pageX -= start.x; }
                        
                    })(e);
                ngGrabbable.placeholder.css({top: e.pageY + 'px', left:  e.pageX + 'px'});
                // remove the class from old if they don't match
                // add class to new if they don't match
                if( currentOver && ! currentOver.is(over) ) {
                    currentOver.removeClass('bcms-highlight-drop-target');
                }
                if( over && ! over.is(currentOver) ) {
                    over.addClass('bcms-highlight-drop-target');
                }
                // this path makes sure that the highligh goes away
                // when not over a valid object
                currentOver = over;
            }
            function handleCleanupCopy(e) {
                $document.off('mouseup',handleMouseUp);
                $document.off('mousemove',handleMouseMove);

                ngGrabbable.placeholder.removeClass('bcms-widget-placeholder-grab-object-attached');
                if( currentOver ) {
                    currentOver.removeClass('bcms-highlight-drop-target');
                    // this is what I'll be creating the new item in
                    if( typeof ngGrabbable.dropTarget === 'function' ) {
                        var data = ngGrabbable.dropTarget(elem,currentOver);
                    }
                    
                    // do whatever here
                    currentOver = undefined;
                }
            }
            function handleCleanup(e) {
                elem.off('mousedown', handleMouseDown);
            }

            var start = {x:0,y:0}, loc = {x:0,y:0}, currentOver;

            // simpler access
            if( !scope.ngGrabbable ) { scope.ngGrabbable = {}; }
            var ngGrabbable = scope.ngGrabbable;

            if( ngGrabbable.dragging ) {
                return; // don't do anything until the object is dropped
            }

            elem.on('mousedown', handleMouseDown);
            elem.on('$destroy', handleCleanup ); // mem leak protection
        }
    }
} ] );

/********************************************** Controllers ************************************************/

app.controller('bcmsAdminPanel',['$scope','$http','$log',function($scope,$http,$log){
    
}]);

function bcmsAdminWidgetSelector($scope,$http,$log) {
    // make a request for the widgets
    // load in the widgets

    var widgets, widgetsdata, types = {};
    $scope.widgetPanelSelection = "";
    $scope.widgets = [];
    $scope.widgetTypes = [ "actor", "container", "view" ];
    $scope.show = {};

    function handleWidgetListResponse(response) {
        widgets = []; widgetsdata = [];
        var rows = response.data.rows, batch = [];
        angular.forEach( rows, function(value, key) {
            var send = ( key+1 == rows.length || (key % 4) == 3 );
            this.push( value.widget );
            batch.push( value.widget );
            if( send == true ) {
                var args = batch.join(','); batch = [];
                $http.get('/libraries/request.php?widget='+encodeURIComponent(args) )
                .then( handleWidgetResponse,function(){console.error('Failed to make call to get widget by name');});
            }
        }, widgets );
    }

    function handleWidgetResponse(response) {
        angular.forEach( response.data.rows, function(value, key) {
            widgetsdata.push( value.definition );
            types[ value.definition.type ] = 1;
            $scope.show[value.definition.type] = false;
        } );
        if( widgets.length == widgetsdata.length ) {
            // copy so the binding isn't brokens
            var sortedWidgetTypes = Object.keys(types).sort()
            angular.copy(widgetsdata,$scope.widgets);
            angular.copy(sortedWidgetTypes,$scope.widgetTypes);
            $scope.widgetPanelSelection = sortedWidgetTypes[0];
            $scope.show[$scope.widgetPanelSelection] = true;
        }
    }

    $http.get('/libraries/request.php?widgets')
    .then( handleWidgetListResponse, function(response) {
        console.error('Failed to make call to get widget list');
    } );

    $scope.updateWidgetControl = function() {
        this.widgetPanelSelection = this.widgetType;
        for( var key in this.show ) {this.show[key] = false;}
        this.show[this.widgetPanelSelection] = true;
    };

    function insertNewWidget(target,data) {
        alert( response );

    };

    $scope.grabOptions = {
        placeholder : angular.element( document.getElementById('bcms_widget_placeholder_grab_object') ), // jquery selector
        dragging : false,
        dropTarget : function(source,dest) {
            var def;
            var expected = source.attr('name');
            // load template, add to dest, probably need to send a 'drop' message with
            // all of the necessary data?
            $.each( $scope.widgets, function(key,value){
                if( this.widget === expected ) {
                    def = value;
                    return false; // break out early
                }
            });
            // create a child element with the correct data
            // load template from template directory (request?)
            // create new template by adding it to the dom and then
            // compiling it.
            var dscope = dest.scope();
            $http.get('/libraries/request.php?template=' + def.widget )
            .then(function(response){insertNewWidget.call(this,dest,def);},
                  function(response) {
                      console.error('Failed to make call to get widget list');
                  } );
        }
    };
}

app.controller('bcmsAdminWidgetSelector',['$scope','$http', '$log', bcmsAdminWidgetSelector]);
