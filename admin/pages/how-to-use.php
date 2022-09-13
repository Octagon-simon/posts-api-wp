<?php

if ( ! defined( 'ABSPATH' ) ) 
exit;

global $wpdb;

?>
<!DOCTYPE html>
<html>

<body>
    <div class="wrap mt-3" id="posts-api-wp">
        <?php
            if(get_option('posts_api_wp_config')):
                $endPoint = esc_url($_SERVER['REQUEST_SCHEME'].'://YOUR_WORDPRESS_BLOG/posts-api-wp/');
        ?>
        <section class="posts-api-wp-section">
            <div class="mb-3">
                <label class="label">Endpoint URL</label>
                <section style="display:flex">
                    <input id="inp_endPoint" type="text" class="w-100 posts-api-wp-input-append posts-api-wp-input"
                        value="<?php echo esc_url($endPoint); ?>" readonly>
                    <button type="button" class="posts-api-wp-btn-outline posts-api-wp-btn-append"
                        onclick="const endPoint = document.querySelector('#inp_endPoint').value.trim();(endPoint !== '') ? (window.navigator.clipboard.writeText(endPoint), alert('Endpoint has been copied')) : ''">Copy</button>
                </section>
            </div>
            <article>
                <p class="posts-api-wp-alert info font-1 mb-3">Make sure to modify the <b>Endpoint URL</b> to reflect your WordPress Blog.</p>
                <h2 class="fw-bold mb-2">A LITTLE TIP</h2>
                <p class="mb-1 font-1">If you have access to the <b>.htaccess</b> file, you can set up a custom route to
                    this Endpoint.</p>
                <pre>##CUSTOM ROUTE TO ENDPOINT<br>
RewriteEngine On<br>
RewriteCond %{REQUEST_METHOD} GET<br>
RewriteCond %{REQUEST_FILENAME} !-f<br>
##Syntax<br>
##RewriteRule CUSTOM_ROUTE$ ENDPOINT_URL [NC,L]<br>
##For Example<br>
RewriteRule api/get-wordpress-posts$ blog/posts-api-wp/ [NC,L]</pre>
            </article>
        </section>
        <section class="mt-3 posts-api-wp-section">
            <article>
                <h2 class="mb-2 fw-bold">Request Method & Authorization Header</h2>
                <p class="font-1 mb-2">Before you can retrieve data with the API, you must send a <b>GET</b> request to the endpoint and include your API Key in the authorization header.</p>
                <p class="mb-2 font-1"><code>Authorization: YOUR_API_KEY</code></p>
                <p class="mb-2 font-1">For example</p>
                <p class="mb-2 font-1"><code class="mb-2">Authorization: XAXXXXBCDXXXXEFXXXXGXXXHIJXXXXXX</code></p>
                <p class="font-1">Before you include your API key in the Authorization Header, make sure it is the recent one which you just generated on the configuration page.</p>
            </article>
        </section>
        <section class="mt-3 posts-api-wp-section">
        <article>
                <h2 class="mb-2 fw-bold">Debugging</h2>
                <p class="posts-api-wp-alert info font-1 mb-3">If you see a <b>HTTP CODE 500 INTERNAL SERVER ERROR,</b> it may be from your wordpress site or the wordpress plugin itself. In such case, disable the plugin then submit an issue with steps to reproduce this error for us to look at it.</p>
                <p class="font-1 mb-2"><code>{ "success" : false, "data" : "API configuration does not exist"}</code></p>
                <p class="font-1 mb-3">If you get this error, it means that you have not configured your API on the configuration page.</p>
                <p class="font-1 mb-2"><code>{ "success" : false, "data" : "You must provide an Authorization header"}</code></p>
                <p class="font-1 mb-3">If you get this error, it means that you did not attach an Authorization header to the request.</p>
                <p class="font-1 mb-2"><code>{ "success" : false, "data" : "Your Authorization header contains an Invalid API key"}</code></p>
                <p class="font-1 mb-3">If you get this error, it means that you did not provide the correct API key. Please regenerate the API key and copy the new one.</p>
                <p class="font-1 mb-2"><code>{ "success" : false, "data" : "Invalid Request Method"}</code></p>
                <p class="font-1 mb-3">If you get this error, it means that you did not send a <b>GET</b> request to the Endpoint.</p>
        </article>
        </section>
        <?php
            else:
        ?>
        <section class="text-center posts-api-wp-section">
            <article>
                <img src="<?php print(plugins_url( '/../img/', __FILE__).'exclamation-triangle.png'); ?>" width="150px">
            <h1 class="fw-bold mt-2 mb-2">API CONFIGURATION NOT SET</h1>
            <p class="font-1">You must configure your API before you can access the contents of this page.</p>

            </article>
        </section>
        <?php endif; ?>
    </div>
</body>

</html>