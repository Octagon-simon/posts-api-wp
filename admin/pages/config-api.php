<?php

$p = explode('/', dirname(__FILE__));
var_dump($p);
exit();

if ( ! defined( 'ABSPATH' ) ) 
    exit;
//include the functions file
require_once(dirname(__FILE__) . '/../core/functions.php');

global $wpdb;

//define arguments
/*
$args = array(
    'numberPosts' => 20,
    'category' => 4,
    'orderby' => 'name',
    'order' => 'ASC',
    'author' => 1,
    'author_name' => "",
    'author__in' => [],
    'author__not__in' => [],
    'category_name' => "",
    'category_and' => "",
    'category__in' => [],
    'category__not__in' => []
);
*/
//var_dump(get_posts(['numberPosts' => 20]));
//get_categories() lists out the categories that has a post assigned to them
//get_the_category(post_id) lists out the categories assigned to a particular post
//var_dump(get_categories());

$success = false;
$success_msg = "";

$author_meta_fields = ['admin_color', 'aim', 'comment_shortcuts', 'description', 'display_name','first_name', 'ID', 'jabber', 'last_name', 'nickname', 'plugins_last_view', 'plugins_per_page', 'rich_editing', 'syntax_highlighting', 'user_email', 'user_firstname', 'user_lastname', 'user_level', 'user_nicename', 'user_status', 'user_url', 'yim'];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['config-api'])){
    //check if configuration was saved before
    if( !empty(get_option('posts_api_wp_config')) ){
        //default configuration
        $config = json_decode(get_option('posts_api_wp_config'));

        if(isset($_POST['numberPosts']) && !empty($_POST['numberPosts'])){
            $config->numberPosts = intval($_POST['numberPosts']);
        }
        if(isset($_POST['cat_in']) && !empty($_POST['cat_in']) && is_array($_POST['cat_in'])){
            if( in_array('all', $_POST['cat_in']) ){
                //reset stored categories
                $config->category_in = [];
                foreach(get_categories() as $cat){
                    //store all category ids
                    $config->category_in[] = intval($cat->term_id);
                }
            }else{
                //store category ids
                $config->category_in = array_map(function($val){
                    return (intval($val));
                }, $_POST['cat_in']);
            }
        }
        if(isset($_POST['order']) && !empty($_POST['order'])){
            $config->order = sanitize_text_field($_POST['order']);
        }
        if(isset($_POST['featuredImg']) && !empty($_POST['featuredImg'])){
            $config->featuredImg = true;
        }
        if(isset($_POST['articleUrl']) && !empty($_POST['articleUrl'])){
            $config->articleUrl = true;
        }
        //author meta
        if(isset($_POST['authorMeta']) && !empty($_POST['authorMeta']) && is_array($_POST['author_meta'])){
            //check & store author meta fields
            $metaFields = [];
            foreach($_POST['author_meta'] as $meta){
                //check if meta field exists in the list of accepted fields
                if( in_array($meta, $author_meta_fields)){
                    //store meta
                    $metaFields[] = $meta;
                }
            }
            $config->author_meta = $metaFields;
        }
        //post date format
        if(isset($_POST['postDate']) && !empty($_POST['postDate']) && isset($_POST['post_date_format']) && !empty($_POST['post_date_format'])){
            //check if user wants a custom date format
            if($_POST['post_date_format'] == 'custom' && !empty($_POST['post_date_format_custom']) ){
                //store date format
                $config->post_date_format = sanitize_text_field($_POST['post_date_format_custom']);
            }else{
                $config->post_date_format = sanitize_text_field($_POST['post_date_format']);
            }
        }
    }else{
        //default configuration
        $config = array(
            'numberPosts' => 20,
            'orderby' => 'name',
            'order' => 'ASC',
            'featuredImg' => false
        );
    
        if(isset($_POST['numberPosts']) && !empty($_POST['numberPosts'])){
            $config['numberPosts'] = intval($_POST['numberPosts']);
        }
        if(isset($_POST['cat_in']) && !empty($_POST['cat_in']) && is_array($_POST['cat_in'])){
            if( in_array('all', $_POST['cat_in']) ){
                //reset stored categories
                $config['category_in'] = [];
                foreach(get_categories() as $cat){
                    //store all category ids
                    $config['category_in'][] = intval($cat->term_id);
                }
            }else{
                //store category ids
                $config['category_in'] = array_map(function($val){
                    return (intval($val));
                }, $_POST['cat_in']);
            }
        }
        if(isset($_POST['order']) && !empty($_POST['order'])){
            $config['order'] = sanitize_text_field($_POST['order']);
        }
        if(isset($_POST['featuredImg']) && !empty($_POST['featuredImg'])){
            $config['featuredImg'] = true;
        }
        if(isset($_POST['articleUrl']) && !empty($_POST['articleUrl'])){
            $config['articleUrl'] = true;
        }
        //author meta
        if(isset($_POST['authorMeta']) && !empty($_POST['authorMeta']) && is_array($_POST['author_meta'])){
            //check & store author meta fields
            $metaFields = [];
            foreach($_POST['author_meta'] as $meta){
                //check if meta field exists in the list of accepted fields
                if( in_array($meta, $author_meta_fields)){
                    //store meta
                    $metaFields[] = $meta;
                }
            }
            $config['author_meta'] = $metaFields;
        }
        //post date format
        if(isset($_POST['postDate']) && !empty($_POST['postDate']) && isset($_POST['post_date_format']) && !empty($_POST['post_date_format'])){
            //check if user wants a custom date format
            if($_POST['post_date_format'] == 'custom' && !empty($_POST['post_date_format_custom']) ){
                //store date format
                $config['post_date_format'] = sanitize_text_field($_POST['post_date_format_custom']);
            }else{
                $config['post_date_format'] = sanitize_text_field($_POST['post_date_format']);
            }
        }
    }
    //update configuration
    update_option('posts_api_wp_config', json_encode($config));
    $success = true;
    $success_msg = "API Configuration has been updated successfully";
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['config-authHeader'])){
    $authKey = sanitize_text_field($_POST['authKey']);
    //check if api key is empty or not
    if( !empty($authKey) ){
        //check if configuration options exist
        if( !empty(get_option('posts_api_wp_config'))){
            //retrieve all config options
            $config = json_decode(get_option('posts_api_wp_config'));
            //add authkey to it
            $config->authKey = md5($authKey);
        }else{
            //store authorization key
            $config['authKey'] = md5($authKey);
        }
        //update config options
        update_option('posts_api_wp_config', json_encode($config));

        $success = true;
        $success_msg = 'Authorization key <b>'.$authKey.'</b> has been saved successfully.';
    }
}
?>
<!DOCTYPE html>
<html>
<body>
    <div class="wrap" id="posts-api-wp">
        <?php
        if($success){
            echo '<div class="posts-api-wp-alert success mb-3 mt-3" style="max-width: 500px;">
                <p class="m-0" style="font-size:1rem">'.$success_msg.'</p></div>';
        }
    ?>
        <section class="mt-3 posts-api-wp-section" style="border: 2px solid #9c27b0;">
            <form style="max-width: 500px;" method="post"
                onsubmit="return confirm('If you save this key, any previous authorization key will be revoked, making it invalid.\n\nAre you sure you have copied this authorization key and wish to use it?')">
                <input type="hidden" name="config-authHeader" value="1">
                <div class="mb-2">
                    <label class="label">Generate an authorization key</label>
                    <section style="display:flex">
                        <input id="inp_authKey" name="authKey" type="text"
                            class="w-100 posts-api-wp-input-append posts-api-wp-input" value="" readonly>
                        <button type="button" id="btn_gen_authKey"
                            class="posts-api-wp-btn-outline posts-api-wp-btn-append">Regenerate</button>
                    </section>
                </div>
                <div class="mb-2">
                    <button class="posts-api-wp-btn" type="submit">Save Key</button>
                </div>
            </form>
        </section>

        <section class="mt-3 posts-api-wp-section">
            <form class="smw-50" style="max-width: 500px;" method="post">
                <input type="hidden" name="config-api" value="1">
                <div class="mb-2">
                    <label class="label">Number of posts</label>
                    <input id="postsNum" name="numberPosts" type="number" class="w-100 posts-api-wp-input" value="5"
                        placeholder="Enter the number of posts to display">
                    <small>Enter the number of posts to display</small>
                </div>
                <div class="mb-2">
                    <label class="label">Select categories</label>
                    <select name="cat_in[]" class="w-100 posts-api-wp-input" multiple>
                        <option value="all">All categories</option>
                        <?php
                        foreach(get_categories() as $cat){
                    ?>
                        <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->cat_name; ?></option>
                        <?php
                        }
                    ?>
                    </select>
                    <small>Select the categories to include</small>
                </div>
                <div class="mb-3">
                    <label class="label">Order posts by</label>
                    <select name="order" class="w-100 posts-api-wp-input font-1">
                        <option value="ASC">Ascending Order</option>
                        <option value="DESC">Descending Order</option>
                    </select>
                </div>
                <div class="mb-3 font-1">
                    <input name="featuredImg" type="checkbox" class="posts-api-wp-input" value="1">&nbsp;Include URL to
                    Featured Image
                </div>
                <div class="mb-3 font-1">
                    <input name="articleUrl" type="checkbox" class="posts-api-wp-input" value="1">&nbsp;Include URL to
                    Post
                </div>
                <div class="mb-3 font-1">
                    <input id="chk_author_meta" name="authorMeta" type="checkbox" class="posts-api-wp-input" value="1">&nbsp;Include Author Meta
                </div>
                <div class="mb-2" id="select_author_meta" style="display:none;">
                    <label class="label">Select Meta data to include</label>
                    <select name="author_meta[]" class="w-100 posts-api-wp-input" multiple>
                        <?php
                        foreach($author_meta_fields as $field){
                        ?>
                            <option value="<?php echo $field; ?>"><?php echo $field; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3 font-1">
                    <input id="chk_format_post_date" name="postDate" type="checkbox" class="posts-api-wp-input" value="1">&nbsp;Format Post Date
                </div>
                <div class="mb-2" id="section_post_date_format" style="display:none;">
                    <label class="label">Select Date Format</label>
                    <select id="select_post_date_format" name="post_date_format" class="w-100 posts-api-wp-input font-1">
                        <option value="">Select One</option>
                        <option value="custom">Custom Date Format</option>
                        <option value="m-d-y">09-18-01</option>
                        <option value="m-d-Y">09-18-2001</option>
                        <option value="D d, Y">Tue 18, 2001</option>
                        <option value="D M d Y">Tue Sep 18 2001</option>
                        <option value="M d, Y">Sept 18, 2001</option>
                    </select>
                </div>
                <div class="mb-2" id="section_post_date_custom_format" style="display:none;">
                    <p class="posts-api-wp-alert info font-1 mb-2">
                        <b>m</b> stands for the month ie <b>01 - 12</b><br>
                        <b>M</b> stands for the month ie <b>Jan - Dec</b><br>
                        <b>d</b> stands for the date ie <b>01 - 31</b><br>
                        <b>D</b> stands for the day of the week ie <b>Sun - Sat</b><br>
                        <b>y</b> stands for the year ie <b>00 - 99</b><br>
                        <b>Y</b> stands for the full year ie <b>2001</b> <br>
                    </p>
                    <label class="label">Enter a Custom Format</label>
                    <input name="post_date_format_custom" type="text" class="w-100 posts-api-wp-input" value="D d, Y" placeholder="Enter the date format to use">
                </div>
                <div class="mb-2">
                    <button class="posts-api-wp-btn" type="submit">Save configuration</button>
                </div>
            </form>
        </section>
    </div>
</body>
</html>