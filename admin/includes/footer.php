 <footer class="text-center" id="footer">&copy;copyright:2013-2018 nathis online shop</footer>
 

         <?php
        // put your code here
        ?>
  <script>
      function updateSizes()
      {
          var sizeString = '';
          for(var i=1;i<=12;i++)
          {
              if(jQuery('#size' +i).val() != ''){
                  sizeString += jQuery('#size' +i).val()+':'+jQuery('#qty'+i).val()+',';
              }
          }
      
          jQuery('#sizes').val(sizeString); 
    }
      function get_child_options(selected){
		  if(typeof selected === 'undefined')
		  {
			  var selected = '';
		  }
     var parentID = jQuery('#parent').val();
     jQuery.ajax({
         url:'/online_store/admin/parses/child_categories.php',
         type: 'POST',
         data: {parentID : parentID, selected : selected},
         success: function(data){
             jQuery('#child').html(data);
         },
         error: function(){alert("something went wrong")},
     });
     
 }
jQuery('select[name="parent"]').change(function(){
	get_child_options();
});
 function update_cart(mode,edit_id,edit_size){
     var data = {"mode" : mode,"edit_id":edit_id,"edit_size":edit_size};
 jQuery.ajax({
     url : '/online_store/admin/parses/update_cart.php',
     method : "post",
     data : data ,
     success : function(){location.reload();},
     error : function(){alert("something went wrong.");},
 });
 }
 </script>
 
    </body>