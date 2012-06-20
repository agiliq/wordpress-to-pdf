<?php

/*
 Plugin Name: Post to Pdf convertor
 * Description: Allows admin to download whole blog or selected posts as PDF file.
 *  */

add_action('admin_footer-edit.php', 'action_load_pdfoption_inselect');

function action_load_pdfoption_inselect() {
  global $post_type;
  if($post_type == 'post') {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('convert_to_pdf').text('<?php _e('Export as Pdf')?>').appendTo("select[name='action']");
        jQuery('<option>').val('convert_to_pdf').text('<?php _e('Export as Pdf')?>').appendTo("select[name='action2']");
        jQuery('<option>').val('blog_to_pdf').text('<?php _e('Export BLOG as Pdf')?>').appendTo("select[name='action']");
        jQuery('<option>').val('blog_to_pdf').text('<?php _e('Export BLOG as Pdf')?>').appendTo("select[name='action2']");
      });
    </script>
    <?php
  }
}


add_action('load-edit.php', 'action_convert_to_pdf');

function action_convert_to_pdf() {

  $wp_list_table = _get_list_table('WP_Posts_List_Table');
  $action = $wp_list_table->current_action();
  
  global $wpdb;
  switch($action) {

    case 'convert_to_pdf':

      $exported = 0;
      if(!isset($_REQUEST['post'])){
		return;
      }
      $post_ids = $_REQUEST['post'];
	  $html = "";
      foreach( $post_ids as $post_id ) {
		  $posttitle = $wpdb->get_results("select * from $wpdb->posts where id='".$post_id."'",ARRAY_A);

		  
		 

		  $html.= "<h2>".$posttitle[0]['post_title']."</h2>".$posttitle[0]['post_content']."<hr>";
		  
      }
		  require_once("dompdf/dompdf_config.inc.php");
		   $dompdf = new DOMPDF();
		  $dompdf->load_html($html);
		  $dompdf->render();
		  if(sizeof($post_ids) > 1){
		  		$dompdf->stream(sizeof($post_ids)."posts".".pdf");
		  }else{
		    	$dompdf->stream($posttitle[0]['post_title'].".pdf");
		  }

    break;
    
    case 'blog_to_pdf' :
    	 
    	$html = "";
    	$posts = $wpdb->get_results("select * from $wpdb->posts ",ARRAY_A);
      foreach( $posts as $post ) {
		  $html.= "<h2>".$post['post_title']."</h2>".$post['post_content']."<br>Status : ".$post['post_status']."<hr>";
		  
      }
		  require_once("dompdf/dompdf_config.inc.php");
		   $dompdf = new DOMPDF();
		  $dompdf->load_html($html);
		  $dompdf->render();
		  if(sizeof($posts) > 1){
		  		$dompdf->stream(get_bloginfo('name')."-".sizeof($posts)."posts".".pdf");
		  }else{
		    	$dompdf->stream($posttitle[0]['post_title'].".pdf");
		  }
    break;
    default: return;
  }
  exit();
}



?>
