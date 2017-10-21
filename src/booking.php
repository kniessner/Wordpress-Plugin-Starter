
<?php
add_action( 'wp_ajax_delete_booking_metadata', 'delete_booking_metadata' );
add_action( 'wp_ajax_nopriv_delete_booking_metadata', 'delete_booking_metadata' );


add_action( 'wp_ajax_bookProject', 'bookProject' );
add_action( 'wp_ajax_nopriv_bookProject', 'bookProject' );


function log_booking($type, $message){

  $option_name = 'node17_logs' ;
  date_default_timezone_set("Europe/Berlin");
  $new_value = "\n <li>\n  ".date('Y-m-d') ." -".date("h:i:sa") ." \n <b>".$type." ". $message."</b>\n </li>\n \n ";

  if ( get_option( $option_name ) !== false ) {
      $value = get_option( $option_name ) ." ".$new_value ;
      update_option( $option_name, $value );
  } else {
      add_option( $option_name, $new_value );
  }

}

 function delete_booking_metadata() {
   if ( isset($_REQUEST) ) {

        $user_id = $_REQUEST['user_id'];
        $event_id = $_REQUEST['event_id'];
        $userProjects = get_user_meta($user_id, 'bookedProjects', true);
        unset($userProjects[$event_id]); //removes the array at given index
        update_user_meta( $user_id, 'bookedProjects', $userProjects);

        $projRegistrations = get_post_meta($event_id, 'participants', true);
        $projRegistrations = array_diff($projRegistrations, array($user_id));

        if(update_post_meta($event_id, 'participants', $projRegistrations )){
          log_booking("info","user ". $user_id ." canceled event :  ".$event_id. " ");
        }else{
          log_booking("error","user ". $user_id ." canceled event :  ".$event_id. " went wrong");
        }
    }
  // die();
 }

 add_action( 'wp_ajax_cancelEvent', 'cancelEvent' );

 function cancelEvent() {
 	global $wpdb; // this is how you get access to the database
 	$user_id = $_POST['user_id'];
 	$event_id = $_POST['event_id'];
 	$userProjects = get_user_meta($user_id, 'bookedProjects', true);
 	unset($userProjects[$event_id]); //removes the array at given index
 	update_user_meta( $user_id, 'bookedProjects', $userProjects);


 	$projRegistrations = get_post_meta($event_id, 'participants', true);
 	$projRegistrations = array_diff($projRegistrations, array($user_id));

  if(update_post_meta($event_id, 'participants', $projRegistrations )){
        log_booking("info","admin unbooked user :".$user_id. "  : from event ".$event_id);
  }else{
        log_booking("error","admin unbooked user :".$user_id. "  : from event ".$event_id);
  }


 	wp_die(); // this is required to terminate immediately and return a proper response
 }

 function save_booking_metadata($event_id,$event_title,$user_id) {
   	global $wpdb;
          $userMeta             = get_user_meta($user_id);
          $projectMeta          = get_post_meta($event_id);
          $userProjects         = get_user_meta($user_id, 'bookedProjects', true);
          $projectParticipants  = get_post_meta($event_id, 'participants', true);
          log_booking("info","user : ".$user_id ." booked event ".$event_id." ".$event_title);

          $days = get_field('schedule',$event_id);
                               if($days){
                                  foreach($days as $d){
                                        $day = date('l', strtotime($d['days']));
                                        echo $day;
                                        $times = $d['times'];
                                        foreach($times as $t){
                                               echo '<p>'.$t['start_time']." - ".$t['end_time']."</p> ";
                                               $duration =  strtotime($t['end_time']) - strtotime($t['start_time']);
                                        }
                                  }
                               }
          $projectstart_date    = get_post_meta($event_id, 'start_date', false);
          $projectend_date      = get_post_meta($event_id, 'end_date', false);
          $projectstart_time    = get_post_meta($event_id, 'start_time', false);
          $projectend_time      = get_post_meta($event_id, 'end_time', false);



          if (!in_array($event_id, $userProjects)) {

/****************
            Save Project ID in User_Meta
****************/
            $data =  array(
                  'id'          => $event_id,
                  'title'       => $event_title,
                  'event_times' => $days,

                );

            if(!get_user_meta($user_id, 'bookedProjects', FALSE)){
                $userProjects[$event_id] = $data;
                add_user_meta( $user_id, 'bookedProjects',$userProjects );
            }else if($userProjects == "" OR !$userProjects){
                $userProjects[$event_id] = $data;
                update_user_meta( $user_id, 'bookedProjects',$userProjects );
            }else{
                $userProjects[$event_id] = $data;
                update_user_meta( $user_id, 'bookedProjects', $userProjects);
            }

          }else{
            log_booking("error", "User: ".$user_id." Event". $event_id ." event had been already booked");
          }


          if (!in_array($user_id, $projectParticipants)) {

/****************
          Save User_ID in Project_Meta
****************/

            if(!get_post_meta($event_id, 'participants', FALSE)){
                add_post_meta($event_id, 'participants', array($user_id) );
            }else if($projectParticipants == "" OR !$projectParticipants){
                update_post_meta($event_id, 'participants', array($user_id) );
            }else{
                array_push($projectParticipants, $user_id);
                update_post_meta($event_id, 'participants', $projectParticipants );
            }

          }else{
            echo "Event schon registriert - Event";

          }
}


function bookProject(){
      if ( isset($_REQUEST) ) {
        $event_id = $_REQUEST['event_id'];
        $user_id = $_REQUEST['user_id'];
        $event_title = $_REQUEST['event_title'];

                    // CHECK AVAILABLE SEATS
                    $projectUsers    = get_post_meta($event_id , 'participants', true);
                    $Seats           = get_post_meta($event_id, "available_seats" );
                    $avalSeats       = get_field( "available_seats" ,$event_id);
                    //CHECK TIME CONFLICT
                    $userProjects  = get_user_meta($user_id, 'bookedProjects',true);
                    $schedule      = get_field('schedule',$event_id);
                    $time_conflict = false;
                    foreach($userProjects as $key => $value){ // get all booked projects
                        foreach($value['event_times'] as $event_times){ // get meta field event times from project
                            if($schedule){ // get time meta from focused project
                                foreach($schedule as $target){

                                        if($event_times['days'] === $target['days']){

                                                $target_times = $target['times'];
                                                foreach($target_times as $t){
                                                    $start_time = $t['start_time'];
                                                    $end_time   = $t['end_time'];
                                                    $duration   = strtotime($t['end_time']) - strtotime($t['start_time']);

                                                    foreach($event_times['times'] as $event_time){ // get each time
                                                        if(
                                                            strtotime($event_time['start_time'])   >= strtotime($start_time)  &&
                                                            strtotime($event_time['start_time'])   <= strtotime($end_time)
                                                            ){
                                                            ?>

                                                            <?php
                                                            $access = false;
                                                            $event_same_time[$value['id']] = array($value['title'],$target['days']);
                                                            $time_conflict      = true;

                                                        }else if(
                                                            strtotime($start_time)    >= strtotime($event_time['end_time'])  &&
                                                            strtotime($start_time)    <= strtotime($event_time['start_time'])
                                                            ){
                                                            ?>

                                                            <?php
                                                            $access = false;
                                                            $event_same_time[$value['id']] = array($value['title'],$target['days']);
                                                            $time_conflict      = true;
                                                        }
                                                    }
                                                }
                                        }

                                }
                            }
                          }
                        }
                    if(count($projectUsers) >= $avalSeats || $time_conflict){
                      echo "full";
                      log_booking("error", "User: ".$user_id." wasnt able to book ". $event_id ." - fully booked ");
                    }else if($time_conflict){
                      echo "time_conflict";
                      log_booking("error", "User: ".$user_id." wasnt able to book ". $event_id ." - time_conflict ");
                    }else{
                      save_booking_metadata($event_id,$event_title, $user_id);
                    }
       }
      die();
}
add_action( 'wp_ajax_lazy_tito', 'lazy_tito' );
add_action( 'wp_ajax_nopriv_lazy_tito', 'lazy_tito' );


?>
