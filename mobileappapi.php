<?php
date_default_timezone_set("America/New_York");

/* @wordpress-plugin
 * Plugin Name:       Mobile app API
 * Description:       All functions which is used in mobile app with JWT Auth.
 * Version:           1.0
 * Author:            Knoxweb
 */
// If this file is called directly, abort.
    
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if(!defined('WPINC')){
    die;
}
    // add_action('rest_api_init', function () {
    //     remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    //     add_filter('rest_pre_serve_request', function ($value) {
    //         header('Access-Control-Allow-Origin: *');
    //         header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    //         header('Access-Control-Allow-Credentials: true');
    //         return $value;
    //     });
    // }, 15);

    function test_jwt_auth_expire($issuedAt){
        return $issuedAt + (9999999 * 10000);
    }
    add_filter('jwt_auth_expire', 'test_jwt_auth_expire');

    add_action('rest_api_init', function(){
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        add_filter('rest_pre_serve_request', function ($value) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Credentials: true');
            return $value;
        });
        
        register_rest_route( 'mobileapi/v1/', '/getCurrentUserInfo', array(
           'methods'    => 'POST', 
           'callback'   => 'getCurrentUserInfo' 
        ));
            
        //getSecoondUserInfo  
        register_rest_route( 'mobileapi/v1/', '/getSecoondUserInfo', array(
           'methods'    => 'POST', 
           'callback'   => 'getSecoondUserInfo' 
        ));
            
            
        register_rest_route('mobileapi/v1', '/register', array(
            'methods'   => 'POST',
            'callback'  => 'MobileApiMakeNewAuthor',
        ));

        register_rest_route('mobileapi/v1', '/create_report', array(
            'methods'   => 'POST',
            'callback'  => 'create_report',
        ));

        register_rest_route('mobileapi/v1', '/retrieve_password', array(
            'methods'   => 'POST',
            'callback'  => 'RetrivePassword',
        ));

        register_rest_route('mobileapi/v1', '/validate_token', array(
            'methods'   => 'POST',
            'callback'  => 'validate_token',
        ));
    
        register_rest_route('mobileapi/v1', '/facebook_login', array(
            'methods'   => 'POST',
            'callback'  => 'facebook_login',
        ));
    
        register_rest_route('mobileapi/v1', '/updateProfile', array(
            'methods'   => 'POST',
            'callback'  => 'updateProfile',
        ));

        register_rest_route('mobileapi', '/get_comment', array(
            'methods'   => 'POST',
            'callback'  => 'getComment',
        ));

        register_rest_route('mobileapi', '/submit_comment', array(
            'methods'   => 'POST',
            'callback'  => 'submitComment',
        ));
    
        register_rest_route('mobileapi', '/reply_comment', array(
            'methods'   => 'POST',
            'callback'  => 'replyComment',
        ));
    
        register_rest_route('mobileapi/v1', '/create_feed', array(
            'methods'   => 'POST',
            'callback'  => 'createFeed',
        ));
    
        register_rest_route('mobileapi/v1', '/create_contact', array(
            'methods'   => 'POST',
            'callback'  => 'create_contact',
        ));
    
        register_rest_route('mobileapi/v1', '/getProfile', array(
            'methods'   => 'GET',
            'callback'  => 'getProfile',
        ));

        register_rest_route('mobileapi/v1', '/getPhotos/', array(
            'methods'   => 'GET',
            'callback'  => 'getPhotos',
        ));
        
         register_rest_route('mobileapi/v1', '/deleteImage/', array(
            'methods'   => 'GET',
            'callback'  => 'DeletePhotos',
        ));
    
        register_rest_route('mobileapi/v1', '/fetch_cat/', array(
            'methods'   => 'POST',
            'callback'  => 'fetch_cat',
        ));

        register_rest_route('mobileapi/v1', '/change_password/', array(
            'methods'   => 'POST',
            'callback'  => 'manually_change_password',
        ));
    
        register_rest_route('mobileapi/v1', '/getServices', array(
            'methods'   => 'GET',
            'callback'  => 'getServices',
        ));
    
        register_rest_route('mobileapi/v1', '/getUserServices', array(
            'methods'   => 'GET',
            'callback'  => 'getbyUserServices',
        ));
    
        register_rest_route('mobileapi/v1', '/addServices', array(
            'methods'   => 'POST',
            'callback'  => 'addServices',
        ));
    
        register_rest_route('mobileapi/v1', '/get_user_role_wise', array(
            'methods'   => 'GET',
            'callback'  => 'get_user_role_wise',
        ));
    
        register_rest_route('mobileapi/v1', '/contactus', array(
            'methods'   => 'POST',
            'callback'  => 'save_contactus',
        ));
    
        register_rest_route('mobileapi/v1/', '/GetSetting', array(
            'methods'   => 'POST',
            'callback'  => 'GetSetting',
        ));

        register_rest_route('mobileapi/v1/', '/save_onesignal_id', array(
            'methods'   => 'POST',
            'callback'  => 'save_onesignal_id',
        ));

        register_rest_route('mobileapi/v1/', '/GetUserMainImage', array(
            'methods'   => 'GET',
            'callback'  => 'GetUserImage',
        ));
        
        register_rest_route('mobileapi/v1', '/get_forms/', array(
            'methods'   => 'GET',
            'callback'  => 'get_forms'
        ));
        register_rest_route('mobileapi/v1', '/submitForm', array(
            'methods'   => 'POST',
            'callback'  => 'submitForm',
        ));
        
        register_rest_route('mobileapi/v1/', '/GetMyNotifications', array(
            'methods'   => 'GET',
            'callback'  => 'GetMyNotifications',
        ));

        register_rest_route('mobileapi/v1/', '/WalletAmountTotal', array(
            'methods'   => 'POST',
            'callback'  => 'WalletAmountTotal',
        ));

        
         register_rest_route('mobileapi/v1/', '/SyncUserLog', array(
            'methods'   => 'POST',
            'callback'  => 'SyncUserLog',
        ));

        register_rest_route('mobileapi/v1/', '/test_notification', array(
            'methods'   => 'POST',
            'callback'  => 'test_notification',
        ));
        
        register_rest_route('mobileapi/v1/', '/get_message_balance', array(
            'methods'   => 'POST',
            'callback'  => 'get_message_balance',
        ));
        
        
        register_rest_route('mobileapi/v1', '/updateDeviceToken', array(
            'methods' => 'POST',
            'callback' => 'updateDeviceToken'
        ));
        
        register_rest_route('mobileapi/v1', '/validateUser', array(
            'methods' => 'POST',
            'callback' => 'validateUser'
        ));
        
        register_rest_route('mobileapi/v1', '/add_user_purchase_message', array(
            'methods' => 'POST',
            'callback' => 'add_user_purchase_message'
        ));

    });
    

    include('stripe-payment-gatway/stripe-payment-gatway.php');
        
    include('custom_stripe_class.php');
    global $Custom_stripe_obj;
    
    include('custom_helper.php');
    global $customHelperObj;
    
    include("new_api.php");
    include("new-custom-api.php");
    include("membership.php");
    
    require_once('invoice/invoice.php');
    
    function test_notification($request){
        global $customHelperObj;
        $user_id = '134';
        $msg = urlencode("facebook.com");
        $customHelperObj->sendSMS(123,$msg);
    }
    
    function add_user_purchase_message($request){
        global $wpdb;
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();  
        $token = $param['token'];
        $user_id = GetMobileAPIUserByIdToken($token);
        if($user_id){
            $selectQuery = 'SELECT * FROM `wp_user_messages` WHERE `user_id`='.$user_id;
            $result      = $wpdb->get_row($selectQuery,ARRAY_A);
            
            switch ($param['subscriptionplan_id']) {
                case 'io.one.message':
                    $message = 100;
                    break;
                case 'io.ten.message':
                    $message = 200;
                    break;
                case 'io.hundred.message':
                    $message = 300;
                    break;
                default:
                    $message = 0;
                    break;
            }
            
            // if(isset($param['extra_msg']) && !empty($param['extra_msg'])){
            //   $extra_msg =  $param['extra_msg'];
            //   $total_message = $message + $extra_msg;
            // }else{
            //     $total_message = $message;
            // }
            
            $total_message = $message;
            
            $insertData = array(
                'user_id'        => $user_id,
                'subscription'   => $param['subscriptionplan_id'],
                'transaction_id' => $param['transaction_id'],
                'receipt'        => $param['receipt'],
                'subscription_device'=> $param['deviceType'],
                'messages'       => $message,
                // 'extra_messages' => $extra_msg
                'extra_messages' => 0
            );
                
            $wpdb->insert("wp_user_purchase_message",$insertData);
         
            if(count($result) > 0){
                $updateData = array();
                $new_message_amount = $result['messages'] + $total_message;
                $updateData['messages']      = $new_message_amount;
                $wpdb->update("wp_user_messages",$updateData,array("user_id"=>$user_id));
            }else{
                $insert_data['user_id']   = $user_id;
                $insert_data['messages']  = $total_message;
                $wpdb->insert("wp_user_messages",$insert_data);
            }
            $data['new_message_amount'] =$new_message_amount;
            $data['msg'] = 'Messages Purchase Successfully!';   
            return new WP_REST_Response($data);
            
        }else{
            $data['status'] = "error";
            $data['error_code'] = "user_expire";
            $data['errormsg'] = "Something went wrong.";
            return new WP_REST_Response($data, 403);
        }
    }
    
    


     function SyncUserLog($request){
         $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
         $param = $request->get_params();
         $user_id = $param['user_id'];
         $type = $param['type'];
         $to_user = $param['to_user'];
         $sendpush = $param['sendpush'];
         $newmessage = $param['newmessage'];
         if($user_id){
            //  date_default_timezone_set('US/Eastern');
             update_user_meta($user_id, 'active_chatpage',$type);
             
             
             if($type==1){
                 update_user_meta($user_id, 'last_active_chatpage',time()); 
                 update_user_meta($user_id, 'chatbox_active_user',$to_user);
                 
                 
             }else{
                 update_user_meta($user_id, 'last_active_chatpage',''); 
                 update_user_meta($user_id, 'chatbox_active_user',''); 
             }
             
             if($sendpush==1){
                  $chatbox_active_user = get_user_meta($to_user,'chatbox_active_user',true);
                  
                  $active_chatpage = get_user_meta($to_user,'active_chatpage',true);
                  $last_active_chatpage = get_user_meta($to_user,'last_active_chatpage',true);
                  
                  $first_name = get_user_meta($user_id,'first_name',true);
                  $last_name = get_user_meta($user_id,'last_name',true);
                  
                  $minutes=0;
                  if($last_active_chatpage > 0){
                     $diff = abs(time() - $last_active_chatpage); 
                     
                     $years = floor($diff / (365*60*60*24)); 
                     
                     $months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24)); 
                     $days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24));
                     $hours = floor(($diff - $years * 365*60*60*24  
       - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
                     $minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
                  
                  
                  }
                  
                  
                  $send=false;
                  if($active_chatpage!=1){
                     sendPushServer($user_id, 'msg_push',$newmessage, "New Message from ".$first_name." ".$last_name,$to_user,'');
                     $send=true;
                  }
                  
                  if($chatbox_active_user!=$user_id && $active_chatpage==1){
                     sendPushServer($user_id, 'msg_push',$newmessage, "New Message from ".$first_name." ".$last_name,$to_user,'');
                     $send=true;
                  }
                  
                  if($minutes>1 && !$send){
                      sendPushServer($user_id, 'msg_push',$newmessage, "New Message from ".$first_name." ".$last_name,$to_user,'');
                  }
                   $data['$send']=$send;
             }
             
         }
          $data['minutes']=$minutes;
          return new WP_REST_Response($data, 200);  
     }
     

    
    function submitForm($request){
        global $wpdb;
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();
        
        $form_id = $param['formId'];
        $user_id = $param['user_id'];
        
        $input_values = $param['formdata'];
        $a = array();
        foreach($input_values as $key=>$val){
            if (strpos($key, '.') !== false) {
                $key = str_replace(".", "_", $key);
            }
            $a[$key] = $val;
        }
        $result = GFAPI::submit_form( $form_id, $a );
        $entry = array('created_by'=>$user_id);
        GFAPI::update_entry($entry,$result['entry_id']);
        $wpdb->update('wp_grievance_steps',array('user_id'=>$entry['created_by']),array('entry_id'=>$result['entry_id']));
        foreach($input_values as $k=>$val){
            $k = str_replace("input_","",$k);
            GFAPI::update_entry_field($result['entry_id'],(String)$k,$val);
        }
        $data['form_id'] = $form_id;
        $data['input_values'] = $a;
        $data['result'] = $result;
        return new WP_REST_Response($result, 200);
    }
    
    function get_forms($request){
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();
        $token = $param['token'];
        $param = $request->get_params();
        $token=$param['token'];
        $form_id=$param['form_id'];
        $user_id = GetMobileAPIUserByIdToken($token);
        if($user_id){
            if($form_id>0){
                $data['forms']=GFAPI::get_form($form_id);
            }else{  
                $data['forms']=GFAPI::get_forms();
            }
            return new WP_REST_Response($data, 200);
        }
        $data = array(
            "status"    => "error",
            "errormsg"  => "user token expired",
            'error_code'=> "user_expire"
        );
        return new WP_REST_Response($data, 403);
    }
    


    
    function getPhotos($request){
        global $wpdb;
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();  
        $token = $param['token'];
        if($param['type']=="public"){
             $user_id = $token;
        }else{
             $user_id = GetMobileAPIUserByIdToken($token);
        }
        if($user_id){
            $parray=array();
            $photos = $wpdb->get_results("SELECT * FROM `user_collection_gallery` where user_id=".$user_id);
            if(count($photos)>0){
                foreach($photos as $p){
                    $img = wp_get_attachment_image_src($p->attachment_id, array('150','150') , true);
                    $gallerImage = $img[0];
              
                    $imgLarge = wp_get_attachment_image_src($p->attachment_id,'large', true);
                    $gallerLarge = $imgLarge[0];
                    $parray[]= array('thumb'=>$gallerImage,'large'=>$gallerLarge,'collection_id'=>$p->collection_id);
                }
            }
            $data['images']=$parray;
            return new WP_REST_Response($data, 200);
         }else{
            $data['status'] = "error";
            $data['error_code'] = "user_expire";
            $data['errormsg'] = "Something went wrong.";
            return new WP_REST_Response($data, 403);
         }
    }
    
    function DeletePhotos($request){
        global $wpdb;
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();  
        $token = $param['token'];
        $collection_id=$param['collection_id'];
        $user_id = GetMobileAPIUserByIdToken($token);
        
         if($user_id){
             $parray=array();
              $photos = $wpdb->get_row("SELECT * FROM `user_collection_gallery` where user_id=".$user_id." and collection_id=".$collection_id,ARRAY_A);
              if(count($photos)>0){
                $delete = $wpdb->query("delete from user_collection_gallery where collection_id=".$collection_id);  
                if($delete){
                       wp_delete_attachment($photos[0]['attachment_id'],true);
                       return new WP_REST_Response($data, 200);  
                }else{
                $data['status'] = "error";
                $data['error_code'] = "not_able_delete";
                $data['errormsg'] = "Something went wrong.";
                return new WP_REST_Response($data, 403);
                }
              }else{
                $data['status'] = "error";
                $data['error_code'] = "user_not_image";
                $data['errormsg'] = "Something went wrong.";
                return new WP_REST_Response($data, 403);
              }
              
         }else{
            $data['status'] = "error";
            $data['error_code'] = "user_expire";
            $data['errormsg'] = "Something went wrong.";
            return new WP_REST_Response($data, 403);
         }
    }
    
    
    function GetMyNotifications($request){
        global $wpdb;
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param = $request->get_params();  
        $token = $param['token'];
        $user_id = GetMobileAPIUserByIdToken($token);
        if($user_id){
            $user = get_userdata($user_id);
            $role = 'customer';
            if (in_array('barber', (array) $user->roles)) {
                $role = 'barber';
            } else {
                $role = $user->roles[0];
            }
            $where= ' where id > 0 ';
            if($role=='barber'){
                $data['view_type']='barber'; 
                $where .=' AND 	user_id='.$user_id; 
            }else{
                $where .=' AND 	user_id='.$user_id;  
                $data['view_type']='customer'; 
            }
            $data['q']="select * from wp_jet_push_notification $where ORDER BY id DESC";
            $notifications = $wpdb->get_results("select * from wp_jet_push_notification $where ORDER BY id DESC",ARRAY_A); 
            $data['notifications']=false;
            $notification=array();
            if(count($notifications) > 0){
                foreach($notifications as $b){
                    
                   $user_sent = get_userdata($b['sent_by']);
                   if (in_array('barber', (array) $user_sent->roles)) {
                     $b['from_user_name']=get_user_meta($b['sent_by'],'shop_name',true);   
                   }else{
                     $b['from_user_name']=get_user_meta($b['sent_by'],'first_name',true)." ".get_user_meta($b['sent_by'], 'last_name', true); 
                    
                    if(trim($b['from_user_name'])==''){
                        $b['from_user_name']=get_user_meta($b['sent_by'], 'nickname', true);
                    }  
                   }
                    
                    
                    $b['to_user_name']=get_user_meta($b['user_id'],'first_name',true)." ".get_user_meta($b['user_id'], 'last_name', true);
                    $b['to_user']= $b['user_id'];
                    if(trim($b['to_user_name'])==''){
                        $b['to_user_name']=get_user_meta($b['user_id'], 'nickname', true);
                    }
                    
                    $useravatar = get_user_meta($b['sent_by'], 'wp_user_avatar', true);
                    if ($useravatar)
                    {
                        $img = wp_get_attachment_image_src($useravatar, array(
                            '150',
                            '150'
                        ), true);
                        $user_avatar = $img[0];
                        $b['from_user_name_img'] = $user_avatar;
                    }
                    else
                    {
                        $b['from_user_name_img'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
                    }
                    $b['created'] = timeago( $b['created']);
                    
                    // $b['message'] = 'Sept 21 &#8211; test 1 Gender';
                    $notification[]=$b; 
                }
                $data['notifications']=$notification;  
            }
            return new WP_REST_Response($data, 200);
        }else{
            $data['status'] = "error";
            $data['error_code'] = "user_expire";
            $data['errormsg'] = "Something went wrong.";
            return new WP_REST_Response($data, 403);
        }
    }
    
    function timeago($date) {
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
	   }
	}


    
    function getSecoondUserInfo($request){
        global  $wpdb,$customHelperObj;
        $data    = array("status"=>"ok","errormsg"=>"",'error_code'=>"");
        $param   = $request->get_params();
        $user_id = $param['id'];

        if($user_id>0){
         $data['userInfo'] = $customHelperObj->getUserInformation($user_id);
         return new WP_REST_Response($data, 200);
        }
        else{
           return new WP_REST_Response($data, 403);
        }
    }
    
    function getCurrentUserInfo($request){
        $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
          $param = $request->get_params();
          $usertoken = $param['token'];
            $user_id = GetMobileAPIUserByIdToken($usertoken);
            $single=array();
        if ($user_id)
        {
            $single['id']=$user_id;
            $single['first_name'] = get_user_meta($user_id, 'first_name', true);
            $single['last_name'] = get_user_meta($user_id, 'last_name', true);
            $single['display_name'] = $single['first_name'] . ' ' . get_user_meta($user_id, 'last_name', true);
            $useravatar = get_user_meta($user_id, 'wp_user_avatar', true);
            if ($useravatar)
            {
                $img = wp_get_attachment_image_src($useravatar, array(
                    '150',
                    '150'
                ) , true);
                $user_avatar = $img[0];
                $single['user_img'] = $user_avatar;
            }
            else
            {
    
                $single['user_img'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
            }
            $data['result']=$single;
        }else{
           return new WP_REST_Response($data, 403);
        }
         return new WP_REST_Response($data, 200);
    }

    function save_onesignal_id($request){
        global $wpdb;
        $param = $request->get_params();
        $oneSignal = $param['oneSignID'];
        $token = $param['token'];
        $timezone = $param['timezone'];
    
        $param['status'] = "ok";
    
        $user_id = GetMobileAPIUserByIdToken($token);
    
        if ($user_id) {
    
            $row_pre_one = $wpdb->query("delete from wp_usermeta where meta_key='one_signal_id' and meta_value='" . $oneSignal . "' and user_id!='" . $user_id . "'");
            $row_pre_one2 = $wpdb->query("delete from wp_usermeta where meta_key='one_signal_id_android' and meta_value='" . $oneSignal . "' and user_id!='" . $user_id . "'");
    
            update_user_meta($user_id, 'last_app_update', date('Y-m-d H:i:s'));
    
            //$DeviceUdid
    
            @$type = $param['type'];
            if ($type == "android") {
                update_user_meta($user_id, 'one_signal_id_android', $oneSignal);
            } else {
                $type == "ios";
                update_user_meta($user_id, 'one_signal_id', $oneSignal);
            }
    
            // Manage Token with Devices
            $res = $wpdb->get_row("select * from wp_user_devices where token='" . $oneSignal . "'");
    
            if (count($res) > 0) {
                $wpdb->query("UPDATE `wp_user_devices` SET `type` = '" . $type . "',`user_id` = '" . $user_id . "',`timezone` = '" . $timezone . "',`status`= '1' WHERE `wp_user_devices`.`token` = '" . $oneSignal . "';");
    
            } else {
                $wpdb->insert("wp_user_devices", array('user_id' => $user_id, 'token' => $oneSignal, 'type' => $type, "timezone" => $timezone,"status"=>'1'));
              
            }
    
            if ($timezone != '') {
                update_user_meta($user_id, 'time_zone', $timezone);
            }
            
            // $doNotDis = get_user_meta($user_id,'notifications_status',true);
            // if(empty($doNotDis)){
            //  update_user_meta($user_id,'notifications_status',1);   
            // }
            
            return new WP_REST_Response($param, 200);
        } else {
            $param['status'] = "error";
            $param['error_code'] = "user_expire";
            return new WP_REST_Response($param, 403);
        }
    }

    function GetSetting($request){
        global $wpdb;
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        $param = $request->get_params();
        $secret_key = get_option('options_secret_key');
        $publishable_key = get_option('options_publisher_key');
        $data['secret_key'] = $secret_key;
        $data['publishable_key'] = $publishable_key;
        $hw = get_option('options_how_video');
        $hwi = get_option('options_how_to_cover');
        
        $messageSettings = array();
        $messageSettings['amount']   = get_option('options_amount');
        $messageSettings['number_of_message'] = get_option('options_number_of_message');
        $data['messageSettings'] = $messageSettings;
    
        $about = array("welcome_text" => nl2br(get_option('options_welcome_text')), "about_text" => nl2br(get_option('options_about')), "how_video" => wp_get_attachment_url($hw), "how_video_c" => wp_get_attachment_url($hwi), "term_text" => nl2br(get_option('options_terms')));
        $data['about'] = $about;
        
        
        $pp_id=125799; 
        $pp_data = get_post($pp_id); 
        $pp = apply_filters('the_content', $pp_data->post_content); 
        $data['pp'] = $pp;
        
        $tos_id=128960; 
        $tos_data = get_post($tos_id); 
        $tos = apply_filters('the_content', $tos_data->post_content); 
        $data['tos'] = $tos;
        
        return new WP_REST_Response($data, 200);
    
    }

    //[START]=>Save contactus form
    function save_contactus($request){
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        $param = $request->get_params();
    
        $name = trim($param['name']);
        $email = trim($param['email']);
        $message = trim($param['details']);
        $phone = trim($param['phone']);
        //$post_type  = 'postman_sent_mail';
        $subject = 'New contactus entry is added from Mobile Apps!';
    
        if ($name == "") {
            $data['status'] = "error";
            $data['msg'] = 'Please provide name';
            $data['error_code'] = "name_blank";
            return new WP_REST_Response($data, 403);
        }
    
        if ($email == "") {
            $data['status'] = "error";
            $data['msg'] = 'Please provide email.';
            $data['error_code'] = "email_blank";
            return new WP_REST_Response($data, 403);
        }
    
        if ($message == "") {
            $data['status'] = "error";
            $data['msg'] = 'Please provide message.';
            $data['error_code'] = "message_blank";
            return new WP_REST_Response($data, 403);
    
        }
        $message = $message . ", Phone No: " . $phone;
        // $to      = get_option('admin_email');
        // $to = 'jamtechtest@gmail.com';
        $to = get_field('contact_us_email', 'options');
        
        $subject = $subject;
        $body = $message;
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $name . ' <' . $email . '>';
    
        if (wp_mail($to, $subject, $body, $headers)) {
            echo "Send";
        } else {
            echo "Not Send";
        }
    
        // $data['msg'] = "Thank you for contacting us. We will get back to you shortly.";
        // return new WP_REST_Response($data, 200);
        exit;
    }

    //This below function is written for get user List by role wise
    function get_user_role_wise($request){
        // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        $param = $request->get_params();
        
        if(isset($param['token']) && !empty($param['token']) ){
             $user_id = GetMobileAPIUserByIdToken($param['token']);
        }
        
        $role = $param['role'];
        $latitude = $param['latitude'];
        $longitude = $param['longitude'];
        
        $q = (isset($param['q']) ? $param['q'] : '');
        $service_id = (isset($param['service_id']) ? $param['service_id'] : '');
        $data['list'] = array();
        $data['user_list'] = array();
        global $wpdb;
        // check if service filter
       
        //Add Pagination
        $page_no = (isset($param['page_no']) ? $param['page_no'] : 1);
        $max_num_pages = 5;
        $paged = ($page_no) ? $page_no : 1;
        $post_per_page = 10;
        
        $offset = ($paged - 1)*$post_per_page;
        $pagination = " LIMIT $offset,$post_per_page ";
        //Add Pagination
       
        $distance = 50;
        $earth_radius = 3959;
        $show_distance_in_result = false;
        
        $integer_day = false;//Filter By Day
        if(isset($param['filter_day']) && trim($param['filter_day']) != '' && $param['filter_day']!='null') {
            $filter_day = $param['filter_day'];
            $integer_day = ($filter_day =='Sunday'? 1 :( ($filter_day =='Monday')? 2 :( ($filter_day =='Tuesday')? 3 : ( ($filter_day =='Wednesday')? 4 : ( ($filter_day =='Thursday')? 5 : ( ($filter_day =='Friday')? 6 : ( ($filter_day =='Saturday')? 7 : false ) ) ) ) )));
        }
        if(isset($param['type']) && $param['type']=="home"){
            $results_by_query = false;
            if(!empty($latitude) && !empty($longitude) ){
                $select_more = '';
                $where_condition = '';
                $join = '';
                $show_distance_in_result = true;
                $sql = "SELECT DISTINCT p.ID, p.display_name,map_lat.meta_value as locLat, map_lng.meta_value as locLong,
                    ($earth_radius * acos(
                        cos(radians($latitude)) * cos(radians(map_lat.meta_value)) 
                        * cos(radians(map_lng.meta_value) - radians($longitude)) 
                        + sin(radians($latitude)) * sin(radians(map_lat.meta_value))
                    )) AS distance
                    FROM wp_users p
                    INNER JOIN wp_usermeta map_lat ON p.ID = map_lat.user_id INNER JOIN wp_usermeta map_lng ON p.ID = map_lng.user_id $join
                    WHERE 1 = 1
                    AND map_lat.meta_key = 'map_lat' AND map_lng.meta_key = 'map_lng' $where_condition HAVING distance < $distance ORDER BY distance ASC $pagination";
                $results = $wpdb->get_results($sql);
                $results_by_query = true;
            }else{
                $lat_lng = false;
                $results = get_users(array('role' =>$role));  
            }
        }elseif(isset($param['type']) &&  $param['type']=="home_map"){
            $select_more = '';
            $where_condition = '';
            $join = '';
            $sql = "SELECT DISTINCT p.ID, p.display_name,map_lat.meta_value as locLat, map_lng.meta_value as locLong,
                ($earth_radius * acos(
                    cos(radians($latitude)) * cos(radians(map_lat.meta_value)) 
                    * cos(radians(map_lng.meta_value) - radians($longitude)) 
                    + sin(radians($latitude)) * sin(radians(map_lat.meta_value))
                )) AS distance
                FROM wp_users p
                INNER JOIN wp_usermeta map_lat ON p.ID = map_lat.user_id INNER JOIN wp_usermeta map_lng ON p.ID = map_lng.user_id
                $join WHERE 1 = 1
                AND map_lat.meta_key = 'map_lat' AND map_lng.meta_key = 'map_lng' $where_condition HAVING distance < $distance ORDER BY distance ASC $pagination";
            
            $results = $wpdb->get_results($sql);
            $results_by_query = true;
        }else{
            $select_more = '';
            $join = '';
            $where_condition = '';
            $is_meta_filter=false;
            $having = '';
            if(isset($param['q']) && trim($param['q']) != '' && $param['q']!='null') {
                $where_condition.= " AND m.meta_key = 'shop_name' AND m.meta_value LIKE '%".$param['q']."%'";
                $is_meta_filter=true;
            }
            if($service_id > 0){
                $join.= ' LEFT JOIN wp_users_services s ON p.ID=s.user_id ';
                $where_condition.= ' AND s.service_id='.$service_id.' AND s.status=1';
            }
            
            $dayJoin=false;
            if(isset($param['filter_day']) && $param['filter_day'] != "all") {
                $dayJoin=true;
                $join.= ' LEFT JOIN wp_users_working_days working ON p.ID=working.user_id ';
                $where_condition.= ' AND working.day="'.$integer_day.'" AND working.status="1"';
            }
            
            if($param['filter_time'] != "") {
                if(!$dayJoin){
                     $join.= ' LEFT JOIN wp_users_working_days working ON p.ID=working.user_id ';
                }  
                
                $format = 'YYYY-MM-DDTHH:mm:ssTZD';
                $from  = $param['filter_time'];
                $from=str_replace(" ","+",$from);
                $from_obj_data = new DateTime($from);
                 
                $from_time_format = $from_obj_data->format('H:i').':00';
                $data['from_time_format'] = $from_obj_data;
                $where_condition.= ' AND working.from_time_format  <= "'.$from_time_format.'" AND working.to_time_format  >= "'.$from_time_format.'"';
            }
            
            if($longitude!='' && $latitude!=''){
                $show_distance_in_result = true;
                $is_meta_filter=true;
                $select_more.= "
                ,($earth_radius * acos(
                    cos(radians($latitude)) * cos(radians(map_lat.meta_value)) 
                    * cos(radians(map_lng.meta_value) - radians($longitude)) 
                    + sin(radians($latitude)) * sin(radians(map_lat.meta_value))
                )) AS distance ";
                $join.= " INNER JOIN wp_usermeta map_lat ON p.ID = map_lat.user_id INNER JOIN wp_usermeta map_lng ON p.ID = map_lng.user_id ";
                $where_condition.=" AND map_lat.meta_key = 'map_lat' AND map_lng.meta_key = 'map_lng' ";
                $having.=" HAVING distance < $distance ";
                $order_by = " ORDER BY distance ASC";
            }else{
                $order_by = " ORDER BY p.ID DESC";
            }
            
            //New Change on 15-Jan-20, su
            $join.= " INNER JOIN wp_usermeta m1 ";
            $where_condition.= " AND m1.meta_key = 'is_barber' AND m1.meta_value=1 ";
            
            $sql = "SELECT DISTINCT p.ID, p.display_name $select_more FROM wp_users p LEFT JOIN wp_usermeta m ON p.ID = m.user_id $join WHERE p.ID > 0 $where_condition $having $order_by $pagination ";  
            $results = $wpdb->get_results($sql);
            $results_by_query = true;
        }
        $users = array();
       
        if(count($results)>0){
            foreach ($results as $key1 => $row1) {
                if($results_by_query == true){
                    $user_results = GetInfoUser($row1);
                    
                }else{
                    $user_results = GetInfoUser($row1->data);
                }
                $current_user_id = $row1->ID;
                $current_userdata = get_userdata($current_user_id);
                $current_user_roles = $current_userdata->roles;
                //Checking User Role is 'barber'
                if(in_array($param['role'],$current_user_roles)){
                    if($show_distance_in_result == false){
                        $user_results['distance'] = false;
                    }
                    
                    if(isset($user_id) && !empty($user_id) ){
                        
                        if($user_results['id'] != $user_id){
                            $users[] = $user_results;
                        }
                        
                    }else{
                        $users[] = $user_results;
                    }
                    
                    
                    
                }
            }  
        }
        $data['is_users'] = false;
        $data['msg']="No Beauticians found.";
         $data['q']=$sql;
        if (count($users) > 0) {
            $data['is_users'] = true;
            $data['rows'] = count($users);
            $data['list'] = $users;
            
            $data['msg']="There are some beauticians found.";
        }
        // $data['q'] = $sql;
        return new WP_REST_Response($data, 200);
    }



    function GetInfoUser($u){
        $row = array("id" => $u->ID, 'shop_name' => '', 'shop_logo' => "", "distance" => 0, 'open_close' => "", "amount_range" => "0");
        $shop_name = get_user_meta($u->ID, 'shop_name', true);
        $first_name = get_user_meta($u->ID, 'first_name', true);
        $last_name = get_user_meta($u->ID, 'last_name', true);
        $useravatar = get_user_meta($u->ID, 'wp_user_avatar', true);
        
        $map_lat = get_user_meta($u->ID, 'map_lat', true);
        $map_lng = get_user_meta($u->ID, 'map_lng', true);
    
        if ($useravatar) {
            $img = wp_get_attachment_image_src($useravatar, array('150', '150'), true);
            $user_avatar = $img[0];
        }else{
            $user_avatar = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
        }
    
        if($shop_name == ''){
            $shop_name = $first_name . " " . $last_name;
            if ($first_name == '' && $last_name == '') {
                $shop_name = $u->display_name;
            }
        }
        $getServicesRangeByuser = getServicesRangeByuser($u->ID);
        $working = GetWorkingDays($u->ID);
        $today = date('l');
        $open_close = "Closed";
    
        if ($today == "Saturday") {
            if ($working['is_saturday'] != false) {
                $open_close = "Open , " . $working['is_saturday']['nfrom_time'] . " - " . $working['is_saturday']['nto_time'];
            }
        }
    
        if ($today == "Sunday") {
            if ($working['is_sunday'] != false) {
                $open_close = "Open , " . $working['is_sunday']['nfrom_time'] . " - " . $working['is_sunday']['nto_time'];
            }
        }
    
        if ($today == "Monday") {
            if ($working['is_monday'] != false) {
                $open_close = "Open , " . $working['is_monday']['nfrom_time'] . " - " . $working['is_monday']['nto_time'];
            }
        }
    
        if ($today == "Tuesday") {
            if ($working['is_tuesday'] != false) {
                $open_close = "Open , " . $working['is_tuesday']['nfrom_time'] . " - " . $working['is_tuesday']['nto_time'];
            }
        }
    
        if ($today == "Wednesday") {
            if ($working['is_wednesday'] != false) {
                $open_close = "Open , " . $working['is_wednesday']['nfrom_time'] . " - " . $working['is_wednesday']['nto_time'];
            }
        }
    
        if ($today == "Thursday") {
            if ($working['is_thursday'] != false) {
                $open_close = "Open , " . $working['is_thursday']['nfrom_time'] . " - " . $working['is_thursday']['nto_time'];
            }
        }
    
        if ($today == "Friday") {
            if ($working['is_friday'] != false) {
                $open_close = "Open , " . $working['is_friday']['nfrom_time'] . " - " . $working['is_friday']['nto_time'];
            }
        }
        
        if(isset($u->distance) && !empty($u->distance)){
            $distance = number_format((float)$u->distance, 2, '.', '');
        }else{
            $distance = 0;
        }
        $row = array(
            "id"        => $u->ID,
            'map_lat'   =>(float)$map_lat,
            'map_lng'   =>(float)$map_lng,
            'shop_name' => $shop_name,
            'shop_logo' => $user_avatar,
            "distance"  => $distance." MI",
            'open_close'=> $open_close,
            "amount_range"=> "$" . number_format($getServicesRangeByuser['min'],2) . " - $" . number_format($getServicesRangeByuser['max'],2)
        );
        return $row;
    }

    function getServicesRangeByuser($user_id = null){
        global $wpdb;
        $array = array("min" => 0, "max" => 0);
        $rows = $wpdb->get_results("select price from wp_users_services where user_id= " . $user_id . " and status=1");
        $price = array();
        if (count($rows) > 0) {
            foreach ($rows as $r) {
                $price[] = $r->price;
            }
    
            $array["max"] = max($price);
            $array["min"] = min($price);
        }
        return $array;
    
    }

    function GetusersbyServiceId($service_id = null){
        $user_list = array();
        if ($service_id) {
            global $wpdb;
            $query = "SELECT wp_users.ID,wp_users.display_name, wp_users.user_nicename,wp_users_services.*  FROM wp_users INNER JOIN wp_usermeta  ON wp_users.ID = wp_usermeta.user_id  LEFT JOIN wp_users_services ON wp_users_services.user_id=wp_users.ID WHERE wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%barber%' AND wp_users_services.service_id='" . $service_id . "' ORDER BY wp_users.user_nicename";
            $user_list = $wpdb->get_results($query);
        }
        return $user_list;
    }

    function getServices($request){
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        global $wpdb;
        $preference_posts = GetAllServicesforuser();
        $data['list'] = $preference_posts;
        return new WP_REST_Response($data, 200);
    }

    function getbyUserServices($request){
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        $param = $request->get_params();
        $token = $param['token'];
        
    
        // when need to fetch services on appointment page for booking.
        $type = $param['type'];
        if ($type == "public") {
            $user_id = $token;
        } else {
            $user_id = GetMobileAPIUserByIdToken($token);
        }
        global $wpdb;
    
        if ($user_id) {
            $preference_posts = GetAllServicesforuser($user_id,$type);
            $data['list'] = $preference_posts;
            return new WP_REST_Response($data, 200);
        } else {
            $data["status"] = "error";
            $data["errormsg"] = "user token expired";
            $data["error_code"] = "user_expire";
            return new WP_REST_Response($data, 403);
        }
    }

    function GetAllServicesforuser($user_id = null,$type=NULL){
        global $wpdb;
        $args = array(
            'post_type' => 'barber_services',
            'posts_per_page' => -1
        );
        
        if(isset($user_id) && !empty($user_id)){
            $args['author__in'] = array(1,$user_id);
        }
        
        $all_posts = get_posts($args);
        $preference_posts = array();
        foreach ($all_posts as $key1 => $val1) {
            $checked = false;
            $RowService = array();
            if ($user_id) {
                $row = $wpdb->get_row("select *  from wp_users_services where user_id= " . $user_id . " and status=1 and  service_id=" . $val1->ID, ARRAY_A);
                if (count($row) > 0) {
                    $checked = true;
                    $RowService = $row;
                }
            }
            $thumbnail_id = get_post_meta($val1->ID, "_thumbnail_id", true);
            $img = wp_get_attachment_image_src($thumbnail_id,'medium', true);
            $cover = $img[0];
            if ($cover == '') {
                $cover = site_url("/wp-content/uploads/2020/03/placeholder.jpg");
            }
    
           if($type=="edit"){
            $preference_posts[] = array(
                'post_id' => $val1->ID,
                'post_title' => $val1->post_title,
                'post_author' => $val1->post_author,
                'checked' => $checked,
                'value' => $RowService,
                'cover' => $cover,
                'type'=>get_post_meta($val1->ID, 'type', true)
            );
           }elseif($type=="private" || $type=="public"){
             if($checked){ 
                  $preference_posts[] = array(
                    'post_id' => $val1->ID,
                    'post_title' => $val1->post_title,
                    'post_author' => $val1->post_author,
                    'checked' => $checked,
                    'value' => $RowService,
                    'cover' => $cover,
                    'type'=>get_post_meta($val1->ID, 'type', true)
                );
             }
           }else{
               $preference_posts[] = array(
                    'post_id' => $val1->ID,
                    'post_title' => $val1->post_title,
                    'post_author' => $val1->post_author,
                    'checked' => $checked,
                    'value' => $RowService,
                    'cover' => $cover,
                    'type'=>get_post_meta($val1->ID, 'type', true)
                );
           }
        }
        return $preference_posts;
    }

    function addServices($request){
    
        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "");
        $param = $request->get_params();
        $token = $param['token'];
    
        $user_id = GetMobileAPIUserByIdToken($token);
        if ($user_id) {
            global $wpdb;
            $data = $param;
            $s = $param['service'];
            $p = $param['price'];
            $t = $param['time'];
            $ty = $param['type'];
            $user_services = GetuserServices($user_id);
    
            //$data['user_services']=$user_services;
            $keyTcheck = array();
            foreach ($s as $key => $value) {
                if ($p[$key] > 0 && $value == true) {
                    $keyTcheck[$key] = 1;
                    $row_count = $wpdb->get_var("select count(*) as row_count from wp_users_services where service_id= " . $key . " and user_id=" . $user_id);
                    if ($row_count > 0) {
                        $update = array('price' => $p[$key], "time" => $t[$key],"type"=>$ty[$key], 'status' => 1);
                        $wpdb->update("wp_users_services", $update, array('service_id' => $key, 'user_id' => $user_id));
                    } else {
                        $insert = array("user_id" => $user_id, 'service_id' => $key,"type"=>$ty[$key], 'price' => $p[$key], 'status' => 1, "time" => $t[$key]);
                        $wpdb->insert("wp_users_services", $insert);
                    }
    
                }
            }
    
            // Delete those which is not selected
            if (count($user_services) > 0) {
                foreach ($user_services as $us) {
                    $serVice_id = $us['service_id'];
                    if (!array_key_exists($serVice_id, $keyTcheck)) {
                        $update = array('status' => 0);
                        $wpdb->update("wp_users_services", $update, array('service_id' => $serVice_id, 'user_id' => $user_id));
                        // $wpdb->delete('wp_users_services',array("service_id"=>$serVice_id,"user_id"=>$user_id));
                    }
                }
            }
    
            $preference_posts = GetAllServicesforuser($user_id);
            $data['list'] = $preference_posts;
            $data['msg'] = "services updated successfully.";
            return new WP_REST_Response($data, 200);
    
        } else {
            $data["status"] = "error";
            $data["errormsg"] = "user token expired";
            $data["error_code"] = "user_expire";
            return new WP_REST_Response($data, 403);
        }
    }

    function GetuserServices($user_id){
        global $wpdb;
        $row = array();
        $row = $wpdb->get_results("select * from wp_users_services where user_id= " . $user_id, ARRAY_A);
        if (count($row) > 0) {
            return $row;
        }
        return $row;
    
    }

    function manually_change_password($request){
        global $wpdb;
        $data = array(
            "status" => "ok",
            "errormsg" => "",
            'error_code' => "",
        );
        $param = $request->get_params();
        $token = $param['token'];
        $user_id = GetMobileAPIUserByIdToken($token);
        $oldpass = $param['oldPassword'];
        $new_pass = $param['newPassword'];
        $user = get_userdata($user_id);
        $check_password = wp_check_password($oldpass, $user->data->user_pass, $user_id);
        $update_password = array();
        if ($check_password) {
            if (!empty($new_pass)) {
                $udata['ID'] = $user->data->ID;
                $udata['user_pass'] = $new_pass;
                $uid = wp_update_user($udata);
                if ($uid) {
                    $update_password['success_msg'] = "The password has been updated successfully";
                    $update_password['success'] = "succeed";
                    //unset($passdata);
                    return new WP_REST_Response($update_password, 200);
                } else {
                     $update_password['error_msg'] = "Something went wrong! please try again";
                    $update_password['error'] = "errored";
                    return new WP_REST_Response($update_password, 403);
                }
            }
        } else {
            $update_password['error_msg'] = "Old Password doesn't match the existing password";
            $update_password['error'] = "errored";
            $update_password['status'] = "false";
        }
    }

    function getProfile($request){
        global $customHelperObj;
        $data = array("status" => "ok","errormsg" => "",'error_code' => "", );
        $param = $request->get_params();
        $token = $param['token'];
        if ($param['type'] == "public") {
            $userId = GetMobileAPIUserByIdToken($token);
            $user_id = $param['user_id'];
            $data['isBlocked'] = $customHelperObj->isBlocked($userId,$user_id);
        } else {
            $user_id = GetMobileAPIUserByIdToken($token);
        }
        
        if ($user_id) {
            $useravatar = get_user_meta($user_id, 'wp_user_avatar', true);
            if ($useravatar) {
                // $img = wp_get_attachment_image_src($useravatar, array('300', '300'), true);
                $img = wp_get_attachment_image_src($useravatar, array('300', '300'), true);
    
                $user_avatar = $img[0];
            } else {
                $user_avatar = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
            }
            $user = get_userdata($user_id);
            
            if ($useravatar) {
                $img = wp_get_attachment_image_src($useravatar, array('300', '300'), true);
                $data['user_avatar'] = $img[0];
            } else {
                $data['user_avatar'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
            }
    
            $data['userImage'] = $user_avatar;
            $data['userImage_id'] = $useravatar;
            
            $role = 'customer';
            if (in_array('business', (array) $user->roles)) {
                $role = 'business';
            } else {
                $role = $user->roles[0];
            }
    
            $data['role'] = $role;
            $username = $user->user_login;
            $user_email = $user->user_email;
            $first_name = get_user_meta($user_id, 'first_name', true);
            $last_name = get_user_meta($user_id, 'last_name', true);
            $phone = get_user_meta($user_id, 'phone', true);
    
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['first_name'] = $first_name;
            $data['last_name'] = $last_name;
            $data['email'] = $user_email;
            
            $my_time_zone = get_user_meta($user_id, 'my_time_zone',true);
            
            if(empty($my_time_zone) || $my_time_zone == '' || $my_time_zone == null){
                $my_time_zone = 'America/New_York';
            }
            
            $data['my_time_zone'] = $my_time_zone;
            
            if($role == 'customer'){
                $data['phone']                  = $phone;
                $data['username']               = get_user_meta($user_id, 'nickname', true);
                $data['notiﬁcations_type']      = get_user_meta($user_id, 'notiﬁcations_type', true);
                $data['notifications_status']   = get_user_meta($user_id, 'notifications_status',true);
                
                
                $data['is_gender']              = get_user_meta($user_id, 'is_gender', true);
                $data['gender']                 = get_user_meta($user_id, 'gender', true);
                
                $data['is_education']           = get_user_meta($user_id, 'is_education', true);
                $data['education']              = get_user_meta($user_id, 'education', true);
                
                $data['is_income']              = get_user_meta($user_id, 'is_income', true);
                $data['income']                 = get_user_meta($user_id, 'income', true);
                
                $data['is_marraige_status']     =  get_user_meta($user_id, 'is_marraige_status', true);
                $data['marraige_status']        =  get_user_meta($user_id, 'marraige_status', true);
                
                $data['is_home_type']         = get_user_meta($user_id, 'is_home_type', true);
                $data['home_type']         = get_user_meta($user_id, 'home_type', true);
                

                
                $data['is_dob']         = get_user_meta($user_id, 'is_dob', true);
                $dob = get_user_meta($user_id, 'dob', true);
                if(isset($dob) && !empty($dob)){
                    $dob = date_format(date_create($dob),"F d,Y");
                    $data['dob']   = date('F d,Y',strtotime($dob));
                }
                else{
                    $data['dob'] = '';
                }

                $data['is_transportation_method'] = get_user_meta($user_id, 'is_transportation_method', true);
                $data['transportation_method'] = get_user_meta($user_id, 'transportation_method', true);
                
                $data['is_children_age_range'] = get_user_meta($user_id, 'is_children_age_range', true);
                $data['children_age_range'] = array(
                    'lower' =>  get_user_meta($user_id, 'children_lower_age', true),
                    'upper' =>  get_user_meta($user_id, 'children_upper_age', true)
                );
                    
                    
                    
                    
                $categories = array();
                $data['categories']        =  $customHelperObj->getUserCategories($user_id);;
                $data['user_display_name'] = ucfirst($user->display_name);
                if (!empty($first_name)) {
                    $data['user_display_name'] = ucfirst($first_name);
                }
                $data['working'] = GetWorkingDays($user_id);
                $data['locations'] = $customHelperObj->userLocations($user_id);
                $data['follow_me'] = get_user_meta($user_id, 'follow_me', true);
            }
            else{
                $data['phone'] = $phone;
                $data['business_lat']        = get_user_meta($user_id, 'lat', true);
                $data['business_long']        = get_user_meta($user_id, 'long', true);
                $data['isVerified'] =          get_user_meta($user_id, 'is_verified_by_admin', true);
                $data['catupdate'] =           get_user_meta($user_id, 'catupdate',true);
             
                $data['business_name']          = get_user_meta($user_id, 'business_name', true);
                $data['business_address']       = get_user_meta($user_id, 'business_address', true);
                $data['business_phone_number']  = get_user_meta($user_id, 'business_phone_number', true);
                $data['business_description']   = get_user_meta($user_id, 'business_description', true);
                $data['business_radius']        = get_user_meta($user_id, 'radius',true);
                $data['business_email']         = get_user_meta($user_id, 'business_email',true);
                $data['timezone']               =  get_user_meta($user_id, 'time_zone',true);
                
                
                $data['facebook_link']  = get_user_meta($user_id, 'facebook_link', true);
                $data['instagram_link'] = get_user_meta($user_id, 'instagram_link', true);
                $data['youtube_link']   = get_user_meta($user_id, 'youtube_link', true);
                $data['twitter_link']   = get_user_meta($user_id, 'twitter_link', true);
                
                $data['available_messages'] =  $customHelperObj->getAvailableMessages($user_id);
                $data['isBuyTest'] =  $customHelperObj->isBuyTest($user_id);
                
                //business_profile
                $business_profile = get_user_meta($user_id, 'business_profile', true);
                if(isset($business_profile) && !empty($business_profile)){
                    $business = wp_get_attachment_image_src($business_profile, array('300', '300'), true);
                    $data['business_profile'] = $business[0];
                }else{
                    $data['business_profile'] = site_url().'/wp-content/uploads/2022/04/contact.png';
                }
            
                $urlStr = get_user_meta($user_id, 'business_website', true);
                      
                if($urlStr!=''){
                    $parsed = parse_url($urlStr);
                    if (empty($parsed['scheme'])) {
                        $urlStr = 'http://' . ltrim($urlStr, '/');
                    }
                }else{
                    $urlStr='';
                }
                
                $Categories = $customHelperObj->getUserCategories($user_id);
                $data['token'] = $param['token'];
                $data['categories']= $Categories;
                $data['business_website'] = $urlStr;
            }
            
            
            
            $data['token'] = $token;

            return new WP_REST_Response($data, 200);
        } else {
            $data["status"] = "error";
            $data["errormsg"] = "user not found.";
            $data["error_code"] = "user_expire";
            return new WP_REST_Response($data, 403);
        }
    }

    function create_contact($request){
        $data = array(
            "status" => "ok",
            "errormsg" => "",
            'error_code' => "",
        );
        $param = $request->get_params();
    
        $token = $param['token'];
        $user_id = GetMobileAPIUserByIdToken($token);
        if ($user_id) {
            $new_post = array(
                'post_title' => "Request From " . $param['name'],
                'post_content' => $param['post_content'],
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'services_request',
                'post_category' => array(0),
            );
    
            $post_id = wp_insert_post($new_post);
    
            if ($post_id) {
                $data['post'] = $post_id;
                update_post_meta($post_id, "name", $param['name']);
                update_post_meta($post_id, "email", $param['email']);
                update_post_meta($post_id, "contact_number", $param['contact_number']);
                update_post_meta($post_id, "message", $param['post_content']);
                update_post_meta($post_id, "service", $param['service']);
                return new WP_REST_Response($data, 200);
            } else {
                $data['post'] = $new_post;
                $data['errormsg'] = "Conatct not created, something went wrong.";
                return new WP_REST_Response($data, 403);
            }
        } else {
            $data = array(
                "status" => "error",
                "errormsg" => "user token expired",
                'error_code' => "user_expire",
            );
        }
        return new WP_REST_Response($data, 403);
    }

    function submitComment($request){
        global $wpdb;
        $data = array(
            "status" => "ok",
            "errormsg" => "",
            'error_code' => "",
        );
    
        $param = $request->get_params();
    
        $token = $param['token'];
        $post_id = $param['post_id'];
        $comment = $param['comment'];
    
        $user_id = GetMobileAPIUserByIdToken($token);
    
        if (!$user_id) {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid token');
            $data['error_code'] = "invalid_token";
            return new WP_REST_Response($data, 403);
        }
    
        // get user by user id
        $user_temp = get_user_by('ID', $user_id);
        $user = $user_temp->data;
    
        if (empty($user)) {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid user');
            $data['error_code'] = "invalid_user";
            return new WP_REST_Response($data, 403);
        }
    
        // check if comment and post id exist
        if ($comment == '' || $post_id == '') {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid request');
            $data['error_code'] = "invalid_request";
            return new WP_REST_Response($data, 403);
        }
    
        $args = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $user->user_login,
            'comment_author_email' => $user->user_email,
            'comment_author_url' => 'http://',
            'comment_content' => $comment,
            'comment_type' => '',
            //'comment_parent' => 0,
            'user_id' => $user->ID,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_date' => current_time('mysql'),
            'comment_approved' => 1,
        );
    
        if (wp_insert_comment($args)) {
            $data = array(
                "status" => "ok",
                "msg" => "comment submitted successfully",
                "errormsg" => "",
                'error_code' => "",
            );
    
            return new WP_REST_Response($data, 200);
        } else {
            $data['status'] = "error";
            $data['errormsg'] = __('Comment could not be submitted');
            $data['error_code'] = "invalid_request";
            return new WP_REST_Response($data, 403);
        }
    }

    function getComment($request){
        $data = array(
            "status" => "ok",
            "errormsg" => "",
            'error_code' => "",
        );
    
        $param = $request->get_params();
        $post_id = $param['post_id'];
    
        // get all comment of current post
        $comments = get_comments(array('post_id' => $post_id, 'order' => 'ASC'));
        $comments_arr = array();
        foreach ($comments as $comment) {
            if ($comment->comment_parent == 0) {
                $temp = array();
                $temp['id'] = $comment->comment_ID;
                $temp['comment_author'] = $comment->comment_author;
                $temp['comment_date'] = $comment->comment_date;
                $temp['content'] = $comment->comment_content;
                $child = get_child($temp);
                if ($child) {
                    $temp['child'] = $child;
                }
                $comments_arr[] = $temp;
            }
        }
        return new WP_REST_Response($comments_arr, 200);
    }

    function replyComment($request){
        $data = array(
            "status" => "ok",
            "errormsg" => "",
            'error_code' => "",
        );
    
        $param = $request->get_params();
    
        $token = $param['token'];
        $post_id = $param['post_id'];
        $parent_cid = $param['parent_cid'];
        $reply = $param['reply'];
    
        $user_id = GetMobileAPIUserByIdToken($token);
    
        if (!$user_id) {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid token');
            $data['error_code'] = "invalid_token";
            return new WP_REST_Response($data, 403);
        }
    
        // get user by user id
        $user_temp = get_user_by('ID', $user_id);
        $user = $user_temp->data;
    
        if (empty($user)) {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid user');
            $data['error_code'] = "invalid_user";
            return new WP_REST_Response($data, 403);
        }
    
        // check if comment and post id exist
        if ($parent_cid == '' || $post_id == '' || $reply == '') {
            $data['status'] = "error";
            $data['errormsg'] = __('Invalid request');
            $data['error_code'] = "invalid_request";
            return new WP_REST_Response($data, 403);
        }
    
        $args = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $user->user_login,
            'comment_author_email' => $user->user_email,
            'comment_author_url' => 'http://',
            'comment_content' => $reply,
            'comment_type' => '',
            'comment_parent' => $parent_cid,
            'user_id' => $user->ID,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_date' => current_time('mysql'),
            'comment_approved' => 1,
        );
    
        if (wp_insert_comment($args)) {
            $data = array(
                "status" => "ok",
                "msg" => "comment submitted successfully",
                "errormsg" => "",
                'error_code' => "",
            );
    
            return new WP_REST_Response($data, 200);
        } else {
            $data['status'] = "error";
            $data['errormsg'] = __('Comment could not be submitted');
            $data['error_code'] = "invalid_request";
            return new WP_REST_Response($data, 403);
        }
    }

    function get_child($comment){
        // child comment
        $args = array('parent' => $comment['id'], 'order' => 'ASC');
        $child_cmts = get_comments($args);
    
        $child_arr = array();
        if ($child_cmts) {
            foreach ($child_cmts as $child_cmt) {
                $temp_child = array();
                $temp_child['id'] = $child_cmt->comment_ID;
                $temp_child['comment_author'] = $child_cmt->comment_author;
                $temp_child['comment_date'] = $child_cmt->comment_date;
                $temp_child['content'] = $child_cmt->comment_content;
                $child_arr[] = $temp_child;
            }
        }
        return $child_arr;
    }

    function GetWorkingDays($user_id){
        global $wpdb;
        $working = array();
        $working['is_sunday']    = false;
        $working['is_monday']    = false;
        $working['is_tuesday']   = false;
        $working['is_wednesday'] = false;
        $working['is_thursday']  = false;
        $working['is_friday']    = false;
        $working['is_saturday']  = false;
        if ($user_id) {
            for ($i = 1; $i <= 7; $i++) {
                
                if ($i == 1) {
                    $working['is_monday']    = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 2) {
                    $working['is_tuesday']   = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 3) {
                    $working['is_wednesday'] = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 4) {
                    $working['is_thursday']  = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 5) {
                    $working['is_friday']    = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 6) {
                    $working['is_saturday']  = GetWorkingDaybyday($user_id, $i);
                }
                if ($i == 7) {
                    $working['is_sunday']    = GetWorkingDaybyday($user_id, $i);
                }
            }
        }
        return $working;
    }

    function GetWorkingDaybyday($user_id, $i){
        $day = array();
        global $wpdb;
        // $result = $wpdb->get_results("select * from wp_users_working_days where user_id='" . $user_id . "' AND	status = true AND day=". $i  ."ORDER BY `working_id` DESC");
        $result = $wpdb->get_results("SELECT * FROM `wp_users_working_days` where user_id='" . $user_id . "' AND	status = true AND day=".$i."  ORDER BY `wp_users_working_days`.`working_id` ASC");
        if(count($result) > 0) {
            foreach($result as $row){
                $date    = new DateTime($row->from_time);
                $date1   = new DateTime($row->to_time);
                $day[]   = array('start_time' => $row->from_time, 'end_time' => $row->to_time, 'nfrom_time'=> $date->format('h:i a'), 'nto_time'=>$date1->format('h:i a') ,'message'=> $row->message, 'time' => ($row->time)?json_decode($row->time):array('lower' => 0, 'upper' => 0));
            }
            
            return $day;
        }else{
            return false;
        }
    }

  function updateProfile($request){
        global $wpdb,$customHelperObj;
        $data = array("status" => "ok","errormsg" => "",'error_code' => "",);
        $param = $request->get_params();
        $token = $param['token'];
        $user_id = GetMobileAPIUserByIdToken($token);
        if ($user_id) {
           
           
            if($param['emailVerfiction'] == true && isset($param['email']) && !empty($param['email'])){
                
                if(email_exists($param['email'])){
                    $data['status'] = "error";
                    $data['errormsg'] = __('Account exists with this email or username.');
                    $data['error_code'] = "user_already";
                    return new WP_REST_Response($data, 403);
                }
                
                $wpdb->update("wp_users",array("user_login"=>$param['email'],"user_email"=>$param['email'], "display_name"=>$param['email'], "user_nicename"=>$param['email']),array("ID"=>$user_id));
                update_user_meta($user_id, 'email', $param['email']);
                $data['msgs'] = "Logout";
                $data['msg'] = "Email has been updated.";
            }
            
            update_user_meta($user_id, 'follow_me', $param['follow_me']);
            
            if(isset($param['first_name']) && !empty($param['first_name'])){
                 update_user_meta($user_id, 'first_name', $param['first_name']);
            }
            
            if(isset($param['last_name']) && !empty($param['last_name'])){
                update_user_meta($user_id, 'last_name', $param['last_name']);
            }
        
            
            if(isset($param['is_dob']) && $param['is_dob']==true){
                update_user_meta($user_id, 'is_dob', '1');
            }
            else{
                update_user_meta($user_id, 'is_dob', '0');
            }
            update_user_meta($user_id, 'dob', $param['dob']);


            if(isset($param['is_gender']) && $param['is_gender']==true){
                update_user_meta($user_id, 'is_gender', '1');
            }
            else{
                update_user_meta($user_id, 'is_gender', '0');
            }
            update_user_meta($user_id, 'gender', $param['gender']);
            
            
            if(isset($param['is_income']) && $param['is_income']==true){
                update_user_meta($user_id, 'is_income', '1');
            }
            else{
                update_user_meta($user_id, 'is_income', '0');
            }
            update_user_meta($user_id, 'income', $param['income']);
            
            
            if(isset($param['is_marraige_status']) && $param['is_marraige_status']==true){
                update_user_meta($user_id, 'is_marraige_status', '1');
            }
            else{
                update_user_meta($user_id, 'is_marraige_status', '0');
            }
            update_user_meta($user_id, 'marraige_status', $param['marraige_status']);
            
            
            if(isset($param['is_home_type']) && $param['is_home_type']==true){
                update_user_meta($user_id, 'is_home_type', '1');
            }
            else{
                update_user_meta($user_id, 'is_home_type', '0');
            }
            update_user_meta($user_id, 'home_type', $param['home_type']);
            
     
            if(isset($param['is_education']) && $param['is_education']==true){
                update_user_meta($user_id, 'is_education', '1');
            }
            else{
                update_user_meta($user_id, 'is_education', '0');
            }
            update_user_meta($user_id, 'education', $param['education']);

     
            if(isset($param['is_transportation_method']) && $param['is_transportation_method']==true){
                update_user_meta($user_id, 'is_transportation_method', '1');
            }
            else{
                update_user_meta($user_id, 'is_transportation_method', '0');
            }
            update_user_meta($user_id, 'transportation_method', $param['transportation_method']);
            
  
            if(isset($param['is_children_age_range']) && $param['is_children_age_range']==true){
                update_user_meta($user_id, 'is_children_age_range', '1');
            }
            else{
                update_user_meta($user_id, 'is_children_age_range', '0');
            }
            $children_age_range = $param['children_age_range'];
            update_user_meta($user_id, 'children_lower_age', $children_age_range['lower']);
            update_user_meta($user_id, 'children_upper_age', $children_age_range['upper']);
            
          
            
            
            
            
            if(isset($param['notiﬁcations_type']) && !empty($param['notiﬁcations_type'])){
                update_user_meta($user_id, 'notiﬁcations_type', $param['notiﬁcations_type']);
            }
            
            // if($param['day_monday']){
                $dayInfo = $param['day_monday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,1,$param['is_monday']);
            // }
            
            // if($param['day_tuesday']){
                $dayInfo = $param['day_tuesday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,2,$param['is_tuesday']);
            // }
            
            // if($param['day_wednesday']){
                $dayInfo = $param['day_wednesday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,3,$param['is_wednesday']);
            // }
            
            // if($param['day_thursday']){
                $dayInfo = $param['day_thursday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,4,$param['is_thursday']);
            // }
            
            // if($param['day_friday']){
                $dayInfo = $param['day_friday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,5,$param['is_friday']);
            // }
            
            // if($param['day_saturday']){
                $dayInfo = $param['day_saturday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,6,$param['is_saturday']);
            // }
            
            // if($param['day_sunday']){
                $dayInfo = $param['day_sunday'];
                $customHelperObj->updateWorkingDay($user_id,$dayInfo,7,$param['is_sunday']);
            // }
            
            $categories = $param['categories'];
            $customHelperObj->saveUserCategories($user_id,$param['categories']);
            
            if(isset($param['removeCategoryId']) && count($param['removeCategoryId'])) {
                $implode = implode(', ', $param['removeCategoryId']);
                $wpdb->query("DELETE FROM `wp_subcat_working` WHERE user_id = '$user_id' and cat_id IN ($implode)");
            }
            
            if(isset($param['categoriesTime']) && !empty($param['categoriesTime'])){
                $categoriesTime = $param['categoriesTime'];
               
                if(count($categoriesTime) > 0){
                  foreach($categoriesTime as $catTime){
                      
                      
                      $category_ids = array_column($catTime, 'term_id');
                      $category_days = array_column($catTime, 'day');
                      
                      if(count($category_ids)){
                          $category_ids = implode(', ', $category_ids);
                          $category_days = implode(', ', $category_days);
                          $sql = "DELETE FROM `wp_subcat_working` WHERE `user_id` = $user_id AND `cat_id` IN ($category_ids) AND `day` NOT IN ($category_days)";
                          $wpdb->query($sql);
                      }
                      
                      foreach($catTime as $catday){
                          $user_id    = $user_id;
                          $cat_id     = $catday['term_id'];
                          $form_time  = $catday['fromTime'];
                          $to_time    = $catday['toTime'];
                          $day        = $catday['day'];
                          $message    = $catday['message'];
                          $datname   = $catday['dayname'];
                          $subtimelower   = $catday['subTime']['lower'];
                          $suntimeupper  = $catday['subTime']['upper'];
                          
                          
                          $customHelperObj->insertSubCatDayTime($user_id,$cat_id,$to_time,$form_time,$message,$day,$datname,$subtimelower,$suntimeupper);
                          
                        }
                    }
                }
            }
            
               
            // if(isset($param['phone']) && !empty($param['phone']) && $param['numberVerfication'] == true){
            if(isset($param['phone']) && !empty($param['phone'])){
                $checkPhone = $customHelperObj->checkPhone($param['phone']);
                if(isset($checkPhone) && !empty($checkPhone)){ 
                    if( (int)$checkPhone == (int)$user_id){
                        update_user_meta($user_id, 'phone', $param['phone']); 
                    }else{
                        $data["status"] = "error";
                        $data["errormsg"] = "Phone number already taken";
                        $data["error_code"] = "phone_alredy_exist";
                        return new WP_REST_Response($data, 403);
                    }
                }
                else{
                   update_user_meta($user_id, 'phone', $param['phone']); 
                }
            }
            else{
                update_user_meta($user_id, 'phone', ''); 
            }
            
            
            
            update_user_meta($user_id, 'is_update',1);
            $data['msg'] = "Profile has been updated.";
            return new WP_REST_Response($data, 200);
        }
        else{
            $data["status"] = "error";
            $data["errormsg"] = "user token expired";
            $data["error_code"] = "user_expire";
            return new WP_REST_Response($data, 403);
        }
    }



function facebook_login($request)
{

    $username = $request->get_param('username');
    $email = $request->get_param('email');
    $fbname = $request->get_param('fbname');
    $facebook_id = $request->get_param('facebook_id');

    if (!is_email($email)) {
        $email = $facebook_id . "_facebook_random@gmail.com";
    }

    $userloginFlag = true;
    $user_id = username_exists($username);
    if (!$user_id and email_exists($email) == false) {
        $userloginFlag = false;
    }
    // check if facebookID exists
    $users_check_facebookID = get_users(
        array(
            'meta_key' => 'facebook_id',
            'meta_value' => $facebook_id,
        )
    );

    if (count($users_check_facebookID) == 0) {
        $userloginFlag = false;
    } else {
        $user_id = $users_check_facebookID[0]->data->ID;
    }

    if ($userloginFlag == true) {
        $data = fb_check_login($user_id, $facebook_id);
    } else {
        $user_id = FBSignup($email, $username, $fbname, $facebook_id);
        $data = fb_check_login($user_id);
    }

    if (count($data) > 0) {
        return new WP_REST_Response($data, 200);
    } else {
        $res = array("status" => 'error');
        return new WP_REST_Response($res, 403);
    }

}

// Facebook Signup function
function FBSignup($user_email, $user_name, $first_name, $facebook_id)
{

    $user_id = username_exists($user_name);
    if (!$user_id and email_exists($user_email) == false) {
        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
        $user_id = wp_create_user($user_name, $password, $user_email);
        $user = new WP_User($user_id);
        $user->set_role('author');
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'nickname', $first_name);
        update_user_meta($user_id, 'facebook_id', $facebook_id);
        return $user_id;
    } else {
        return $user_id;
    }

}

// Facebook Login function
function fb_check_login($user_id, $facebook_id = null)
{
    $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;

    /** Try to authenticate the user with the passed credentials*/
    $user = get_userdata($user_id);
    if (count($user) > 0) {
        if ($facebook_id) {
            update_user_meta($user_id, 'facebook_id', $facebook_id);
        }

        /** Valid credentials, the user exists create the according Token */
        $issuedAt = time();
        $notBefore = apply_filters('jwt_auth_not_before', $issuedAt, $issuedAt);
        $expire = apply_filters('jwt_auth_expire', $issuedAt + (DAY_IN_SECONDS * 7), $issuedAt);

        $token = array(
            'iss' => get_bloginfo('url'),
            'iat' => $issuedAt,
            'nbf' => $notBefore,
            'exp' => $expire,
            'data' => array(
                'user' => array(
                    'id' => $user->data->ID,
                ),
            ),
        );

        /** Let the user modify the token data before the sign. */
        $token = JWT::encode(apply_filters('jwt_auth_token_before_sign', $token, $user), $secret_key);

        /** The token is signed, now create the object with no sensible user data to the client*/
        $data = array(
            'token' => $token,
            'user_email' => $user->data->user_email,
            'user_nicename' => $user->data->user_nicename,
            'user_display_name' => $user->data->display_name,
        );

        /** Let the user modify the data before send it back */
        $data = apply_filters('jwt_auth_token_before_dispatch', $data, $user);
        return $data;
    }

}

function validate_token($request)
{
    $param = $request->get_params();
    $token = $param['token'];
    $user_id = GetMobileAPIUserByIdToken($token);
    if ($user_id) {
        $res['status'] = "ok";
        return new WP_REST_Response($res, 200);
    } else {
        $res['status'] = "error";
        $res['msg'] = "Your session expired, please login again";
        return new WP_REST_Response($res, 200);
    }

}

// Create new user
function MobileApiMakeNewAuthor($request)
{
    global $customHelperObj;
    $param = $request->get_params();
    // $name = $param['userName'];
    $first_name = $param['first_name'];
    $email = $param['email'];
    // $userName = $param['userName'];
    $last_name = $param['last_name'];
    $business_name = $param['business_name'];
    $password = $param['password'];
    $role = $param['role'];
    $phone = $param['phone'];
    $name = $first_name.' '.$last_name;
    $verified_by_admin = no;
    //  return new WP_REST_Response($param['jw_auth_sec'], 200);

    // JWT_AUTH_SECRET_KEY define in wp-config
    if ($param['jw_auth_sec'] != JWT_AUTH_SECRET_KEY) {
        $data['status'] = "error";
        $data['errormsg'] = __('cheating----.');
        $data['error_code'] = "token_error";
        return new WP_REST_Response($data, 403);
    }

    if (!is_email($email)) {
        $data['status'] = "error";
        $data['errormsg'] = __('This is not a Valid Email.');
        $data['error_code'] = "invalid_email";
        return new WP_REST_Response($data, 403);
    }

    $user_id = username_exists($email);
    if ($passowrd == " ") {
        $data['status'] = "error";
        $data['errormsg'] = __('Please provide password.');
        $data['error_code'] = "password_blank";
        return new WP_REST_Response($data, 403);
    }
    if (!$user_id and email_exists($email) == false) {
        //$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $user_id = wp_create_user($email, $password, $email);
        $user = new WP_User($user_id);

        $user->set_role($role);
        
        if($role=="business"){
            if( isset($param['image']) && $param['image'] != ''){
            $attachment_id =  $customHelperObj->uploadImage($param['image'],"profile".$user_id);
            update_user_meta($user_id, 'wp_user_avatar',$attachment_id);
            }
            update_user_meta($user_id, 'is_business',1);
            update_user_meta($user_id,'is_verified_by_admin',$verified_by_admin);
            update_user_meta($user_id, 'business_name',$business_name);
            update_user_meta($user_id,'catupdate','no');
        }else{
            update_user_meta($user_id, 'is_business',0);
        }

        update_user_meta($user_id,'my_time_zone','America/New_York');
        update_user_meta($user_id, 'nicename', $first_name);
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'emailVerified', 'no');
        
        if(isset($param['opt_in_sms']) && ($param['opt_in_sms'] == true || $param['opt_in_sms'] == 'true')){
            update_user_meta($user_id, 'opt_in_sms', $param['opt_in_sms']);
            update_user_meta($user_id, 'is_mobile_verified', 'yes');
        }
        else{
            update_user_meta($user_id, 'opt_in_sms', false);
            update_user_meta($user_id, 'is_mobile_verified', 'no');
        }
        
        
        update_user_meta($user_id, 'notiﬁcations_type', 'push_notification');

        // update_user_meta($user_id,"wp_user_avatar",$userImg);
        
                $year = date('Y');
                $message = '<div style="background-color: #03254a; display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px; margin-bottom:20px;">
                    <img src="'.site_url().'/wp-content/uploads/2024/02/icon.png" style="background-color: #03254a; height: 70px; width: 70px; margin:auto; padding: 10px;"> 
                </div>';
                if($role=="business"){
                    
                //   $message .= 'Welcome <strong>'.ucwords($name).'</strong>, <br/>';
                //   $message .= 'Thank you for choosing Lightningbug for your advertising needs <br/>';
                //   $message .= 'Please confirm your email by clicking this link:  <a style="color: #008CBA; text-align: center; cursor: pointer; " > <b>Confirm</b></a><br/><br/>';
                //   $message .= 'We will begin your approval process for advertising on Lightningbug and should have it ready within 48 hours.  <br/><br/>';
                //   $message .= 'In the meantime, you can set up your profile – what you want your clients to see about your business. Get to know how to set up deals and send them out using our tutorial in the App. <br/><br/>';
                //   $message .= 'If you have any questions or comments, please email <a mailto:href="info@lightningbug.info">info</a> <br/> ';
                //   $message .= 'Or see our Frequently Asked Questions <br/><br/>';
                //   $message .= 'We are looking forward to working with you. <br/><br/>';
                //   $message .= 'Sincerely, <br/>';
                //   $message .= 'Thank you <br/><br/>';
                  
                  $message .= 'Welcome <strong>'.ucwords($name).'</strong>, to Lightning Bug!<br/><br/>';
                  $message .= 'Please complete your business profile in the app before you send out awesome deals. The more complete your information, the more targeted your deals can be. This makes sure you reach your customer profile.<br/><br/>';
                  $message .= 'You can also get to know how to set up deals and send them out using our tutorial <br/><br/>';
                  $message .= 'Need help or have questions? Please see our FAQ in the app or on our website https://www.lightningbug.info.<br/>';
                  $message .= 'Or, contact mailto:info@lightningbug.info <br/><br/>';
                  $message .= 'If you have any questions or comments, please email <a mailto:href="info@lightningbug.info">info</a> <br/> ';
                  $message .= 'Or see our Frequently Asked Questions <br/><br/>';
                  $message .= 'We are looking forward to working with you. <br/><br/>';
                  $message .= 'Thank you,<br/>';
                  $message .= 'The Lightning Bug team<br/><br/>';
                  
                  
                  
                  $messages = '<div style="background-color: #03254a; display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px; margin-bottom:20px;">
                             <img src="'.site_url().'/wp-content/uploads/2024/02/icon.png" style="background-color: #03254a; height: 70px; width: 70px; margin:auto; padding: 10px;"> 
                        </div>';
                  $messages .= 'A new advertiser has been registered, below are the user details: <br/><br/>';
                  $messages .= sprintf(__('Username: %s'), $name) . "<br/>";
                  $messages .= sprintf(__('Useremail : %s'), $email) . "<br/>";
                  $messages .= sprintf(__('Phone Number : %s'), $phone) . "<br/><br/>";
                //   $messages .= '<a style="color: #008CBA; text-align: center; cursor: pointer;border: 1px solid; padding: 9px; border-radius: 5px; text-decoration: none;" href="https://lightningbug.betaplanets.com/wp-admin/users.php" > <b>Approve</b></a><br/><br/>';
                  $messages .= '<br/><br/>';
                  $messages .='<footer style="background-color: #03254a;padding: 5px;">
                    <h3 style="text-align: center; margin:0px; font-size: 14px;color:#fff;"><i class="fa fa-copyright" aria-hidden="true"></i> '.$year.' All Rights Reserved</h3>
                </footer>';
                  $admail = $customHelperObj->adminMail($messages);
                  
                
                    //Welcome Admin Mail
                        $welcome_admin = get_field('admin_welcome_email', 'options');
                        $admin_headers = array('Content-Type: text/html; charset=UTF-8');
                        $admin_title = 'New Advertiser needs Approval - Lightning Bug';
                        
                        $admin_messages = '<div style="background-color: #03254a; display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px; margin-bottom:20px;">
                                 <img src="'.site_url().'/wp-content/uploads/2024/02/icon.png" style="background-color: #03254a; height: 70px; width: 70px; margin:auto; padding: 10px;"> 
                            </div>';
                        $admin_messages .= 'Hello Admin,<br/><br/>';
                        $admin_messages .= 'New Advertiser!: Click here to review and approve! '. site_url().'/wp-admin <br/><br/>';
                        $admin_messages .= 'Thank you,<br/>';
                        $admin_messages .= 'The Lightning Bug team <br/><br/>';
                        
                        $admin_messages .='<footer style="background-color: #03254a;padding: 5px;">
                            <h3 style="text-align: center; margin:0px; font-size: 14px;color:#fff;"><i class="fa fa-copyright" aria-hidden="true"></i> '.$year.' All Rights Reserved</h3>
                        </footer>';
                        
                        if(!empty($welcome_admin)){
                            wp_mail($welcome_admin, $admin_title, $admin_messages,$admin_headers);
                        }
                    // End Welcome Admin Mail
               
                }else{
                //   $message .= 'Thank you <strong>'.ucwords($name).'</strong>, for signing up with Lightningbug! <br/><br/>';
                //   $message .= 'Remember to set your preferences under WHO, WHAT, WHEN, WHERE, HOW to get the deals  you want when you want it! <br/><br/>';
                //   $message .= 'Please confirm your email by clicking this link: <a style="color: #008CBA; text-align: center;cursor: pointer; " > <b>Confirm</b></a> <br/><br/>';
                //   $message .= 'Sincerely, <br/>';
                //   $message .= 'Thank you <br/><br/>';
                    
                    $message .= 'Hi <strong>'.ucwords($first_name).'</strong>,<br/><br/>';
                    $message .= 'Thank you for verifying your email and signing up to receive deals via Lightning Bug. To get the best deals catered to you and your needs, remember to set your preferences under WHO, WHAT, WHEN, and WHERE to get the deals you want when you want! Check out the tutorial on our website for more information.<br/><br/>';
                    $message .= 'Need help or have questions? Please see our FAQ in the app or on our website https://www.lightningbug.info.<br/><br/>';
                    $message .= 'Or, contact info@lightningbug.<br/><br/>';
                    $message .= 'Thank you, <br/>';
                    $message .= 'The Lightning Bug team<br/><br/>';
                }
                

                if (is_multisite()) {
                    $blogname = $GLOBALS['current_site']->site_name;
                } else
                // The blogname option is escaped with esc_html on the way into the database in sanitize_option
                // we want to reverse this for the plain text arena of emails.
                {
                    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                }
            
                $title = sprintf(__('[%s] Welcome email'), $blogname);
                $headers = array('Content-Type: text/html; charset=UTF-8');
              
                $message .='<footer style="background-color: #03254a;padding: 5px;">
                    <h3 style="text-align: center; margin:0px; font-size: 14px;color:#fff;"><i class="fa fa-copyright" aria-hidden="true"></i> '.$year.' All Rights Reserved</h3>
                </footer>';
            
                if ($message && !wp_mail($email, $title, $message,$headers)) {
                    $data = array("status" => "error", "msg" => "The e-mail could not be sent..");
                    return new WP_REST_Response($data, 403);
                }

        $data = array("status" => "ok", "errormsg" => "", 'error_code' => "", 'user_id' => $user_id , 'adminmail' =>$admail ,  $data['userInfo'] = $customHelperObj->getUserInformation($user_id));
        return new WP_REST_Response($data, 200);
    } else {
        $data['status'] = "error";
        $data['errormsg'] = __('Account exists with this email or username.');
        $data['error_code'] = "user_already";
        return new WP_REST_Response($data, 403);
    }
}

function user_id_exists($user)
{
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));

    if ($count == 1) {return true;} else {return false;}
}

// Get User ID by token
function GetMobileAPIUserByIdToken($token)
{
    $decoded_array = array();
    $user_id = 0;
    if ($token) {

        try {
            $decoded = JWT::decode($token, JWT_AUTH_SECRET_KEY, array('HS256'));
            $decoded_array = (array) $decoded;
            if (count($decoded) > 0) {
                $user_id = $decoded_array['data']->user->id;
            }

            if (user_id_exists($user_id)) {
                return $user_id;
            } else {
                return false;

            }

        } catch (\Exception $e) { // Also tried JwtException
            return false;
        }
    }
}

// forgot password
function RetrivePassword($request){
    global $wpdb, $current_site;

    $data = array("status" => "ok", "msg" => "Please check your mail. you will be receive login instructions.");

    $param = $request->get_params();
    $user_login = sanitize_text_field($param['user_login']);

    if (!is_email($user_login)) {
        $data = array("status" => "error", "msg" => "Please provide valid email.");
        return new WP_REST_Response($data, 403);
    }

    if (empty($user_login)) {
        $data = array("status" => "error", "msg" => "User email is empty.");
        return new WP_REST_Response($data, 403);

    } elseif (strpos($user_login, '@')) {

        $user_data = get_user_by('email', trim($user_login));

    } else {
        $login = trim($user_login);
        $user_data = get_user_by('login', $login);
    }

    if (!$user_data) {
        $data = array("status" => "error", "msg" => "User not found using this email.");
        return new WP_REST_Response($data, 403);
    }

    // redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    $allow = apply_filters('allow_password_reset', true, $user_data->ID);

    if (!$allow) {
        $data = array("status" => "error", "msg" => "Password reset not allowed.");
        return new WP_REST_Response($data, 403);
    } elseif (is_wp_error($allow)) {
        $data = array("status" => "error", "msg" => "Something went wrong");
        return new WP_REST_Response($data, 403);
    }

    //$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
    // if ( empty($key) ) {
    // Generate something random for a key...
    $key = get_password_reset_key($user_data);
    $password = wp_generate_password(6, false);
    wp_set_password($password, $user_data->ID);

    // do_action('retrieve_password_key', $user_login, $key);
    // Now insert the new md5 key into the db
    //$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
    // }
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $year = date('Y');
    $message = '<div style="background-color: #03254a; display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px;">
             <img src="https://lightningbug.betaplanets.com/wp-content/uploads/2024/02/icon.png" style="background-color: #03254a; height: 70px; width: 70px; margin:auto; padding: 10px;"> 
        </div>';
    $message .= __('Hello ,') . "<br/><br/>";
    $message .= __('Someone requested that the password be reset for the following account:') . "<br/><br/>";
    $message .= sprintf(__('Username: %s'), $user_login) . "<br/>";
    $message .= sprintf(__('New Password : %s'), $password) . "<br/><br/>";
    $message .= __('Thank you') . "<br/><br/>";
    $message .='<footer style="background-color: #03254a;padding: 5px;">
                <h3 style="text-align: center; margin:0px; font-size: 14px;color:#052c53;"><i class="fa fa-copyright" aria-hidden="true"></i> '.$year.' All Rights Reserved</h3>
            </footer>';
    if (is_multisite()) {
        $blogname = $GLOBALS['current_site']->site_name;
    } else{
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }

    $title = sprintf(__('[%s] Password Reset'), $blogname);
    $title = apply_filters('retrieve_password_title', $title);
    $message = apply_filters('retrieve_password_message', $message, $key);
    
    if ($message && !wp_mail($user_email, $title, $message,$headers)) {
        $data = array("status" => "error", "msg" => "The e-mail could not be sent..");
        return new WP_REST_Response($data, 403);
    }
    // wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );

    return new WP_REST_Response($data, 200);
}

add_filter('jwt_auth_token_before_dispatch', 'mobileapi_jwt_auth_token_before_dispatch', 10, 2);
function mobileapi_jwt_auth_token_before_dispatch($data, $user){
    global $customHelperObj;
    $role = 'customer';
    if (in_array('business', (array) $user->roles)) {
        $role = 'business';
    }else{
        $role = $user->roles[0];
    }
    $is_update= get_user_meta($user->ID, 'is_update', true);
    if($is_update==1){
        $data['is_update']=true; 
    }else{
        $data['is_update']=false;
    }
    $data['role'] = $role;
    $first_name = get_user_meta($user->ID, "first_name", true);
    if(!empty($first_name)){
        $data['user_display_name'] = ucfirst($first_name);
    }else{
        $data['user_display_name'] = ucfirst($user->display_name);
    }
    $useravatar = get_user_meta($user->ID, 'wp_user_avatar', true);
    if($useravatar){
        $img = wp_get_attachment_image_src($useravatar, array('150', '150'), true);
        $data['userImage'] = $img[0];
    }else{
        $data['userImage'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
    }
    
    $data['userImage_id'] = $useravatar;
    
    if($useravatar){
        $img = wp_get_attachment_image_src($useravatar, array('150', '150'), true);
        $data['user_avatar'] = $img[0];
    }else{
        $data['user_avatar'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
    }
    $data['user_id']        = $user->ID;
    $data['email']          = $user->user_email;
    $data['first_name']     = get_user_meta($user->ID, 'first_name', true);
    $data['last_name']      = get_user_meta($user->ID, 'last_name', true);
    $data['phone']          = get_user_meta($user->ID, 'phone', true);
    $data['username']       = get_user_meta($user->ID, 'nickname', true);
    $user_id                = $user->ID;

    $my_time_zone = get_user_meta($user_id, 'my_time_zone',true);
            
    if(empty($my_time_zone) || $my_time_zone == '' || $my_time_zone == null){
        $my_time_zone = 'America/New_York';
    }
    
    $data['my_time_zone'] = $my_time_zone;
            
    $data['emailVerified']  = get_user_meta($user->ID, 'emailVerified', true);
    if($role == 'customer'){
        $data['about']                  = get_user_meta($user_id, 'about', true);
        $data['notes']                  = get_user_meta($user_id, 'notes', true);
        $data['notifications_status']   = get_user_meta($user_id, 'notifications_status',true);
        $data['address1']               = get_user_meta($user_id, 'address1', true);
        $data['address2']               = get_user_meta($user_id, 'address2', true);
        $data['city']                   = get_user_meta($user_id, 'city', true);
        $data['state'] = get_user_meta($user_id, 'state', true);
        $data['zipcode'] = get_user_meta($user_id, 'zipcode', true);
        $data['working'] = GetWorkingDays($user_id);
        
        
                $data['is_gender']              = get_user_meta($user_id, 'is_gender', true);
                $data['gender']                 = get_user_meta($user_id, 'gender', true);
                
                $data['is_education']           = get_user_meta($user_id, 'is_education', true);
                $data['education']              = get_user_meta($user_id, 'education', true);
                
                $data['is_income']              = get_user_meta($user_id, 'is_income', true);
                $data['income']                 = get_user_meta($user_id, 'income', true);
                
                $data['is_marraige_status']     =  get_user_meta($user_id, 'is_marraige_status', true);
                $data['marraige_status']        =  get_user_meta($user_id, 'marraige_status', true);
                
                $data['is_home_type']         = get_user_meta($user_id, 'is_home_type', true);
                $data['home_type']         = get_user_meta($user_id, 'home_type', true);
                
                $data['is_dob']         = get_user_meta($user_id, 'is_dob', true);
                $dob = get_user_meta($user_id, 'dob', true);
                if(isset($dob) && !empty($dob)){
                    $dob = date_format(date_create($dob),"F d,Y");
                    $data['dob']   = date('F d,Y',strtotime($dob));
                }
                else{
                    $data['dob'] = '';
                }

                $data['is_transportation_method'] = get_user_meta($user_id, 'is_transportation_method', true);
                $data['transportation_method'] = get_user_meta($user_id, 'transportation_method', true);
                
                $data['is_children_age_range'] = get_user_meta($user_id, 'is_children_age_range', true);
                $data['children_age_range'] = array(
                    'lower' =>  get_user_meta($user_id, 'children_lower_age', true),
                    'upper' =>  get_user_meta($user_id, 'children_upper_age', true)
                );
                
                                            
        $data['notiﬁcations_type'] = get_user_meta($user_id, 'notiﬁcations_type', true);
        $data['categories'] =  $customHelperObj->getUserCategories($user_id);
        $data['locations'] = $customHelperObj->userLocations($user_id);
        $data['checkLogin'] = $customHelperObj->checkLogin($user_id);
        
    }else{
            $data['is_verify']          = get_user_meta($user_id, 'is_verified_by_admin', true);
            $data['accound_suspended']          = get_user_meta($user_id, 'accound_suspended', true);
            $data['business_name']          = get_user_meta($user_id, 'business_name', true);
            $data['business_address']       = get_user_meta($user_id, 'business_address', true);
            $data['business_phone_number']  = get_user_meta($user_id, 'business_phone_number', true);
            $data['business_description']   = get_user_meta($user_id, 'business_description', true);
            $data['business_radius']        = get_user_meta($user_id, 'radius', true);
            $data['business_lat']        = get_user_meta($user_id, 'lat', true);
            $data['business_long']        = get_user_meta($user_id, 'long', true);
            $data['isVerified'] =          get_user_meta($user_id, 'is_verified_by_admin', true);
            $data['catupdate'] =           get_user_meta($user_id, 'catupdate',true);
            $data['timezone']            =get_user_meta($user_id, 'time_zone',true);
           
            $data['available_messages'] =  $customHelperObj->getAvailableMessages($user_id);
            $data['isBuyTest'] =  $customHelperObj->isBuyTest($user_id);
            $data['categories'] =  $customHelperObj->getUserCategories($user_id);
            //business_profile
            $data['facebook_link']  = get_user_meta($user_id, 'facebook_link', true);
            $data['instagram_link'] = get_user_meta($user_id, 'instagram_link', true);
            $data['youtube_link']   = get_user_meta($user_id, 'youtube_link', true);
            $data['twitter_link']   = get_user_meta($user_id, 'twitter_link', true);
            
            $business_profile = get_user_meta($user_id, 'business_profile', true);
            if(isset($business_profile) && !empty($business_profile)){
                $business = wp_get_attachment_image_src($business_profile, array('300', '300'), true);
                $data['business_profile'] = $business[0];
            }else{
                $data['business_profile'] = site_url().'/wp-content/uploads/2022/04/contact.png';
            }
            
            $urlStr = get_user_meta($user_id, 'business_website', true);
            if($urlStr!=''){
                $parsed = parse_url($urlStr);
                if (empty($parsed['scheme'])) {
                    $urlStr = 'http://' . ltrim($urlStr, '/');
                }
            }else{
                $urlStr='';
            }
            $data['business_website'] = $urlStr;
            $data['is_suspended'] = get_user_meta($user->ID, 'account_suspended', true);
    }    
    
    return $data;
}

function get_message_balance($request){
    global $customHelperObj;
    $param = $request->get_params();
    $token = $param['token'];
    $user_id = GetMobileAPIUserByIdToken($token);
    
    $msg_balance = 0;
    if ($user_id) {
        $msg_balance = $customHelperObj->getAvailableMessages($user_id);
    }
    
    $data['msg_balance'] = $msg_balance;
    
    return new WP_REST_Response($data, 200);
}

function GetUserImage($request)
{
    $param = $request->get_params();
    $token = $param['token'];
    $user_id = GetMobileAPIUserByIdToken($token);
    $useravatar = get_user_meta($user_id, 'wp_user_avatar', true);
    if ($useravatar) {
        $img = wp_get_attachment_image_src($useravatar, array('300', '300'), true);
        $data['userImage'] = $img[0];
    } else {
        $data['userImage'] = site_url().'/wp-content/uploads/2022/04/user-placeholder.jpg';
    }
    return new WP_REST_Response($data, 200);
}

    add_action('rest_insert_attachment', 'func_rest_insert_attachment', 10, 3);
    function func_rest_insert_attachment($attachment, $request, $is_create){
        global $wpdb;
        if(isset($request['type']) && $request['type']=="userimage"){
          //wp_user_avatar
          $useravatar = get_user_meta($request['user'], 'wp_user_avatar', true);
         
          if($useravatar){
               wp_delete_attachment($useravatar,true); 
           }
           update_user_meta($request['user'], 'wp_user_avatar', $attachment->ID);
        }
        
        if(isset($request['type']) && $request['type']=="deal_image" ){
              update_post_meta($request['post_id'], '_thumbnail_id', $attachment->ID);
              set_post_thumbnail($request['post_id'],$attachment->ID);
              $wpdb->update('wp_posts', ['post_parent' => $request['post_id']], ['ID' => $attachment->ID]);
            
        }
        
        if(isset($request['type']) && $request['type']=="businessProfile"){
          //wp_user_avatar
           update_user_meta($request['user'], 'business_profile', $attachment->ID);
        }
  
       if(isset($request['type']) && $request['type']=="barberGallery"){
          //wp_user_avatar
          ImageGalleryUpload($attachment->ID,$request['user']);
          
        }
  
        // if(isset($request['type']) && $request['type']=="service_image"){
        //   //wp_user_avatar
        //     update_post_meta($request['post_id'], '_thumbnail_id', $attachment->ID);
        //   set_post_thumbnail($request['post_id'],$attachment->ID);
        //   return $attachment->ID;
         
        // }
        
        if(isset($request['type']) && $request['type']=="service_image"){
        //   update_post_meta($request['post_id'], '_thumbnail_id', $attachment->ID);
        //   set_post_thumbnail($request['post_id'],$attachment->ID);
          return $attachment->ID;
         
        }

    }

function ImageGalleryUpload($attachment,$user_id){
    global $wpdb;
    $wpdb->insert('user_collection_gallery',array('user_id'=>$user_id,'attachment_id'=>$attachment));
}

function sendPushServer($user_id = null, $type = null, $msg = null, $title = null, $touser, $post_id = null)
{
    global $wpdb,$customHelperObj;

    // $query = "SELECT * FROM wp_user_devices WHERE user_id='" . $touser . "' and status = 1";
    
    
    $token = array();
    // $results = $wpdb->get_results($query);
    
    $token = getDeviceIDS($touser);
    
    
    // HASAN commented
    // $customHelperObj->decreaseMessages($user_id);
    // foreach ($results as $data) {
    //     $token[] = $data->token;
    // }
    if (count($token) > 0) {
        $insert = $wpdb->insert('wp_jet_push_notification', array( //rj
            'user_id' => $touser,
            'date' => date("F j, Y"),
            'title' => $title,
            'message' => $msg,
            'type' => $type,
            'post_id' => $post_id,
            'status' => '0',
            'sent_by' => $user_id,
            'created'=> date('Y-m-d H:i:s'),
        ));
        $data=array(
            'type'=>$type,
            "booking_id"=>$post_id,
            "sent_by" => $user_id,
            'to_user' => $touser,
            
            );
        sendMessage($msg, $token, $data, $title);
    }
}
// function sendMessage($msgData, $device_token, $data, $title)
// {
//     $content = array("en" => $msgData);
//     $heading = array("en" => $title);
//     $fields = array(
//         'app_id' => "8e8e69e1-f6b3-46e6-8cb0-683e2eff397c",
//         'data' => $data,
//         'contents' => $content,
//         'headings' => $heading,
//         'include_player_ids' => $device_token,
//     );
//     $fields = json_encode($fields);
//     /*print("\nJSON sent:\n");
//     print($fields);*/
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
//         'Authorization: Basic MzgwMjIwNjQtYmE4ZC00MDA0LThkY2YtMTY3ZTg4MjRmNzQ1'));
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_HEADER, false);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     $response = curl_exec($ch);
//     curl_close($ch);
//     return $response;
// }

function sendMessage($msgData, $device_token, $data, $title)
{
    $content = array("en" => $msgData);
    $heading = array("en" => $title);
    
    
    if(isset($data['type']) && $data['type'] == 'new_deal' && isset($data['booking_id']) && $data['booking_id']!=''){
        
        $post_id = $data['booking_id'];
        $author_id = $data['sent_by'];
        $thumbnail_id = get_post_meta($post_id, "_thumbnail_id", true);
    
        if (!empty($thumbnail_id) && $thumbnail_id != '125908') {
            $img = wp_get_attachment_image_src($thumbnail_id,'medium', true);
            $cover = $img[0];
        }
        else{
            $useravatar = get_user_meta($author_id, 'wp_user_avatar', true);

            if ($useravatar) {
                $img = wp_get_attachment_image_src($useravatar, array('300', '300'), true);
                $cover = $img[0];
            } else {
                $cover = site_url().'/wp-content/uploads/2024/01/icon.png';
            }
        }
        
        
        $fields = array(
            'app_id' => "8e8e69e1-f6b3-46e6-8cb0-683e2eff397c",
            'data' => $data,
            'contents' => $content,
            'headings' => $heading,
            'include_player_ids' => $device_token,
            // Android-specific options
            'large_icon' => $cover,
            // 'big_picture' => "https://lightningbug2.betaplanets.com/wp-content/uploads/2024/01/icon.png",
            
            // iOS-specific options (Add media attachment for large image)
            'ios_attachments' => array(
                'id1' => $cover // URL to the image
            ),
             'mutable_content' => true
        );
    }
    else{
        $fields = array(
            'app_id' => "8e8e69e1-f6b3-46e6-8cb0-683e2eff397c",
            'data' => $data,
            'contents' => $content,
            'headings' => $heading,
            'include_player_ids' => $device_token
        );
    }
            
            
        // $fields = array(
        //     'app_id' => "8e8e69e1-f6b3-46e6-8cb0-683e2eff397c",
        //     'data' => $data,
        //     'contents' => $content,
        //     'headings' => $heading,
        //     'include_player_ids' => $device_token,
        //     // Android-specific options
        //     'large_icon' => "https://lightningbug2.betaplanets.com/wp-content/uploads/2024/01/icon.png",
        //     // 'big_picture' => "https://lightningbug2.betaplanets.com/wp-content/uploads/2024/01/icon.png",
    
        //     // iOS-specific options (Add media attachment for large image)
        //     'ios_attachments' => array(
        //         'id1' => "https://lightningbug2.betaplanets.com/wp-content/uploads/2024/01/icon.png",
        //     ),
        //      'mutable_content' => true
        // );
            

    

    $fields = json_encode($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
        'Authorization: Basic MzgwMjIwNjQtYmE4ZC00MDA0LThkY2YtMTY3ZTg4MjRmNzQ1'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

add_action( 'user_register', 'barber_registration_save', 10, 1 );
function barber_registration_save( $user_id ) {
$user = get_userdata($user_id);
            if (in_array('barber', (array) $user->roles)) {
                update_user_meta($user_id, 'is_barber',1);
            } else {
                update_user_meta($user_id, 'is_barber',0); 
            }
}

add_action( 'profile_update', 'barber_profile_update', 10, 2 );
function barber_profile_update( $user_id, $old_user_data ) {
            $user = get_userdata($user_id);
            if (in_array('barber', (array) $user->roles)) {
                update_user_meta($user_id, 'is_barber',1);
            } else {
                update_user_meta($user_id, 'is_barber',0); 
            }
    }
    
//////

    // ________________________[updateDeviceToken]_________________________________
    function updateDeviceToken($request){
        global $wpdb;
        $data = array("code" => 200, "status" => "ok", "msg" => "",'error_code' => "");
        $param = $request->get_params();
    	$user_id = $param['userid'];
        $deviceID=$param['deviceID'];
        $deviceData=$param['deviceData'];
        $status=$param['status'];
        $deviceData['device_timezone']=$param['timezone'];
        switch($status){
            case 'login':
                $res = saveDeviceDetails($user_id,$deviceData);
            break;
            case 'logout':
                $res = removeDeviceDetails($user_id,$deviceData);
            break;
        }
        $data['res'] = $res;
        return new WP_REST_Response($data, 200);
    }
    
    
    // ________________________[saveDeviceDetails]_________________________________    
    function saveDeviceDetails($user_id,$device){
        global $wpdb;
        $uuid=$device[0]['uuid'];
        // print_r($uuid);
        if($uuid){
            $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}users_device_details WHERE user_id =$user_id  and device_uuid='".$uuid."'");
            $numberofcounts = $results;
            update_user_meta($user_id,'device_timezone',$device['device_timezone']);
            
            if($numberofcounts==0){
                $response = $wpdb->insert('wp_users_device_details', array(
                    'user_id' => (int) $user_id,
                    'device_uuid' =>$device[0]['uuid'],
                    'device_model' =>$device[0]['model'],
                    'deviceplatform' =>$device[0]['platform'],
                    'deviceversion' =>$device[0]['version'],
                    'timezone' =>$device[0]['offset'],
                    'device_token' =>$device[0]['deviceToken'],
                    'isuserloggedin' =>1,
                    'device_timezone'=>$device['device_timezone'],
                    'logindate' =>$device[0]['logindate'],
                ));
                
                $wpdb->query($wpdb->prepare("UPDATE wp_users_device_details SET isuserloggedin=0 WHERE user_id!=".$user_id." and device_uuid='".$device[0]['uuid']."'"));
              
            }else{
                $response =  $wpdb->update(
                            	'wp_users_device_details', 
                            	array(     
                                    'device_model' =>$device[0]['model'],
                                    'deviceplatform' =>$device[0]['platform'],
                                    'deviceversion' =>$device[0]['version'],
                                    'timezone' =>$device[0]['offset'],
                                    'device_token' =>$device[0]['deviceToken'],
                                    'isuserloggedin' =>1,
                                     'device_timezone'=>$device['device_timezone'],
                                    'logindate' =>$device[0]['logindate'],
                                ),
                                array(
                            	     'device_uuid' =>$device[0]['uuid'],
                        	         'user_id' => (int)$user_id
                            	) 
                            );
                $wpdb->query($wpdb->prepare("UPDATE wp_users_device_details SET isuserloggedin=0 WHERE user_id!=".$user_id." and device_uuid='".$device[0]['uuid']."'"));
            }
        }
        
        return $device;
    }

    // ________________________[removeDeviceDetails]_________________________________
    function removeDeviceDetails($user_id,$device){
        global $wpdb;
        $response =  $wpdb->update( 
        	'wp_users_device_details', 
        	array(     
                'isuserloggedin' => 0
            ),
            array( 
                'device_uuid' => $device[0]['uuid']
                // 'user_id' => (int)$user_id
        	)
        );
        return $response;
    }

    // ________________________[getDeviceIDS]_________________________________
    function getDeviceIDS($userid){
        global $wpdb;
        $device=array();
        $result = $wpdb->get_results( "SELECT device_token FROM wp_users_device_details WHERE user_id = $userid AND isuserloggedin = 1",ARRAY_A);
        foreach ( $result as $data ) 
        {
        	$device[] =  $data['device_token'];
        }
        return $request = array_unique($device);
    }
    
    // add_action('init','e_test');
    // function e_test(){
    //     if($_REQUEST['key'] == 'test'){
    //         sendMessageTest('4157381241', 'Hey Light Bug is on testing!');
    //     }
    // }
    
    // function sendMessageTest($phone,$msg){
    //     $data = array();
    //         $body  = "Lightning Bug: ";
    //         $body .= $msg;
    //         $ID    = 'AC61846076a471f987cfdff525be45920f';
    //         $token = '23ab739ee7780ed0057a9561881eb664';
    //         try{
    //             $client = new Client($ID, $token);
    //             $Response = $client->messages->create(
    //                   '+1'.$phone,
                      
    //                     array(
    //                         'from' => '+16056464657',
    //                         'body' => $body
    //                     )
    //             );
    //             print_r($Response->status);
    //         }
    //         catch (Exception $e){
    //             $data['Error']=$e;
    //             print_r($e);
    //         }
    //     return $data;
    // }
function validateUser($request){
    global $wpdb;
    $param = $request->get_params();
    $user_id = GetMobileAPIUserByIdToken($param['token']);
    if($user_id){
        $suspended = get_user_meta($user_id, 'account_suspended', true);
        if ($suspended == '1'){
            $data['status']     = "error";
            $data['type']       = "account_suspended";
            $data['message']    = "Your account is suspended.";
            return new WP_REST_Response($data, 403);
        }
        $verify = get_user_meta($user_id, 'is_verified_by_admin', true);
        if($verify == 'no'){
             $data['status']    = "error";
            $data['type']       = "pending_approval";
            $data['message']    = "Your account is pending for admin approval.";
            return new WP_REST_Response($data, 403);
        }
        $data['status'] = "success";
        $data['message'] = "This is valid user.";
        return new WP_REST_Response($data, 200);
    }else{
        $data['status'] = "error";
        $data['message'] = "The session has been expired.";
        return new WP_REST_Response($data, 403);
    }
}


// add_action('wp_error_added','custom_error_added',99,4);
// function custom_error_added($code, $message, $data, $obj){
//     if($code == '[jwt_auth] incorrect_password'){
//         $obj->remove($code);
//         $obj->errors['incorrect_password'][] = 'Password is incorrect';
//         $obj->error_data['incorrect_password'] = array('status' => 403);
//     }elseif($code == '[jwt_auth] invalid_email' || $code == '[jwt_auth] invalid_username'){
//         $obj->remove($code);
//         $obj->errors['invalid_email'][] = 'Username is invalid';
//         $obj->error_data['invalid_email'] = array('status' => 403);
//     }elseif($code == '[jwt_auth] empty_username'){
//         $obj->remove($code);
//         $obj->errors['empty_username'][] = 'Username is empty';
//         $obj->error_data['empty_username'] = array('status' => 403);
//     }elseif($code == '[jwt_auth] empty_password'){
//         $obj->remove($code);
//         $obj->errors['empty_password'][] = 'Password is empty';
//         $obj->error_data['empty_password'] = array('status' => 403);
//     }
// }


add_filter('authenticate', 'checkAccount', 99, 1);
function checkAccount($user){
    global $wpdb;
    if ($user instanceof WP_User) {
        if($user->roles[0] == 'business'){
            // $suspended = get_user_meta($user->ID, 'account_suspended', true);
            // if ($suspended == '1') {
            //     return new WP_Error('account_suspended', 'Your account is suspended.');
            // }
            $verify = get_user_meta($user->ID, 'is_verified_by_admin', true);
            if($verify == 'no') {
                return new WP_Error('pending_approval', 'Your account is pending for admin approval.');
            }
        }
        
        $checkUser = $wpdb->get_row("SELECT * FROM `delete_account_request` WHERE `user_id` = $user->ID " , ARRAY_A);
        if($checkUser){
            return new WP_Error('account_suspended', 'Your account has been removed');
        }
    }else{
        return new WP_Error('authentication_failed', 'Invalid username or incorrect password.');
    }
    return $user;
}




// function reset_all_nt() {
//     if($_REQUEST['type'] && $_REQUEST['type'] == 'reset'){
//         $users = get_users(array('role' =>'customer')); 
//         // print_r(count($users));
        
//         foreach($users as $u){
//             update_user_meta($u->ID, 'notiﬁcations_type', 'push_notification');
//         }

//     }
// }
// add_action( 'init', 'reset_all_nt' );
