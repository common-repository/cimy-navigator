Cimy Navigator

WordPress doesn't have a good way to generate pages or categories lists, this plug-in is inspirited by the one from Adi Sieker, that was a very good base to start.
It uses an array because it can provide better tag options than a simple string launch, but every option has a default value, so if the default one is the one desired can be omitted in the initialization.
If you have your custom css with this plug-in you can create also interactive menu or very useful site maps, see screenshots.

Bugs or suggestions can be mailed at: cimmino.marco@gmail.com


REQUIREMENTS:
- WordPress = 2.x

INSTALLATION:
- just copy whole Cimy_Navigator subdir into your plug-in directory and activate it


USAGE EXAMPLES:
This is only a small example how to use the plug-in, of course you can add options or just modify them.

EXAMPLE FOR PAGES:

$params = array(
		'show_previous_levels' => "all",
		'show_same_level' => 1,
		'show_next_levels' => "all",
		'sort_column' => 'menu_order',
		'a_class_current' => 'a_current',
		'a_class_nocurrent' => 'a_nocurrent',
		'nested_li_ul_li' => 0,
	);
	
	if (!is_page())
		/* change <page_id> with a valid page_id from your blog */
		$params['current'] = <page_id>;

$result = init_args($params);

/* gives the root item ID */
$root = $result['root'];

/* this function can be called more than one time without calling init_args again, because all options are saved into $result array */
$links = cimy_navigator($result);


EXAMPLE FOR CATEGORIES:

$params = array(
		'type' => "categories",
		'show_previous_levels' => "all",
		'show_same_level' => 1,
		'show_next_levels' => "all",
		'sort_column' => 'menu_order',
		'a_class_current' => 'a_current',
		'a_class_nocurrent' => 'a_nocurrent',
		'nested_li_ul_li' => 0,
	);
	
	if (!is_category())
		/* change <category_id> with a valid category_id from your blog */
		$params['current'] = <category_id>;

$result = init_args($params);

/* gives the root item ID */
$root = $result['root'];

/* this function can be called more than one time without calling init_args again, because all options are saved into $result array */
$links = cimy_navigator($result);


PARAMETERS (should be added in $params array):

'type'
choose what should be displayed: 'pages' or 'categories'
[default: 'pages']

'current'
set the current item
[default: the current item visited or if it's not a page (or not a category) then default is 0]

'list_tag' [0 or 1]
add following tags: <div> and <ul> before elements in a level and <li> for each element
[default: 1]

'depth' [1, 2, 3, ...]
you can specify which was the max level depth to show, levels starts from number 1 and are relative to items to show
[default: -1, equal to: no limit]

'show_root' [0 or 1]
show the root item
[default: 0]

'show_path_to_root' [0 or 1]
show the root and all items encountered to reach the current item
[default: 0]

'show_previous_levels' [0, 1, 2, ..., "all"]
show n previous levels
[default: 0]

'show_same_level' [0 or 1]
show all the items in the current level
[default: 0]

'show_next_levels' [0, 1, 2, ..., "all"]
show n next levels
[default: 0]

'show_map' [0 or 1]
show all the site map
[default: 0]

'show_previous_map_levels' [0, 1, 2, ..., "all"]
show n previous map levels (map means all items, not only the current's previous levels)
[default: 0]

'silent' [0 or 1]
this feature let you to not print anything and just use the array returned by the plug-in
[default: 0]

'own_tag_open'
add a custom open tag after the default open tags and before the item
[default: '']

'own_tag_close'
add a custom close tag before the default close tags and after the item
[default: '']

'nested' [0 or 1]
list all next levels if there are any before list other items
[default: 1]

'nested_li_ul_li' [0 or 1]
just don't close the <li> tag if there are next levels, tag will be closed after next level
[default: 0]



'a_class_current'
set a different class for <a> tag in the current item
[default: '']

'a_class_nocurrent'
set a different class for <a> tag in non current items
[default: '']

'a_id_current'
set a different id for <a> tag in the current item
[default: 'current[#item_id]' (current1, current2, etc)]

'a_id_nocurrent'
set a different id for <a> tag in non current items
[default: 'nocurrent[#item_id]' (nocurrent1, nocurrent2, etc)]



'li_class_current'
set a different class for <li> tag in the current item
[default: '']

'li_class_nocurrent'
set a different class for <li> tag in non current items
[default: '']

'li_id_current'
set a different id for <li> tag in the current item
[default: 'current[#item_id]' (current1, current2, etc)]

'li_id_nocurrent'
set a different id for <li> tag in non current items
[default: 'nocurrent[#item_id]' (nocurrent1, nocurrent2, etc)]



'ul_class_previous_level'
set a different class for <ul> tag in previous level items
[default: 'level#' (level1, level2, etc) + '-previous_level' if 'ul_append_type' is set to 1]

'ul_class_same_level'
set a different class for <ul> tag in the same level items
[default: 'level#' (level1, level2, etc) + '-same_level' if 'ul_append_type' is set to 1]

'ul_class_next_level'
set a different class for <ul> tag in next level items
[default: 'level#' (level1, level2, etc) + '-next_level' if 'ul_append_type' is set to 1]

'ul_class_all_levels'
set a different class for <ul> tag specific only when param 'show_map' is set to 1
[default: 'level#' (level1, level2, etc) + '-all_levels' if 'ul_append_type' is set to 1]



'ul_id_previous_level'
set a different id for <ul> tag in previous level items
[default: '']

'ul_id_same_level'
set a different id for <ul> tag in the same level items
[default: '']

'ul_id_next_level'
set a different id for <ul> tag in next level items
[default: '']

'ul_id_all_levels'
set a different id for <ul> tag specific only when param 'show_map' is set to 1
[default: '']



'ul_append_type' [0 or 1]
append '-[type]' to every <ul> class and id
[default: 1]

[type] can be:
'previous_level' for items in a previous level
'same_level' for items in the same level
'next_level' for items in a next level
'all_levels' for all items only when param 'show_map' is set to 1


'div_class_previous_level'
set a different class for <div> tag in previous level items
[default: '']

'div_class_same_level'
set a different class for <div> tag in the same level items
[default: '']

'div_class_next_level'
set a different class for <div> tag in next level items
[default: '']

'div_class_all_levels'
set a different class for <div> tag specific only when param 'show_map' is set to 1
[default: '']



'div_id_previous_level'
set a different id for <div> tag in previous levels items
[default: 'n#' (n1, n2, etc) + '-previous_level' if 'ul_append_type' is set to 1]

'div_id_same_level'
set a different id for <div> tag in same level items
[default: 'n#' (n1, n2, etc) + '-same_level' if 'ul_append_type' is set to 1]

'div_id_next_level'
set a different id for <div> tag in next level items
[default: 'n#' (n1, n2, etc) + '-next_level' if 'ul_append_type' is set to 1]

'div_id_all_levels'
set a different id for <div> tag specific only when param 'show_map' is set to 1
[default: 'n#' (n1, n2, etc) + '-all_levels' if 'ul_append_type' is set to 1]



'show_div' [0 or 1]
show <div> tag for every items
[default: 1]

SPECIFIC ONLY FOR CATEGORIES:

'hide_empty' [0 or 1]
hide categories without posts
[default: 0]

'include_cat'
include one or more categories, if more than one separate with ','
[default: '']

'exclude_cat'
exclude one or more categories, if more than one separate with ','
[default: '1,2' they represents Uncategorized and Blogroll]


KNOWN ISSUES:
- plug-in doesn't provide a widget (will come in a future release)
- plug-in when used in a post and not a page then shows nothing until you specify in the "current" parameter a valid page_id, this behavior applies also to categories when you are trying to show categories in a non-category area like a post or page, set the current or see examples on how to fix the problem in the README file.


CHANGE LOG:
v0.4.2 - 03/01/2009
- Changed plug-in link, we have a new home!
- Renamed REAMDE file to README_OFFICIAL.txt due to WordPress Plugin Directory rules

v0.4.1 - 25/07/2007
- Fixed silent mode to be real silent also with comments

v0.4.0 final - 24/07/2007
- Home page url updated

v0.4.0 release candidate 1 - 19/07/2007
- Cimy Navigator is rewritten for about 60% because some heavy design-bugs were found, this is also why 0.3.0 final was never released
- Changed a lot of parameters, see README file for new documentation; unfortunetaly plug-in cannot remains compatible with older versions, so if you are upgrading you have to check new parameters names
- Documentation is now very detailed, so I hope will be easier to use this plug-in

v0.3.0 release candidate 1 - 14/04/2007
- Name change to "Cimy Navigator" since version 0.3.0 beta3 not only pages can be displayed but also categories
- Fixed 'li_id_current' and 'li_id_nocurrent' produces attributes id 'current' and 'nocurrent' with duplicates that is forbidden by html rfc, thanx to Amitabh that reminds me
- Changed 'li_id_current', 'li_id_nocurrent', 'div_id_parent' and 'div_id_children' if set to empty string they aren't ovverriden by default
- Added known bugs section to readme file

v0.3.0 beta3 - 18/03/2007
- Fixed a php error when 'depth' parameter was set too low, now it prints a warning
- Changed plug-in link, now it points to the specific blog page

v0.3.0 beta2 - 06/03/2007
- Fixed categories links

v0.3.0 beta1 (never released to public)
- Added possibility to choose from pages or categories to be displayed, use 'type' parameter
- Added 4 new parameters: 'type' mentioned before and also 'hide_empty', 'include', 'exclude' only for categories

v0.2.0 - 22/01/2007
- First public release