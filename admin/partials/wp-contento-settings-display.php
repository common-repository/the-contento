<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.contento.com.ng
 * @since      1.0.0
 *
 * @package    Wp_Contento
 * @subpackage Wp_Contento/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="mc4wp-admin" class="wrap mc4wp-settings">
    <p class="breadcrumbs">
        <?php
        settings_errors();
        ?>
    </p>
    <p class="breadcrumbs">
        <span class="prefix"><?php echo __('You are here: ', 'contento-for-wp-settings'); ?></span>
        <span class="current-crumb"><strong>Contento for WordPress</strong></span>
    </p>
    <div class="row">
        <!-- Main Content -->
        <div class="main-content col col-4">
            <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

            <form method="post" action="<?php echo admin_url('options.php'); ?>">

                <?php
                settings_fields($this->plugin_name);
                do_settings_sections($this->plugin_name);

                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <?php _e('Status', 'contento-for-wp-settings'); ?>
                        </th>
                        <td>
                            <?php if ($connected) { ?>
                                <span
                                    class="status positive"><?php _e('CONNECTED', 'contento-for-wp-settings'); ?></span>
                            <?php } else { ?>
                                <span
                                    class="status neutral"><?php _e('NOT CONNECTED', 'contento-for-wp-settings'); ?></span>
                            <?php }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label
                                for="contento_username"><?php _e('Email', 'contento-for-wp-settings'); ?></label>
                        </th>
                        <td>
                            <input type="email" class="widefat"
                                   placeholder="<?php _e('Your Contento Email', 'contento-for-wp-settings'); ?>"
                                   id="contento_username"
                                   name="<?php echo $this->plugin_name; ?>[contento_username]"
                                   value="<?php echo $username ?>"/>
                            <p class="help">
                                <?php _e('Not created an account yet?.', 'contento-for-wp-settings'); ?>
                                <a target="_blank"
                                   href="https://www.contento.com.ng/register"><?php _e('Sign Up here.', 'contento-for-wp-settings'); ?></a>
                            </p>
                        </td>

                    </tr>
                    <tr valign="top">
                        <th scope="row"><label
                                for="contento_password"><?php _e('Password', 'contento-for-wp-settings'); ?></label>
                        </th>
                        <td>
                            <input type="text" class="widefat"
                                   placeholder="<?php _e('Your Contento Password', 'contento-for-wp-settings'); ?>"
                                   id="contento_password"
                                   name="<?php echo $this->plugin_name; ?>[contento_password]"
                                   value="<?php echo $password ?>"/>
                        </td>

                    </tr>
                    <tr valign="top">
                        <th scope="row"><label
                                for="contento_password"><?php _e('Job', 'contento-for-wp-settings'); ?></label>
                        </th>
                        <td>
                            <label>
                            <input type="checkbox"
                                   id="contento_is_job"
                                   name="<?php echo $this->plugin_name; ?>[contento_is_job]"/>Are you a Job board?</label>
                        </td>

                    </tr>

                </table>
                <?php submit_button(__('Save Changes', 'primary', 'submit', TRUE)) ?>
            </form>

        </div>

        <div class="sidebar col col-2 mc4wp-box">
            <h2>Manage Default Subscription</h2>
            <form method="post" action="<?php echo admin_url('options.php'); ?>">

                <?php
                settings_fields($this->plugin_name . '-subscription');
                do_settings_sections($this->plugin_name . '-subscription');
                ?>
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row"><label
                                for="contento_subscriptions"><?php _e('Subscription', 'contento-for-wp-settings'); ?></label>
                        </th>
                        <td>
                            <select name="<?php echo $this->plugin_name . '-subscription'; ?>[contento_subscriptions]"
                                    class="widefat" id="contento_subscriptions">
                                <?php
                                if ($connected) {
                                    foreach ($res as $sub) {
                                        echo '<option value="' . $sub->id . '">' . $sub->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <p class="help">
                                <?php _e('Not subscribed yet?.', 'contento-for-wp-settings'); ?>
                                <a target="_blank"
                                   href="https://www.contento.com.ng/user/create-subscription"><?php _e('Create Subscription.', 'contento-for-wp-settings'); ?></a>
                            </p>
                        </td>

                    </tr>


                </table>
                <?php submit_button(); ?>
            </form>
        </div>

    </div>
</div>
