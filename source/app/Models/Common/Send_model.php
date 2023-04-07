<?php namespace App\Models\Common;

use CodeIgniter\Model;

/**
 * Class common sitemodel
 *
 * @package App\Models
 */
class Send_model extends Model
{
    private $send_datas = [];
    public $sender_mode = "one";

    public function __construct()
    {        
        $this->smsDB  = db_connect('smsdb');
    }

    /*
    * 세종 전용 send라이브러리 
    * $site : 사이트코드[toeic,basics ...]
    * $send_type : [ sms:세종sms, kakao:카카오알림톡(세종), sms_over:세종해외sms ]
    * $receive_num : 받는사람,
    * $msg_code : 특수 코드 
    *		sms : [1: SMS 2:SMS URL 3: MMS (filecnt 가 0이면 LMS) 4: MMS URL 5: 국제 SMS text 6: 알림톡  7: 친구톡(filecnt 가 0이면 친구톡-text, 0보다 크면 친구톡-image) ]
    *		sms_over : 5
    *		kakao : 템플릿 코드
    * $send_data : 보낼데이터 배열화
    *		sms : array('subject'=>'','content'=>'')
    *		sms_over : array('subject'=>'','content'=>'')
    *		kakao : array('content'=>'','price'=>'','finance'=>'' ...)
    * $send_num : 보내는사람
    * $sends_type : 1:비동보 2:동보(동보발송시 스팸 테이블 참조하지 않음)
    *
    *
    */
    public function Send($site,$send_type,$receive_num,$msg_code,$send_data = array(),$request_time="",$send_num = "",$sends_type=1){

        $result = true;

        $receive_num = $this->dstr_encrypt(str_replace("-","",$receive_num)); 
        
        $request_time = ( !$request_time ) ? date("Y-m-d H:i:s") : $request_time;
        $send_num = ($send_num) ? $send_num : "0264090878";

        $send_data['subject'] = (isset($send_data['subject'])) ? $send_data['subject'] : "";
        $send_data['content'] = (isset($send_data['content'])) ? $send_data['content'] : "";
        $send_data['content2'] = (isset($send_data['content2'])) ? $send_data['content2'] : "";
        $send_data['template_code'] = (isset($send_data['template_code'])) ? $send_data['template_code'] : "";
        $send_data['profile_key'] = (isset($send_data['profile_key'])) ? $send_data['profile_key'] : "";
        
        switch( $send_type ){
            case 'sms' :
                $sends_type = 1;
                $msg_type = $msg_code;
                break;
            case 'sms_over' :
                $msg_type = '5';
                
                break;
           /* case 'kakao' :
                $msg_type = "6";

                //기초영어 관련 사이트는 하나로 묶음 #1
                if( in_array($site, array('basics','real','livechat','max','superkids')) ){
                    $site = 'basics';
                }
                break;
            case 'kakaof' :
                $msg_type = "7";
                break;*/
            default : 
                return false;
                break;
        }
        
        $this->send_datas[] = [
            'msg_type'          => $msg_type,
            'send_type'         => '1',
            'dstaddr'           => $receive_num,
            'callback'          => $send_num,
            'stat'              => '0',
            'subject'           => $send_data['subject'],
            'text'              => $send_data['content'],
            'text2'             => $send_data['content2'],
            'request_time'      => $request_time,
            'k_template_code'   => $send_data['template_code'],
            'k_expiretime'      => '',
            'k_next_type'       => '7',
            'sender_key'        => $send_data['profile_key'],
            'ext_col1'          => $site
        ];
        
        if( $this->sender_mode == 'one' ){
            $result = $this->setData();
        }
        return $result;
    }

    public function send_multi($send_datas = array()){
        $this->ssender_mode = "multi"; 
        foreach( $send_datas as $send_data ){
            $this->send($send_data['site'],$send_data['send_type'],$send_data['receive_num'],$send_data['msg_code'],$send_data['send_data'],$send_data['request_time'],$send_data['send_num'],$send_data['sends_type']);
        }
        
        $result = $this->setData();
        return $result;
    }
    
    public function dstr_encrypt($string){

        $key = "a56cc515fe7aab50ffef0d8ae2fe0c78e0ea48de282c8020cf6ec06311be2e4d";
        $iv = $this->hex2bin("83f24a9e674606e183f24a9e674606e1"); //iv 값을 bin 처리
        $enc_str =  openssl_encrypt($string, "AES-256-CBC", $key,0,$iv);
        return $enc_str;
    }

    private function setData(){
        $this->smsDB->table(DB_T_sms_msg_queue)->insertBatch($this->send_datas);
    }
    
    private function hex2bin( $str ) {
        $sbin = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; $i += 2 ) {
            $sbin .= pack( "H*", substr( $str, $i, 2 ) );
        }

        return $sbin;
    }
}