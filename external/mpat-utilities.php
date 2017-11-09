<?php
/**
 *
 * Copyright (c) 2017 MPAT Consortium , All rights reserved.
 * Fraunhofer FOKUS, Fincons Group, Telecom ParisTech, IRT, Lacaster University, Leadin, RBB, Mediaset
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 *
 * AUTHORS:
 * Miggi Zwicklbauer (miggi.zwicklbauer@fokus.fraunhofer.de)
 * Thomas Tröllmich  (thomas.troellmich@fokus.fraunhofer.de)
 *
 **/
class Mpat_Utilities
{

    public static function user_agent_is_smart_tv()
    {
        $agent = getenv('HTTP_USER_AGENT');
        return strpos(strtolower($agent), "hbbtv") !== false && strpos(strtolower($agent), "firetv-firefox-plugin") === false;
    }

    public static function print_a($a)
    {
        print('<pre>');
        print_r($a);
        print('</pre>');
    }


    public static function get_template_parts($parts = array())
    {
        foreach ($parts as $part) {
            get_template_part($part);
        };
    }

    public static function get_page_id_from_path($path)
    {
        $page = get_page_by_path($path);
        if ($page) {
            return $page->ID;
        } else {
            return null;
        };
    }

    public static function add_slug_to_body_class($classes)
    {
        global $post;

        if (is_page()) {
            $classes[] = sanitize_html_class($post->post_name);
        } elseif (is_singular()) {
            $classes[] = sanitize_html_class($post->post_name);
        };

        return $classes;
    }

    public static function get_category_id($cat_name)
    {
        $term = get_term_by('name', $cat_name, 'category');
        return $term->term_id;
    }

    public static function minified()
    {
        return '';
    }

}

?>