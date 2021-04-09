<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
if($cart_id != ''){
 
$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['items'],true);
  
$i = 1;
$subtotal = 0;
$item_count = 0;
    
}


?>
<div class="col-md-12">
    <div class="row">
        <h2 class="text-center col-md-12">My shopping cart</h2> <hr>
        <div class="bg-danger col-md-12">
            <?php if($cart_id == ''): ?>
            <p class="text-center text-danger">
                Your shopping cart is empty
            </p>
        </div>
        <?php else: ?>
        <table class="table table-bordered table-condensed table-striped">
            <thead><th>#</th><th>Item</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub total</th></thead>
        <tbody>
            <?php 
                foreach ($items as $item){
                    $product_id = $item['id'];
                    $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                    $product = mysqli_fetch_assoc($productQ);
                    $sArray = explode(',',$product['size']);
                    foreach ($sArray as $sizestring){
                        $s = explode(':',$sizestring);
                        if($s[0] == $item['size']){
                            $available = $s[1];
                        }
                    }
                    ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $product['title'];?></td>
                <td><?= money($product['price']);?></td>
                <td><button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?= $product['id'];?>','<?= $item['size'];?>');">-</button>
                <?= $item['quantity'];?>
                    <?php if($item['quantity']< $available):?>
                    <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?= $product['id'];?>','<?= $item['size'];?>');">+</button>
                    <?php else:?>
                    <span class="text-danger">Max reached</span>
                    <?php endif;?></td>
                <td><?= $item['size'];?></td>
                <td><?= money($item['quantity']*$product['price']);?></td>
            </tr>
            <?php 
            $i++;
               $item_count+= $item['quantity'];
               $subtotal +=($product['price']*$item['quantity']);
                                       
                        }
                        $tax = TAXRATE * $subtotal;
                        $tax = number_format($tax,2);
                        $grand_total = $tax + $subtotal;
            ?>
        </tbody>
        </table>
        <table class="table table-bordered table-condensed text-right">
            <thead class="totals-header"><th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th></thead>
        <tbody>
            <tr>
                <td><?= $item_count;?></td>
                <td><?= money($tax);?></td>
                <td><?= money($subtotal);?></td>
                <td class="bg-success"><?= $grand_total;?></td>
            </tr>
        </tbody>
        </table>
        <button type="button" class="btn bnt-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
            <span class="glyphicon glyphicon-shopping-cart"></span>Checkout >>
        </button>
        
        <!--Modal-->
        <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkoutModalLabel">Shipping address</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <form action="thankyou.php" method="post" id="payment-form">
                        <span class="bg-danger text-center" id="payment-errors"></span>
                        <input type="hidden" name="tax" value="<?= $tax; ?>" >
                        <input type="hidden" name="sub_total" value="<?= $sub_total; ?>" >
                        <input type="hidden" name="grand_total" value="<?= $grand_total; ?>" >
                        <input type="hidden" name="cart_id" value="<?= $cart_id; ?>" >
                        <input type="hidden" name="description" value="<?= $item_count.' item'.(($item_count>1)?'s':''); ?>" >
                        
                           <!-- <div class="form-group col-md-12"> 
                            <label for="option">Select delivery location</label>
                            <select class="form-control" name="payment_type" id="payment_type">
                                <option value=""></option>
                                <option value="fundi">NWU bus stop</option>
                                <option value="Bank card">Bohlale</option>
                                <option value="Bank card">Longfellow</option>
                                <option value="Bank card">Faranani</option>
                                <option value="Bank card">Ebukhosini</option>
                                <option value="Bank card">Moagi</option>
                                
                            </select>
                            </div>
                        </div> -->
                        <div id="step1" style="display:block;">
                            <div class="form-group col-md-6">
                                <label for="full_name">Full name:</label>
                                <input class="form-control" id="full_name" name="full_name" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email:</label>
                                <input class="form-control" id="email" name="email" type="Email" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="street">Street address:</label>
                                <input class="form-control" id="street" name="street" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="street2">Street address 2:</label>
                                <input class="form-control" id="street2" name="street" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">City:</label>
                                <input class="form-control" id="city" name="city" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state">State:</label>
                                <input class="form-control" id="state" name="state" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="zip">Zip code:</label>
                                <input class="form-control" id="zip" name="zip" type="text" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="zip">Country:</label>
                                <input class="form-control" id="country" name="country" type="text" >
                            </div>
                        </div>
                           <div id="step0" style="display: block">
                            <div class="form-group col-md-12"> 
                            <label for="option">Select payment option</label>
                            <select class="form-control" name="payment_type" id="payment_type">
                                <option value=""></option>
                                <option value="fundi">Credit Purchase</option>
                                <option value="Bank card">Bank Card</option>
                            </select>
                            </div>
                        <div id="step2" style="display:none;">
                            <div class="form-group col-md-3">
                                <label for="name">Name on Card</label>
                                <input type="text" id="name" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="number">Card number</label>
                                <input type="text" id="number" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="cvc">CVC </label>
                                <input type="text" id="cvc" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="exp-month">Expire month</label>
                                <select id="exp-month" class="form-control">
                                    <option value=""></option>
                                    <?php for($i=1; $i<13; $i++): ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                    <?php  endfor;?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="exp_year">Expire year</label>
                                <select id="exp_year" class="form-control">
                                    <option value=""></option>
                                    <?php $yr = date("Y"); ?>
                                    <?php for($i  =0;$i<11;$i++): ?>
                                    <option value="<?= $yr+$i?>"><?= $yr+$i?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button" style="display:block">Next >>></button>
                <button type="button" class="btn btn-primary" onclick="back_address(); " id="back_button" style="display:none"><<< Back</button>
                <button type="submit" class="btn btn-primary" id="check_out_button" style="display:none">Check out >>></button>
                </form>
              </div>
           </div>
          </div>
       </div>
    
        <?php endif;?>
    </div>
</div>
<script>
  function back_address(){
      jQuery('#payment-errors').html("");
                jQuery('#step1').css("display","block");
                jQuery('#step2').css("display","none");
                jQuery('#next_button').css("display","block");
                jQuery('#back_button').css("display","none");
                jQuery('#check_out_button').css("display","none");
                jQuery('#checkoutModalLabel').html("Shipping address");
  }  
    

    function check_address(){
        var datas = {
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'street' :jQuery('#street').val(),
            'street2' :jQuery('#street2').val(),
            'city' :jQuery('#city').val(),
            'state' :jQuery('#state').val(),
            'zip' :jQuery('#zip').val(),
            'country' :jQuery('#country').val(),
        };
        jQuery.ajax({
            url  : '/online_store/admin/parses/check_address.php',
           method : 'POST',
           data : datas,
           success : function(data){
                if(data == 'passed'){
                jQuery('#payment-errors').html(data);
               
            }
            if(data != 'passed')
            {
                jQuery('#payment-errors').html("gggggg");
                jQuery('#step1').css("display","none");
                jQuery('#step2').css("display","block");
                jQuery('#next_button').css("display","none");
                jQuery('#back_button').css("display","inline-block");
                jQuery('#check_out_button').css("display","inline-block");
                jQuery('#checkoutModalLabel').html("Enter your card details");
            }
           },
           error : function(){alert("something went wrong.");},
           
        });
    }
</script>
<?php
include 'includes/footer.php';


