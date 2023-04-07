<?php
if (! function_exists('FormFill'))
{
	/**
	 * Convert a min to Time
	 *
	 * @param Int $num it will convert to int
	 *
	 * @return Array|null
	 */
	function FormFill( String $type, Array $form, String $key , $default ="", $compare_value = "")
	{
        $result = "";
        switch( $type ){
            case 'text' : 
                if( isset($form[$key]) ) {
                    return $form[$key];
                }                
                break;
            case 'checkbox' : 
                if( isset($form[$key]) ) {
                    if( is_array($form[$key]) ){
                        if( in_array( $compare_value, $form[$key] ) ){
                            return "checked";
                        }
                    }else if( $form[$key] == $compare_value ){
                        return "checked";
                    }
                }
                break;
            case 'select' :                 
                if( isset($form[$key]) ) {
                    if( is_array($form[$key]) ){
                        if( in_array( $compare_value, $form[$key] ) ){
                            return "selected";
                        }
                    }else if( $form[$key] == $compare_value ){
                        return "selected";
                    }
                }
                break;
            case 'array' : 
                if( isset($form[$key]) ){
                    return $form[$key];
                }             
                break;
        }

        return $default;
	}
}
