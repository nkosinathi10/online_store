
<footer class="text-center" id="footer">&copy;copyright:2013-2018 nathis online shop</footer>

 <script>
 jQuery(window).scroll(function()
 {
     var vscroll = jQuery(this).scrollTop();
     jQuery('#logotext').css({
         "transform" : "translate(0px, "+vscroll/2+"px)"
     });
          var vscroll = jQuery(this).scrollTop();
     jQuery('#logotext').css({
         "transform" : "translate(0px, "+vscroll/12+"px)"
     });
          var vscroll = jQuery(this).scrollTop();
     jQuery('#logotext').css({
         "transform" : "translate(0px, "+vscroll/2+"px)"
     });
 });
 function detailsmodal(id)
 {
     var data = {"id" : id};
     jQuery.ajax({
       url : '/online_store/includes/detailslightbox.php',
       method: "post",
       data : data,
       success: function(data){
           jQuery('body').append(data);
           jQuery('#details-modal').modal('toggle');
           
       },
       error:function(){
           alert("something went wrong");
       }
     });
     
 }
 
 
 function add_to_cart()
 {
	 jQuery('#moda_errors').html("");
	 var size = jQuery('#size').val();
	 var quantity = jQuery('#quantity').val();
	 var available = jQuery('#available').val();
	 var error ='';
	 var data = jQuery('#add_product_form').serialize();
	 if(size == '' || quantity == '' || quantity == 0){
		 error += '<p class="text-danger text-center"> you must choose size and quantity.</p>';
		 jQuery('#moda_errors').html(error);
		 return;
	 }
	 else if(quantity > available){
		 error += '<p class="text-danger text-center"> There are only'+available+' available.</p>';
		 jQuery('#moda_errors').html(error);
		 return;
	 }
	 else{
		 jQuery.ajax({
			url:'/online_store/admin/parses/add_cart.php',
            method : 'post',
            data:data,
            success : function(){
                location.reload();
            },
            error : function(){alert("something went wrong");}			
		 });
	 }
 }
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
         <?php
        // put your code here
        ?>
    </body>
 </html>