<?php

use Illuminate\Support\Facades\Route;

// Https://Docs.TrixCMS.Eu

Route::group(['namespace' => 'Admin'], function() {
    Route::get('forum/config', ['as' => 'admin.forum.config', 'uses' => 'HomeController@config']);

    Route::post('forum/ajax_config', ['as' => 'admin.forum.ajax_config', 'uses' => 'HomeController@ajax_config']);
    Route::post('forum/ajax_custom', ['as' => 'admin.forum.ajax_custom', 'uses' => 'HomeController@ajax_custom']);
    Route::post('forum/ajax_share', ['as' => 'admin.forum.ajax_share', 'uses' => 'HomeController@ajax_share']);
    Route::post('forum/ajax_icon', ['as' => 'admin.forum.ajax_icon', 'uses' => 'HomeController@ajax_icon']);
    Route::get('forum/xhr_share_delete/{id}', ['as' => 'admin.forum.xhr_share_delete', 'uses' => 'HomeController@xhr_share_delete']);
    Route::get('forum/xhr_icon_delete/{id}', ['as' => 'admin.forum.xhr_icon_delete', 'uses' => 'HomeController@xhr_icon_delete']);

    Route::get('forum/forum', ['as' => 'admin.forum.forum', 'uses' => 'HomeController@forum']);

    Route::post('forum/ajax_forum_nestable', ['as' => 'admin.forum.ajax_forum_nestable', 'uses' => 'HomeController@ajax_forum_nestable']);
    Route::post('forum/ajax_forum', ['as' => 'admin.forum.ajax_forum', 'uses' => 'HomeController@ajax_forum']);
    Route::post('forum/ajax_forum_get_edit', ['as' => 'admin.forum.ajax_forum_get_edit', 'uses' => 'HomeController@ajax_forum_get_edit']);
    Route::post('forum/ajax_forum_edit', ['as' => 'admin.forum.ajax_forum_edit', 'uses' => 'HomeController@ajax_forum_edit']);
    Route::post('forum/ajax_forum_tags', ['as' => 'admin.forum.ajax_forum_tags', 'uses' => 'HomeController@ajax_forum_tags']);
    Route::get('forum/xhr_forum_delete/{id}', ['as' => 'admin.forum.xhr_forum_delete', 'uses' => 'HomeController@xhr_forum_delete']);
    Route::get('forum/xhr_tag_delete/{id}', ['as' => 'admin.forum.xhr_tag_delete', 'uses' => 'HomeController@xhr_tag_delete']);


    Route::get('forum/ranks', ['as' => 'admin.forum.ranks', 'uses' => 'HomeController@ranks']);
    Route::get('forum/generatePermissions', ['as' => 'admin.forum.generatePermissions', 'uses' => 'HomeController@generatePermissions']);

    Route::post('forum/ajax_add_rank', ['as' => 'admin.forum.ajax_add_rank', 'uses' => 'HomeController@ajax_add_rank']);
    Route::post('forum/ajax_edit_rank', ['as' => 'admin.forum.ajax_edit_rank', 'uses' => 'HomeController@ajax_edit_rank']);
    Route::post('forum/ajax_ranks_nestable', ['as' => 'admin.forum.ajax_ranks_nestable', 'uses' => 'HomeController@ajax_ranks_nestable']);
    Route::post('forum/ajax_forum_get_rank', ['as' => 'admin.forum.ajax_forum_get_rank', 'uses' => 'HomeController@ajax_forum_get_rank']);
    Route::post('forum/ajax_forum_get_rp', ['as' => 'admin.forum.ajax_forum_get_rp', 'uses' => 'HomeController@ajax_forum_get_rp']);
    Route::get('forum/xhr_rank_delete/{id}', ['as' => 'admin.forum.xhr_rank_delete', 'uses' => 'HomeController@xhr_rank_delete']);
    Route::get('forum/xhr_rank_default/{id}', ['as' => 'admin.forum.xhr_rank_default', 'uses' => 'HomeController@xhr_rank_default']);





    Route::get('forum/users', ['as' => 'admin.forum.users', 'uses' => 'HomeController@users']);
    Route::get('forum/user/{id}', ['as' => 'admin.forum.user', 'uses' => 'HomeController@user']);

    Route::get('forum/user/xml/unban/{id}', ['as' => 'admin.forum.user.xml.unban', 'uses' => 'HomeController@user__xml__unban']);
    Route::get('forum/user/xml/ban/{id}', ['as' => 'admin.forum.user.xml.ban', 'uses' => 'HomeController@user__xml__ban']);
    Route::get('forum/user/xml/avatarReset/{id}', ['as' => 'admin.forum.user.xml.avatarReset', 'uses' => 'HomeController@user__xml__avatarReset']);
    Route::post('forum/user/ajax/rank', ['as' => 'admin.forum.user.ajax.rank', 'uses' => 'HomeController@user__ajax__rank']);

});