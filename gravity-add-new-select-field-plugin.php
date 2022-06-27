<?php
/*
 * Plugin Name: WPDEFT A00078 GRAVITY
 * Version: 1.0
 * Plugin URI: https://wpdeft.com/
 * Description: Plugin to add minimum quantuty field settings in Product field.
 * Author: MARK STANLEY
 * Author URI: https://wpdeft.com/
 */
add_action( 'gform_field_standard_settings', 'wpdeft_gf_add_quantity_field', 10,  3);
function wpdeft_gf_add_quantity_field( $position, $form_id )
{
    if ($position == 25) {
         // retrieve the data earlier stored in the database or create it
          $highlight_fields = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10');
           ?>
           <li class="quantity_setting field_setting">

                    <label for="quantity_field" class="section_label">
                           <?php esc_html_e('Minimum Quantity', 'gravityforms'); ?>
                    </label>

                 

                    <select id="quantity_field" onchange="SetFieldProperty('QuantityField', this.value);">
                    <?php  foreach ($highlight_fields as $key => $value) { ?> 
                         <option value="<?php echo $key;?>"> <?php echo $value; ?> </option>
                    <?php  }  ?>
                    </select> 

               
            </li>
       <?php
       }
   }


   // save the custom field value with the associated Gravity Forms field
add_action('gform_editor_js', 'wdm_editor_script', 11, 3);

function wdm_editor_script()
{
  ?>

   <script type='text/javascript'>

   // To display custom field under each type of Gravity Forms field
   jQuery.each(fieldSettings, function(index, value) {
     fieldSettings[index] += ", .quantity_setting";
   });

   // store the custom field with associated Gravity Forms field
   jQuery(document).bind("gform_load_field_settings", function(event, field, form){
     
     // save field value: Start Section B
     jQuery("#quantity_field").val(field["QuantityField"]);
     // End Section B

    });

   </script>

   <?php
}

add_filter( 'gform_field_validation', function ( $result, $value, $form, $field ) { 

	//echo '<pre>'; print_r($field); echo '</pre>'; //die();
	
    if ( $field->type == 'product' ) {

    	//echo '<pre>'; print_r($value); echo '</pre>';
 
        //echo $field->label.'->'.$field->QuantityField;
        $input_quantity = intval(rgar( $value, $field->id . '.3' ));
        if ( !empty( $input_quantity ) && ($field->QuantityField > $input_quantity)){
            $result['is_valid'] = false;
            $result['message']  = empty( $field->errorMessage ) ? __( 'You can not order this item less than '.$field->QuantityField. ' quantity' , 'gravityforms' ) : $field->errorMessage;
        } else {
            $result['is_valid'] = true;
            $result['message']  = '';
        }
    }
 
    return $result;
}, 10, 4 );
?>