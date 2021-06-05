<?php


use Illuminate\Support\Facades\Route;

// Https://Docs.TrixCMS.Eu

Route::group(['prefix' => 'forum', 'middleware' => 'forum_checkUses'], function() {

    Route::get('/', ['as' => 'forum', 'uses' => 'ForumController@index']);


    Route::get('/json/home', ['as' => 'forum.json.home', 'uses' => 'ForumController@jsonHome']);
    Route::get('/json/forum/{id}', ['as' => 'forum.json.forum', 'uses' => 'ForumController@jsonForum']);


    Route::get('/json/forum/thread/{id}', ['as' => 'forum.json.forum.thread', 'uses' => 'ForumController@jsonForumThread']);
    Route::get('/json/forum/thread/{id}/{page}', ['as' => 'forum.json.forum.thread', 'uses' => 'ForumController@jsonForumThread']);


    Route::get('{slug}.{id}', ['as' => 'forum.forum', 'uses' => 'ForumController@forum']);

    Route::group(['prefix' => 'profile', 'middleware' => 'forum_checkAuth'], function() {
        Route::get('', ['as' => 'forum.profile', 'uses' => 'ForumController@profile']);
        Route::get('history', ['as' => 'forum.profile.history', 'uses' => 'ForumController@profileHistory']);
    });

    Route::get('user/{slug}.{id}', ['as' => 'forum.user', 'uses' => 'ForumController@user']);
    Route::get('users', ['as' => 'forum.users', 'uses' => 'ForumController@users']);

    /** THREAD */
    Route::group(['prefix' => 'thread'], function() {
        Route::get('{slug}.{id}', ['as' => 'forum.thread', 'uses' => 'ThreadController@thread']);
        Route::get('edit/{slug}.{id}', ['as' => 'forum.threadEdit', 'uses' => 'ThreadController@edit_thread', 'middleware' => 'forum_checkAuth']);
        Route::get('edit/history/{slug}.{id}', ['as' => 'forum.threadEditHistory', 'uses' => 'ThreadController@editHistory_thread', 'middleware' => 'forum_checkAuth']);
        Route::get('new/{slug}.{id}', ['as' => 'forum.threadNew', 'uses' => 'ThreadController@new_thread', 'middleware' => 'forum_checkAuth']);
    });

    Route::group(['prefix' => 'post', 'middleware' => 'forum_checkAuth'], function() {

        Route::group(['prefix' => 'thread'], function() {
            Route::post('report', ['as' => 'forum.post.threadReport', 'uses' => 'ThreadController@ajax_report_thread']);
            Route::post('reply', ['as' => 'forum.post.threadReply', 'uses' => 'ThreadController@ajax_reply_thread']);
            Route::post('edit', ['as' => 'forum.post.threadEdit', 'uses' => 'ThreadController@ajax_edit_thread']);
            Route::post('new', ['as' => 'forum.post.threadNew', 'uses' => 'ThreadController@ajax_new_thread']);
            Route::post('get/report', ['as' => 'forum.post.threadGetReport', 'uses' => 'ThreadController@ajax_get_report']);
            Route::post('location', ['as' => 'forum.post.updateThreadLocation', 'uses' => 'ForumController@updateThreadLocation']);
        });

        Route::group(['prefix' => 'profile', 'middleware' => 'forum_checkAuth'], function() {
            Route::post('notification', ['as' => 'forum.post.notification', 'uses' => 'ForumController@ajax_notification']);
            Route::post('setting', ['as' => 'forum.post.setting', 'uses' => 'ForumController@ajax_setting']);
            Route::post('avatar', ['as' => 'forum.post.avatar', 'uses' => 'ForumController@ajax_avatar']);
        });

    });

    Route::group(['prefix' => 'xml', 'middleware' => 'forum_checkAuth'], function() {

        Route::get('thread/delete/{id}', ['as' => 'forum.threadDelete', 'uses' => 'ThreadController@xml_delete_thread']);
        Route::get('moderate/{action}/{id}', ['as' => 'forum.moderateAction', 'uses' => 'ForumController@moderateAction']);
        Route::get('perms/{id}', ['as' => 'forum.perms', 'uses' => 'ForumController@perms']);
        Route::get('likeMessage/{id}', ['as' => 'forum.like.message', 'uses' => 'ForumController@likeMessage']);
        Route::get('followUser/{id}', ['as' => 'forum.follow.user', 'uses' => 'ForumController@followUser']);

    });

});

Route::get('forum/profile/avatar/default/{slug}.{id}.png', ['as' => 'forum.defaultProfile', 'uses' => 'ForumController@defaultProfile']);
