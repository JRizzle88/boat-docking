<?php

bd_reservations_update();

function bd_reservations_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bd_submissions";

    $id = $_GET["id"];

    $row = $wpdb->get_row($wpdb->prepare("SELECT id, user_id, name, email, boat_length, notes, admin_notes FROM $table_name WHERE id = %s", $id));
    //var_dump($res);
    $name = (isset($_POST["name"])) ? $_POST["name"] : $row->name;
    $email = (isset($_POST["email"])) ? $_POST["email"] : $row->email;
    $length = (isset($_POST["boat_length"])) ? $_POST["boat_length"] : $row->boat_length;
    $notes = (isset($_POST["notes"])) ? $_POST["notes"] : $row->notes;
    $admin_notes = (isset($_POST["admin_notes"])) ? $_POST["admin_notes"] : $row->admin_notes;
    //$arr = ['name' => $name, 'email' => $email, 'boat_length' => $length];
    //var_dump($arr);
//update
    if (isset($_POST['update'])) {
        //var_dump($name);
        $wpdb->update(
            $table_name, //table
                array('name' => $name, 'email' => $email, 'boat_length' => $length, 'notes' => $notes, 'admin_notes' => $admin_notes), //data
                array('ID' => $id), //where
                array('%s', '%s', '%s'), //data format
                array('%s') //where format
        );
        //include 'boat-docking-admin-reservations.php';
        header("Refresh:0; url=admin.php?page=bd-reservations");
    }
//delete
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
        ?>
        <div class="updated"><p>Submission deleted</p></div>
        <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Submissions</a>
        <?php
        header("Refresh:0; url=admin.php?page=bd-reservations");
    } else {
        ?>
        <div class="wrap">
            <h2>Edit Submission <?php echo $id ?></h2>
            <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Submissions</a>
            <?php if (isset($_POST['delete']) && $_POST['delete']) { ?>
                <div class="updated"><p>Submission deleted</p></div>
                <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Submissions</a>

            <?php } else if (isset($_POST['update']) && $_POST['update']) { ?>
                <div class="updated"><p>Submission updated</p></div>
                <a href="<?php echo admin_url('admin.php?page=bd-reservations') ?>">&laquo; Back to All Submissions</a>

            <?php } else { ?>
                <form method="post" action="<?php echo admin_url('admin.php?page=bd-reservations_update&id=' . $row->id) ?>">
                    <table class='wp-list-table widefat fixed'>
                        <!--<tr>
                            <th>Current User?</th>
                            <td>
                                <?php echo ($row->user_id) ? 'Yes ' : 'No '; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="">Add as User</a>
                            </td>
                        </tr>-->
                        <tr><th>Name</th><td><input type="text" name="name" value="<?php echo $row->name; ?>"/></td></tr>
                        <tr><th>Email</th><td><input type="text" name="email" value="<?php echo $row->email; ?>"/></td></tr>
                        <tr><th>Boat Length</th><td><input type="text" name="boat_length" value="<?php echo $row->boat_length; ?>"/></td></tr>
                        <tr><th>Notes</th><td><textarea name="notes" value="<?php echo $row->notes; ?>"><?php echo stripslashes_deep($row->notes); ?></textarea></td></tr>
                        <tr><th>Admin Notes</th><td><textarea name="admin_notes" value="<?php echo $row->admin_notes; ?>"><?php echo stripslashes_deep($row->admin_notes); ?></textarea></td></tr>
                    </table>
                    <hr>
                    <input type='submit' name="update" value='Save' class='button button-primary'> &nbsp;&nbsp;
                    <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure you want to delete this reservation?')">
                </form>
            <?php } ?>
        </div>
    <?php
        //include 'boat-docking-admin-reservations.php';
    }
    ?>
    <?php
}
