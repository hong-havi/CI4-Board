<?php
if (! function_exists('VDataCheck'))
{
	/**
	 * 값을 받아서 존재 유무 판단후 없을시 기본값 반환
	 *
	 * @param String $value 비교값
	 * @param String $type 비교타입
	 * @param String $nullDef 없을시 반환 기본값
	 *
	 * @return String|null
	 */
	function VDataCheck( String $value, String $type , String $nullDef = "" ): ?String
	{
        if( !isset($value) ){
            $value = "";
        }

        $empty_flag = false;
        switch( $type ){
            case 'date' :
                if( $value == '0000-00-00' || !$value ){
                    $empty_flag = true;
                }
                break;
            default : 
                if( !$value ){
                    $empty_flag = true;
                }
                break;
        }

        if( $empty_flag  == true ){
            return $nullDef;
        }else{
            return $value;
        }


	}
}
