<?php
?>
<style>
    body {
        margin:0;
        padding:0;
        zoom:1;
        width:100%;
        background: lightgrey;
    }
    .bcms-rounded { /* site generic style */
        -webkit-border-radius: 12px;
        -moz-border-radius: 12px; 
        border-radius: 12px; 
    }
    input[type=submit] {
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px; 
        border-radius: 8px;
        font-size: +.6em;
    }
    .bcms-form-text {
        font-size: +2em;
        color:white;
    }
    input.bcms-form-text {
        background: lightgrey;
        padding: 0px 10px 0px 10px;
        font-size: +1em;
        color:black;
    }
    .bcms-error {
        border-color: red;
    }

/*******************************************************/
    #new_background_panel {
        height: 100%;
        margin: 10px;
        padding: 10px;
        background: grey;
        border: darkgrey solid 1px;
    }
    #new_site_creation label {
        padding: 0px 5px 0px 0px;
    }
    #new_form_title {
        color:white;
        font-size: +3em;
        border-bottom: darkgrey solid 4px;
    }
    #new_form_data {
        padding-top:10px;
    }
    
</style>
<div id='new_background_panel' class="bcms-rounded">
    <div id='new_form_title'>
        Create new Site
    </div>
    <div id='new_form_data'>
        <form id="new_site_creation" onsubmit="return validateForm(event);" method="get" action="new/create.php" class="bcms-form-text">
            <label>Enter Project Name -</label>
            <input name="site" text="" size="44" class="bcms-rounded bcms-form-text" title="Name of App, no spaces" validator="validateProjectName">
            <p></p>
            <input type="submit"></form>
        </form>
        <script>
            function validateProjectName(event) {
                return !/(^\s*$)|\s+/.test(this.value);
            }
            function validateForm(event) {
                var target = event.target;
                var elements = document.getElementsByTagName('input');
                for ( var key in elements ) {
                    var elem = elements[key];
                    var fn = window[elem.getAttribute('validator')];
                    if( typeof fn == "function" ) {
                        var valid = fn.call(elem,event);
                        if(!valid) { 
                            elem.className = (elem.className).replace(/bcms-error\s*/g,"") + " bcms-error";
                            return false; 
                        }
                        elem.className = (elem.className).replace(/bcms-error\s*/g,"");
                    }
                }
                return true;
            }
        </script>
    </div>
</div>