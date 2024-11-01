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
        if (isset($_GET['message'])) {
            echo '<div class="alert alert-success">
            <ul>
                   <li>' . urldecode($_GET['message']) . '</li>
              </ul>
        </div>';
        }
        ?>
    </p>
    <p class="breadcrumbs">
        <span class="prefix"><?php echo __('You are here: ', 'contento-for-wp-author-settings'); ?></span>
        <span class="current-crumb"><strong>Contento for WordPress</strong></span>
    </p>
    <div class="row">
        <!-- Main Content -->
        <div class="main-content col col-4">
            <h2>Manage Default Subscription</h2>
            <form role="form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="contento_authors">
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row"><label
                                for="contento_subscriptions"><?php _e('Subscription', 'contento-for-wp-author-settings'); ?></label>
                        </th>
                        <td>
                            <select name="author_subscription"
                                    class="widefat" id="contento_subscriptions">
                                <?php
                                if ($connected) {
                                    foreach ($res as $sub) {
                                        echo '<option value="' . $sub->id . '">' . $sub->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>

                    </tr>


                </table>
                <?php submit_button(); ?>
            </form>
        </div>

    </div>

</div>
