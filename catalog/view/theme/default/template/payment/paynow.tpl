<?php
/**
 * catalog/view/theme/default/template/payment/paynow.tpl
 */

?>

<form action="<?php echo $action; ?>" method="post">   
  <input type="hidden" name="m1" value="<?php echo $m1; ?>" />
  <input type="hidden" name="m2" value="24ade73c-98cf-47b3-99be-cc7b867b3080" />  
  <input type="hidden" name="p2" value="<?php echo $p2; ?>" />
  <input type="hidden" name="p3" value="<?php echo $p3; ?>" />
  <input type="hidden" name="p4" value="<?php echo $p4; ?>" />    
  <input type="hidden" name="m4" value="<?php echo $m4; ?>" />
  <input type="hidden" name="m5" value="<?php echo $m5; ?>" />
  <input type="hidden" name="m6" value="<?php echo $m6; ?>" />
  <input type="hidden" name="m10" value="<?php echo 'route=payment/paynow/callback'; ?>" />  
  <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />    
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
