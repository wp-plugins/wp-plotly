<?php
/**
 * Plugin Name: Plotly
 * Plugin URI: http:s//github.com/plotly/wp-plotly
 * Description: Embed plotly graphs in your posts
 * Version: 1.0.3
 * Author: André Farzat
 * Author URI: https://plot.ly
 * License: GPL2
 */



add_action( 'init', 'plotly_add_embed_handlers' );
function plotly_add_embed_handlers() {
    wp_embed_register_handler( 'plotly', '#https?://(www\.)?plot\.ly/~([\w\-\.]+)/(\d+).*#i', 'plotly_embed_handler' );
}

function plotly_embed_handler($matches, $attr, $url, $rawattr){
    $username = $matches[2];
    $idlocal = $matches[3];

    $localurl = "https://plot.ly/~$username/$idlocal";
    $image = "<img src='${localurl}.png' onerror='this.onerror=null;this.src=\"https://plot.ly/404.png\"' />";


    if( empty($username) || empty($idlocal) ){
        return apply_filters( 'embed_plotly', $url, $matches, $attr, $url, $rawattr );
    }

    if( is_admin() ){
        $embed = $image;
    } else {
        $embed = "<div>";
        $embed .= "<a href='{$localurl}' target='_blank'>$image</a>";
        $embed .= "<script data-plotly='${username}:{$idlocal}' src='https://plot.ly/embed.js' async></script>";
        $embed .= "</div>";
    }

    return apply_filters( 'embed_plotly', $embed, $matches, $attr, $url, $rawattr );
}


?>
