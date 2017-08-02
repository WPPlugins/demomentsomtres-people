<?php
/*
  Plugin Name: DeMomentSomTres People
  Plugin URI: http://demomentsomtres.com/catala
  Description: Show the people in your organization
  Version: 1.2
  Author: Marc Queralt
  Author URI: http://demomentsomtres.com/
  License: GPLv2
 */
define('DMST_PEOPLE_DOMAIN', 'dmst-people');

load_plugin_textdomain(DMST_PEOPLE_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
add_action('init', 'dmst_create_people');
add_action('add_meta_boxes', 'dmst_people_admin');
add_action('save_post', 'dmst_people_save_meta');
// Register a new shortcode: [demomentsomtres-people] and its stylesheets and scripts
add_shortcode('demomentsomtres-people', 'dmst_people_shortcode');
wp_register_script('dmst-people-script', plugins_url('/dmst_people.js', __FILE__), "", "20130215", true);
wp_register_style('dmst-people-style', plugins_url('/dmst_people.css', __FILE__), "", "20130215", false);

function dmst_create_people() {
    $labels = array(
        'name' => _x('Departments', 'taxonomy general name', DMST_PEOPLE_DOMAIM),
        'singular_name' => _x('Department', 'taxonomy singular name', DMST_PEOPLE_DOMAIM),
        'search_items' => __('Search Department', DMST_PEOPLE_DOMAIM),
        'all_items' => __('All Departments', DMST_PEOPLE_DOMAIM),
        'parent_item' => __('Parent Department', DMST_PEOPLE_DOMAIM),
        'parent_item_colon' => __('Parent Department:', DMST_PEOPLE_DOMAIM),
        'edit_item' => __('Edit Department', DMST_PEOPLE_DOMAIM),
        'update_item' => __('Update Department', DMST_PEOPLE_DOMAIM),
        'add_new_item' => __('Add New Department', DMST_PEOPLE_DOMAIM),
        'new_item_name' => __('New Department Name', DMST_PEOPLE_DOMAIM),
    );

    register_taxonomy('department', '', array(
        'hierarchical' => true,
        'labels' => $labels
    ));
    register_post_type('people', array(
        'labels' => array(
            'name' => __('People', DMST_PEOPLE_DOMAIM),
            'singular_name' => __('Person', DMST_PEOPLE_DOMAIM),
            'add_new' => __('Add New', DMST_PEOPLE_DOMAIM),
            'add_new_item' => __('Add New Person', DMST_PEOPLE_DOMAIM),
            'edit' => __('Edit', DMST_PEOPLE_DOMAIM),
            'edit_item' => __('Edit Person', DMST_PEOPLE_DOMAIM),
            'new_item' => __('New Person', DMST_PEOPLE_DOMAIM),
            'view' => __('View', DMST_PEOPLE_DOMAIM),
            'view_item' => __('View Person', DMST_PEOPLE_DOMAIM),
            'search_items' => __('Search People', DMST_PEOPLE_DOMAIM),
            'not_found' => __('No People found', DMST_PEOPLE_DOMAIM),
            'not_found_in_trash' => __('No People found in Trash', DMST_PEOPLE_DOMAIM),
            'parent' => __('Parent People', DMST_PEOPLE_DOMAIM)
        ),
        'public' => true,
        'menu_position' => 15,
        'supports' => array('title', 'editor', /* 'comments', */ 'thumbnail', 'custom-fields'),
        'taxonomies' => array('department'),
        'has_archive' => true
            )
    );
}

function dmst_people_admin() {
    add_meta_box(
            'dmst_people_additional_info', __('Additional info', DMST_PEOPLE_DOMAIN), 'dmst_people_additional_info', 'people', 'normal', 'high'
    );
}

function dmst_people_additional_info($post) {
    $dmst_people_title = get_post_meta($post->ID, 'dmst_people_title', true);
    ?>
    <p>
        <? echo __('Title', DMST_PEOPLE_DOMAIN); ?>:<br/>
        <textarea name="dmst_people_title" style="width:100%;height:4em;"/><?php echo esc_attr($dmst_people_title); ?></textarea>
    </p>
    <?
}

function dmst_people_save_meta($post_id) {
    if (isset($_POST['dmst_people_title'])):
        update_post_meta($post_id, 'dmst_people_title', strip_tags($_POST['dmst_people_title']));
    endif;
}

function dmst_people_shortcode() {
    wp_enqueue_script('dmst-people-script');
    wp_enqueue_style('dmst-people-style');
    $result = '<div class="dmst_people">';
    $departments = get_terms('department', array('orderby' => 'slug', 'hide_empty' => 0));
    foreach ($departments as $d):
        $result.='<h2 class="department"><a href="#tab' . $d->slug . '">' . $d->name . '</a></h2>';
        $result.='<div id="tab' . $d->slug . '" class="panelDepartment">';
        $args = array(
            'post_type' => 'people',
            'tax_query' => array(
                array(
                    'taxonomy' => 'department',
                    'field' => 'slug',
                    'terms' => $d->slug
                )
            ),
            'orderby' => 'name',
            'order' => 'ASC',
            'nopaging' => 'true'
        );
        $query = new WP_Query($args);
        $people = $query->posts;
//        echo '<pre>'.apply_filters('the_content',print_r($people,true)).'</pre>';
        $i = 0;
        foreach ($people as $p):
            $i = ($i + 1) % 4;
            if ($i == 0):
                $last = " last";
            else:
                $last = "";
            endif;
//            $result .= '<pre>' . print_r($p, true) . '</pre>';
            $meta = get_post_meta($p->ID);
//            $result.= '<pre>' . print_r($meta, true) . '</pre>';
            $result .= '<a class="people_image' . $last . '" href="#people-' . $p->post_name . '">';
            if (has_post_thumbnail($p->ID)):
                $result .= get_the_post_thumbnail($p->ID, 'medium');
            else:
                $result .= '<p class="no_image">' . __('No image', DMST_PEOPLE_DOMAIN) . '</p>';
            endif;
            $result .= '</a>';
            $result .= '<div class="people_description" id="people-' . $p->post_name . '">';
            $result .= '<h3 class="people_name" id="' . $p->post_name . '">' . $p->post_title . '</h3>';
            $result .= '<div class="people_title">' . apply_filters('the_content', $meta['dmst_people_title'][0]) . '</div>';
            $result .= apply_filters('the_content', $p->post_content) . '</div>';
        endforeach;
        $result .= '</div><!--end of #tab' . $d->slug . '"-->';
    endforeach;
//    $result.='<!--tabs--><ul class="dmst_people_tabs">' . '</ul>';
//    $result.='<!--tabcontents--><ul class="dmst_people_tabcontents">';
//    $result.='</ul><!--tabcontents-->';
    $result.='</div>';
    return $result;
}
?>