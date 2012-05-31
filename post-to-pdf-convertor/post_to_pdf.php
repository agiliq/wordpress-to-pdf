<?php
/* Plugin Name: Post To Pdf convertor
 * Description: Allows admin to download each of his posts as a pdf file
 *  */
 
add_action('admin_menu', 'post_to_pdf');
function post_to_pdf() {

	add_options_page(  'Post To Pdf Options', 
            'Post To Pdf', 
            8, 
            __FILE__, 
            'post_to_pdf_options');
}
function post_to_pdf_options() {
require_once("dompdf/dompdf_config.inc.php");
	global $wpdb;
	
	
	$posts = $wpdb->get_results("select post_title,post_content from $wpdb->posts where post_status='publish'",ARRAY_A);
	
     
	$tabl = "<form name='f1' action='".plugins_url()."/post_to_pdf/getpdf.php' method='post'><table id='showposts_table'>";
	$i=0;
	foreach($posts as $post){
		$tabl.= "<tr><td>".($i+1)."</td><td>".$post['post_title']."</td><td></td><td><a href=".plugins_url()."/post_to_pdf/getpdf.php?p=\"".urlencode($post['post_content'])."\"  >Download</a></td></tr>";
	    $i++;
	}
		$tabl.= "</table></form><br>";
echo $tabl;
}


?>
