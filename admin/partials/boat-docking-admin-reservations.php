<div class="wrap">
    <h2>Boat Docking - All Submissions</h2>
    <!--<div class="tablenav top">
        <div class="alignleft actions">
            <a href="<?php echo admin_url('admin.php?page=bd_reservations_create'); ?>">Add New</a>
        </div>
        <br class="clear">
    </div>-->
        <div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->bd_submissions_obj->prepare_items();
								$this->bd_submissions_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
        <?php
            //global $wpdb;
            //$table_name = $wpdb->prefix . "bd_submissions";
            //$rows = $wpdb->get_results("SELECT id, user_id, name, email, boat_length from $table_name");
        ?>
        <!--<table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Name</th>
                <th class="manage-column ss-list-width">Email</th>
                <th class="manage-column ss-list-width">Boat Length</th>
                <th class="manage-column ss-list-width">Current User?</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->email; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->boat_length; ?></td>
                    <td class="manage-column ss-list-width"><?php echo ($row->user_id) ? 'Yes' : 'No'; ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=bd-reservations_update&id=' . $row->id); ?>">Update</a>
                        <a href="<?php echo admin_url('admin.php?page=bd-reservations_delete&id=' . $row->id); ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>-->
</div>
