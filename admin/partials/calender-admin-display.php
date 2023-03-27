<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://kushankur.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Calender
 * @subpackage Calender/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1 class="cc-title">Schedule Content</h1>
<!--Add Input fields on Schedule Content Page-->
<div class="wrap">


    <form method="post">
        <input type="hidden" name="action" value="cc_form">

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required /><br />

        <label for="occasion">Occasion:</label>
        <input type="text" name="occasion" id="occasion" required /><br />

        <label for="post_title">Post Title:</label>
        <input type="text" name="post_title" id="post_title" required /><br />

        <label for="author">Author:</label>
        <select name="author" id="author" required>
            <?php
            $users = get_users(array(
                'fields' => array('ID', 'display_name')
            ));
            foreach ($users as $user) {
                echo '<option value="' . $user->ID . '">' . $user->display_name . '</option>';
            }
            ?>
        </select><br>

        <label for="reviewer">Reviewer:</label>
        <select name="reviewer" id="reviewer" required>
            <?php
            $admins = get_users(array(
                'role' => 'administrator',
                'fields' => array('ID', 'display_name')
            ));
            foreach ($admins as $admin) {
                echo '<option value="' . $admin->ID . '">' . $admin->display_name . '</option>';
            }
            ?>
        </select><br>

        <?php submit_button('Schedule Post'); ?>

    </form>
</div>