<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.contento.com.ng
 * @since      1.0.0
 *
 * @package    Wp_Contento
 * @subpackage Wp_Contento/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Contento
 * @subpackage Wp_Contento/admin
 * @author     Femtosh Global Solutions <info@femtosh.com>
 */
class Wp_Contento_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    protected $host;
    protected $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->host = 'http://api.contento.com.ng';
        $this->options = get_option($this->plugin_name);


    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Contento_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Contento_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-contento-admin.css', array(), $this->version, 'all');
        wp_register_style('contento-admin-styles', plugin_dir_url(__FILE__) . 'css/admin-styles' . '.min' . '.css', array(), $this->version);
        wp_enqueue_style('contento-admin-styles');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Contento_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Contento_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-contento-admin.js', array('jquery'), $this->version, false);

    }

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        //add_menu_page()
        $menu_items = array(
            'general' => array(
                'title' => __('Contento API Settings', 'contento-for-wp'),
                'text' => __('Settings', 'contento-for-wp'),
                'slug' => 'settings',
                'callback' => array($this, 'DisplaySettingsPage'),
                'position' => 80
            ),
            'other' => array(
                'title' => __('Other Settings', 'contento-for-wp'),
                'text' => __('Other', 'contento-for-wp'),
                'slug' => 'other',
                'callback' => array($this, 'DisplayOtherSettingsPage'),
                'position' => 90
            ),
            'mysettings' => array(
                'title' => __('Author Settings', 'contento-for-wp'),
                'text' => __('Author Settings', 'contento-for-wp'),
                'slug' => 'author-settings',
                'callback' => array($this, 'DisplayAuthorSettingsPage'),
                'position' => 90,
                'capability' => 'publish_posts'
            ),
            'post' => array(
                'title' => __('Contento', 'contento-for-wp'),
                'text' => __('Contento Posts', 'contento-for-wp'),
                'slug' => '',
                'callback' => array($this, 'DisplayPostPage'),
                'position' => 0,
                'capability' => 'publish_posts'
            )
        );
        add_menu_page('Contento', 'Contento', 'publish_posts', 'contento-for-wp', array($this, 'DisplayPostPage'), 'http://contento.com.ng/images/logo-small.png', '5'
        );
        // sort submenu items by 'position'
        uasort($menu_items, array($this, 'sort_menu_items_by_position'));

        // add sub-menu items
        array_walk($menu_items, array($this, 'add_menu_item'));
    }

    public function sort_menu_items_by_position($a, $b)
    {
        $pos_a = isset($a['position']) ? $a['position'] : 80;
        $pos_b = isset($b['position']) ? $b['position'] : 90;
        return $pos_a < $pos_b ? -1 : 1;
    }

    public function add_menu_item(array $item)
    {

        // generate menu slug
        $slug = 'contento-for-wp';
        if (!empty($item['slug'])) {
            $slug .= '-' . $item['slug'];
        }

        // provide some defaults
        $parent_slug = !empty($item['parent_slug']) ? $item['parent_slug'] : 'contento-for-wp';
        $capability = !empty($item['capability']) ? $item['capability'] : 'manage_options';

        // register page
        $hook = add_submenu_page($parent_slug, $item['title'] . ' - Contento for Wordpress', $item['text'], $capability, $slug, $item['callback']);

        // register callback for loading this page, if given
        if (array_key_exists('load_callback', $item)) {
            add_action('load-' . $hook, $item['load_callback']);
        }
    }

    public function GetUpdatedPost($username, $password, $subscription, $hook)
    {
        $headers = $this->headers($username, $password);
        $args = ['body' => ['subscription' => $subscription, 'hook' => $hook], 'headers' => $headers];
        $url = '/me/subscription-posts';
        $response = wp_remote_post($this->host . $url, $args);
        return $response;
    }

    public function GetSubscriptionSources($username, $password, $subscription)
    {
        $headers = $this->headers($username, $password);
        $args = ['body' => ['subscription' => $subscription], 'headers' => $headers];
        $url = '/me/get-sources';
        $response = wp_remote_post($this->host . $url, $args);
        return $response;
    }

    public function GetSubscriptionCategories($username, $password)
    {
        $headers = $this->headers($username, $password);
        $args = ['headers' => $headers];
        $url = '/me/select-categories';
        $response = wp_remote_get($this->host . $url, $args);
        return $response;
    }

    public function GetContent($username, $password, $post)
    {
        $headers = $this->headers($username, $password);
        $args = ['headers' => $headers];
        $url = '/me/content/' . $post;
        $response = wp_remote_get($this->host . $url, $args);
        return $response;
    }

    public function RegularizeContent($content)
    {
        $photos = $this->GetMultipleImageUrl($content);
        foreach ($photos as $photo) {
            try {
                $image = media_sideload_image($photo, 0);
                preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $image, $result);
                $new_photo = $result[3];
                $content = str_replace($photo, $new_photo[0], $content);
            } catch (\Exception $exception) {
                continue;
            }
        }
        return $content;
    }

    public function PublishDraft($content, $title)
    {
        $postarr = ['post_content' => $content, 'post_title' => $title];
        $post = wp_insert_post($postarr);

    }

    public function PublishContent($content, $title)
    {
        $postarr = ['post_content' => $content, 'post_title' => $title, 'post_status' => 'publish'];
        wp_insert_post($postarr);
    }

    public function UpdatePublished($username, $password, $subscription, $hook, $feed)
    {
        $headers = $this->headers($username, $password);
        $args = ['body' => ['feed' => $feed, 'subscription' => $subscription, 'hook' => $hook], 'headers' => $headers];
        $url = '/me/notify-publish';
        $response = wp_remote_post($this->host . $url, $args);
        return $response;
    }

    public function DisplayPostPage()
    {
        $option = get_option($this->plugin_name . '-subscription');
        if (!empty($this->options) && !empty($option)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
            $contento_author = get_current_user_id();
            $exist = get_option($this->plugin_name . '_author' . $contento_author);
            if ($exist) {
                $subscription = $exist['subscription'];
                $hook = $exist['hook'];
            } else {
                $subscription = $option['contento_subscriptions'];
                $hook = $option['contento_hook'];
            }


            if (isset($_GET['action']) && isset($_GET['post'])) {
                $action = $_GET['action'];
                $post = $_GET['post'];
                $content = $this->GetContent($username, $password, $post);
                $content = json_decode(wp_remote_retrieve_body($content));
                if ($action == 'view') {
                    echo($content->content);
                    exit();
                } elseif ($action == 'draft') {
                    $new_content = $this->RegularizeContent($content->content);
                    $this->PublishDraft($new_content, $content->title);
                    $this->UpdatePublished($username, $password, $subscription, $hook, $post);
                    //wp_redirect(admin_url('/admin.php?page=contento-for-wp'));
                    echo 'Added to Draft';
                } elseif ($action == 'publish') {
                    $new_content = $this->RegularizeContent($content->content);
                    $this->PublishContent($new_content, $content->title);
                    $this->UpdatePublished($username, $password, $subscription, $hook, $post);
                    //wp_redirect(admin_url('/admin.php?page=contento-for-wp'));
                    echo 'Published Successfully';
                }
            }
            $response = $this->GetUpdatedPost($username, $password, $subscription, $hook);
            $code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            if ($code == 200) {
                require_once('contento_posts.php');
                $response_body = json_decode($response_body);
                $post_display = new contento_posts($response_body);
                $post_display->my_render_list_page();
                //include_once('partials/wp-contento-posts-display.php');
            } else {
                echo $response_body;
            }
        }
    }

    public function GetMultipleImageUrl($content)
    {
        preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $content, $matches);
        return ($matches[3]);

    }

    public function DisplayOtherSettingsPage()
    {
        if (!empty($this->options)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
        } else {

            $username = null;
            $password = null;
        }
        $response = $this->SubscriptionList($username, $password);
        $code = wp_remote_retrieve_response_code($response);
        $connected = false;
        if ($code == 200) {
            $connected = true;
            $option = get_option($this->plugin_name . '-subscription');
            $subscription = $option['contento_subscriptions'];
            $res = $this->GetSubscriptionSources($username, $password, $subscription);
            $res = json_decode(wp_remote_retrieve_body($res));

            include_once('partials/wp-contento-others-display.php');

        } else {
            echo '<a href="' . admin_url('/admin.php?page=contento-for-wp-settings') . '">' . __('Click here to update your account details & Subscription first', $this->plugin_name) . '</a>';
        }
    }

    public function DisplayAuthorSettingsPage()
    {
        if (!empty($this->options)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
        } else {

            $username = null;
            $password = null;
        }
        $response = $this->SubscriptionList($username, $password);
        $code = wp_remote_retrieve_response_code($response);
        $connected = false;
        if ($code == 200) {
            $connected = true;
            $res = json_decode(wp_remote_retrieve_body($response));
        }
        include_once('partials/wp-contento-authors-display.php');
    }

    public function SubscriptionList($username, $password)
    {
        $headers = $this->headers($username, $password);
        $args = ['headers' => $headers];
        $url = '/me/subscriptions';
        $response = wp_remote_get($this->host . $url, $args);
        return $response;
    }

    public function headers($username, $password)
    {
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
        );
        return $headers;
    }

    public function UpdateSubscriptionURL($username, $password, $sub)
    {
        $headers = $this->headers($username, $password);
        $args = ['body' => ['subscription' => $sub, 'url' => get_site_url()], 'headers' => $headers];
        $url = '/me/update-subscription-url';
        $response = wp_remote_post($this->host . $url, $args);
        return $response;
    }

    public function DisplaySettingsPage()
    {
        if (!empty($this->options)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
        } else {

            $username = null;
            $password = null;
        }
        $response = $this->SubscriptionList($username, $password);
        $code = wp_remote_retrieve_response_code($response);
        $connected = false;
        if ($code == 200) {
            $connected = true;
            $res = json_decode(wp_remote_retrieve_body($response));
        }
        include_once('partials/wp-contento-settings-display.php');
    }

    public function update_contento_details()
    {
        if (current_user_can('activate_plugins')) {
            register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
        }
    }

    public function update_contento_subscription()
    {
        if (current_user_can('activate_plugins')) {
            register_setting($this->plugin_name . '-subscription', $this->plugin_name . '-subscription', array($this, 'SubscriptionInput'));
        }
    }

    public function update_contento_autopost()
    {
        if (current_user_can('activate_plugins')) {
            register_setting($this->plugin_name . '-autopost', $this->plugin_name . '-autopost', array($this, 'TheAutopublish'));
        }
    }

    /*public function update_author_settings()
    {
        if (current_user_can('publish_posts')) {
            register_setting($this->plugin_name . '-author' . $contento_author, $this->plugin_name . '-author' . $contento_author, array($this, 'PostCategories'));
        }

    }*/

    public function do_contento_cron()
    {
        if (!empty($this->options)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
            $option = get_option($this->plugin_name . '-subscription');
            $subscription = $option['contento_subscriptions'];
            $hook = $option['contento_hook'];


        } else {

            $username = null;
            $password = null;
            $subscription = null;
            $hook = null;

        }
        $res = $this->GetSubscriptionSources($username, $password, $subscription);
        $code = wp_remote_retrieve_response_code($res);
        $res = json_decode(wp_remote_retrieve_body($res));
        $autopost_option = get_option($this->plugin_name . '-autopost');
        if ($code == 200) {
            if (!empty($autopost_option)) {

                foreach ($res as $item) {
                    if (isset($autopost_option[$item->id])) {
                        $autopost_value = $autopost_option[$item->id];
                        if ($autopost_value == 1) {
                            $new_content = $this->RegularizeContent($item->content);
                            $this->PublishContent($new_content, $item->title);
                            $this->UpdatePublished($username, $password, $subscription, $hook, $item->id);
                        } elseif ($autopost_value == 0) {

                        } else {
                            $needles = explode(',', $autopost_value);
                            $needles = array_unique($needles);
                            $needles = array_filter($needles);
                            $needles = array_values($needles);
                            if (!empty($needles)) {
                                foreach ($needles as $needle) {
                                    if (stripos($item->title . ' ' . $item->content, $needle) !== false) {
                                        $new_content = $this->RegularizeContent($item->content);
                                        $this->PublishContent($new_content, $item->title);
                                        $this->UpdatePublished($username, $password, $subscription, $hook, $item->id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function TheAutopublish($input)
    {
        if (!empty($this->options)) {
            $username = $this->options['contento_username'];
            $password = $this->options['contento_password'];
            $option = get_option($this->plugin_name . '-subscription');
            $subscription = $option['contento_subscriptions'];

        } else {

            $username = null;
            $password = null;
            $subscription = null;
        }
        $res = $this->GetSubscriptionSources($username, $password, $subscription);
        $res = json_decode(wp_remote_retrieve_body($res));
        $valid = [];
        for ($i = 0; $i < count($res); $i++) {
            $param = $input['autopost' . $i];
            if (isset($input['publish_all' . $i])) {
                $valid[$param] = 1;
            } else {
                $indent = $input['autopost_option' . $i];
                if ($indent == '') {
                    $valid[$param] = 0;
                } else {
                    $valid[$param] = $indent;
                }

            }
        }
        return $valid;
    }

    public function PostCategories()
    {
        $author_subscription = $_POST['author_subscription'];
        $contento_author = get_current_user_id();
        $valid = [];
        $response = $this->UpdateSubscriptionURL($this->options['contento_username'], $this->options['contento_password'], $author_subscription);
        $code = wp_remote_retrieve_response_code($response);
        $hook = wp_remote_retrieve_body($response);
        if ($code == 200) {
            $valid['hook'] = $hook;
        } else {
            $valid['hook'] = 0;
        }
        $valid['subscription'] = $author_subscription;

        $exist = get_option($this->plugin_name . '_author' . $contento_author);
        if ($exist) {
            update_option($this->plugin_name . '_author' . $contento_author, $valid);
        } else {
            add_option($this->plugin_name . '_author' . $contento_author, $valid);
        }
        $message = urlencode('Settings saved successfully');
        wp_redirect(admin_url() . 'admin.php?page=' . 'contento-for-wp-author-settings' . '&message=' . $message);
    }

    public function SubscriptionInput($input)
    {
        $subscription = $input['contento_subscriptions'];
        $response = $this->UpdateSubscriptionURL($this->options['contento_username'], $this->options['contento_password'], $subscription);
        $code = wp_remote_retrieve_response_code($response);
        $hook = wp_remote_retrieve_body($response);
        if ($code == 200) {
            $valid['contento_hook'] = $hook;
        } else {
            $valid['contento_hook'] = 0;
        }
        $valid['contento_subscriptions'] = $subscription;
        return $valid;
    }

    public function validate($input)
    {
        // All checkboxes inputs
        $valid = [];
        $valid['contento_username'] = $input['contento_username'];
        $valid['contento_password'] = $input['contento_password'];
        return $valid;
    }

    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('/admin.php?page=contento-for-wp-settings') . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }
}
