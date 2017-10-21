<?php
/*add_filter('acf/load_field/name=ticket_access', 'acf_load_ticketsTypes');
add_filter('acf/load_field/name=valid_ticket_types', 'acf_load_ticketsTypes');

function acf_load_ticketsTypes( $field ) {

     $field['choices'] = array();
     $choices = array();
     $releases = TitoConnection('releases');
     foreach($releases['data'] as $item) { //foreach element in $arr
        $rels = TitoConnection('releases/'.$item['id']);
         $field['choices'][ $item['id'] ] = $rels['data']['attributes']['title'];

     }
     $field['choices'][ 'no_ticket' ] = 'No Ticket';

     return $field;
}
*/
?>
