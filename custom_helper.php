<?php
    //Twillio
    require_once('twillio/vendor/autoload.php');
    use Twilio\Rest\Client;
    //Twillio
    
    class customHelper{
     /* Member variables */
        var $wpdb;
        function __construct() {
            global $wpdb, $time_stamp;
            $this->wpdb = $wpdb;
            $this->time_stamp = time();
        }
        
        function checkCoupon($user_id,$coupon_id){
            $query = "SELECT COUNT(*) as total FROM `wp_coupon_logs` WHERE `user_id`= $user_id AND coupon_id = $coupon_id AND type = 'one-time-use'";
            $res   =  $this->wpdb->get_row($query,ARRAY_A);
            if($res['total'] > 0){
                return true;
            }else{
                return false;
            }
        }
        
     
        function updateCouponLog($user_id,$coupon_id){
            $coupon_type = get_post_meta($coupon_id,'coupon_type',true);
             $insert = array(
                'user_id'   =>$user_id,
                'coupon_id'=>$coupon_id,
                'type'     =>$coupon_type,
            );
            $this->wpdb->insert('wp_coupon_logs',$insert);
            return $this->wpdb->insert_id;
        }
        
        
        
         function getTimeZone($timezone, $time)
            {
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $name = $timeZone->getName();
                $UTC = new DateTimeZone($name);
                $newTZ = new DateTimeZone($timezone);
                $date = new DateTime($time, $UTC);
                $date->setTimezone($newTZ);
                $array_time = $date->format('g:ia');
              
                return $array_time;
                
            }
            
            public function getMaxValueIncome(){
                $query="SELECT MAX(CAST(meta_value AS int)) as income FROM `wp_usermeta` WHERE wp_usermeta.meta_key = 'income'";
                $result = $this->wpdb->get_row($query,ARRAY_A);
                
                if($result){
                   return $result['income']; 
                }
                
            }
            
            public function getSubCatDayTime($user_id,$cat_id){
                $query  = "SELECT * FROM `wp_subcat_working` WHERE `user_id` = $user_id AND `cat_id` = $cat_id";
                $result = $this->wpdb->get_results($query,ARRAY_A);
                
                if(count($result)>0){
                    return $result;
                }
                
            }
            
            
            public function checkSubCatDaytime($user_id,$cat_id){
                $query  = "SELECT * FROM `wp_subcat_working` WHERE `user_id` = $user_id AND `cat_id` = $cat_id";
                $result = $this->wpdb->get_row($query,ARRAY_A);
                
                if($result){
                  return true;
                }else{
                   return false;  
                }
                
            }
            
            public function insertSubCatDayTime($user_id, $cat_id, $to_time, $from_time, $message, $day,$datname,$subtimelower,$suntimeupper){
             
               
                $query  = "SELECT * FROM `wp_subcat_working` WHERE `user_id` = $user_id AND `cat_id` = $cat_id AND `day` = $day";
                $result = $this->wpdb->get_row($query,ARRAY_A);
                
                if($result){
                  
                    $this->wpdb->update("wp_subcat_working",array('to_time'=>$to_time['time'],'from_time'=>$from_time['time'], 'to_time_tz'=>$to_time['timezone_time'], 'from_time_tz'=>$from_time['timezone_time'], 'message'=>$message,'day'=> $day,'dayname'=>$datname, 'timelower'=>$subtimelower,'timeupper'=>$suntimeupper,),array("id"=>$result['id'],));
                   
                    
                }else{
                    $insert =array(
                        'user_id'    =>$user_id,
                        'cat_id'     =>$cat_id,
                        'day'        =>$day,
                        'from_time'  =>$from_time,
                        'to_time'    =>$to_time,
                        'message'    =>$message,
                        'dayname'   =>$datname,
                        'timelower'   =>$subtimelower,
                        'timeupper' =>$suntimeupper,
                        'to_time_tz'=>$to_time['timezone_time'],
                        'from_time_tz'=>$from_time['timezone_time']
                    );

                      $this->wpdb->insert('wp_subcat_working',$insert);
                    
                }
           
            }
        
            public function insertCountSendMessage($targetedUser_id,$from_time,$to_time,$day,$date,$message,$post_id){
                
                $query  = "SELECT * FROM `wp_count_send_message` WHERE `user_id` = $targetedUser_id AND `post_id` = $post_id AND `date` = '$date'";
                $result = $this->wpdb->get_row($query,ARRAY_A);
                

                if($result){
                    $count = $result['send_message'];
                    $send_message = $count +1;
                    
                    $this->wpdb->update("wp_count_send_message",array('send_message'=> $send_message),array("id"=>$result['id']));
                    update_post_meta($post_id, 'is_Send',"true");
                    
                    
                }else{
                    $insert = array(
                        'user_id'    =>$targetedUser_id,
                        'from_time'  =>$from_time,
                        'to_time'    =>$to_time,
                        'day'        =>$day,
                        'date'       =>$date,
                        'message'    =>$message,
                        'post_id'   =>$post_id,
                        'send_message' => 1,
                    );
                    $this->wpdb->insert('wp_count_send_message',$insert);
                    update_post_meta($post_id, 'is_Send',"true");
                }
                
            }
        
        public function getCheckSendMessage($user_id,$post_id,$date){
            
            $query  = "SELECT * FROM `wp_count_send_message` WHERE `user_id` = $user_id AND `post_id` = $post_id AND `date` = '$date'";
            $result = $this->wpdb->get_row($query,ARRAY_A);
            
            if(count($result) > 0){
                 return $result;
            }
            
        }
        
        public function getCustomerWorkingDay($user_id){
            
            $query  = " SELECT * FROM `wp_users_working_days` WHERE `user_id` = $user_id";
            $result = $this->wpdb->get_results($query,ARRAY_A);
            
            if(count($result) > 0){
                 return $result;
            }
            
        }
        
        
        public function adminMail($messages){ 
            $email = get_option('admin_email');
            if($email){
               if (is_multisite()) {
                    $blogname = $GLOBALS['current_site']->site_name;
                }else{
                    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                }
                
                $title = sprintf(__('[%s] Approval email'), $blogname);
                $headers = array('Content-Type: text/html; charset=UTF-8');
                
               if($messages && !wp_mail($email, $title, $messages,$headers)) {
                $result = 'The e-mail could not be sent.';
                }else{
                   $result = 'Admin eamil send successfully..';
                  }
            }else{
                 $result = 'Admin eamil not found';
                }
            
             return $result;
            
        }
        
        public function userLiveLocations($user_id){
            $location = array();
            $where = "WHERE `user_id` = $user_id";
            
            
            // COMMENTED FOLLOW ME
            // $follow_me = get_user_meta($user_id,'follow_me',true);
            // if(!empty($follow_me)){
            //     $where .= " AND `type` = 'current'";
            // }else{
                 $where .= " AND `type` != 'current'";
            // }
            $query  = "SELECT * FROM `wp_user_locations`   $where";
            $result = $this->wpdb->get_results($query,ARRAY_A);
            
            if(count($result) > 0){
                 return $result;
            }
        }
        
        public function isBuyTest($user_id){
            
          $text_buy = get_user_meta($user_id,'text_buy',true);
          if($text_buy == 'yes'){
               return 'yes';
          }else{
              return 'no';
          }
        }
        
        public function decreaseMessages($user_id){
            $available_messages =  $this->getAvailableMessages($user_id);
            $available_messages  = $available_messages - 1 ;
            $this->wpdb->update("wp_user_messages",array('messages'=> $available_messages),array("user_id"=>$user_id));
        }
        
        // public function updateWorkingDay($user_id,$daysInfo,$day,$is_day){
        //     $format = 'YYYY-MM-DDTHH:mm:ssTZD';
        //     if($is_day){
        //         $query ="DELETE FROM `wp_users_working_days` WHERE `user_id`= $user_id AND `day`= $day";
        //         $this->wpdb->query($query);
                
        //         foreach($daysInfo as $timeSlot){
        //             $from     = $timeSlot['start_time'];
        //             $from_obj = new DateTime($from);
        //             $from_time_format = $from_obj->format('H:i').':00';
                     
        //             $to     = $timeSlot['end_time'];
        //             $to_obj = new DateTime($to);
        //             $to_time_format = $to_obj->format('H:i').':00';
                    
        //             $message = $timeSlot['message'] ?  $timeSlot['message'] : 0;
        //             if(isset($from) && !empty($from) && isset($to) && !empty($to)){
        //               $this->wpdb->insert('wp_users_working_days', array('user_id' => $user_id, 'day' => $day, 'message' => $message, 'from_time' => $timeSlot['start_time'], 'time' => json_encode($timeSlot['time']), 'to_time' => $timeSlot['end_time'],'from_time_format'=>$from_time_format,'to_time_format'=>$to_time_format, 'status' => $is_day)); 
        //             }
        //         }
        //     }else{
        //         $this->wpdb->update('wp_users_working_days',array('status'=>$is_day), array('user_id' => $user_id, 'day' => $day));
        //     }    
        // }
        
        
        public function updateWorkingDay($user_id,$daysInfo,$day,$is_day){
            $format = 'YYYY-MM-DDTHH:mm:ssTZD';
            
            if(!$is_day){
                $daysInfo = [];
            }

            $query ="DELETE FROM `wp_users_working_days` WHERE `user_id`= $user_id AND `day`= $day";
            $this->wpdb->query($query);
            
            if(count($daysInfo)){
                foreach($daysInfo as $timeSlot){
                    $from     = $timeSlot['start_time'];
                    $from_obj = new DateTime($from);
                    $from_time_format = $from_obj->format('H:i').':00';
                     
                    $to     = $timeSlot['end_time'];
                    $to_obj = new DateTime($to);
                    $to_time_format = $to_obj->format('H:i').':00';
                    
                    $message = $timeSlot['message'] ?  $timeSlot['message'] : 0;
                    if(isset($from) && !empty($from) && isset($to) && !empty($to)){
                       $this->wpdb->insert('wp_users_working_days', array('user_id' => $user_id, 'day' => $day, 'message' => $message, 'from_time' => $timeSlot['start_time'], 'time' => json_encode($timeSlot['time']), 'to_time' => $timeSlot['end_time'],'from_time_format'=>$from_time_format,'to_time_format'=>$to_time_format, 'status' => $is_day)); 
                    }
                }
            }
            else if($is_day){
                $this->wpdb->insert('wp_users_working_days', array('user_id' => $user_id, 'day' => $day, 'message' => '', 'from_time' => '', 'time' => '', 'to_time' => '','from_time_format'=>'','to_time_format'=>'', 'status' => $is_day, 'no_limit' => 1)); 
            }
        }
        
        
        

        public function isFollowing($user_id,$follower){
            $select = "SELECT * FROM `wp_follow` WHERE `follower_id`= $follower AND `user_id`=$user_id";
            $query  = $this->wpdb->get_row($select,ARRAY_A);
            if(count($query)> 0){
                return true;
            }else{
                return false;
            }
        }
        
        public function isBlocked($user_id,$to_user_id){
            $select = "SELECT * FROM `wp_blocked_user` WHERE `user_id`= $user_id AND `to_user_id`=$to_user_id";
            $query  = $this->wpdb->get_row($select,ARRAY_A);

            if(count($query)> 0){
                return true;
            }else{
                return false;
            }
        }

        
       // Check login
        public function checkLogin($user_id){
            $loginStatus = get_user_meta($user_id,'loginStatus',true);
            if(!isset($loginStatus) || empty($loginStatus)){
                update_user_meta($user_id,'loginStatus',1);
                return 0 ;
            }else{
                return $loginStatus;
            }
        }
        //Check login
        
        //Single guest info
        public function getSingleGuestInfo($guest_id, $user_id){
            
            $timezone = get_user_meta($user_id ,'my_time_zone',true);
            
            if(empty($timezone)){
                $timezone = 'America/New_York';
            }
                            
            $select = "SELECT * FROM `wp_guest_users` WHERE `id` = $guest_id";
            $result = $this->wpdb->get_row($select, ARRAY_A);
            $guestInfo = array();
            if(count($result) > 0){
                $guestInfo['id']        = $result['id'];
                $guestInfo['name']      = $result['name'];
                $guestInfo['phone']     = $result['phone'];
                $guestInfo['order_no']  = $result['order_no'];
                $guestInfo['status']    = $result['status'];
                $guestInfo['action']    = $result['action'];
                $guestInfo['other']   = $result['other'];
                $guestInfo['isCompleted'] = $result['action'] == 'completed'? true : false;
                $guestInfo['is_custom_msg']    = $result['is_custom_msg'] == '0' ? false : true;
                $guestInfo['custom_msg']   = $result['custom_msg'];
                
                $guestInfo['updated']   = $result['updated'];
                $guestInfo['created']   = human_time_diff( strtotime($result['created']), current_time( 'timestamp' ) );
                // $guestInfo['created'] = $this->humanTimeDiff(strtotime($result['created']), current_time( 'timestamp' ));
                $guestInfo['created_at_str']   = strtotime($result['created']);
                // $guestInfo['time_in']   = date('h:i a', strtotime('+9 hour +30 minutes', strtotime($result['created'])));
                $guestInfo['created_at'] = $result['created'];
                $guestInfo['time_in_new']   = date('h:i a', strtotime(getTime($timezone, $result['created'])));
            }
            return $guestInfo;
        }
        //Single guest info
        
        
        public function humanTimeDiff($createdTimestamp, $currentTimestamp) {

            $timeDiff = abs($currentTimestamp - $createdTimestamp);  // Get the difference in seconds
            
            $seconds = floor($timeDiff);
            $minutes = floor($seconds / 60);
            $hours = floor($minutes / 60);
            $days = floor($hours / 24);
            
            $timeAgo = '';
            
            if ($days > 0) {
                $timeAgo .= $days . ' day' . ($days === 1 ? '' : 's') . ' ';
            }
            
            if ($hours > 0) {
                $timeAgo .= ($hours % 24) . ' hour' . (($hours % 24) === 1 ? '' : 's') . ' ';
            }
            
            if ($minutes > 0) {
                $timeAgo .= ($minutes % 60) . ' minute' . (($minutes % 60) === 1 ? '' : 's') . ' ';
            }
            
            if(trim($timeAgo) == ''){
                if ($seconds > 0) {
                    $timeAgo .= ($seconds % 60) . ' second' . (($seconds % 60) === 1 ? '' : 's');
                }
            }
            
            
            return $timeAgo;
        }



        
        
       //Check phone number    
       public function checkPhone($phone){
           $select = "SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'phone' AND `meta_value` = '$phone'";
           $res    = $this->wpdb->get_row($select,ARRAY_A);
           if(count($res) > 0){
               return $res['user_id'];
           }else{
               return '';
           }
       }
       //Check phone number    
         
         
        //  public function sendOTP($phone,$msg,$otp){
        //       $data = array();
        //       if(!isset($phone) || empty($phone)){ 
        //         $body  = "Lightning Bug: ";
        //         $body .= $msg;
        //         $ID    = 'ACc9492c152447bc01b2d853b44053cf9e';
        //         $token = 'e190eae011d12fdfb63f4899cf07a79d';
        //         try{
        //             $client = new Client($ID, $token);
        //             $Response = $client->messages->create(
        //                 //   '+1'.$phone,
        //                  
        //                     '+91'.$phone,
        //                 array(
        //                     'from' => '+15706647233',
        //                     'body' => $body
        //                 )
        //             );
                    
        //             $send_Response = $Response->status;
        //             $data['response'] = $send_Response;
                    
        //         }
        //         catch (Exception $e){
        //              $data['response'] = $e;
                    
        //         }
        //     }
        //       return $data;
        //  }
        
        //send message && notification
           public function sendMessage($phone,$msg, array $args = null){
            $data = array();
            // $availableMessages = $this->getAvailableMessages($args['user_id']);
                $body  = "Lightning Bug: ";
                $body .= $msg;
                // $ID    = 'ACc9492c152447bc01b2d853b44053cf9e';
                // $token = 'e190eae011d12fdfb63f4899cf07a79d';
                $ID    = 'AC61846076a471f987cfdff525be45920f';
                $token = '23ab739ee7780ed0057a9561881eb664';
                try{
                    $client = new Client($ID, $token);
                    $Response = $client->messages->create(
                          '+1'.$phone,
                          
                            array(
                                // 'from' => '+12095542334',
                                'from' => '+16195774371',
                                'body' => $body
                            )
                        
                        // array(
                        //     'from' => '+15706647233',
                        //     'body' => $body
                        // )
                    );
                    // $this->decreaseMessages($userId);
                    // $send_Response = $Response->status;
                    // $availableMessages = $availableMessages -1;
                    $data['sendType'] = 'sms'; 
                }
                catch (Exception $e){
                    $data['Error']=$e;
                    // print_r($e);
                    //on error push userId in to error array
                }
           
            return $data;
        }
        //send message && notification
        
       //Send SMS    
       public function sendSMS($phone,$msg, array $args = null){
            $data = array();
            // $availableMessages = $this->getAvailableMessages($args['user_id']);
            $userId = $this->checkPhone($phone);
            if(!isset($userId) || empty($userId)){ 
                $body  = "Lightning Bug: ";
                $body .= $msg;
                $ID    = 'AC61846076a471f987cfdff525be45920f';
                $token = '23ab739ee7780ed0057a9561881eb664';
                try{
                    $client = new Client($ID, $token);
                    $Response = $client->messages->create(
                          '+1'.$phone,
                          array(
                            'from' => '+16195774371',
                            'body' => $body
                        )
                        
                        // array(
                        //     'from' => '+12095542334',
                        //     'body' => $body
                        // )
                    );
                    // $this->decreaseMessages($userId);
                    $send_Response = $Response->status;
                    // $availableMessages = $availableMessages -1;
                    $data['sendType'] = 'sms'; 
                    $data['send_Response'] = $send_Response;
                    
                }
                catch (Exception $e){
                    $data['Error']=$e;
                    // print_r($e);
                    //on error push userId in to error array
                }
            }
            else{
               sendPushServer($args['user_id'], $args['type'],$msg, "Lightning Bug",$userId, $args['guest_id']);  
            //   $availableMessages = $availableMessages -1 ;
               $data['sendType'] = 'pushNotification'; 
            }
            
            return $data;
        }
        //Send SMS   
        
        //Get deal information
        public function getDealInformation($post_id,$post=null,$user_id = null){
                $data = array();
                if(!isset($post) || empty($post)){
                  $post = get_post($post_id);
                  $post = json_decode(json_encode($post,true), true); 
                }
                
                $author_id = get_post($post_id)->post_author;

                $data['post_id']      =$post_id;
                $data['post_title']   =$post['post_title'];
                $data['post_content'] =$post['post_content'];
                $data['guid']         =$post['guid'];
                $thumbnail_id = get_post_meta($post_id, "_thumbnail_id", true);
                
                // $img          = wp_get_attachment_image_src($thumbnail_id,'medium', true);
                // $cover = $img[0];
                // if ($cover == '') {
                //     $cover = site_url("/wp-content/uploads/2020/03/placeholder.jpg");
                // }
                
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
                
                $data['cover']               = $cover;
                $data['age']                 = array(
                                                    'lower' =>  get_post_meta($post_id, 'lower_age', true),
                                                    'upper' =>  get_post_meta($post_id, 'upper_age', true)
                                                );
                $data['author']              = get_post_meta($post_id, 'author',true);
                $data['authorInfo']          = $this->getUserInformation($data['author']);
                $data['gender']              = get_post_meta($post_id,'gender',true);
                $data['children_age_range']  = get_post_meta($post_id,'children_age_range',true);
                $data['children_age_range']  = array(
                                                   'lower' =>  get_post_meta($post_id, 'children_lower_age', true),
                                                   'upper' =>  get_post_meta($post_id, 'children_upper_age', true)
                                               );
                $metaInfo                    = get_post_meta($post_id);                               
                $data['marriage_status']     = get_post_meta($post_id,'marriage_status',true);
                $data['transportation_mode'] = get_post_meta($post_id,'transportation_mode',true);
                $data['home_type']           = get_post_meta($post_id,'home_type',true);
                // $data['income']              = !empty($metaInfo['income']['0'])?$metaInfo['income']['0']:'';
                 $data['income']             = get_post_meta($post_id,'income',true);
                 
                $data['education']           = get_post_meta($post_id,'education',true);
                $data['deal_type']           = get_post_meta($post_id,'deal_type',true);
                $data['duration']            = get_post_meta($post_id,'duration',true);
                $data['duration_type']       = get_post_meta($post_id,'duration_type',true);
                $data['expiry']              = get_post_meta($post_id,'expiry',true);
                $data['broadcast_radius']    = get_post_meta($post_id,'broadcast_radius',true);
                $total_share                 = get_post_meta($post_id,'total_share',true);
                $data['total_share']         = (isset($total_share) && !empty($total_share))? $total_share : 0;  
                $data['remaining']           = $this->remainingTime($post_id);
                // $data['expired_at']           = date("m-d-Y g:i a", strtotime(get_post_meta($post_id,'expiry',true)));
                $data['expired_at']           = $this->remainingTimeAgo($post_id);
                
                
                
                
                $post_status = get_post_status($post_id);
                if($post_status == 'future'){
                   $data['schedule_remaining_time'] = $this->remainingScheduleTime($post_id); 
                }
                else{
                    $data['schedule_remaining_time'] = '--';
                }
                
                
                $data['post_status'] = $post_status;
                
                $category_list               = wp_get_post_terms($post_id, 'lighting_cats' );
                
                
                
                // SORTED IDS
                
                $parents = get_terms( array(
                    'taxonomy'   => 'lighting_cats',
                    'hide_empty' => false,
                    'parent'     => 0,
                    'order'      => 'DESC'
                ) );
                $parents = json_decode(json_encode($parents,true),true);
                $categories = array();
                foreach($parents as $parent){
                    $categories[] =  $this->get_cat_informations($parent['term_id'],$parent, array('child_categories' => true));
                }
                
                $all_sorted_cat_ids = array();

                foreach ($categories as $element) {
                    $all_sorted_cat_ids[] = $element['term_id'];
                
                    if (!empty($element['child_categories'])) {
                        foreach ($element['child_categories'] as $element2) {
                            $all_sorted_cat_ids[] = $element2['term_id'];
                
                            if (!empty($element2['child_categories'])) {
                                foreach ($element2['child_categories'] as $element3) {
                                    $all_sorted_cat_ids[] = $element3['term_id'];
                                }
                            }
                        }
                    }
                }
                
                $data['all_sorted_cat_ids'] = $all_sorted_cat_ids;
                
                
                $sorted_categories = $this->sortCats($category_list, $all_sorted_cat_ids);

                
                // $categories
                
                
                $data['unsorted_categories']          = $category_list;
                $data['categories']          = $sorted_categories;
                
                // $data['categories'] = $category_list;
                
                $data['is_liked']            = $this->is_liked_post($post_id,$user_id);
                $data['is_unliked']          = $this->is_unliked_post($post_id,$user_id);
                $data['total_likes']         = $this->totalLikes($post_id);
                $data['total_unlikes']       = $this->totalUnlikes($post_id);
                
                if($post_status != 'draft'){
                    $data['isExpired']           = $this->isExpired($post_id,$user_id);
                }
                else{
                    $data['isExpired']           = "no";
                }
                
                $data['isSend']              = get_post_meta($post_id,'is_Send',true);
                
                $data['previous_target_users'] = get_deal_users($post_id);
                
                $data['publish_date'] = $post['post_date'];
                $data['publish_date'] = $post['post_date_gmt'];
                
                
                
                
                
                $expiry  =  get_post_meta($post_id,'expiry',true);
                $date2   =  strtotime($expiry);
                $data['expired_at_str']   = $date2;
                
                
                
                $sch_dt  =  get_the_date( "Y-m-d H:i:s", $post_id );
                $date3   =  strtotime($sch_dt);
                $data['schedule_remaining_time_str'] = $date3;
                
                
            
            
                $tt = human_time_diff( strtotime($expiry), current_time( 'timestamp' ) );
                
                
                // https://app.tagster.com/socialfeed/125661
        
                $share_url = site_url().'/product/'.$post_id;
                $data['sharable_link'] = $share_url; 
                
                
                            
                // $timezone = get_user_meta($data['author'] ,'my_time_zone',true);

                // if(empty($timezone)){
                //     $timezone = 'America/New_York';
                // }
                
                // $user_dt = getTimeUTC($timezone, date('Y-m-d H:i:s',strtotime($post['post_date'])));
                
                // $postdate_sch = date('Y-m-d g:i a',strtotime($user_dt));
                // $postdate_gmt = gmdate('Y-m-d g:i a',strtotime($postdate_sch));
                            
                // $data['publish_date_old'] = $post['post_date'];
                // $data['publish_date'] = $postdate_gmt;
                            
                
                return $data;
        }
        
        
        function sortCats($arr_to_sort, $all_sorted_cat_ids) {
            // Create an associative array to store the indices of elements in the main_arr
            $indexMap = array_flip($all_sorted_cat_ids);
            
            // Sort the sub_arr based on the indices in the main_arr
            usort($arr_to_sort, function($a, $b) use ($indexMap) {
                if(isset($indexMap[$a->term_id])){
                    $indexA = $indexMap[$a->term_id];
                }
                else{
                    $indexA = -1;
                }
                
                if(isset($indexMap[$b->term_id])){
                    $indexB = $indexMap[$b->term_id];
                }
                else{
                    $indexB = -1;
                }
                
                return $indexA - $indexB;
            });
            
            return $arr_to_sort;
        }


    
        //Get deal information

        
        public function isExpired($post_id,$user_id){
            $expiry  = get_post_meta($post_id,'expiry',true);
            
           
            if(isset($expiry) && !empty($expiry)){
                $date = date("Y-m-d H:i:s");
                // $time = $this->getClientTimeZone($timezone,$date);
               
                if(strtotime($date) > strtotime($expiry)){
                    return 'yes';
                }else{
                    return 'no';
                }
            }else{
                return 'yes';
            }    
        }
        
        
        function getClientTimeZone($timezone, $time)
            {
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $name = $timeZone->getName();
                $UTC = new DateTimeZone($name);
                // $newTZ = new DateTimeZone($timezone);
                $date = new DateTime($time, $UTC);
                // $date->setTimezone($newTZ);
                $array_time = $date->format('Y-m-d H:i:s');
              
                return $array_time;
                
            }
        
        public function remainingTime($post_id){
            
            $expiry  =  get_post_meta($post_id,'expiry',true);
            $date1   =  strtotime(date("Y-m-d H:i:s"));
            $date2   =  strtotime($expiry);
            
            
            $tt = human_time_diff( strtotime($expiry), current_time( 'timestamp' ) );
            return $tt;
            
            if(isset($expiry) && !empty($expiry)){
                $totalSecondsDiff = abs($date2-$date1); //42600225
                $totalMinutesDiff = $totalSecondsDiff/60; //710003.75
                $totalHoursDiff   = $totalSecondsDiff/60/60;//11833.39
                $totalDaysDiff    = $totalSecondsDiff/60/60/24; //493.05
                
                if($totalMinutesDiff > 60 ){
                    if($totalHoursDiff > 24){
                        return (int)$totalDaysDiff .' Days';
                    }else{
                        return (int)$totalHoursDiff .' Hr';
                    }
                }else{
                    return (int)$totalMinutesDiff.' Min';
                }
            }else{
                return 0 .' Min';
            }
         
        }
        
        public function remainingTimeAgo($post_id){
            $expiry  =  get_post_meta($post_id,'expiry',true);
            $date1   =  strtotime(date("Y-m-d H:i:s"));
            $date2   =  strtotime($expiry);
            
            $tt = human_time_diff( strtotime($expiry), current_time( 'timestamp' ) );
            return $tt." ago";
            
            if(isset($expiry) && !empty($expiry)){
                $totalSecondsDiff = abs($date2-$date1); //42600225
                $totalMinutesDiff = $totalSecondsDiff/60; //710003.75
                $totalHoursDiff   = $totalSecondsDiff/60/60;//11833.39
                $totalDaysDiff    = $totalSecondsDiff/60/60/24; //493.05
                
                if($totalMinutesDiff > 60 ){
                    if($totalHoursDiff > 24){
                        return (int)$totalDaysDiff .' Days ago';
                    }else{
                        return (int)$totalHoursDiff .' Hr ago';
                    }
                }else{
                    return (int)$totalMinutesDiff.' Min ago';
                }
            }else{
                return 0 .' Min ago';
            }
         
        }
        
        public function uploadImage($base64_img, $title){
        	$upload_dir         =   wp_upload_dir();
        	$upload_path        =   str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ). DIRECTORY_SEPARATOR;
        	$explode            =   explode(";base64,", $base64_img);
            $img                =   str_replace(' ', '+', $explode['1']);
        	$decoded            =   base64_decode( $img );
        	$filename           =   $title . '.jpeg';
        	$file_type          =   'image/jpeg';
        	$hashed_filename    =   md5( $filename . microtime() ) . '_' . $filename;
        	$upload_file        =   file_put_contents( $upload_path . $hashed_filename, $decoded);
        	$attachment         =   array(
        		'post_mime_type' => $file_type,
        		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($hashed_filename)),
        		'post_content'   => '',
        		'post_status'    => 'inherit',
        		'guid'           => $upload_dir['url'] . '/' . basename($hashed_filename)
        	);
            $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename);
            
             // Generate attachment metadata and update it
            wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $upload_path . $hashed_filename));
        	return $attach_id;
        }
        
        
        public function remainingScheduleTime($post_id){

            $sch_dt  =  get_the_date( "Y-m-d H:i:s", $post_id );
            
            $date1   =  strtotime(date("Y-m-d H:i:s"));
            $date2   =  strtotime($sch_dt);
            
            $tt = human_time_diff( strtotime($sch_dt), current_time( 'timestamp' ) );
            return "After ".$tt;
            
            
            if(isset($sch_dt) && !empty($sch_dt)){
                $totalSecondsDiff = abs($date2-$date1); //42600225
                $totalMinutesDiff = $totalSecondsDiff/60; //710003.75
                $totalHoursDiff   = $totalSecondsDiff/60/60;//11833.39
                $totalDaysDiff    = $totalSecondsDiff/60/60/24; //493.05
                
                if($totalMinutesDiff > 60 ){
                    if($totalHoursDiff > 24){
                        return 'After '.(int)$totalDaysDiff .' Days';
                    }else{
                        return 'After '.(int)$totalHoursDiff .' Hr';
                    }
                }else{
                    return 'After '.(int)$totalMinutesDiff.' Min';
                }
            }else{
                return 'After 0 Min';
            }
         
        }
        
        //Get Available Messages
            public function getAvailableMessages($user_id){
              $availableMessages = 0;
              $query  = "SELECT * FROM `wp_user_messages` WHERE `user_id`=".$user_id;
            //   print_r($query);exit;
              $result = $this->wpdb->get_row($query,ARRAY_A);
              if(count($result) > 0){
                    $availableMessages = (int)$result['messages'];
              }
              return $availableMessages;
            }
        //Get Available Messages
        
        
        //Save User categories
        public function saveUserCategories($user_id,array $categores = null){
            if($categores != null ){
               $insert_data = array();
               $this->wpdb->query("DELETE FROM `wp_users_categories` WHERE user_id=".$user_id);  
               foreach($categores as $cat){
                    $insert_data['user_id'] = $user_id;
                    $insert_data['cat_id']  = $cat;
                    // $insert_data['from_time']    = $cat['from_time'] ? $cat['from_time'] : 0;
                    // $insert_data['to_time']      = $cat['to_time']   ? $cat['to_time'] : 0;
                    $this->wpdb->insert("wp_users_categories",$insert_data);
                }
            }else{
                $this->wpdb->delete("wp_users_categories",array("user_id"=>$user_id));
            }
        }
        //Save User categories
        
        //Get User categories
        public function getUserCategories($user_id){
          $categories = array();
          $query  = "SELECT * FROM `wp_users_categories` WHERE `user_id`=".$user_id;
          $result = $this->wpdb->get_results($query,ARRAY_A);
          if(count($result) > 0){
             foreach($result as $row){
                $categories[] = (int)$row['cat_id'];
                // $tmp = array();
                // $tmp['term_id']      = $row['cat_id'];
                // $tmp['from_time']    = $row['from_time'] ? $row['from_time'] : 0;;
                // $tmp['to_time']      = $row['to_time'] ? $row['to_time'] : 0;;
                // $categories[]        = $tmp;
             } 
          }
          return $categories;
        }
        //Get User categories
        
        //Get specific comment Data
        public function get_cat_informations($term_id, array $result1 = null, array $args = null){
            $data = array();
            if($result1 == null){
                $result1 = get_term($term_id,'lighting_cats');
                $result1 = json_decode(json_encode($result1, true), true);
            }
            
            $data['term_id'] = $result1['term_id'];
            $data['name']    = $result1['name'];
            $data['isChecked']    = false;
            
         
            
            if(isset($args['child_categories']) && !empty($args['child_categories']) && $args['child_categories'] == true){
                $data['show_child_categories'] = "Show";
                $child_comments = array();
                $cmt2_res=get_terms('lighting_cats', array(
                   'parent'   => $term_id,
                   'hide_empty' => false,
                  ));
                
                $cmt2_res = json_decode(json_encode($cmt2_res, true), true);
                if(count($cmt2_res) > 0){
                    $data['child_categories'] = $cmt2_res;
                    foreach($cmt2_res as $roww){
                       
                       if(isset($args['user_id']) && !empty($args['user_id'])){
                           $subData =   $this->get_cat_informations($roww['term_id'], $roww, array('user_id' => $user_id,'child_categories' => true));
                           $user_id = $args['user_id'];
                           $isDay        =    $this->checkSubCatDaytime($user_id,$roww['term_id']);
                           $subData['isDayTime'] = $isDay;
                           
                        //   $TimePeriod = $this->getCatTimePeriod($user_id,$roww['term_id']);
                        //   $subData['from_time'] = $TimePeriod['from_time'];
                        //   $subData['to_time']   = $TimePeriod['to_time'];
                           
                        }else{
                            $subData =   $this->get_cat_informations($roww['term_id'], $roww, array('child_categories' => true));
                            
                        }
                        $child_comments[] = $subData;
                    }
                }
                $data['child_categories'] = $child_comments;
            }
            return $data;
        }
        
        public function getCatTimePeriod($user_id ,$cat_id){
            $data = array('from_time' => '', 'to_time' => '');
            $query   = "SELECT * FROM `wp_users_categories` WHERE `user_id` = $user_id AND `cat_id` = $cat_id ";
            $respons = $this->wpdb->get_row($query,ARRAY_A);
            if($respons){
                $data['from_time'] = $respons['from_time'];
                $data['to_time']   = $respons['to_time'];
            }
            
            return $data;
        }
        
        public function userLocations($user_id){
            $location = array();
            $query  = "SELECT * FROM `wp_user_locations` WHERE `user_id` = $user_id";
            $result = $this->wpdb->get_results($query,ARRAY_A);
            
            if(count($result) > 0){
                foreach($result as $row){
                    $tmp = array();
                    $tmp['location']  = $row['location'];
                    $tmp['latitude']  = (float)$row['latitude'];
                    $tmp['longitude'] = (float)$row['longitude'];
                    $tmp['radius']    = $row['radius'];
                    $tmp['type']      = $row['type'];
                    $tmp['created']   = date('Y-m-d',$row['created']);
                    $location[] = $tmp;
                 }
            }
            return $location;
        }
        
        public function is_liked_post($post_id,$user_id){
            if(isset($user_id) && !empty($user_id)){
                $select = "SELECT * FROM `wp_ulike` WHERE `status`= 'like' AND `user_id`= $user_id AND `post_id` = $post_id ";
                $result = $this->wpdb->get_results($select,ARRAY_A);
                if(count($result) > 0){
                    return true;
                }else{
                    return false; 
                }
            }else{
                return false; 
            }    
        }
        
        public function is_unliked_post($post_id,$user_id){
            if(isset($user_id) && !empty($user_id)){
                $select = "SELECT * FROM `wp_ulike` WHERE `status`= 'unlike' AND `user_id`= $user_id AND `post_id` = $post_id ";
                $result = $this->wpdb->get_results($select,ARRAY_A);
                if(count($result) > 0){
                    return true;
                }else{
                    return false; 
                }
            }else{
                return false; 
            }
        }
        
        public function totalLikes($post_id){
            $select = "SELECT * FROM `wp_ulike` WHERE `status`= 'like' AND `post_id` = $post_id ";
            $result = $this->wpdb->get_results($select,ARRAY_A);
            return count($result);
        }
        
        public function totalUnlikes($post_id){
            $select = "SELECT * FROM `wp_ulike` WHERE `status`= 'unlike' AND `post_id` = $post_id ";
            $result = $this->wpdb->get_results($select,ARRAY_A);
            return count($result);
        }
        
        
        public function getUserInformation($user_id){
            $data = array();
            $user = get_userdata($user_id);
            $role = 'customer';
            if (in_array('business', (array) $user->roles)) {
                $role = 'business';
            } else {
                $role = $user->roles[0];
            }
            
           $useravatar = get_user_meta($user_id, 'wp_user_avatar', true);
            if ($useravatar) {
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
            
            $data['role']       = $role;
            $data['user_id']    = $user_id;
            $data['username']   = $user->user_login;
            $data['email']      = $user->user_email;
            $data['first_name'] = get_user_meta($user_id, 'first_name', true);
            $data['last_name']  = get_user_meta($user_id, 'last_name', true);
            $data['phone']      = get_user_meta($user_id, 'phone', true);
            
            $data['my_time_zone']      = get_user_meta($user_id, 'my_time_zone', true);
            

            if($role == 'business'){
                $business_profile = get_user_meta($user_id, 'business_profile', true);
                if(isset($business_profile) && !empty($business_profile)){
                    $business = wp_get_attachment_image_src($business_profile, array('300', '300'), true);
                    $data['business_profile'] = $business[0];
                }else{
                    $data['business_profile'] = site_url().'/wp-content/uploads/2022/04/contact.png';
                }
                
                $data['business_name']          = get_user_meta($user_id, 'business_name', true);
                $data['business_address']       = get_user_meta($user_id, 'business_address', true);
                $data['business_phone_number']  = get_user_meta($user_id, 'business_phone_number', true);
                $data['business_description']   = get_user_meta($user_id, 'business_description', true);
                $data['business_radius']        = get_user_meta($user_id, 'radius', true);
                $data['business_lat']        = get_user_meta($user_id, 'lat', true);
                $data['business_long']        = get_user_meta($user_id, 'long', true);
                $data['notiﬁcations_type']  = get_user_meta($user_id, 'notiﬁcations_type', true);
                $date['timezone']            =get_user_meta($user_id, 'timezone',true);
                
                $data['facebook_link']  = get_user_meta($user_id, 'facebook_link', true);
                $data['instagram_link'] = get_user_meta($user_id, 'instagram_link', true);
                $data['youtube_link']   = get_user_meta($user_id, 'youtube_link', true);
                $data['twitter_link']   = get_user_meta($user_id, 'twitter_link', true);
                
            }
            return $data;
        }
        
         public function sendMyMail($email, $type,$title, $msg){
            $user    = get_user_by_email( $email );
            $name    = get_user_meta($user->ID,'first_name',true).' '.get_user_meta($user->ID,'last_name',true);
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $subject = $type.' |  Lightning bug App';
            $message = '<div style="background-image: url('.$this->site_url.'/wp-content/uploads/2024/01/bg.png); display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px;background-repeat: no-repeat;background-size: cover;"><img src="'.$this->site_url.'/wp-content/uploads/2024/01/icon.png" style="margin: auto; padding: 5px; width: 100px;"> 
                        </div><h3 style="text-align: center; margin:10px 0px;">'.$title.'</h3>
                        <br>Dear <strong>'.$name.'</strong>,
                        <br/>'.$msg.'<br/>Thank you!<br/>
                        <footer style="background-image: url('.$this->site_url.'/wp-content/uploads/2024/01/bg.png);padding: 5px;background-repeat: no-repeat;background-size: cover;height:50px">
                        <h3 style="text-align: center; margin:0px; font-size: 14px; color: white;line-height: 0px;"> Lightning bug App</h3>
                        </footer>';
            $mailResult = wp_mail($email, $subject, $message, $headers);
            return $mailResult;
        }
    }

    global $customHelperObj;
    $customHelperObj = new customHelper();