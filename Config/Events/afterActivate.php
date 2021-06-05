<?php
//FTG
use Extensions\Plugins\Forum_arckene__421339390\App\Controllers\Admin\HomeController;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumConfiguration;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumCustomization;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumIconType;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumPermissions;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanks;
use Extensions\Plugins\Forum_arckene__421339390\App\Models\ForumRanksRp;
use Illuminate\Support\Facades\File;


if (empty(ForumConfiguration::first()) || empty(ForumCustomization::first())):
    ForumConfiguration::insert([
        'forum' => 1,
        'created_at' => now()
    ]);
    ForumCustomization::insert([
        'color__main' => '#1e9294',
        'color__second' => '#f5efef',
        'color__background' => '#131316',
        'created_at' => now()
    ]);
endif;


if (empty(ForumIconType::first())) {
    ForumIconType::insert([
        'name' => 'Font Awesome 5',
        'website' => 'https://fontawesome.com/',
        'format' => '<i class="#icon#"></i>',
        'import' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css',
        'type' => 1,
        'created_at' => now()
    ]);
    ForumIconType::insert([
        'name' => 'Devicon',
        'website' => 'https://konpa.github.io/devicon/',
        'format' => '<i class="#icon#"></i>',
        'import' => 'https://cdn.jsdelivr.net/gh/konpa/devicon@master/devicon.min.css',
        'type' => 1,
        'created_at' => now()
    ]);
    ForumIconType::insert([
        'name' => 'Material Icon',
        'website' => 'https://material.io/resources/icons',
        'format' => '<i class="material-icons">#icon#</i>',
        'import' => 'https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css',
        'type' => 1,
        'created_at' => now()
    ]);
    ForumIconType::insert([
        'name' => 'Friconix',
        'website' => 'https://friconix.com/',
        'format' => '<i class="#icon#"></i>',
        'import' => 'https://friconix.com/cdn/friconix.js',
        'type' => 2,
        'created_at' => now()
    ]);
}

if (count(ForumPermissions::get()) == 0) {
    app(HomeController::class)->generatePermissions();
}

if (count(ForumRanks::get()) == 0) {
    $first = ForumRanks::insertGetId([
        'name' => 'LANG::MEMBER',
        'background' => "#dbdbdb",
        'color' => '#1f1f1f',
        'default' => 1,
        'power' => 3,
        'created_at' => now()
    ]);

    $data = [1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0];
    foreach ($data as $key => $v) {
        ForumRanksRp::insert([
            'rank_id' => $first,
            'permission_id' => $key + 1,
            'action' => $v,
            'created_at' => now()
        ]);
    }

    $second = ForumRanks::insertGetId([
        'name' => 'LANG::MODERATOR',
        'background' => "#109d21",
        'color' => '#e2d9d9',
        'default' => null,
        'power' => 2,
        'created_at' => now()
    ]);

    $data = [1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0, 1, 0];
    foreach ($data as $key => $v) {
        ForumRanksRp::insert([
            'rank_id' => $second,
            'permission_id' => $key + 1,
            'action' => $v,
            'created_at' => now()
        ]);
    }

    $third = ForumRanks::insertGetId([
        'name' => 'LANG::ADMINISTRATOR',
        'background' => "#bc2020",
        'color' => '#efebeb',
        'default' => null,
        'power' => 1,
        'created_at' => now()
    ]);
    $data = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
    foreach ($data as $key => $v) {
        ForumRanksRp::insert([
            'rank_id' => $third,
            'permission_id' => $key + 1,
            'action' => $v,
            'created_at' => now()
        ]);
    }
}

if(!File::exists(public_path('forum_arckene')))
    File::makeDirectory(public_path('forum_arckene'));

if(!File::exists(public_path('forum_arckene/avatar')))
    File::makeDirectory(public_path('forum_arckene/avatar'));



return;