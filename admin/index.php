<?php
require_once '../core/init.php';
if(!is_logged_in())
{
	header('Location: login.php');
}

include 'includes/header.php';
include 'includes/navigation.php';
?>
<!-- Orders to fill-->
<?php 
 $txnQ = "SELECT t.id, t.cart_id,t.full_name,t.description, t.txn_date, t.grand_total, c.items, c.paid, c.shipped FROM transactions t LEFT JOIN cart c ON t.cart_id = c.id WHERE c.paid = 1 AND c.shipped = 0 ORDER BY t.txn_date";

 $txnResults = $db->query($txnQ);

 
?>
<div class="col-md-12">
    <table class="table table-bordered table-condensed table-striped">
        <thead>
        <th></th><th>Name</th><th>Description</th><th>Total</th><th>Date</th>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($txnResults)): ?>
        <tr>
        <td><a href="orders.php?txn_id=<?= $order['id']; ?>"class="btn btn-xs btn-info">Details</a></td>
        <td><?= $order['full_name'] ?></td>
        <td><?= $order['description']; ?></td>
        <td><?= money($order['grand_total']);?></td>
        <td><?= pretty_date($order['txn_date']); ?></td>
        </tr>
        <?php endwhile; ?> 
        </tbody>
    </table>
        
</div>

<?php

include 'includes/footer.php';
?>
