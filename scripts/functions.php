<?php
function chargerClasse($classe) {
    if (file_exists("classes/" . $classe . ".php")) {
        require "classes/" . $classe . ".php";
    } else if (file_exists("repositories/" . $classe . ".php")) {
        require "repositories/" . $classe . ".php";
    } else {
        exit("Le fichier " . $classe . ".php n'a pas été trouvé.\nVerifiez votre code.");
    }
}
spl_autoload_register("chargerClasse");

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