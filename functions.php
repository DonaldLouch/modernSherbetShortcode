<?php
/*
  * Plugin Name: modernSherbet Shortcode
  * Description: Create your WordPress shortcode for the theme modernSherbet.
  * Version: 1.1.2/1
  * Author: Donald Louch Productions
  * Author URI: https://donaldlouch.ca
*/

/*
=====================================================================
	Update Checker
		- Plugin Update Checker By YahnisElsts
=====================================================================
*/

		require 'plugin-update-checker/plugin-update-checker.php';
		$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			'https://github.com/DonaldLouch/modernSherbetShortcode',
			__FILE__,
			'modernsherbet-shortcode'
		);
		$myUpdateChecker->setBranch('stable');

/*
=====================================================================
  Shortcode: Toggle
		- Introduced in version 1.0
    - Calls the script
    - Creates the shortcode
    - Usage:
        [toggle id="ID" title="TITLE"]
        Content
        [/toggle]
=====================================================================
*/

//Calls the toggle.js script
  function toggleScriptLoad() {
    wp_enqueue_script('toggle', get_template_directory_uri() .'/scripts/js/toggle.js');
  }
  add_action( 'wp_enqueue_scripts', 'toggleScriptLoad' );

//Shortcode: Toggle
  function toggleShortcode( $atts, $content = null ) {
    extract( shortcode_atts(
    array(
      'title' => 'Click To Open',
      'id'=> 'Tab ID',
    ),
    $atts ) );
    return '<a class="toggleButton" onclick="toggle_visibility(&#39;'.$id.'&#39;)">'.$title.'</a><section id="'.$id.'" class="panel">' . do_shortcode($content). '</section>';
  }
  add_shortcode('toggle', 'toggleShortcode');

/*
=====================================================================
  Shortcode: Tabs
		- Introduced in version 1.0
    - Calls the script
    - Creates the shortcode
    - Usage:
        [tabs]
        [tab tabTitle="title 1" id="tab1"]Your content goes here 1...[/tab]
        [tab tabTitle="title 2" id="tab2"]Your content goes here 2...[/tab]
        ...
        [/tabs]
    - Adapted From https://pastebin.com/sAshJ348 ; https://tympanus.net/codrops/2014/03/21/responsive-full-width-tabs/

=====================================================================
*/

  function tabsScripts(){
    wp_enqueue_script('tabsJS', get_template_directory_uri() .'/scripts/js/tabsFunction.js', null );
  }
  add_action('wp_enqueue_scripts', 'tabsScripts');

  function tabShortcode( $atts, $content = null ) {
    extract( shortcode_atts(
    array(
      'title' => '',
      'id' => ''
    ),
    $atts) );
    global $single_tab_array;
    $single_tab_array[] = array('title' => $title, 'id' => $id, 'content' => do_shortcode($content) );
  }
  add_shortcode('tab', 'tabShortcode');

  function tabsShortcode( $atts, $content = null ) {
    global $single_tab_array;
    $single_tab_array = array();

    $tabs_nav = '';
    $tabs_content = '';
    $tabs_output = '';


    $tabs_nav .= '<div id="tabs">';
    $tabs_nav .= '<nav>';

    do_shortcode($content);

    ;

    foreach ( $single_tab_array as $tab => $tab_attr_array ) {
      $tabs_nav .= '<a href="&#35;'. $tab_attr_array["id"] .'">'.$tab_attr_array["title"].'</a>';
      $tabs_content .= '<section id="'. $tab_attr_array["id"] .'">'.$tab_attr_array['content'].'</section>';
    }

    $tabs_nav .= '</nav>';
    $tabs_output = $tabs_nav . '<div class="tabContent">' . $tabs_content . '</div>';
    $tabs_output .= '</div>';
    $tabs_output .= '
      <script>
      new tabsFunction(document.getElementById("tabs"))
      </script>
    ';

    return $tabs_output;
  }
  add_shortcode('tabs', 'tabsShortcode');

/*
=====================================================================
	Shortcode: Button(s)
		- Introduced in version 1.0
		- Creates the shortcode
		- Usage:
				[button link="https://donaldlouch.ca" class="button | flatButton"]Best Site Ever![/button]
=====================================================================
*/

	function buttonShortcode($atts, $content = null) {
		extract(shortcode_atts(array('link' => '#', 'class' => 'button'), $atts));
		return '<a class="'.$class.'" href="'.$link.'">' . do_shortcode($content) . '</a>';
	}
	add_shortcode('button', 'buttonShortcode');


	/*
	=====================================================================
	  Shortcode: Div No Break
			- Introduced in version 1.1
	    - Creates the shortcode
	    - Usage:
	        [nobreak]CONTENT[/nobreak]
	=====================================================================
	*/

	  function divNoBreakShortcode($atts, $content = null) {
	    extract(shortcode_atts(array(), $atts));
	    return '<div class="divNoBreak">' . do_shortcode($content) . '</div>';
	  }
	  add_shortcode('nobreak', 'divNoBreakShortcode');

/*
=====================================================================
  Shortcode: Columns (Version 1.0.1b1)
    - Introduced in version 1.1.2 | TO BE Updated in version 1.2
		- Creates the shortcode
    - Usage:
        [columns number="2|3|4|5|1/3|3/1|1/2/1" id=""]
        [column singleID="column1"]Your content goes here 1...[/column]
        [column singleID="column2"]Your content goes here 2...[/column]
        ...
        [/columns]
=====================================================================
*/

  function columnShortcode( $atts, $content = null ) {
    extract( shortcode_atts(
    array(
      'name' => ''
    ),
    $atts) );
    global $single_column_array;
    $single_column_array[] = array( 'name' => $name, 'content' => do_shortcode($content) );
  }
  add_shortcode('column', 'columnShortcode');

  function columnsShortcode( $atts, $content = null ) {
    extract( shortcode_atts(
    array(
      'number' => '',
      'id' => ''
    ),
    $atts) );
    global $single_column_array;
    $single_column_array = array();

    $columns_start = '';
    $columns_content = '';
    $columns_end = '';
    $columns_output = '';

    $columns_start .= '<div class="columns '.$number.'" id="'.$id.'">';

    do_shortcode($content);

    ;

    foreach ( $single_column_array as $column => $column_attr_array ) {
      $columns_content .= '<div id="'. $column_attr_array["name"] .'" class="column">'.$column_attr_array['content'].'</div> ';
    }

    $columns_end .= '</div>';
    $columns_output = $columns_start . $columns_content .  $columns_end;

    return $columns_output;
  }
  add_shortcode('columns', 'columnsShortcode');
?>
