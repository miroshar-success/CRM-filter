<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                <h4 class="no-margin">woocommerce_summary</h4>
             </div>
            </div>
          </div>
        <div class="col-md-12 ">
          <div class="panel_s">
           <div class="panel-body">
<?php if(is_array($results )){ ?>
                  <div class="row mbot15">
                        <div class="col-md-2 col-xs-6 border-right">
                          <h3 class="bold"><?php echo html_escape(count($results)) ?></h3>
                          <span class="text-info"><?php echo html_escape(_l('total_orders')) ?></span>
                        </div>
                        <div class="col-md-3 col-xs-6 border-right">
                          <h3 class="bold"><?php echo html_escape(count($customers)) ?></h3>
                          <span class=""> <?php echo html_escape(_l('woocommerce_customers')) ?></span>
                        </div>
                        <div class="col-md-2 col-xs-6 border-right">
                          <h3 class="bold"><?php echo html_escape(count($products)) ?></h3>
                          <span class="text-warning"> <?php echo html_escape(_l('all_products')) ?> </span>
                        </div>
                        <div class="col-md-2 col-xs-6">
                          <h3 class="bold"><?php echo html_escape($sale[0]['total_sales']); ?></h3>
                          <span class="text-success"> <?php echo html_escape(_l('total_sales')) ?></span>
                        </div>
                      </div>
                     </div>
                </div>
               </div>
  <?php } else { ?>
    <div class="panel_s">
    <div class="panel-body">
     <div class='alert alert-warning'>
     <?php echo html_escape($results); ?>
   </div> 
   </div> 
   </div> 
   <?php }?>
     </div>
     </div>
    </div>                                
<?php init_tail(); ?>
</body>
</html>
  