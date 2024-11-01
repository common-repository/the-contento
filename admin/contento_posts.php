<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . '/wp-admin/includes/class-wp-list-table.php');
}

/**
 * Created by PhpStorm.
 * User: OluOmotoso
 * Date: 20-Apr-17
 * Time: 11:43 AM
 */
class contento_posts extends WP_List_Table
{
    public $response;

    function __construct($response)
    {
        $this->response = $response;
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'post',     //singular name of the listed records
            'plural' => 'posts',    //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));

    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array('title', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'publish' => 'Publish',
            'draft' => 'Add to Draft'
        );
        return $actions;
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => 'Title',
            'description' => 'Description',
            'updated_at' => 'Time Elapsed'
        );
        return $columns;
    }


    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $current_page = $this->get_pagenum();
        $per_page = 10;
        $data = $this->response;
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page' => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'title':
            case 'description':
                return $item->$column_name;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    public function column_updated_at($item)
    {
        $time = strtotime($item->updated_at);
        return human_time_diff($time). ' ago';
    }

    function column_title($item)
    {

        //Build row actions
        $actions = array(
            'view' => sprintf('<a href="?page=%s&action=%s&post=%s" target="_blank">View</a>', $_REQUEST['page'], 'view', $item->id),
            'draft' => sprintf('<a href="?page=%s&action=%s&post=%s">Draft</a>', $_REQUEST['page'], 'draft', $item->id),
            'publish' => sprintf('<a href="?page=%s&action=%s&post=%s">Publish</a>', $_REQUEST['page'], 'publish', $item->id),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(source:%2$s)</span>%3$s',
            /*$1%s*/
            $item->title,
            /*$2%s*/
            $item->datasources_feed->datasource->url,
            /*$3%s*/
            $this->row_actions($actions)
        );
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/
            $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/
            $item->id                //The value of the checkbox should be the record's id
        );
    }

    function my_render_list_page()
    {
        echo '<div class="wrap"><h2>The Contento Posts</h2>';
        $this->prepare_items();
        $this->display();
        echo '</div>';
    }

}