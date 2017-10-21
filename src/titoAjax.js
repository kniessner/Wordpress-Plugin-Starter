jQuery(document).ready(function($){


$(".async_meta").on("click", reload_project_data);
function reload_project_data(post_id,key){
var post_id  = $(this).data('postid');
var key      = $(this).data('meta');


    $.ajax({
        url: MyAjax.ajaxurl,
        data: {
          'action': 'reload_meta_data',
          'post_id'  : post_id,
          'key'	 : key
        },
        success:function(data) {
            console.log('data',data);
            console.log('data-length',data.length);
        },
        error: function(errorThrown){
            console.error(errorThrown);
        },

    });
}




  $(".eventbooking").on("click", bookingProcess);
  function bookingProcess(e){

      if(this.id === 0){
        alert('Something went wrong! Please reload the page and try it again');
        location.reload();
      }

      $('.loader-wrap').show();
      $.ajax({
          url: MyAjax.ajaxurl,
          data: {
              'action':'bookProject',
              'event_title'   : this.name,
              'user_id'       : this.id,
              'event_id'      : this.value
          },

          success:function(data) {
              console.log('data'+data);
              if(data === 'full'){
                $('#node_l_message').text('Sorry, all seats are already booked');
                $('#node_l').modal('show');
              }else{
                $('#node_l_message').text('Booked');
                $('#node_l').modal('show');
              }
              //$('event_'+this.value).addClass('booked');


              location.reload();

          },
          error: function(errorThrown){
              console.error(errorThrown);
          }
      });
  }


  $( ".unbooking" ).on( "click", delbookingProcess );
  function delbookingProcess(e){
    console.log(this.name);
    console.log(this.value);
    if(this.name === 0){
      alert('Something went wrong! Please reload the page and try it again');
      location.reload();
    }
    $('.loader-wrap').show();
    $.ajax({
        url: MyAjax.ajaxurl,
        data: {
            'action':'delete_booking_metadata',
            //'event_title'   : this.name,
            'user_id'      : this.name,
            'event_id'     : this.value
        },

        success:function(data) {
            console.log(data);
            $('#node_l_message').text('canceled');
            $('#node_l').modal('show');
            /*$('#node_l').on('hidden.bs.modal', function (e) {

            });*/
            location.reload();
          //  alert('canceled');
          //  location.reload();

        },
        error: function(errorThrown){
            console.error(errorThrown);
        }
    });
  }


  function registerUsers(data){
      //console.log(data);
      $.ajax({
          url: MyAjax.ajaxurl,
          data: {
              'action':'registerUsersHook',
              'data'   : data,
          },
          success:function(data) {
              console.log('data',data);
              $(location).attr('href', 'https://17.nodeforum.org/profile');

          },
          error: function(errorThrown){
              console.error(errorThrown);
              $(location).attr('href', '../../error');

          }
      });
  }

  Tito.on('registration:started', function(data){
    console.log('registrations started',data);
    console.log('line-items',data["line_items"].length);
    console.log('quant',data["line_items"][0]['quantity']);

  });


   Tito.on('registration:finished', function(data){
     console.log('registrations finished', data);
     var items = data["line_items"].length;
     var quant = data["line_items"][0]['quantity'];
     console.log(quant);
     console.log(items);
     if(items === 1 && quant === 1){
          registerUsers(data);
     }
     //
   });

   Tito.on('registration:complete', function(data){
     console.log('registrations complete');
        registerUsers(data);
   });


   $( ".new_user" ).on( "click", create_new_account );
   function create_new_account(e){
     console.log('create_new_account');
     if(this.id === 0){
       alert('Something went wrong! Please reload the page and try it again');
       location.reload();
     }
     $.ajax({
         url: MyAjax.ajaxurl,
         data: {
             'action':'create_new_account',
             'user_id'      : this.name,
             'event_id'     : this.value
         },

         success:function(data) {
             console.log(data);
             location.reload();

         },
         error: function(errorThrown){
             console.error(errorThrown);
         }
     });
   }

});
