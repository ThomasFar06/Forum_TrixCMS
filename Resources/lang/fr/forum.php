<?php

return [
    "title" => "Forum",
    'forum_desc' => '{0} Il n\'y a pas encore de discussions dans ce forum.|' .
        '[1] Il y a :count discussion dont la dernière, ":thread", a été démarrée par :author :date.|' .
        '[2,*] Il y a :count discussions dont la dernière, ":thread", a été démarrée par :author :date.',

    'forum_thread_table_title' => 'Titre',
    'forum_thread_table_answer' => 'Réponses',
    'forum_thread_table_last' => 'Dernière réponse',

    "Widget" => [
        "Statistic" => [
            "{0} <i class=\"fas fa-comments\"></i> Discussion : <span>:count</span>|[2,*]<i class=\"fas fa-comments\"></i> Discussions : <span>:count</span>",
            "{0} <i class=\"fas fa-comment\"></i> Message : <span>:count</span>|[2,*]<i class=\"fas fa-comment\"></i> Messages : <span>:count</span>",
            "{0} <i class=\"fas fa-users\"></i> Membre : <span>:count</span>|[2,*]<i class=\"fas fa-users\"></i> Membres : <span>:count</span>",
            "<i class=\"fas fa-user\"></i> Dernier membre : <span>:member</span>",
        ],
        "Staff" => [
            "{0} Il n'y a aucun membre de l'équipe en ligne|[1] Il y a :count membre de l'équipe en ligne: |[2,*] Il y a :count membres de l'équipe en ligne:"
        ],
        "Online" => [
            "{0} Il n'y a aucun membre en ligne|[1] Il y a :count membre en ligne: |[2,*] Il y a :count membres en ligne:"
        ]
    ],

    "profile" => "Profil / :slug",
    "User" => [
        "staff" => "Membre de l'équipe",
        "register" => "Inscrit :date",
        "last_activity" => "",
        "link" => [
            "Suivi",
            "Activitées récentes",
            "Derniers messages",
            "Statistiques",
            "Paramètre",
            "Paramètre Notification",
        ],
        "link2" => [
            "Suivi",
            "Activitées récentes",
            "Derniers messages",
            "Statistiques"
        ],
    ],

    "by_date" => "Par :user, :date",

    "empty_title" => "Nouvel utilisateur",
    "empty_forum" => "Il n'y a pas de forum de discussions dans cette catégorie.",
    "threads" => "Discussions",
    "messages" => "Messages",
    "sub_forums" => "Sous forums",
    "sub_title" => "Forum / :slug",
    "thread_title" => "Discussions / :slug",
    "thread_info" => "Discussion dans « :forum » démarée par :author :date.",
    "Profile" => [
        "login" => "Se connecter",
        "register" => "S'inscire",
        "profile" => "Mon profil",
        "history" => "Mon historique"
    ],
    "Reply" => [
        "placeholder" => "Écrire une réponse...",
        "action" => "Répondre",
        "preview" => "Visualisation",
        "submit" => "Répondre a la discussion",
        "report" => "Signaler",
        "edit" => "Modifier",
        "delete" => "Supprimer"
    ],
    "date_format" => "le :date à :hours",
    "quotes" => "Par :user :format",
    "Moderate" => [
        "h1" => "Outils de modération",
        "h2" => "Discussion #:id",
        "pin" => "Épingler",
        "lock" => "Fermer",
        "archive" => "Archiver",
        "delete" => "Supprimer la discussion",
        "ban" => "Bannir l'utilisateur",
        "category_choose" => "Choisir une catégorie ou déplacer cette discussion",
        "update" => "Mettre à jour la discussion #:id",
        "report_empty" => "Cette discussion n'a pas été signalée",
        "report_exist" => "Cette discussion a été signalée <span id=\"reportsNumber\">:number</span> fois",
        "show_more" => "Afficher plus",
        "show_less" => "Afficher moins",
        "report_action" => "Action liée au signalement (utilisable sans).",
        "report" => "Nombre de signalement: :number",
        "like" => "Nombre de \"j'aime\": :number",
        "id" => "Identifiant général: :number",
        "edit_history" => "Historique de modification",
        "report_tools" => "Outils de signalement modérateur",
    ],
    "Report" => [
        "h1" => "Outil de signalement",
        "h2" => "Message #",
        "label" => "Raison du signalement",
        "placeholder" => "Bonjour, je signale ce message car...",
        "send" => "Signaler ce message"
    ]
];