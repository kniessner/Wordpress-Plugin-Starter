<?php

function justcreateUser($user_email,$id,$ticket) {
  $account      = TitoConnection('tickets?filter[registration]='.$id);
  var_dump($account);


          $user_id = username_exists( $user_email );
            if ( !$user_id and email_exists($user_email) == false ) {
            echo "creating";

            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $new_user = wp_create_user( $user_email, $random_password, $user_email );
            update_user_meta( $new_user, 'Ticket', array($ticket));
            update_user_meta( $new_user, 'TitoID', $id);

            if($account && $account['data']){

              $rel          = $account['data'][0]['relationships'];
              $registUrl    = $rel['registration']['links']["related"];
              $registInfos  = CConnection($registUrl);
              $address      = $registInfos['data']['attributes']['billing-address']['address'];
              $city         = $registInfos['data']['attributes']['billing-address']['city'];
              $country      = $registInfos['data']['attributes']['billing-address']['country'];

              update_user_meta( $new_user, 'Address', $address);
              update_user_meta( $new_user, 'City', $city);
              update_user_meta( $new_user, 'Country', $country);

            }
             if($new_user && $random_password){
                wp_new_user_notification($new_user, $random_password);
             }
             echo "Account created";
             log_booking("new user created manuel"," new user " . $new_user." with email ".$user_email ." and ticket". $ticket ."  had been manuelly created " );

          } else {
            $pretickets = get_user_meta($user_id, 'Ticket', true);
            array_push($pretickets, $ticket);
            update_user_meta( $user_id, 'Ticket', $pretickets);
            echo " Account updated" ;
            log_booking("user created updated"," user " . $user_id." got ticket". $ticket ." " );

         }


}

function register_User() {

        justcreateUser($_GET['email'],$_GET['tito_id'],$_GET['ticket']);
}


function register_Users() {
  echo "register_Users";
  $accounts = TitoConnection('tickets');
  foreach( $accounts['data'] as $item) {
      ?><pre><?php
        $rel          = $item['relationships'];
        $ticketUrl    = $rel['release']['links']["related"];
        $ticketInfos  = CConnection($ticketUrl);
        $registUrl    = $rel['registration']['links']["related"];
        $registInfos  = CConnection($registUrl);
        $wp_user      = username_exists( $item['attributes']['email'] );
        $tito_id      = $item['id'];
        $user_mail    = $item['attributes']['email'];
        $user_ticket  = $ticketInfos['data']['attributes']['title'];

        $ticket_id  = $ticketInfos['data']['id'];
        echo "slug ".$ticket_id;
        $ticket_access = get_field('ticket_access', 'option');


        foreach ($ticket_access as $key => $ticket) {
          if($ticket === $ticket_id){
            echo "user gets account";
            justcreateUser($user_mail,$tito_id,$user_ticket);
          }else{
          }
        };


        //justcreateUser($user_mail,$tito_id,$user_ticket);
  }
}
 ?>
