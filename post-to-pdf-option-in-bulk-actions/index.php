<?php

/*
 Plugin Name: Post to Pdf convertor
 * Description: Allows admin to download each post as a pdf file
 *  */

add_action('admin_footer-edit.php', 'custom_bulk_admin_footer');

function custom_bulk_admin_footer() {
  global $post_type;
  if($post_type == 'post') {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('convert_to_pdf').text('<?php _e('Export as Pdf')?>').appendTo("select[name='action']");
        jQuery('<option>').val('convert_to_pdf').text('<?php _e('Export as Pdf')?>').appendTo("select[name='action2']");
      });
    </script>
    <?php
  }
}


add_action('load-edit.php', 'custom_bulk_action');

function custom_bulk_action() {

  $wp_list_table = _get_list_table('WP_Posts_List_Table');
  $action = $wp_list_table->current_action();
  
  global $wpdb;
  switch($action) {

    case 'convert_to_pdf':

      $exported = 0;
      $post_ids = $_REQUEST['post'];

      foreach( $post_ids as $post_id ) {
		  $posttitle = $wpdb->get_results("select * from $wpdb->posts where id='".$post_id."'",ARRAY_A);

		  require_once("dompdf/dompdf_config.inc.php");
		  $dompdf = new DOMPDF();

		  $html = "<h2>".$posttitle[0]['post_title']."</h2>".$posttitle[0]['post_content'];
		  $dompdf->load_html($html);
		  $dompdf->render();
		  $dompdf->stream($posttitle[0]['post_title'].".pdf");
      }

    break;
    default: return;
  }
  exit();
}



?>
