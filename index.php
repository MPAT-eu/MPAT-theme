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
 * Jean-Claude Dufourd (jean-claude.dufourd@telecom-paristech.fr
 * Stefano Miccoli (stefano.miccoli@finconsgroup.com)
 **/

add_filter( 'the_password_form', 'the_password_form' );

$agent = strtolower(getenv('HTTP_USER_AGENT'));
if (strpos($agent, "hbbtv") !== false){
	header("Content-Type: application/vnd.hbbtv.xhtml+xml;charset=UTF-8");
	?>
<!DOCTYPE html PUBLIC "-//HbbTV//1.1.1//EN" "http://www.hbbtv.org/dtd/HbbTV-1.1.1.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<?php
} else {
header('Content-Type: text/html;charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<?php
}
if (post_password_required()){
    echo get_the_password_form();
} else {
    the_page();
}
?>
</html>
<?php

function the_page(){
    ?><head>
    <meta http-equiv="Content-Type" content="application/vnd.hbbtv.xml+xhtml;charset=utf-8" />
    <title><?php
        echo bloginfo("name"); // should be the site title in customizer, regardless of the post
        ?></title>
        <?php wp_head(); ?>
    </head>
    <body>
    <!-- this next should go to mpat-core.php -->
    <div style="visibility: hidden; width: 0pt; height: 0pt;">
        <object id="appMan" type="application/oipfApplicationManager" width="0" height="0"></object>
    </div>
    <div id="vidcontainer"></div>
    <div id="main"></div>
    <script type="text/javascript">
      var TVDebugServerInterface = (function () {
        "use strict";
        var serverUrl = location.protocol + '//' + location.hostname + ':' + 3000;
        var exports = {};

        exports.log = function (message) {
          if (location.hash === "#tvdebug") {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", serverUrl + "/log?message=" + encodeURIComponent(message));
            xhr.send();
          }
        };

        return exports;
      })();
        TVDebugServerInterface.log(">>>>>>>>>>>>>>>>>> loading "+window.location.href);
        if (hbbtvlib_initialize() || (!!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0)){
            TVDebugServerInterface.log("after HbbTV initialisation");
            setTimeout(function(){
                hbbtvlib_show();
                TVDebugServerInterface.log("after HbbTV show");
            },10);
        }
    </script>
    <?php wp_footer(); ?>
    <script type="text/javascript">
        if (location.hash === '#preview') {
        	var console = document.getElementById('console');
        	if (console) console.style.display = 'none';
        }
    </script>
    </body><?php
}



function the_password_form(){
    global $post;
    ob_start();
    ?>
    <head>
        <script src="<?php echo get_template_directory_uri()?>/frontend/js/hbbtvlib.js"></script>
        <script src="<?php echo get_template_directory_uri()?>/frontend/js/keycodes.js"></script>
        <script src="<?php echo get_template_directory_uri()?>/frontend/js/debuglib.js"></script>
    </head>
    <body>

        <span>
            <h2 class="module_header_text_color">Enter PIN</h2>
            <form id="password-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );?>" method="post">
                <input id="password-input" placeholder="TYPE PIN" name="post_password" type="password" maxlength="8"></input>
            </form>
        </span>
        <script src="<?php echo get_template_directory_uri()?>/frontend/js/password.js"></script>
        <style>
            span {
                text-align: center;
                display: table;
                margin: 0 auto;
			    width: 20%;
			    height: 20%;
			    left: 50%;
			    padding:15px;
			    background-color: rgba(255,255,255,0.8);
            }
            input {
			    border: none;
			    height: 25px;
			}
        </style>

    </body>
    <?php return ob_get_clean();
}
