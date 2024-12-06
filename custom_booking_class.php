<?php
    add_action('rest_api_init', function(){
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        add_filter('rest_pre_serve_request', function ($value) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Credentials: true');
            return $value;
        });
        
        
        
        register_rest_route('mobileapi/v1/', '/get_completed_booking', array(
            'methods'   => 'POST',
            'callback'  => 'get_completed_booking',
        ));
        
         register_rest_route('mobileapi/v1/', '/get_barber_booked_time_on_date', array(
            'methods'   => 'POST',
            'callback'  => 'get_barber_booked_time_on_date',
        ));
        
    });

Class Custom_booking{
    /* Member variables */
    var $wpdb;
    function __construct() {
        global $wpdb, $time_stamp;
        $this->wpdb = $wpdb;
        $this->time_stamp = time();
    }
    
    
    public function get_booking_type($booking_id){
       $query = "SELECT * FROM `wp_booking` WHERE `booking_id` = ".$booking_id." ORDER BY `booking_id` DESC";
       $result = $this->wpdb->get_row($query,ARRAY_A);
       $response = $result['select_type'];
       return $response;
    }
    
    public function get_booking_barber($booking_id){
       $query = "SELECT * FROM `wp_booking` WHERE `booking_id` = ".$booking_id." ORDER BY `booking_id` DESC";
       $result = $this->wpdb->get_row($query,ARRAY_A);
       $response = $result['barber_id'];
       return $response;
    }    
    
   public function check_service_home_availability($barber_id,$service_id){
       
       $query = "SELECT * FROM `wp_users_services` WHERE `user_id` = ".$barber_id." AND `service_id` = ".$service_id." ORDER BY `id` DESC";
       $result = $this->wpdb->get_row($query,ARRAY_A);
       $response = $result['type'];
       return $response;
    }
    
    
    public function send_email($user_id,$message){
        
        $res = get_user_by('ID',$user_id);
        $userInfo = $res->data;
        $to = $userInfo->user_email;
        // $to = 'meram40315@nhmty.com';
        
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $name = $first_name . " " . $last_name;
        if ($first_name == '' && $last_name == '') {
           $name = $userInfo->display_name;
        }
        
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $name . ' <' . $to . '>';
        $subject   = 'Service Booking';
        
        $body = '<div style="max-width: 800px;width: 100%;margin: 0px auto;">
                               <div  style="width: 100%; border-spacing: 0px; box-shadow: 0px 0px 5px #c3c3c3 !important;">
                                <table style="width: 100%;">
                                    <thead style="width: 100%;background: #ee7182;">
                                        <tr style="height: 10px;">
                                            <td style="padding-bottom:10px;"><img src="https://lightningbug.betaplanets.com/wp-content/uploads/2021/07/icon.png" style=" width: 20%"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td  style="padding: 20px;">
                                                <p><strong>'.$name.' </strong> </p>
                                                <p><strong>'.date("Y F d").'</strong> </p>
                                               
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="margin-left:10%"><p>'.$message.'</p></div>
                                <h4 style="margin-left:40% ">Thanks for using our app</h4>
                                <div style="width: 97%; background: #ea697d;color: #fff; text-align: center; padding: 10px;">© '.date("Y F d").' Site. All Rights Reserved.</div>
                               </div>
                               
                            </div>';
        
        if (wp_mail($to, $subject, $body, $headers)) {
            return   "Send";
        } else {
          return "Not Send";
        }
        
        // return $body;
    }
    
    
    public function get_shop_review($shop_id){
        $data = array();
        $select = "SELECT * FROM `wp_custom_user_rating` WHERE 	`to_user_id` = $shop_id and `user_type`= 'customer'";
        $result = $this->wpdb->get_results($select,ARRAY_A);
        $total_rating = 0 ;
        foreach($result as $row){
            $review = array();
            $review['review_id'] = $row['id']; 
            $review['user_id']   = $row['user_id'];
            $useravatar = get_user_meta($row['user_id'], 'wp_user_avatar', true);
            if($useravatar){
                $img = wp_get_attachment_image_src($useravatar, array('150','150'),true);
                $user_avatar = $img[0];
                $review['user_img'] = $user_avatar;
            }
            else{
                $review['user_img'] = 'http://1.gravatar.com/avatar/1aedb8d9dc4751e229a335e371db8058?s=96&d=mm&r=g';
            }
            $review['user_name'] = ucfirst(get_user_meta($row['user_id'],'first_name',true).' '.get_user_meta($row['user_id'],'last_name',true)); 
            $review['type']      = $row['type']; 
            $review['rating']    = number_format($row['rating'],1); 
            $review['feedback']  = $row['feedback']; 
            $review['created']   = date('d M Y' , strtotime($row['created'])); 
            
            $reviews[] = $review;
            
            $total_rating = $total_rating + $row['rating'];
        }
        
        $avg_rating = $total_rating / count($reviews);
        
        $data['total_reviews']= count($reviews);
        $data['avg_rating']= number_format($avg_rating,1);
        
        $data['reviews'] = $reviews;
        
        return $data;
    }
    
      public function get_user_review($user_id){
        $data = array();
        $select = "SELECT * FROM `wp_custom_user_rating` WHERE 	`to_user_id` = $user_id and `user_type`= 'barber'";
        $result = $this->wpdb->get_results($select,ARRAY_A);
        $total_rating = 0 ;
        foreach($result as $row){
            $review = array();
            $review['review_id'] = $row['id']; 
            $review['user_id']   = $row['user_id'];
            $useravatar = get_user_meta($row['user_id'], 'wp_user_avatar', true);
            if($useravatar){
                $img = wp_get_attachment_image_src($useravatar, array('150','150'),true);
                $user_avatar = $img[0];
                $review['user_img'] = $user_avatar;
            }
            else{
                $review['user_img'] = 'http://1.gravatar.com/avatar/1aedb8d9dc4751e229a335e371db8058?s=96&d=mm&r=g';
            }
            $review['user_name'] = ucfirst(get_user_meta($row['user_id'],'first_name',true).' '.get_user_meta($row['user_id'],'last_name',true)); 
            $review['type']      = $row['type']; 
            $review['rating']    = number_format($row['rating'],1); 
            $review['feedback']  = $row['feedback']; 
            $review['created']   = date('d M Y' , strtotime($row['created'])); 
            
            $reviews[] = $review;
            
            $total_rating = $total_rating + $row['rating'];
        }
        
        $avg_rating = $total_rating / count($reviews);
        
        $data['total_reviews']= count($reviews);
        $data['avg_rating']= number_format($avg_rating,1);
        
        $data['reviews'] = $reviews;
        
        return $data;
    }
    
    public function get_booking_review($booking_id){
        $review = array();
        $review['shop_review'] = $this->get_booking_shop_review($booking_id);
        $review['user_review'] = $this->get_booking_user_review($booking_id);
        
        return $review;
    }
    
     public function get_booking_user_review($booking_id){
        $select = "SELECT * FROM `wp_custom_user_rating` WHERE post_id = $booking_id and `user_type`= 'customer'";
        $row = $this->wpdb->get_row($select,ARRAY_A);
        $review = array();
     
        if(count($row) > 0){
            $review['review_id'] = $row['id']; 
            $review['user_id']   = $row['user_id'];
            $useravatar = get_user_meta($row['user_id'], 'wp_user_avatar', true);
            if($useravatar){
                $img = wp_get_attachment_image_src($useravatar, array('150','150'),true);
                $user_avatar = $img[0];
                $review['user_img'] = $user_avatar;
            }
            else{
                $review['user_img'] = 'http://1.gravatar.com/avatar/1aedb8d9dc4751e229a335e371db8058?s=96&d=mm&r=g';
            }
            $review['user_name'] = ucfirst(get_user_meta($row['user_id'],'first_name',true).' '.get_user_meta($row['user_id'],'last_name',true)); 
            $review['type']      = $row['type']; 
            $review['rating']    = number_format($row['rating'],1); 
            $review['feedback']  = $row['feedback']; 
            $review['created']   = date('d M Y' , strtotime($row['created'])); 
        }
        
        return $review;
    }
    
      public function get_booking_shop_review($booking_id){
        $select = "SELECT * FROM `wp_custom_user_rating` WHERE post_id = $booking_id and `user_type`= 'barber'";
        $row = $this->wpdb->get_row($select,ARRAY_A);
        $review = array();
        if(count($row) > 0){
            $review['review_id'] = $row['id']; 
            $review['user_id']   = $row['user_id'];
            $useravatar = get_user_meta($row['user_id'], 'wp_user_avatar', true);
            if($useravatar){
                $img = wp_get_attachment_image_src($useravatar, array('150','150'),true);
                $user_avatar = $img[0];
                $review['user_img'] = $user_avatar;
            }
            else{
                $review['user_img'] = 'http://1.gravatar.com/avatar/1aedb8d9dc4751e229a335e371db8058?s=96&d=mm&r=g';
            }
            $review['user_name'] = ucfirst(get_user_meta($row['user_id'],'shop_name',true)); 
            $review['type']      = $row['type']; 
            $review['rating']    = number_format($row['rating'],1); 
            $review['feedback']  = $row['feedback']; 
            $review['created']   = date('d M Y' , strtotime($row['created'])); 
       }
        
        return $review;
    }
    
    //Get All Booking(their services) by barber_id and status 
    function get_booking_by_barberId_and_status($barber_id = null, $status = null, array $arg = null){
        
        $data = array();
        $more_condition= '';
        if($barber_id!=''){
            $more_condition.= " AND wp_booking.trainer_id='$barber_id' ";
        }
        if(isset($arg['date']) && $arg['date']!=''){
            
            $more_condition.= " AND DATE(wp_booking.date)= DATE('".$arg['date']."') ";
        }
        
        if(isset($status)){
            if(is_array($status)){
                $status  = implode(',',$status);
                $more_condition.= " AND wp_booking.status IN ($status) ";
            }else{
                $more_condition.= " AND wp_booking.status='$status' ";
            }
        }
    
        $select = "SELECT wp_booking.*, (SELECT SUM(wp_booking_services.item * wp_booking_services.amount) FROM wp_booking_services WHERE wp_booking_services.booking_id = wp_booking.booking_id) as total_service_amount FROM wp_booking WHERE wp_booking.booking_id > 0 $more_condition ORDER BY wp_booking.time ASC";
        
        /*****TESTING On POSTMAN *****/
        // if(isset($arg['postman'])){
            // echo $select;
        // }
        /*****TESTING On POSTMAN *****/
        
        $results = $this->wpdb->get_results($select,ARRAY_A);
        foreach($results as $row){
            $tmp = array();
            $tmp['booking_id']          = $row['booking_id'];
            $tmp['barber_id']           = $row['trainer_id']; 
            $tmp['user_id']             = $row['user_id'];
            $tmp['stripe_card_id']      = $row['stripe_card_id']; 
            $tmp['date']                = $row['date'];
            $tmp['time']                = $row['time'];
            $tmp['day']                 = $row['day'];
            $tmp['total_service_amount']= $row['total_service_amount'];
            $tmp['status']              = $row['status'];
            $tmp['payment_status']      = $row['payment_status'];
            $tmp['payment_transaction_id'] = $row['payment_transaction_id'];
            $tmp['payment_log']         = $row['payment_log'];
            $tmp['paymentIntent_id']    = $row['paymentIntent_id'];
            //$tmp['paymentIntent_success_log'] = $row['paymentIntent_success_log'];
            //$tmp['paymentIntent_error_log'] = $row['paymentIntent_error_log'];
            $tmp['created']             = $row['created'];  
            
            if(isset($arg['booking_services']) && $arg['booking_services'] == true){
                $results1 = $this->get_booking_services_details_by_booking_id($row['booking_id'], array());
                $tmp['services_list'] =  $results1;
            }else{
                $tmp['services_list'] =  false;
            }
            
            $data[] = $tmp;
        }
        if(count($data)>0){
            return $data;
        }else{
            return false;
        }
        
    }
    
    //Getting All Services by Booking_id
    function get_booking_services_details_by_booking_id($booking_id = null, array $arg = null){
        $more_condition= '';
        if($booking_id!=''){
            $more_condition.= " AND wp_booking_services.booking_id='$booking_id' ";
        }
        // $select = "SELECT * FROM wp_booking_services WHERE wp_booking_services.booking_service_id > 0 $more_condition ORDER BY wp_booking_services.created DESC";
        $select_barber_id = "(SELECT barber_id FROM wp_booking WHERE wp_booking.booking_id=wp_booking_services.booking_id) as trainer_id ";
        $select_service_time = "(SELECT time FROM wp_users_services WHERE (wp_users_services.user_id = trainer_id) AND (wp_users_services.id=wp_booking_services.service_id)) as time ";
         $select = "SELECT *,$select_barber_id, $select_service_time FROM wp_booking_services WHERE wp_booking_services.booking_service_id > 0 $more_condition ORDER BY wp_booking_services.created DESC";
         
        //  echo $select; exit;
         
        $results = $this->wpdb->get_results($select,ARRAY_A);
        if(count($results)>0){
            return $results;
        }else{
            return false;
        }
    }
    
    //get Total Duration of booked Services
    function get_totat_service_duration($barber_id = null, array $service_ids_quantities = null, array $arg = null){
        $more_conditions = "";
        if(isset($barber_id) && $barber_id!=''){
            $more_conditions.= "AND wp_users_services.user_id = '$barber_id' ";
        }
        $total_service_duration = 0;
        foreach($service_ids_quantities as $service_id => $value){
            $select ="SELECT SUM(wp_users_services.time * ".$service_ids_quantities[$service_id].") as service_duration FROM wp_users_services WHERE wp_users_services.id ='$service_id' $more_conditions";
            $tmp = $this->wpdb->get_var($select);
            $total_service_duration = $total_service_duration + $tmp;
        }
        return $total_service_duration;
    }
    
    //CHeck Booking Time Slot of User's Booking
    function check_booking_time_slot($user_id = null, $barber_id = null, array $myservices = null, array $booking_time_info = null){
        $response = array();
        $response['error']  = false;
        $response['msg']    = "Success.";
        $response['allow']  = false;
        
        $shop_name = get_user_meta($barber_id,'shop_name',true);
        
        $select_time = $booking_time_info['select_time'];
        $select_date = $booking_time_info['select_date'];
        $select_day = $booking_time_info['select_day'];
        $start_datetime = $select_date." ".$select_time;
        //-> Code For Get Service Duration
        $booked_service_ids = array_values(array_column($myservices, 'service_id'));
        $booked_service_quantity = array_values(array_column($myservices, 'item'));
        $service_ids_quantities = array();
        foreach($booked_service_ids as $key => $value1){
            $service_ids_quantities[$booked_service_ids[$key]] =    $booked_service_quantity[$key];
        }
        //Getting Total Duration Of Booked Services.(Return In Minutes)
        $service_duration_minute = $this->get_totat_service_duration($barber_id, $service_ids_quantities, array());
        //-> Code For Get Service Duration
        
        $Service_StartDate = date("Y-m-d", strtotime($select_date));
        // $Service_EndDate = date("Y-m-d", strtotime('+'.$service_duration_minute.' minutes',strtotime($Service_StartDate)));
        $Service_EndDate = date("Y-m-d", strtotime($Service_StartDate));
            
        $Service_StartTime = date("h:i A", strtotime($select_time));
        $Service_EndTime = date("h:i A", strtotime('+'.$service_duration_minute.' minutes',strtotime($Service_StartTime)));
        //Getting Barber Shop (Opening & closing Time) and working time Slot by (Date & barber Id). 
        $shop_working_times = $this->get_shop_opening_closing_time_on_date($barber_id, $select_date, array());
        
        $start1 = date("Y-m-d h:i A", strtotime($start_datetime));
        $end1 = date("Y-m-d h:i A", strtotime('+'.$service_duration_minute.' minutes',strtotime($start_datetime)));
        $requested_time_slot = $this->SplitTime($start1, $end1,60,$select_date,$select_date);
        array_shift($requested_time_slot);
        $requested_time_slot = array_values($requested_time_slot);
        //Barber Shop is Open Or Not
        if($shop_working_times != false){ 
            $matched_time_slot = array();
            $select = "SELECT * FROM wp_booking WHERE wp_booking.trainer_id ='$barber_id' AND DATE(wp_booking.date) = DATE('".$Service_StartDate."') AND wp_booking.status IN (1,4) ";
            $result = $this->wpdb->get_results($select, ARRAY_A);
            if(count($result)>0){
                $booked_time_slot = array();
                foreach($result as $row){
                    $booked_services = $this->get_booking_services_details_by_booking_id($row['booking_id'], array());
                    $booked_service_ids = array_values(array_column($booked_services, 'service_id'));
                    $booked_service_quantity = array_values(array_column($booked_services, 'item'));
                    $service_ids_quantities = array();
                    foreach($booked_service_ids as $key => $value1){
                        $service_ids_quantities[$booked_service_ids[$key]] =    $booked_service_quantity[$key];
                    }
                    //Getting Total Duration Of Booked Services.(Return In Minutes)
                    $booked_serv_duration_min = $this->get_totat_service_duration($barber_id, $service_ids_quantities, array());
                    $Booked_StartTime = date("Y-m-d h:i A", strtotime($row['date']." ".$row['time']));
                    $Booked_EndTime = date("Y-m-d h:i A", strtotime('+'.$service_duration_minute.' minutes',strtotime($Booked_StartTime)));
                    
                    $tmp_booked_timeslot = $this->SplitTime($Booked_StartTime, $Booked_EndTime,60,$select_date);
                    
                    $booked_time_slot = array_merge($booked_time_slot,$tmp_booked_timeslot);
                }
                $matched_time_slot = array_intersect($booked_time_slot,$requested_time_slot);
                
                // $matched_time_slot = array_merge($matched_time_slot,array_intersect($booked_TimeSlot,$requested_time_slot));
               
            }
            if(count($matched_time_slot)<=0){
                $response['allow'] = true;
            }else{
                $response['error'] = true;
                foreach($matched_time_slot as $date1){
                    $tmp[] = date('h:i A', strtotime($date1));
                }
                $response['msg'] = "Sorry, Trainer does not accept booking on this day.";
            }
        }else{
            $response['error'] = true;
            $response['msg'] = "Sorry, Trainer does not accept booking on this day.";
        }
        return $response;
    }
    
    // Split Working hours on a specific durations
    function SplitTime($start_time = null, $end_time = null, $duration = '60',$date=NULL){
        $ReturnArray = array ();// Define output
        $start_time    = strtotime ($start_time); //Get Timestamp
        $end_time      = strtotime ($end_time); //Get Timestamp
        
        date_default_timezone_set('US/Eastern');
        
      
  
       $currenttime = date('H:i:s:u');
        $currentDate= date('Y-m-d');
       //echo "date" .$date;
       list($hrs,$mins,$secs,$msecs) = split(':',$currenttime);
       
         if($currentDate > $date){
              return $ReturnArray;
         }
       
    
        $AddMins  = $duration * 60;
        while ($start_time <= $end_time){ //Run loop
            $hr = date ("H", $start_time);
            if($currentDate==$date){
            if($hrs < $hr){
               $ReturnArray[] = date ("G:i", $start_time);
            }
            }else{
              $ReturnArray[] = date ("G:i", $start_time);  
            }
            $start_time += $AddMins; //Endtime check
        }
        return $ReturnArray;
    }
    
    //Getting Barber Shop (Opening & closing Time) and working time Slot. 
    function get_shop_opening_closing_time_on_date($barber_id = null, $date = null, array $arg = null){
        $response = array();
        //date to timestamp
        $timestamp = strtotime($date);
        
        //to get to number of the day (0 to 6, 0 being sunday, and 6 being saturday) :
        $day = date('w', $timestamp);
        
        //Get Barber Working Days;
        $working = GetWorkingDays($barber_id);
       
        $working_day_index = ($day == '0') ? "is_sunday" : (($day == 1)? "is_monday" : (($day == 2)? "is_tuesday" : (($day == 3)? "is_wednesday" : (($day == 4)?"is_thursday" : (($day == 5)?"is_friday" : (($day == 6) ? "is_saturdy" : null ))))));
        
        //Checking that the barber is working On inputed Date(Day) or Not
        if($working[$working_day_index] != false){
            $day_working_info = $working[$working_day_index];
            
            //Getting Shop Opening Time
            $format = 'YYYY-MM-DDTHH:mm:ssTZD';
            $date1_obj = new DateTime($day_working_info['from_time']);
            $shop_open_time = $date1_obj->format('Y-m-d H:i');//Shop Opening Time
            
            //Getting Shop Closing Time
            $date2_obj = new DateTime($day_working_info['to_time']);
            $shop_close_time = $date2_obj->format('Y-m-d H:i');//Shop Closeing Time
            
            //Get Working Time Slot In diffrence of 5 mintue
            $interval = '60';
            $working_time_slot = $this->SplitTime($shop_open_time,$shop_close_time,$interval,$date);
            $response['opening_time'] = $date1_obj->format('H:i');
            $response['closing_time'] = $date2_obj->format('H:i');
            $response['working_time_slot'] = $working_time_slot;
            return $response;
        }else{
            return false;
        }
    }
    
    //
    // function get_total_service_amount_of_bookings($booking_ids = null){
    //     if(is_array($booking_ids)){
    //         $select = "SELECT * FROM wp";     
    //         echo "<pre>";
    //         print_r($booking_ids);
    //         echo "</pre>";
    //         echo "Array";
           
    //     }else{
        
    //     }
    // }
}

global $custom_booking_obj;
$custom_booking_obj = new Custom_booking();

//[START]=> Get Available Time On Given Date
function get_barber_booked_time_on_date($request){
    date_default_timezone_set("America/New_York");
    $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
    $param = $request->get_params();  
    $token = $param['token'];
    $user_id = GetMobileAPIUserByIdToken($token);
    $barber_id = $param['barber_id'];
    $date = $param['date'];
    if($user_id){
        if($param['postman']){
            
        }
        if($barber_id==''){
            $data['status'] = "error";
            $data['error_code'] = "barber_id is missing..";
            $data['errormsg'] = "Please choose Barber";
            return new WP_REST_Response($data, 403);
        }
        if($date==''){
            $data['status'] = "error";
            $data['error_code'] = "date is missing";
            $data['errormsg'] = "Please choose date";
            return new WP_REST_Response($data, 403);
        }
        $shop_name = get_user_meta($barber_id,'shop_name',true);
        //Custom_booking Class Objects
        global $custom_booking_obj;
       
        //Getting Barber Shop (Opening & closing Time) and working time Slot by (Date & barber Id). 
        $shop_working_times = $custom_booking_obj->get_shop_opening_closing_time_on_date($barber_id, $date, array());
        // return new WP_REST_Response($shop_working_times, 200);   
        //Barber Shop is Open Or Not
        if($shop_working_times != false){
            //Barber Working Time Details(Opening, Closing, working Time Slot) On Inputed date(Day) 
            $working_time_slot = $shop_working_times['working_time_slot'];
           
            $arg1 = array(
                'postman'           => @$param['postman'], // Uncomment and Test the called Function
                'booking_services'  => true,
                'date'              => $date
            );
            
            $status = array(1,4); // 1= Accepted, 2 = Completed, 4= Start
            $all_booking = $custom_booking_obj->get_booking_by_barberId_and_status($barber_id,$status,$arg1);
            
            $booked_time = array();
            $booked_time_slot = array();
            if(count($all_booking)>0){
                foreach(@$all_booking as $row){
                    $tmp =array();
                    $booked_service_ids = array_values(array_column($row['services_list'], 'service_id'));
                    $booked_service_quantity = array_values(array_column($row['services_list'], 'item'));
                    
                    $service_ids_quantities = array();
                    foreach($booked_service_ids as $key => $value1){
                        $service_ids_quantities[$booked_service_ids[$key]] =    $booked_service_quantity[$key];
                    }
                    
                  
                    
                    //Getting Total Duration Of Booked Services.(Return In Minutes)
                    $service_duration_minute = $custom_booking_obj->get_totat_service_duration($barber_id, $service_ids_quantities, array());
                    
                    //Getting Booking Time.
                    $format = 'YYYY-MM-DDTHH:mm:ssTZD';
                    $date3_obj = new DateTime($row['time']);
                    $Service_StartTime = $date3_obj->format('Y-m-d h:i A'); // Service Start Time
                    
                    //Add Total_Service_Duration in Booking Time.
                    $Service_EndTime = date("Y-m-d h:i A", strtotime('+'.$service_duration_minute.' minutes',strtotime($Service_StartTime)));
                    
                    $tmp['start_time']      = date('h:i A',strtotime($Service_StartTime));
                    $tmp['end_time']        = date('h:i A',strtotime($Service_EndTime));
                    $tmp['duration_mintue'] = $service_duration_minute;
                    $booked_time[] = $tmp;
                    
                    //Current Booked Time Slot
                    $tmp_booked_time_slot = $custom_booking_obj->SplitTime($tmp['start_time'],$tmp['end_time'],60,$arg1['date']);
                   
                    $remove_last_timeslots[]= array_pop($tmp_booked_time_slot);
                   
                    //All Booked Time Slot 
                    $booked_time_slot = array_unique(array_merge($booked_time_slot,$tmp_booked_time_slot));
                }
            }
            
            $unmatch_time = array_diff($working_time_slot,$booked_time_slot);
            $avilable_time_slot = array();
            foreach($unmatch_time as $date){
                $avilable_time_slot[] = date('h:i A',strtotime($date));
            }
            $data['avilable_time_slot'] = $avilable_time_slot;
             $data['working_time_slot'] = $working_time_slot;
            
            /**************/
            $tmp1 = array();
            $new_arr1 = array_merge($booked_time_slot, $remove_last_timeslots);
            foreach($new_arr1 as $date){
                $tmp1[] = date('h:i A',strtotime($date));
            }
            $data['booked_time_slot'] = $tmp1;
            
            $data['booked_times'] = $booked_time;
            return new WP_REST_Response($data, 200);
        }else{
            $data['status'] = "error";
            $data['error_code'] = "'".ucfirst($shop_name)."' is closed on this day(".date('l', strtotime($date)).")";
            $data['errormsg'] = "'".ucfirst($shop_name)."' is closed on this day(".date('l', strtotime($date)).")";
            return new WP_REST_Response($data, 403);
        }
    }else{
        $data['status'] = "error";
        $data['error_code'] = "user_expire";
        $data['errormsg'] = "Something went wrong.";
        return new WP_REST_Response($data, 403);
    }
}
//[END]=> Get Available Time On Given Date


//[START]=> The Below Function is written for return all completed booking.
function get_completed_booking($request){
    date_default_timezone_set("America/New_York");
    global $wpdb;
    $data=array("status"=>"ok","errormsg"=>"",'error_code'=>"");
    $param = $request->get_params();  
    $token = $param['token'];
    $user_id = GetMobileAPIUserByIdToken($token);
    if($user_id){
        global $custom_booking_obj;
        $arg = array('booking_services'=> true);
        //3 = Complete Booking
        $booking_list = $custom_booking_obj->get_booking_by_barberId_and_status($user_id,3, $arg);
        $data['booking_list'] = $booking_list;
        if(count($booking_list)>0){
            $data['msg'] = "There are some completed booking.";
            return new WP_REST_Response($data, 200);
        }else{
            $data['status'] = "error";
            $data['error_code'] = "completed_booking_are_not_found.";
            $data['errormsg'] = "There are no any completed booking.";
            return new WP_REST_Response($data, 403);
        }
    }else{
        $data['status'] = "error";
        $data['error_code'] = "user_expire";
        $data['errormsg'] = "Something went wrong.";
        return new WP_REST_Response($data, 403);
    }
}
//[END]=> The Below Function is written for return all completed booking.



?>
