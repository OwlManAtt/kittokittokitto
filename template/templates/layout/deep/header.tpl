<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" xmlns:spry='http://ns.adobe.com/spry'>
    <head>
        <title>{if $page_title eq ''}{$site_name} | Welcome{else}{$site_name} | {$page_title}{/if}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css" media="screen">
            @import url( {$display_settings.public_dir}/resources/styles/style.css );
        </style>
        
        {foreach from=$spry.css item=css_file}
        <link href="{$display_settings.public_dir}/resources/script/spry/widgets/{$css_file}" rel="stylesheet" type="text/css" />
        {/foreach}
        
        <script type='text/javascript' src='{$display_settings.public_dir}/resources/script/fat.js'></script>
        <script type='text/javascript' src='{$display_settings.public_dir}/resources/script/yasashii.js'></script>
        
        {foreach from=$spry.js item=js_file}
        <script type="text/javascript" src="{$display_settings.public_dir}/resources/script/spry/widgets/{$js_file}"></script>
        {/foreach}
        
        {if $include_tinymce == 1}{include file='layout/tinymce.tpl' theme=$tinymce_theme}{/if}
    </head>
    <body>
        <div id='header'>
            <h1><a id='site_name' href='{$display_settings.public_dir}'>{$site_name}</a></h1>
        </div>
        
        <div id='main-box'>
            <div id='left-column'>
                {if $logged_in == true}
                {include file="layout/navlinks_logged_in.tpl"}
                {include file="layout/user_box.tpl"}
                {else}
                {include file="layout/navlinks_not_logged_in.tpl"}
                {/if}
            </div>

            <div id='right-column'>
                {if $site_notice != ''}{include file='layout/event.tpl' notice=$site_notice}{/if}
                <h2 class="page-title">{$page_html_title}</h2>

                <!-- Page beging here. -->

