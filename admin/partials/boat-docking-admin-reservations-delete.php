<?php

bd_reservations_delete();

function bd_reservations_delete() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bd_submissions";

    $id = $_GET["id"];
    //var_dump($id);
    if($id) {
        //var_dump($id);
        //$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
        $wpdb->delete(
		    $table_name,
		    array('ID' => $id) // End array
		);
    }
    //header("Refresh:0; url=admin.php?page=bd-reservations");
}
