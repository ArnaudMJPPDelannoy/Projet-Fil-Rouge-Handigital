<?php
// spl_autoload_register(function($className) {
//     include "classes/" . $className . ".php";
// });

/**
 * Is the Object's element set and not empty?
 *
 * @param   object  $object   The object you want to search in.
 * @param   string  $element  The name of the element in the object.
 *
 * @return  bool            True if object and element both exists and are not empty.
 */
function isSetAndNotEmptyObject($object, string $element) {
    return isset($object[$element]) && !empty($object[$element]);
}

session_start();
?>