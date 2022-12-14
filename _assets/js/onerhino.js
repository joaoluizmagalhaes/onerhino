(function($) {

  $('#items_list').sortable({
    update: function (event, ui) {
      callAjax('reorder_list');
      
    }
  }).on('click', '.delete', function() {
    $(this).closest('li').remove(); 

    callAjax('remove_item');
    
  });


  function callAjax(action) {
    var siteDomain =  document.location.origin + '/jobsite/wp-admin/admin-ajax.php';
      
    // POST to server using $.post or $.ajax
    $.ajax({
        data: {
          action: action,
          data: $('#items_list').sortable('toArray')
        },
        type: 'POST',
        url: siteDomain,
        success: function(data){
          console.log(data)
          alert('success updated the list');
        }
    });
  }


})( jQuery );