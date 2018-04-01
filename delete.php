<?php
    include 'dbc.php';
    $id = 0;    
    if ( !empty($_GET['id']))
    {
        $id = $_REQUEST['id'];
    }
    
	deleteLibrary($id);
    header("Location: library.php");
         

?>