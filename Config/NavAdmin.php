<?php

return [
    "Forum" => [
        "type" => "dropdown",
        "icon" => "fas fa-edit",
        "lists" => [
            "Configuration" => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.forum.config'),
            ],
            "Forum" => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.forum.forum'),
            ],
            "Grades" => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.forum.ranks'),
            ],
            "Utilisateurs" => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.forum.users'),
            ],
        ]
    ],
];

