<?php
// When register_global in php.ini set to off, we need to
// extract the parameters passed from GET or POST method ourself.
// If the action part in a form tag use $PHP_SELF, we will still have GET
// variable even though the POST method is used. Therefore, extract GET
// variables only when no POST variable is available.
if (isset($_POST) && !empty($_POST)) {
    extract($_POST, EXTR_OVERWRITE);
} else {
    if (isset($_GET) && !empty($_GET)) {
        extract($_GET, EXTR_OVERWRITE);
    }
}
if (isset($_SESSION) && !empty($_SESSION)) {
    extract($_SESSION, EXTR_OVERWRITE);
}


?>
