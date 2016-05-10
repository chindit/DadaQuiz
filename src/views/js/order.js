  $(function() {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
  });

$(document).ready(function(){
    $('#quiz-send').click(function(event){
       //Preparing «sortable» (if any)
       $('.sortable').each(function(index){
           //Preparing storage
           var storage = new Array();
           
          //Running through all «sortable» lists
          $(this).find('li').each(function(indexUl){
             //Looping every <li> item
             storage.push($(this).find('.reponse').html());
          });
          //Adding array to input
          $('input[name="'+$(this).attr('id')+'"]').val(storage.join(','));
       });
    });

});
