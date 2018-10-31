<?php

bd_reservations_create();

function bd_reservations_create() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bd_submissions";

    //$id = $_GET["id"];

    //$row = $wpdb->get_row($wpdb->prepare("SELECT id, user_id, name, email, boat_length, notes FROM $table_name WHERE id = %s", $id));
    //var_dump($res);
    $name = (isset($_POST["name"]) && !empty($_POST["name"])) ? $_POST["name"] : null;
    $email = (isset($_POST["email"]) && !empty($_POST["email"])) ? $_POST["email"] : null;
    $length = (isset($_POST["boat_length"]) && !empty($_POST["boat_length"])) ? $_POST["boat_length"] : null;
    $notes = (isset($_POST["notes"]) && !empty($_POST["notes"])) ? $_POST["notes"] : null;
    $admin_notes = (isset($_POST["admin_notes"]) && !empty($_POST["admin_notes"])) ? $_POST["admin_notes"] : null;

    //$arr = ['name' => $name, 'email' => $email, 'boat_length' => $length];
    //var_dump($arr);
//update
    if (isset($_POST['create'])) {
        $sql = $wpdb->prepare("INSERT INTO {$table_name} (name, email, boat_length, notes, admin_notes) VALUES ({$name},{$email},{$length},{$notes},{$admin_notes})");
        //var_dump($name);
        $wpdb->query($query);
        //$wpdb->insert(
        //        $table_name, //table
        //        array('name' => $name, 'email' => $email, 'boat_length' => $length, 'notes' => $notes),
        //        array('%s', '%s', '%s', '%s')
        //);
        //include 'boat-docking-admin-reservations.php';
        header("Refresh:0; url=admin.php?page=bd-reservations");
    } else {
        $create_nonce = wp_create_nonce( 'bd_create_bd_submission' );
        ?>
        <div class="wrap">
            <h2>Create Dock Reservation</h2>
            <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Reservations</a>
            <?php if (isset($_POST['delete']) && $_POST['delete']) { ?>
                <div class="updated"><p>Reservation deleted</p></div>
                <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Reservations</a>

            <?php } else if (isset($_POST['update']) && $_POST['update']) { ?>
                <div class="updated"><p>Reservation updated</p></div>
                <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Reservations</a>

            <?php } else if (isset($_POST['create']) && $_POST['create']) { ?>
                    <div class="updated"><p>Reservation created</p></div>
                    <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Reservations</a>

            <?php } else { ?>
                <form method="post" action="<?php echo admin_url('admin.php?page=bd-reservations_create&_wpnonce='. $create_nonce) ?>">
                    <table class='wp-list-table widefat fixed'>
                        <tr><th>Name</th><td><input type="text" name="name" value=""/></td></tr>
                        <tr><th>Email</th><td><input type="text" name="email" value=""/></td></tr>
                        <tr><th>Boat Length</th><td><input type="text" name="boat_length" value=""/></td></tr>
                        <tr><th>Notes</th><td><textarea name="notes" value=""></textarea></td></tr>
                        <tr><th>Admin Notes</th><td><textarea name="admin_notes" value=""></textarea></td></tr>
                    </table>
                    <hr>
                    <input type='submit' name="update" value='Save' class='button button-primary'> &nbsp;&nbsp;
                </form>
            <?php } ?>
        </div>
    <?php
    }
}
