
<?php
/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_event_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'book' post, don't update it.
    if ( "events" != $post_type ) return;

    // - Update the post's metadata.

    /*if ( isset( $_POST['book_author'] ) ) {
        update_post_meta( $post_id, 'book_author', sanitize_text_field( $_POST['book_author'] ) );
    }

    if ( isset( $_POST['publisher'] ) ) {
        update_post_meta( $post_id, 'publisher', sanitize_text_field( $_POST['publisher'] ) );
    }

    // Checkboxes are present if checked, absent if not.
    if ( isset( $_POST['inprint'] ) ) {
        update_post_meta( $post_id, 'inprint', TRUE );
    } else {
        update_post_meta( $post_id, 'inprint', FALSE );
    }*/
    create_node_relations($post_id);
}
add_action( 'save_post', 'save_event_meta', 10, 3 );




add_action( 'wp_ajax_reset_people_relations', 'reset_people_relations' );
add_action( 'wp_ajax_nopriv_reset_people_relations', 'reset_people_relations' );

function reset_people_relations(){
                     $event_id   = $_REQUEST['event_id'];
                     delete_post_meta($event_id, 'people_ids');
                     delete_post_meta($event_id, 'team_member_ids');
                     echo "deleted all people refs in event: ".$event_id;
                     $projects = get_field('pi_event', $event_id); // gets event related project
                     if( $projects ):
                     foreach( $projects as $project ):
                     delete_post_meta($project->ID, 'people_ids');
                     delete_post_meta($project->ID, 'team_member_ids');
                     echo "deleted all people refs in project: ".$project->ID;
                     endforeach;
                     else:
                     endif;
}
add_action( 'wp_ajax_reset_all_relations', 'reset_all_relations' );
add_action( 'wp_ajax_nopriv_reset_all_links_in_projects', 'reset_all_relations' );

function reset_all_relations(){
                     $event_id   = $_REQUEST['event_id'];
                     delete_post_meta($event_id, 'people_ids');
                     delete_post_meta($event_id, 'team_member_ids');
                     echo "deleted all connection to  : ".$person->ID." to EVENT ".$event_id ."<br />";
                     $projects = get_field('pi_event', $event_id); // gets event related project
                     if( $projects ):
                     foreach( $projects as $project ):
                     delete_post_meta($project->ID, 'people_ids');
                     delete_post_meta($project->ID, 'team_member_ids');
                     endforeach;
                     else:
                     endif;
}
add_action( 'wp_ajax_reset_all_links_in_projects', 'reset_all_links_in_projects' );
add_action( 'wp_ajax_nopriv_reset_all_links_in_projects', 'reset_all_links_in_projects' );


function reset_all_links_in_projects(){

                  $args = array( 'post_type' => 'projects', 'posts_per_page' => -1 );
                  $loop = new WP_Query( $args );
                  while ( $loop->have_posts() ) : $loop->the_post();
                                    delete_post_meta(get_the_ID(), 'people_ids');
                                    delete_post_meta(get_the_ID(), 'team_member_ids');
                                    delete_post_meta(get_the_ID(), 'event_ids');
                                    echo get_the_ID()." Project - all links deleted <br /> ";
                  endwhile;

}


add_action( 'wp_ajax_reset_all_links_in_persons', 'reset_all_links_in_persons' );
add_action( 'wp_ajax_nopriv_reset_all_links_in_persons', 'reset_all_links_in_persons' );


function reset_all_links_in_persons(){

                  $args = array( 'post_type' => 'person', 'posts_per_page' => -1 );
                  $loop = new WP_Query( $args );
                  while ( $loop->have_posts() ) : $loop->the_post();
                                    delete_post_meta(get_the_ID(), 'team_member_ids');
                                    delete_post_meta(get_the_ID(), 'project_ids');
                                    delete_post_meta(get_the_ID(), 'event_ids');
                                    echo get_the_ID()." People - all links deleted <br /> ";
                  endwhile;

}




add_action( 'wp_ajax_create_node_relations', 'create_node_relations' );
add_action( 'wp_ajax_nopriv_create_node_relations', 'create_node_relations' );


function create_node_relations($I){
    if($I){
        $event_id   = $I;

    }else{
        $event_id   = $_REQUEST['event_id'];

    }
/**************************************** CONNECT PEOPLE
********************************************************************************/
if( have_rows('pi_people',$event_id) ){ // gets event related people
                     while ( have_rows('pi_people',$event_id) ){
                     the_row();
                     $post_objects = get_sub_field('person');
                     if( $post_objects ):
                     foreach( $post_objects as $person):

                                       // save PEOPLE to EVENT
                                       if(get_post_meta($event_id, 'people_ids', FALSE)) {
                                           $people_ids = get_post_meta($event_id, 'people_ids', TRUE);
                                           array_push($people_ids,$person->ID);
                                           echo "ADDED PERSON: ".$person->ID." to EVENT ".$event_id ."<br />";
                                           update_post_meta($event_id, 'people_ids', array_unique($people_ids));
                                       } else {
                                           add_post_meta($event_id, 'people_ids', array($person->ID));
                                           echo "ADDED PERSON: ".$person->ID." to EVENT ".$event_id ."<br />";
                                       }
                                       // save EVENTS to PEOPLE
                                       if(get_post_meta($person->ID, 'event_ids', FALSE)) {
                                            $events = get_post_meta($person->ID, 'event_ids', TRUE);
                                            array_push($events,$event_id);
                                            update_post_meta($person->ID, 'event_ids', array_unique($events));
                                            echo "ADDED EVENT: ".$event_id." to PERSON".$person->ID ."<br />";
                                       } else {
                                             add_post_meta($person->ID, 'event_ids', array($event_id));
                                             echo "ADDED EVENT: ".$event_id." to PERSON".$person->ID ."<br />";
                                       }


                                                         										 	// $post_object->ID = Person
                    $projects = get_field('pi_event', $event_id); // gets event related project
                    if( $projects ): ?>
                            <?php foreach( $projects as $p ): // variable must NOT be called $post (IMPORTANT)

                                          //saving EVENTS to PROJECT
                                          if(get_post_meta($p->ID, 'event_ids', FALSE)) {
                                              $event_ids2 = get_post_meta($p->ID, 'event_ids', true);
                                              array_push($event_ids2,$event_id);
                                              update_post_meta($p->ID, 'event_ids', array_unique($event_ids2));
                                              echo "ADDED EVENT: ".$event_id." to PROJECT ".$p->ID ."<br />";
                                          } else {
                                              add_post_meta($p->ID, 'event_ids', array($event_id));
                                              echo "ADDED EVENT: ".$event_id." to PROJECT ".$p->ID ."<br />";
                                          }

                                          //save PEOPLE to PROJECT
                                          if(get_post_meta($p->ID, 'people_ids', FALSE)) {
                                              $people_ids2 = get_post_meta($p->ID, 'people_ids', true);
                                              array_push($people_ids2,$person->ID);
                                              update_post_meta($p->ID, 'people_ids', array_unique($people_ids2));
                                              echo "ADDED PERSON: ".$person->ID." to PROJECT ".$p->ID ."<br />";
                                          } else {
                                              add_post_meta($p->ID, 'people_ids', array($person->ID));
                                              echo "ADDED PERSON: ".$person->ID." to PROJECT ".$p->ID ."<br />";
                                          }
                                          //save PROJECTS to PEOPLE
                                          if(get_post_meta($person->ID, 'project_ids', FALSE)) {
                                               $project_ids2 = get_post_meta($person->ID, 'project_ids', true);
                                               array_push($project_ids2,$p->ID);
                                               update_post_meta($person->ID, 'project_ids', array_unique($project_ids2));
                                               echo "ADDED PROJECT: ".$p->ID." to PERSON ".$person->ID ."<br />";
                                         } else {
                                               add_post_meta($person->ID, 'project_ids', array($p->ID));
                                               echo "ADDED PROJECT: ".$p->ID." to PERSON ".$person->ID ."<br />";
                                        }
                                          //save PROJECTS to EVENTS
                                          if(get_post_meta($event_id, 'project_ids', FALSE)) {
                                              $project_ids3 = get_post_meta($event_id, 'project_ids', true);
                                              array_push($project_ids3,$p->ID);
                                              update_post_meta($event_id, 'project_ids', array_unique($project_ids3));
                                              echo "ADDED PROJECT: ".$p->ID." to EVENT ".$event_id ."<br />";
                                          } else {
                                              add_post_meta($event_id, 'project_ids', array($p->ID));
                                              echo "ADDED PROJECT: ".$p->ID." to EVENT ".$event_id ."<br />";
                                          }
                            endforeach; ?>

                  <?php endif;
                     endforeach;
                     else:
                                       $projects = get_field('pi_event', $event_id); // gets event related project
                                                        if( $projects ): ?>
                                                                  <?php foreach( $projects as $p ): // variable must NOT be called $post (IMPORTANT)

                                                                                //saving EVENTS to PROJECT
                                                                                if(get_post_meta($p->ID, 'event_ids', FALSE)) {
                                                                                $event_ids2 = get_post_meta($p->ID, 'event_ids', true);
                                                                                array_push($event_ids2,$event_id);
                                                                                update_post_meta($p->ID, 'event_ids', array_unique($event_ids2));
                                                                                echo "ADDED EVENT: ".$event_id." to PROJECT ".$p->ID ."<br />";
                                                                                } else {
                                                                                add_post_meta($p->ID, 'event_ids', array($event_id));
                                                                                echo "ADDED EVENT: ".$event_id." to PROJECT ".$p->ID ."<br />";
                                                                                }

                                                                                //save PEOPLE to PROJECT
                                                                                if(get_post_meta($p->ID, 'people_ids', FALSE)) {
                                                                                $people_ids2 = get_post_meta($p->ID, 'people_ids', true);
                                                                                array_push($people_ids2,$person->ID);
                                                                                update_post_meta($p->ID, 'people_ids', array_unique($people_ids2));
                                                                                echo "ADDED PERSON: ".$person->ID." to PROJECT ".$p->ID ."<br />";
                                                                                } else {
                                                                                add_post_meta($p->ID, 'people_ids', array($person->ID));
                                                                                echo "ADDED PERSON: ".$person->ID." to PROJECT ".$p->ID ."<br />";
                                                                                }
                                                                                //save PROJECTS to PEOPLE
                                                                                if(get_post_meta($person->ID, 'project_ids', FALSE)) {
                                                                                $project_ids2 = get_post_meta($person->ID, 'project_ids', true);
                                                                                array_push($project_ids2,$p->ID);
                                                                                update_post_meta($person->ID, 'project_ids', array_unique($project_ids2));
                                                                                echo "ADDED PROJECT: ".$p->ID." to PERSON ".$person->ID ."<br />";
                                                                               } else {
                                                                                add_post_meta($person->ID, 'project_ids', array($p->ID));
                                                                                echo "ADDED PROJECT: ".$p->ID." to PERSON ".$person->ID ."<br />";
                                                                              }
                                                                                //save PROJECTS to EVENTS
                                                                                if(get_post_meta($event_id, 'project_ids', FALSE)) {
                                                                                $project_ids3 = get_post_meta($event_id, 'project_ids', true);
                                                                                array_push($project_ids3,$p->ID);
                                                                                update_post_meta($event_id, 'project_ids', array_unique($project_ids3));
                                                                                echo "ADDED PROJECT: ".$p->ID." to EVENT ".$event_id ."<br />";
                                                                                } else {
                                                                                add_post_meta($event_id, 'project_ids', array($p->ID));
                                                                                echo "ADDED PROJECT: ".$p->ID." to EVENT ".$event_id ."<br />";
                                                                                }
                                                                  endforeach; ?>

                                                        <?php endif;
                     endif;

}
}
/**************************************** CONNECT TEAM-MEMBER
********************************************************************************/

                            $team_members = get_field('team_member', $event_id);
                                      if( $team_members){ ?>
                                          <?php foreach( $team_members as $team_member){
                                                            // save TEAM-MEMBER to EVENT
                                                            if(get_post_meta($event_id, 'team_member_ids', FALSE)) {
                                                            $team_member_ids = get_post_meta($event_id, 'team_member_ids', TRUE);
                                                            array_push($team_member_ids,$team_member->ID);
                                                            echo "ADDED TEAM-MEMBER: ".$team_member->ID." to EVENT ".$event_id ."<br />";
                                                            update_post_meta($event_id, 'team_member_ids', array_unique($team_member_ids));
                                                            } else {
                                                            add_post_meta($event_id, 'team_member_ids', array($team_member->ID));
                                                            echo "ADDED TEAM-MEMBER: ".$team_member->ID." to EVENT ".$event_id ."<br />";
                                                            }
                                                            // save EVENTS to TEAM-MEMBER
                                                            if(get_post_meta($team_member->ID, 'event_ids', FALSE)) {
                                                            $events = get_post_meta($team_member->ID, 'event_ids', TRUE);
                                                            array_push($events,$event_id);
                                                            update_post_meta($team_member->ID, 'event_ids', array_unique($events));
                                                            echo "ADDED EVENT: ".$event_id." to TEAM-MEMBER".$team_member->ID ."<br />";

                                                            } else {
                                                            add_post_meta($team_member->ID, 'event_ids', array($event_id));
                                                            echo "ADDED EVENT: ".$event_id." to TEAM-MEMBER".$team_member->ID ."<br />";
                                                            }
                                                            $projects = get_field('pi_event', $event_id); // gets event related project
                                                                             if( $projects ): ?>
                                                                                       <?php foreach( $projects as $p ): // variable must NOT be called $post (IMPORTANT)

                                                                                                     //save TEAM-MEMBER to PROJECT
                                                                                                     if(get_post_meta($p->ID, 'team_member_ids', FALSE)) {
                                                                                                     $team_member_ids2 = get_post_meta($p->ID, 'team_member_ids', true);
                                                                                                     array_push($team_member_ids2,$team_member->ID);
                                                                                                     update_post_meta($p->ID, 'team_member_ids', array_unique($team_member_ids2));
                                                                                                     echo "ADDED TEAM-MEMBER: ".$team_member->ID." to PROJECT ".$p->ID ."<br />";
                                                                                                     } else {
                                                                                                     add_post_meta($p->ID, 'team_member_ids', array($team_member->ID));
                                                                                                     echo "ADDED TEAM-MEMBER: ".$team_member->ID." to PROJECT ".$p->ID ."<br />";
                                                                                                     }
                                                                                                     //save PROJECTS to PEOPLE
                                                                                                     if(get_post_meta($team_member->ID, 'project_ids', FALSE)) {
                                                                                                     $project_ids2 = get_post_meta($team_member->ID, 'project_ids', true);
                                                                                                     array_push($project_ids2,$p->ID);
                                                                                                     update_post_meta($team_member->ID, 'project_ids', array_unique($project_ids2));
                                                                                                     echo "ADDED PROJECT: ".$p->ID." to TEAM-MEMBER ".$team_member->ID ."<br />";
                                                                                                     } else {
                                                                                                      add_post_meta($team_member->ID, 'project_ids', array($p->ID));
                                                                                                     echo "ADDED PROJECT: ".$p->ID." to TEAM-MEMBER ".$team_member->ID ."<br />";
                                                                                                     }

                                                                                       endforeach; ?>

                                                                             <?php endif;
                                           } ?>
                               <?php }
}

?>
