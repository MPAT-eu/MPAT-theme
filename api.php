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

function is_valid($arg){
	return isset($arg) && !empty($arg);
}
ob_start();
require_once("../../../wp-load.php");
ob_end_clean();

if (is_valid($_GET) && is_valid($_GET['action']) && is_valid($_GET['args'])){
	$action = $_GET['action'];
	$args = $_GET['args'];
	switch ($action) {
		case 'get_gallery_item_info':
			get_gallery_item_info($args);
			break;
	}
} else {
	error("Not Found");
}

function get_gallery_item_info($id){
	$info = get_post_meta($id,'_mpat_galleryItemContent',true);
	if (is_valid($info)){
		$info['title']=get_the_title($id);
		echo json_encode($info);
	} else {
		error("No Gallery Item Found");
	}
}


function error($msg){
	header('HTTP/1.1 400 Bad Request');
	echo $msg;
	exit(0);
}

?>