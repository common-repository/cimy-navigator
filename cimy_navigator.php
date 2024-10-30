<?php
/*
Plugin Name: Cimy Navigator
Plugin URI: http://www.marcocimmino.net/cimy-wordpress-plugins/cimy-navigator/
Description: Display header and/or sidebar menu with pages or categories list.
Author: Marco Cimmino
Version: 0.4.2
Author URI: mailto:cimmino.marco@gmail.com
*/

/*
Copyright (c) 2007-2009 Marco Cimmino
Originally based on another plug-in from Adi Sieker

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.


The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

$cimy_navigator_version = "0.4.2";

$start_cimy_navigator_comment = "<!--\n";
$start_cimy_navigator_comment.= "\tStart code from Cimy Navigator ".$cimy_navigator_version."\n";
$start_cimy_navigator_comment.= "\tCopyright (c) 2007-2009 Marco Cimmino\n";
$start_cimy_navigator_comment.= "\thttp://www.marcocimmino.net/cimy-wordpress-plugins/cimy-navigator/\n";
$start_cimy_navigator_comment.= "-->\n";

$end_cimy_navigator_comment = "\n<!--\n";
$end_cimy_navigator_comment.= "\tEnd of code from Cimy Navigator\n";
$end_cimy_navigator_comment.= "-->\n";


// if $type == 'title' then return the root's title else return root's id
function get_root($parents, $tree, $type) {

	if (isset($parents[0])) {
		if ($parents[0] != 0)
			$root_id = $parents[0];
		else if (isset($parents[1]))
			$root_id = $parents[1];
		else
			$root_id = 0;
	}
	else
		$root_id = 0;

	if (isset($type)) {
		if ($type == 'title')
			return $tree[$root_id]['title'];
		else
			return $root_id;
	}
	else
		return $root_id;
}

function init_args($params) {
	global $wp_query;

	$parents = array();
	
	if (!isset($params['current']))				$params['current'] = -1;
	if (!isset($params['show_root']))			$params['show_root'] = 0;
	if (!isset($params['show_path_to_root']))		$params['show_path_to_root'] = 0;
	if (!isset($params['show_previous_levels']))		$params['show_previous_levels'] = 0;
	if (!isset($params['show_same_level']))			$params['show_same_level'] = 0;
	if (!isset($params['show_next_levels']))		$params['show_next_levels'] = 0;
	
	if (!isset($params['show_previous_map_levels']))	$params['show_previous_map_levels'] = 0;
	if (!isset($params['show_map']))			$params['show_map'] = 0;
	
	if (!isset($params['silent']))				$params['silent'] = 0;
	if (!isset($params['depth']))				$params['depth'] = -1;
	
	if (!isset($params['list_tag']))			$params['list_tag'] = 1;
	if (!isset($params['home']))				$params['home'] = 'home';
	if (!isset($params['own_tag_open']))			$params['own_tag_open'] = '';
	if (!isset($params['own_tag_close']))			$params['own_tag_close'] = '';
	if (!isset($params['nested']))				$params['nested'] = 1;
	
	if (!isset($params['a_class_current']))			$params['a_class_current'] = '';
	if (!isset($params['a_class_nocurrent']))		$params['a_class_nocurrent'] = '';
	
	if (!isset($params['li_class_current']))		$params['li_class_current'] = '';
	if (!isset($params['li_class_nocurrent']))		$params['li_class_nocurrent'] = '';
	
	if (!isset($params['ul_class_previous_level']))		$params['ul_class_previous_level'] = '';
	if (!isset($params['ul_class_same_level']))		$params['ul_class_same_level'] = '';
	if (!isset($params['ul_class_next_level']))		$params['ul_class_next_level'] = '';	
	if (!isset($params['ul_class_all_levels']))		$params['ul_class_all_levels'] = '';

	if (!isset($params['ul_id_previous_level']))		$params['ul_id_previous_level'] = '';
	if (!isset($params['ul_id_same_level']))		$params['ul_id_same_level'] = '';
	if (!isset($params['ul_id_next_level']))		$params['ul_id_next_level'] = '';
	if (!isset($params['ul_id_all_levels']))		$params['ul_id_all_levels'] = '';
	
	if (!isset($params['ul_append_type']))			$params['ul_append_type'] = 1;

	if (!isset($params['nested_li_ul_li']))			$params['nested_li_ul_li'] = 0;
	
	if (!isset($params['show_div']))			$params['show_div'] = 1;
	if (!isset($params['div_class_previous_level']))	$params['div_class_previous_level'] = '';
	if (!isset($params['div_class_same_level']))		$params['div_class_same_level'] = '';
	if (!isset($params['div_class_next_level']))		$params['div_class_next_level'] = '';
	if (!isset($params['div_class_all_levels']))		$params['div_class_all_levels'] = '';

	// missing div_id_[previous,same,next,all]_level[s] because defaults are built-in in the function that generate them
	
	if (!isset($params['type']))				$params['type'] = 'pages';
	if (!isset($params['hide_empty']))			$params['hide_empty'] = 0;
	if (!isset($params['exclude_cat']))			$params['exclude_cat'] = '1,2';
	if (!isset($params['include_cat']))			$params['include_cat'] = '';

	// levels
	if (strtoupper($params['show_previous_levels']) == "ALL") {
		$params['show_previous_levels'] = 0;
		$params['show_all_previous_levels'] = 1;
	}
	
	if (strtoupper($params['show_next_levels']) == "ALL") {
		$params['show_next_levels'] = 0;
		$params['show_all_next_levels'] = 1;
	}
	
	// map
	if (strtoupper($params['show_previous_map_levels']) == "ALL") {
		$params['show_previous_map_levels'] = 0;
		$params['show_all_previous_map_levels'] = 1;
	}

	/*if (strtoupper($params['show_next_map_levels']) == "ALL") {
		$params['show_next_map_levels'] = 0;
		$params['show_all_next_map_levels'] = 1;
	}*/

	if ($params['exclude_cat'] != "")
		$params['exclude_cat'] = ','.$params['exclude_cat'];

	if ($params['include_cat'] != "")
		$params['include_cat'] = ','.$params['include_cat'];

	if ($params['current'] == "")
		$params['current'] = -1;
	
	if ($params['current'] == -1) {
		if ($wp_query->is_page == true)
			$params['current'] = $wp_query->post->ID;
		else if ($wp_query->is_category == true)
			$params['current'] = $wp_query->query_vars['cat'];
		else
			$params['current'] = 0;
	}
		

	if ($params['type'] == 'categories')
		$args = "hide_empty=".$params['hide_empty']."&include=".$params['include_cat']."&exclude=".$params['exclude_cat'];
	else
		$args = 'sort_column='.$params['sort_column'].'&show_root'.$params['show_root'].'&current'.$params['current'].'&home'.$params['home'];

	// Query pages or categories
	if ($params['type'] == 'categories')
		$items = get_categories($args);
	else
		$items = get_pages($args);

	if ($items) {

		$tree = array();

		// Now loop over all items that were selected
		foreach ($items as $item) {

			if ($params['type'] == 'categories') {
				$id = $item->cat_ID;
				$parent_id = $item->category_parent;
			}
			else {
				$id = $item->ID;
				$parent_id = $item->post_parent;
			}

			// set the title for the current item
			if ($params['type'] == 'categories')
				$tree[$id]['title'] = $item->cat_name;
			else
				$tree[$id]['title'] = $item->post_title;

			// set the parent
			$tree[$id]['parent'] = $parent_id;
	
			//next line added as a lookup for password
			if ($params['type'] == 'categories')
				$tree[$id]['post_password'] = "";
			else
				$tree[$id]['post_password'] = $item->post_password;
	
			// check for page called home to set it to current if at index
			if ($params['type'] == 'pages') {
				if ($params['current'] == 0 && $item->post_title == $params['home']) {
					$params['current'] = $id;
					$params["is_home_modified"] = 1;
				}
			}

			// set the selected date for the current page
			// depending on the query arguments this is either
			// the creation date or the modification date
			// as a unix timestamp. It will also always be in the
			// ts field.

			if ($params['type'] == 'pages') {
				if (!empty($params['show_date'])) {
					if ('modified' == $params['show_date'])
						$tree[$id]['ts'] = $item->time_modified;
					else
						$tree[$id]['ts'] = $item->time_created;
				}
			}

			// Using the parent ID of the current item as the
			// array index we set the current item as a child of that item.
			// We can now start looping over the $tree array
			// with any ID which will output the item links from that ID downwards.
			$tree[$parent_id]['children'][] = $id;

		}
		
		/*
			RULES ARE:
			--> show all children till END
				'show_all_children' => -1
			
			--> show all children till n times
				'show_all_children' => <n>
		
			--> show child with that ID
				'show_child' => <id>
		
		*/
		
		$rules_queue = array();
		$parents = array();
		$current = $tree[$params['current']]['parent'];
		$old_current = $params['current'];
		$flag = true;
		$level = 0;
		
		$ret = array();
		$ret['father'] = $current;
		
		if ($params['show_all_previous_map_levels']) {
			$params['show_root'] = 0;
			$params['show_path_to_root'] = 0;
			$params['show_all_previous_levels'] = 0;
		}
		
		if  ($params['show_all_previous_levels']) {
			$params['show_root'] = 0;
			$params['show_path_to_root'] = 0;
		}

		// parents
		while ($flag) {
			if ($current == 0)
				$flag = false;
			
			if (!$params['show_map']) {
				// order is important!
				if ($level > 0) {
					// show all previous map
					if (($params['show_all_previous_map_levels']) && ($current == 0))
						array_unshift($rules_queue, array(
							"id" => $current,
       							"level" => $level,
	      						"show_children" => $level - 1,
	     						"type" => "previous_level",
						));
					
					// show all previous levels
					else if (($params['show_all_previous_levels']))
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_children" => 0,
							"type" => "previous_level",
						));
					
					// show all previous map levels
					else if (($level == $params['show_previous_map_levels']) || (($level < $params['show_previous_map_levels']) && (!$flag)))
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_children" => $level - 1,
							"type" => "previous_level",
						));
					
					// show previous levels
					else if ($level <= $params['show_previous_levels'])
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_children" => 0,
							"type" => "previous_level",
						));
					
					// show path to root
					else if ($params['show_path_to_root'])
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_child" => $old_current,
							"type" => "previous_level",
						));
					
					// show root
					else if (($params['show_root']) && (!$flag))
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_child" => $old_current,
							"type" => "previous_level",
						));
				}
				else {
					if ($params['show_same_level'])
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_children" => 0,
							"type" => "same_level",
						));
					
					else
						array_unshift($rules_queue, array(
							"id" => $current,
							"level" => $level,
							"show_child" => $old_current,
							"type" => "same_level",
						));
				}
			}
			else if ($current == 0)
				array_unshift($rules_queue, array(
					"id" => $current,
					"level" => $level,
					"show_children" => -1,
					"type" => "all_levels",
				));
			
			$level++;
			
			array_unshift($parents, $current);
			$old_current = $current;
			$current = $tree[$current]['parent'];
		}
		
		$current = $params['current'];
		$parents[] = $current;

		if (!$params['show_map']) {
			// children
			// order is important!
			if ($params['show_all_next_levels'])
				array_push($rules_queue, array(
					"id" => $current,
					"level" => 1,
					"show_children" => -1,
					"type" => "next_level",
				));
			
			else if ($params['show_next_levels'])
				array_push($rules_queue, array(
					"id" => $current,
					"level" => 1,
					"show_children" => $params['show_next_levels'] - 1,
					"type" => "next_level",
				));
		}

		$ret['params'] = $params;
		$ret['rules_queue'] = $rules_queue;
		$ret['parents'] = $parents;
		
		$ret['tree'] = $tree;
		$ret['root'] = get_root($parents, $tree, $params['root_type']);

		return $ret;
	}
}

function cimy_navigator($options) {
	global $start_cimy_navigator_comment, $end_cimy_navigator_comment;
	
	$params = $options['params'];
	
	if (!$params['silent'])
		echo "\n".$start_cimy_navigator_comment."\n";
	
	if (!isset($options)) {
		$links = array();
		
		$links[] = "You passed a value to Cimy Navigator that doesn't exist. Check if you wrote correctly the call and check readme file for possible solutions. If you are using categories you probably set 'hide_empty' to 1 or you haven't categories at all, in this case see 'exclude_cat' parameter to let show also 'Uncategorized' and/or 'Blogroll'.";
		
		return $links;
	}
	
	$links = cimy_navigator_rec($options);
	
	if (empty($links)) {
		$links[] = "There are no results, possible causes: 1) you are showing a post and you haven't specified the current page_id that you want to use, this is needed because the plug-in cannot understand which is the current page_id if you are NOT showing a page in this moment, see README file for details on how to fix the problem; 2) Same thing but you are trying to show categories in a post or in a page, see README file too.";
	}
	
	if (!$params['silent'])
		echo "\n".$end_cimy_navigator_comment."\n";
	
	return $links;
}
	
function cimy_navigator_rec($options) {
	
	$rules = $options['rules_queue'];
	$tree = $options['tree'];
	$params = $options['params'];
	$parents = $options['parents'];
	
	if (isset($options['abs_level']))
		$abs_level = $options['abs_level'];
	else
		$abs_level = 0;
	
	if (isset($options['links']))
		$links = $options['links'];
	else
		$links = array();
	
	if (!$params['nested']) {
		foreach ($rules as $rule) {
			$ret = output_children($params, $tree, $parents, $rule, 0, $abs_level, $links);
			
			$links = $ret['links'];
			$abs_level = $ret['abs_level'];
		}
	}
	else if (count($rules) > 0) {
		$rule = array_shift($rules);

		$ret = output_children($params, $tree, $parents, $rule, 0, $abs_level, $links, $rules);
		
		$links = $ret['links'];
	}
	
	return $links;
}

function output_children($params, $tree, $parents, $rule, $level, $abs_level, $links, $rules=false) {

	$item_id_backup = array();
	$children = array();

	if (isset($rule['show_children'])) {
		$show_children = true;
		$id = $rule['id'];
		$max_level = $rule['show_children'];
		$children = $tree[$id]['children'];
	}
	else if (isset($rule['show_child'])) {
		$show_children = false;
		$id = $rule['id'];
		$children[] = $rule['show_child'];
		$max_level = 0;
	}
	
	if ((($max_level >= $level) || ($max_level == -1) || ($params['nested'])) && (count($children) > 0)) {

		$level++;

		$ul_div = generate_ul_div($params, $abs_level + 1, $params['ul_append_type'], $rule['type']);
	
		if ($params['depth'] == -1 || $abs_level <= $params['depth']) {

			if (($params['list_tag']) && (!$params['silent'])) {
				if ($params['show_div']) {
					echo '<div'.$ul_div['div_id'].$ul_div['div_class'].'>';
					echo "\n";
				}

				echo '<ul'.$ul_div['ul_id'].$ul_div['ul_class'].'>'."\n";
			}

			foreach ($children as $item_id) {
			
				$cur_item = $tree[$item_id];
				$title = $cur_item['title'];

				$a_and_li = generate_a_li($params, $level, $item_id, $parents);
				$li_html = '<li'.$a_and_li['li_class'].$a_and_li['li_id'].'>';

				if (!$params['silent']) {
					echo $params['own_tag_open'];

					if ($params['list_tag'])
						echo $li_html;
				}

				if ($params["type"] == "pages")
					$url = get_page_link($item_id);
				else
					$url = get_category_link($item_id);

				$link = '<a href="'.$url.'" title="'.wp_specialchars($title).'"'.$a_and_li['a_id'].$a_and_li['a_class'].'>'.$title.'</a>';
				
				if (!$params['silent'])
					echo $link;
				
				if (!$params['silent']) {
					if (($params['list_tag']) && (!$params['nested_li_ul_li']))
						echo '</li>';

					echo $params['own_tag_close'];
					echo "\n";
				}
				
				// produce also an array output to be used
				if (!is_array($links[$abs_level]))
					$links[$abs_level] = array();

				if ((!$params['nested']) && ($params['list_tag'])) {
					$links[$abs_level][$a_and_li['li_pure_id']] = $li_html.$link."</li>";
				}
				else {
					$links[$abs_level][$a_and_li['li_pure_id']] = $link;
				}

				// RECURSION!
				if ($params['nested']) {
					if (($show_children) && (($max_level >= $level) || ($max_level == -1))) {
						$rule['id'] = $item_id;
	
						$ret = output_children($params, $tree, $parents, $rule, $level, $abs_level + 1, $links, $rules);
						
						$links = $ret['links'];
					}
					else if (in_array($item_id, $parents)) {
						$options['rules_queue'] = $rules;
						$options['tree'] = $tree;
						$options['params'] = $params;
						$options['parents'] = $parents;
						$options['abs_level'] = $abs_level + 1;
						$options['links'] = $links;
						
						$links = cimy_navigator_rec($options);
					}
				}
				else
					$item_id_backup[][$abs_level + 1] = $item_id;
				
				if (($params['list_tag']) && ($params['nested_li_ul_li']) && (!$params['silent']))
					echo "</li>\n";
			}

			if (($params['list_tag']) && (!$params['silent'])) {
				echo "</ul>\n";

				if ($params['show_div'])
					echo "</div>\n";
			}

			if (!$params['nested']) {
				foreach ($item_id_backup as $items) {
					foreach ($items as $a_level=>$item_id) {
						$rule['id'] = $item_id;

						$ret = output_children($params, $tree, $parents, $rule, $level, $a_level, $links);
					
						$links = $ret['links'];
						
						if (in_array($item_id, $parents))
							$abs_level = $ret['abs_level'];
					}
				}
			}

			if (!$params['silent'])
				echo "\n";
		}
	}
	
	$ret = array();
	
	$ret['abs_level'] = $abs_level;
	$ret['links'] = $links;
	
	return $ret;
}

function generate_ul_div($params, $level, $ul_append_type, $type) {

	// who can be: "parent" or "children"
	
	$ul_id = '';
	$ul_class = '';
	$div_id = '';
	$div_class = '';
	$ret = array();

	// UL_ID
	$ul_id = $params['ul_id_'.$type];

	// UL_CLASS
	if ($params['ul_class_'.$type] == '')
		$ul_class = 'level'.$level;
	else
		$ul_class = $params['ul_class_'.$type];


	// DIV_CLASS
	$div_class = $params['div_class_'.$type];

	// DIV_ID
	if (!isset($params['div_id_'.$type]))
		$div_id = 'n'.$level;
	else
		$div_id = $params['div_id_'.$type];

	// GENERATE UL_CLASS_CODE
	if ($ul_class != '') {

		if ($ul_append_type)
			$ul_class.= '-'.$type;

		$ul_class = ' class="'.$ul_class.'"';
	}

	// GENERATE UL_ID_CODE
	if ($ul_id != '') {

		if ($ul_append_type)
			$ul_id.= '-'.$type;

		$ul_id = ' id="'.$ul_id.'"';
	}

	// GENERATE DIV_CLASS_CODE
	if ($div_class != '')
		$div_class = ' class="'.$div_class.'"';

	// GENERATE DIV_ID_CODE
	if ($div_id != '')
		$div_id = ' id="'.$div_id.'"';

	$ret['ul_id'] = $ul_id;
	$ret['ul_class'] = $ul_class;
	$ret['div_id'] = $div_id;
	$ret['div_class'] = $div_class;

	return $ret;
}

function generate_a_li($params, $level, $item_id, $parents) {

	$li_id = '';
	$li_class = '';
	$a_id = '';
	$a_class = '';

	$ret = array();

	// WRITING THE CURRENT
	if (in_array($item_id, $parents)) {
		// <li>
		if (!isset($params['li_id_current']))
			$li_id = 'current_'.$item_id;
		else
			$li_id = $params['li_id_current'].$item_id;

		$li_class = $params['li_class_current'];
		
		// <a>
		if (!isset($params['a_id_current']))
			$a_id = 'current_'.$item_id;
		else
			$a_id = $params['a_id_current'].$item_id;

		$a_class = $params['a_class_current'];
	}
	// WRITING NOCURRENT
	else {
		// <li>
		if (!isset($params['li_id_nocurrent']))
			$li_id = 'nocurrent_'.$item_id;
		else
			$li_id = $params['li_id_nocurrent'].$item_id;

		$li_class = $params['li_class_nocurrent'];
		
		// <a>
		if (!isset($params['a_id_nocurrent']))
			$a_id = 'nocurrent_'.$item_id;
		else
			$a_id = $params['a_id_nocurrent'].$item_id;

		$a_class = $params['a_class_nocurrent'];
	}

	// store before final changes
	$ret['li_pure_id'] = $li_id;
	$ret['a_pure_id'] = $a_id;

	// GENERATE LI_ID_CODE
	if ($li_id != '')
		$li_id = ' id="'.$li_id.'"';

	// GENERATE LI_CLASS_CODE
	if ($li_class != '')
		$li_class = ' class="'.$li_class.'"';

	// GENERATE A_ID_CODE
	if ($a_id != '')
		$a_id = ' id="'.$a_id.'"';

	// GENERATE A_CLASS_CODE
	if ($a_class != '')
		$a_class = ' class="'.$a_class.'"';

	$ret['li_id'] = $li_id;
	$ret['li_class'] = $li_class;
	
	$ret['a_id'] = $a_id;
	$ret['a_class'] = $a_class;

	return $ret;
}

?>
