<?php
//get the post url
function getPostUrl(int $post_id = 0) :string{
    return(esc_url( get_permalink($post_id) ) );
}

function getPostFeaturedImage(int $post_id = 0, string $image_size) :string{
    //image size => small, medium, large
    return(get_the_post_thumbnail_url($post_id, $image_size) );
}

function getAuthorMeta(string $field = '', int $author_id) :string{
    //the_author_meta returns text like youre echoing the one from get_author_meta. it should be used when you wish to directly echo an author meta without the use of the echo or print keyword
    //returns the author meta data
    return(get_the_author_meta($field, $author_id));
}
?>