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
            <h2>Manage AutoPublishing By Subscription</h2>

            <form method="post" action="<?php echo admin_url('options.php'); ?>">

                <?php
                settings_fields($this->plugin_name . '-autopost');
                do_settings_sections($this->plugin_name . '-autopost');
                ?>
                <table class="form-table">

                    <?php
                    $options = get_option($this->plugin_name . '-autopost');
                    for ($i = 0; $i < count($res); $i++) {
                        if (isset($options[$res[$i]->id])) {
                            $before[$i] = $options[$res[$i]->id];
                            if ($before[$i] == 1 || 0) {
                                $before[$i] = null;
                            }
                        } else {
                            $before[$i] = null;
                        }
                        echo '<tr valign="top">
                        <th scope="row"><label
                                for="contento_subscriptions">' . $res[$i]->feed->datasource->url . '</label>
                        </th>
                        <td>';

                        echo '<input type="hidden" value="' . $res[$i]->id . '" name="' . $this->plugin_name . '-autopost[autopost' . $i . ']">';
                        echo '<p><label><input type="checkbox" name="' . $this->plugin_name . '-autopost[publish_all' . $i . ']"></label>Publish all ' . $res[$i]->feed->datasource->url . ' posts automatically</p>';
                        echo '' . _e('Type keywords to publish if present in an article, separate each by comma. Do not type anything here if you marked publish all') . '<textarea rows="5" cols="10" class="widefat" name="' . $this->plugin_name . '-autopost[autopost_option' . $i . ']">' . $before[$i] . '</textarea>';
                        echo '</td></tr>';
                    }
                    ?>
                </table>
                <?php submit_button(__('Save Changes', 'primary', 'submit', TRUE)) ?>
            </form>

        </div>

    </div>
</div>
