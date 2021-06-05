<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Controllers;

class ForumFunction
{
    /**
     * @param string $date
     * @param bool $uc
     * @return string|null
     */
    function format__date($date, $uc = true)
    {
        if ((bool)strtotime($date)) {
            if ((strtotime(now()) - strtotime($date) < 86400)) {
                $toReturn = $date->diffForHumans();
            } else {
                $toReturn = __('forum_arckene::public/global.Format.date', ['date' => $date->format('d/m/Y'), 'time' => $date->format('H\\hi')]);
            }
        } else {
            return null;
        }

        return $uc ? ucfirst($toReturn) : $toReturn;
    }

    /**
     * @param integer $user_id
     * @param bool|string $date
     * @return string
     */
    function format__author(int $user_id, $date = false)
    {
        $user_link = route('forum.user', [user($user_id)->pseudo, $user_id]);

        if ($date) {
            return $this->format__author($user_id) . ', ' . $this->format__date($date, false);
        } else {
            return __('forum_arckene::public/global.Format.author', ['author' => $this->format__link($user_link, user($user_id)->pseudo)]);
        }
    }

    /**
     * @param string $href
     * @param string $content
     * @param string $target
     * @return string
     */
    function format__link(string $href, string $content, $target = "_SELF")
    {
        return "<a href=\"{$href}\" target=\"{$target}\">{$content}</a>";
    }

    function format__tag($tag) {
        return "<span class='tag' style='color: {$tag['text_color']}; background: {$tag['background_color']}'>{$tag['name']}</span>";
    }

}