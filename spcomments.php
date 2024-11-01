<?php
/**
 * @package Spcomments
*/

/*
Plugin Name: SP Comments Shortcode Kit
Plugin URI: http://www.prozac2000.com/
Description: Special Shortcoder for help you to categorize your comments
Version: 1.9
Author: Pro Zeta Gamma
Author URI: http://www.prozac2000.com
License: GPLv2 or later
Text Domain: Spcomments
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SPCOMMENTS_VERSION', '1.8' );
define( 'SPCOMMENTS__MINIMUM_WP_VERSION', '3.7' );
define( 'SPCOMMENTS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_action("init", "spcm_register_script");

function spcm_register_script() {
    wp_register_style( 'spcomments_style', plugins_url('/default.css', __FILE__), false, '1.0.2', 'all');
	wp_register_script ( 'spcomments_script', plugins_url ( 'script.js', __FILE__ ), array("jquery"), '2.3' );
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'spcm_enqueue_style');


function spcm_enqueue_style() {
	
	wp_enqueue_style( 'spcomments_style' );
	wp_enqueue_script('spcomments_script');

}

//
// Comments Wrapper: Aggiunge il codice aggiuntivo alla sessione che inserisce il commento
//
		
	add_action("comment_post","spcm_insert_comment",10,1);
	
	function spcm_insert_comment($comment_id) {
		
		//
		// Leggo l'argomento a cui fa riferimento il commento
		//
		$sp_comment_column = esc_attr($_POST['sp_comment_column']);
		
		//
		// Ma se il commento viene da una textarea messa in un punto specifico
		// leggo il campo arg e imposto questo come argomento
		//
		//
		$sp_arg_bypass = $_POST["arg"];
		if (!empty($sp_arg_bypass)) $sp_comment_column = $sp_arg_bypass;
		
		//
		// Aggiungo il campo personalizzato
		//
		update_comment_meta($comment_id,"sp_comment_column",$sp_comment_column);
	
	}

	function spcm_filter($comments) {
	
			//
			// delete comments with fields in column
			//
			
			$new_comments = array();
			
			foreach($comments as $comment) {
				
				$column = get_comment_meta( $comment->comment_ID, "sp_comment_column", $single = true );
				
				if ($column=="") array_push($new_comments,$comment); 
				
				}
			
			return $new_comments;
	
	}  
	
	add_filter ("comments_array", "spcm_filter");

//
// Shortcode che visualizza i commenti inseriti sotto un certo argomento
//

function spcm_col( $atts ) {

	$column_query = $atts["arg"];
	$args_nocommentstext = $atts["nocommentstext"];

	if (empty($args_nocommentstext)) $args_nocommentstext = "No comments yet on this topic";
	
	global $wp_query;
    $post_id = $wp_query->get_queried_object_id();

	 $comments = @get_comments(array('post_id' => $post_id->ID));
	 
	 $html = "";
	 
	 foreach($comments as $comment) {
	 
	 	$column = get_comment_meta( $comment->comment_ID, "sp_comment_column", $single = true );  
	   
	   	if ($column_query==$column) {
	   
		   $html .= '<li id="li-comment-'.$comment->comment_ID.'">
			 <div id="comment-'.$comment->comment_ID.'">
			  <div class="comment-author vcard">'.get_avatar($comment->comment_author_email,$size='48',$default=''). 
				 sprintf(__('<cite class="fn">%s</cite>'), get_comment_author_link($comment->comment_ID)).'
			  </div>';
			
			if ($comment->comment_approved == '0') $html .= '<em>'.__('Your comment is awaiting moderation.').'</em><br />';
		
			$html .= '<div class="comment-meta commentmetadata">
					<a href="'.htmlspecialchars( get_comment_link( $comment->comment_ID ) ).'">'
					.sprintf(__('%1$s at %2$s'), $comment->comment_date,  get_comment_time($comment->comment_date)).'</a>'
					.edit_comment_link(__('(Edit)'),'  ','').'</div>'
					.$comment->comment_content.'<div class="reply">'
					.get_comment_reply_link(array("depth" => $comment->comment_parent),$comment->comment_ID).'</div>
					</div>';
		}
		
	 }
	
	if ($html=="") $html = __($args_nocommentstext);
	 
	$html = "<ul class=\"spcomments-column-arg\">".$html."</ul>";
	return $html;

	}
	add_shortcode( 'spcomments', 'spcm_col' );
	
	
//
// Shortcode che visualizza il bottone con gli argomenti ed il modulo argomenti da inserire
//

add_shortcode( 'spcomments_button', 'spcm_button' );

function spcm_tut_fields() {
		
		$args_arguments = $GLOBALS["SPCOMMENT_ARGS"];
		$args_arguments_array = explode(",",$args_arguments);
		$num = 1;

		echo('<fieldset id="spcomments_arguments">');
		
		foreach($args_arguments_array as $arg) {

			echo('<div class="spcomments-columns comment-form-column-'.$num.'">
			<label for="sp_comment_column-'.$num.'">'.$arg.'</label>
			<input type="radio" name="sp_comment_column" id="sp_comment_column-'.$num.'" value="'.$arg.'" required="required" />
			</div>');
			
			$num++;

		}

		echo('</fieldset>');
		
		return;

}

function spcm_button( $atts ) {
	
	$args_btext = @$atts["text"];
	$args_arguments = @$atts["args"];
	$args_form_title = @$atts["formtitle"];
	$args_form_popupstyle = @$atts["popupstyle"];
	$args_id = @$atts["id"];
	$args_form_comment_field_label = @$atts["cflabel"];
	$args_placeholder = @$atts["placeholder"];
	
	$args_formid = @$atts["formid"];
	$args_arg = @$atts["arg"];
	$args_hideargs = @$atts["hideargs"];
	
	if ($args_btext=="") $args_btext = __("Submit");
	if ($args_arguments=="") $args_arguments = "Argument1,Argument2,Argument3";
	if ($args_form_title=="") $args_form_title = __("Leave a Reply");
	if ($args_form_popupstyle=="") $args_form_popupstyle = "spoiler";
	if (empty($args_id)) $args_id = rand(1,99999);
	if (empty($args_arg)) $args_arg = "none";
	if ($args_form_comment_field_label=="") $args_form_comment_field_label = "Comment";
	if ($args_placeholder=="") $args_placeholder = __("Your comment here...");
	
	$args_arguments_array = explode(",",$args_arguments);
	$GLOBALS["SPCOMMENT_ARGS"] = $args_arguments;

	//
	// Inserimento bottone secondario
	//

	if ($args_formid!="") {
		
		ob_start();
		
		echo "<button id=\"spcomments-sbutton-".$args_id."\"
		 data-id=\"".$args_formid."\"
		  data-sarg=\"".$args_arg."\"
		   data-hideargs=\"".$args_hideargs."\"
		    class=\"spcomments-button\">".$args_btext."</button>";
			
		return ob_get_clean();
		
		}

	//
	// Inserimento form
	//
	
	add_action("comment_form_logged_in_after","spcm_tut_fields");
	add_action("comment_form_after_fields","spcm_tut_fields");
		
	ob_start();
	echo "<div class=\"spcomment-main spc-".$args_id."\">";
	echo "<button id=\"spcomments-button-".$args_id."\" data-id=\"".$args_id."\" class=\"spcomments-button\">".$args_btext."</button>";
	echo "<div id=\"spcomments-form-block-".$args_id."\" data-id=\"".$args_id."\" class=\"spcomments-form-block";
	if ($args_form_popupstyle=="modal") echo(" modal");
	echo "\">";
	if ($args_form_popupstyle=="modal") echo "<div class=\"modal-content\">
		<span data-id=\"".$args_id."\" class=\"spcomment-close\">&times;</span>";

	if( comments_open() ) {
		comment_form(
			array(
			"title_reply" => $args_form_title,
			"comment_field" => "<p class=\"comment-form-comment\"><label for=\"comment\">"._x( $args_form_comment_field_label, "noun" )."</label><textarea id=\"comment\" name=\"comment\" cols=\"45\" rows=\"8\" aria-required=\"true\" placeholder=\"".$args_placeholder."\"></textarea></p>"
			)
		);
	} else {
		
		echo("<span class=\"spcomment-cclosed\">".__("Comments Closed")."</span>");
		
		}

	if ($args_form_popupstyle=="modal") echo ("</div>");
	echo "</div></div>";
	
	remove_action("comment_form_logged_in_after","spcm_tut_fields");
	remove_action("comment_form_after_fields","spcm_tut_fields");

	return ob_get_clean();
	
}

//
//
// Inserisce la textarea dei commenti in un punto specifico
//
//
// ********************************************************
//
//

add_shortcode( "spcomments_textarea", "spcm_commentform" );

function spcm_commentform($atts) {

	$args_btext = @$atts["text"];
	$args_form_title = @$atts["formtitle"];
	$args_id = @$atts["id"];
	$args_form_comment_field_label = @$atts["cflabel"];
	$args_placeholder = @$atts["placeholder"];
	
	$args_formid = @$atts["formid"];
	$args_arg = @$atts["arg"];
	
	if ($args_btext=="") $args_btext = __("Submit");
	if ($args_arguments=="") $args_arguments = "Argument1,Argument2,Argument3";
	if ($args_form_title=="") $args_form_title = __("Leave a Reply");
	if (empty($args_id)) $args_id = rand(1,99999);
	if (empty($args_arg)) $args_arg = "none";
	if ($args_form_comment_field_label=="") $args_form_comment_field_label = "Comment";
	if ($args_placeholder=="") $args_placeholder = __("Your comment here...");

	//
	// Inserimento form
	//
	
	//add_action("comment_form_logged_in_after","spcm_tut_fields");
	add_action("comment_form_after_fields","spcm_tut_fields");
		
	ob_start();
	echo "<div class=\"spcomment-main spc-".$args_id."\">";
	echo "<div id=\"spcomments-form-block-".$args_id."\" data-id=\"".$args_id."\" class=\"spcomments-form-block-inserted";
	echo "<div class=\"textarea-content\">";

	if( comments_open() ) {
		comment_form(
			array(
			"title_reply" => $args_form_title,
			"comment_field" => "<p class=\"comment-form-comment\"><input name=\"arg\" type=\"hidden\" value=\"".$args_arg."\"><label for=\"comment\">"._x( $args_form_comment_field_label, "noun" )."</label><textarea id=\"comment\" name=\"comment\" cols=\"45\" rows=\"3\" aria-required=\"true\" placeholder=\"".$args_placeholder."\"></textarea></p>"
			)
		);
	} else {
		
		echo("<span class=\"spcomment-cclosed\">".__("Comments Closed")."</span>");
		
		}

	echo "</div></div>";
	
	remove_action("comment_form_logged_in_after","spcm_tut_fields");
	remove_action("comment_form_after_fields","spcm_tut_fields");

	return ob_get_clean();
	
}