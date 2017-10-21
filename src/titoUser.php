<?php
  function autoLogin($u,$p){

    $errors = array();

    $username = esc_sql($u);
    $password = esc_sql($p);
    $remember = "true";

    $login_data = array();
    $login_data['user_login'] = $username;
    $login_data['user_password'] = $password;
    $login_data['remember'] = $remember;
    $user_verify = wp_signon($login_data, true);

    if (is_wp_error($user_verify)) {
        $errors[] = 'Invalid username or password. Please try again!';
    } else {
        wp_set_auth_cookie($user_verify->ID);
        wp_redirect(home_url()."/profile");
        exit;
    }
  }

  function autoLoginUser($user_id){
  $user = get_user_by( 'id', $user_id );
    if( $user ) {
      wp_set_current_user( $user_id, $user->user_login );
      wp_set_auth_cookie( $user_id );
      do_action( 'wp_login', $user->user_login, $user);
    }
  }

    function RegisterTrigger(){
      if (isset($_GET['registration_id'])) {
            $id = $_GET['registration_id'];

              $account = TitoConnection('tickets?filter[registration]='.$id);

                $rel          = $account['data'][0]['relationships'];
                $ticketUrl    = $rel['release']['links']["related"];
                $id           = $account['data'][0]['id'];
                $email        = $account['data'][0]['attributes']['email'];
                $name         = $account['data'][0]['attributes']['name'];
                $ticketInfos  = CConnection($ticketUrl);

                $ticket       = $ticketInfos['data']['attributes']['title'];

                $registUrl    = $rel['registration']['links']["related"];
                $registInfos  = CConnection($registUrl);
                $baddress     = $registInfos['data']['attributes']['billing-address'];
                $address      = $registInfos['data']['attributes']['billing-address']['address'];
                $city         = $registInfos['data']['attributes']['billing-address']['city'];
                $country      = $registInfos['data']['attributes']['billing-address']['country'];
                createUser($name,$email,$id,$ticket,$baddress);
      }else{
          echo "please login";
      }
    }




      function createUser($user_name,$user_email,$id,$ticket,$Uri) {

   			$user_id = username_exists( $user_email );
   				if ( !$user_id and email_exists($user_email) == false ) {

            $account      = TitoConnection('tickets?filter[registration]='.$id);
            $rel          = $account['data'][0]['relationships'];
            $registUrl    = $rel['registration']['links']["related"];
            $registInfos  = CConnection($registUrl);
            $address      = $registInfos['data']['attributes']['billing-address']['address'];
            $city         = $registInfos['data']['attributes']['billing-address']['city'];
            $country      = $registInfos['data']['attributes']['billing-address']['country'];

   					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
   					$new_user = wp_create_user( $user_email, $random_password, $user_email );
            update_user_meta( $new_user, 'Ticket', array($ticket));
            update_user_meta( $new_user, 'TitoID', $id);
            update_user_meta( $new_user, 'TiToUri', $Uri);
            update_user_meta( $new_user, 'Address', $address);
            update_user_meta( $new_user, 'City', $city);
            update_user_meta( $new_user, 'Country', $country);


            log_booking("new user created auto"," new user " . $new_user." with email ".$user_email ." and ticket". $ticket ."  had been created after ticketsell" );

              if($new_user && $random_password){
                wp_new_user_notification($new_user, $random_password);
            	}


              $errors = array();
                         $username = esc_sql($user_email);
                         $password = esc_sql($random_password);
                         $remember = "true";

                         $login_data = array();
                         $login_data['user_login'] = $username;
                         $login_data['user_password'] = $password;
                         $login_data['remember'] = $remember;
                         $user_verify = wp_signon($login_data, true);

                         if (is_wp_error($user_verify)) {
                             $errors[] = 'Invalid username or password. Please try again!';
                         } else {
                             wp_set_auth_cookie($user_verify->ID);
                             echo "User is loged in";
                             log_booking("new user auto login"," new user " . $new_user." ".$username ." has been logged in " );

                             //wp_redirect(home_url()."/profile");
                             //exit;
                         }

          } else {
            echo "update";

            $pretickets = get_user_meta($user_id, 'Ticket', true);
            echo $pretickets;
            array_push($pretickets, $ticket);
            //$tickets = array_diff($pretickets, $ticket);
            update_user_meta( $user_id, 'Ticket', $pretickets);
            echo " Account updated" ;
            log_booking("user update"," User " . $user_id ."  bought new ticket ".$ticket ." " );

            //wp_redirect(home_url()."/profile");
            //exit;
   				}

      //die();
   }

   add_action( 'wp_ajax_registerUsersHook', 'registerUsersHook' );
   add_action( 'wp_ajax_nopriv_registerUsersHook', 'registerUsersHook' );

    function registerUsersHook(){
          $data = $_REQUEST['data'];
          echo "register function";

          $id =  $data['slug'];
          $ticket_no = count($data['tickets']);


          log_booking("ticket sell"," ticket sold - selling-id:" . $id ."" );
          foreach ($data['tickets'] as $order) {
              $ticket_id = $order['release_slug'];
              echo "ticket id ".$ticket_id;
              $ticket_access = get_field('ticket_access', 'option');
              foreach ($ticket_access as $key => $ticket) {
                if($ticket === $ticket_id){
                  echo "user gets account";
                  $ticket = $order['release_title'];
                  $ref = $order['reference'];
                  $email = $order['email'];
                  $first_name = $order['first_name'];
                  $last_name = $order['last_name'];
                  $admin_url = $order['admin_url'];
                  $release_slug =  $order['release_slug'];
                  createUser($last_name,$email,$id,$ticket,$admin_url);
                }else{
                  log_booking("ticket sell"," ticket id :" . $ticket_id ."  - without workshop " );
                }
              };
          }
    }




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
