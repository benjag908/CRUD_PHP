<?php

session_start();

require '../config/database.php';

$id = $conn->real_escape_string($_POST['id']);

$sql = "DELETE FROM pelicula WHERE id=$id";
if ($conn->query($sql)) {

    $dir = "posters";
    $poster = $dir . '/' . $id . '.jpg';


   if(file_exists($poster)){
        unlink($poster);
   } 

    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro eliminado";

}else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al eliminar regristro";
}
header('Location: index.php');
