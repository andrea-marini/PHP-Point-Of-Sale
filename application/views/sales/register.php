<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('sales_register'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}

if (isset($warning))
{
	echo "<div class='warning_mesage'>".$warning."</div>";
}
?>
<div id="register_wrapper">
<?php echo form_open("sales/change_mode",array('id'=>'mode_form')); ?>
	<span><?php echo $this->lang->line('sales_mode') ?></span>
<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
</form>
<?php echo form_open("sales/add",array('id'=>'add_item_form')); ?>
<label id="item_label" for="item">

<?php
if($mode=='sale')
{
	echo $this->lang->line('sales_find_or_scan_item');
}
else
{
	echo $this->lang->line('sales_find_or_scan_item_or_receipt');
}
?>
</label>
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));?>
<div id="new_item_button_register" >
		<?php echo anchor("items/view/-1/width:360",
		"<div class='small_button'><span>".$this->lang->line('sales_new_item')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));
		?>
	</div>

</form>
<table id="register">
<thead>
<tr>
<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
<th style="width:30%;"><?php echo $this->lang->line('sales_item_number'); ?></th>
<th style="width:30%;"><?php echo $this->lang->line('sales_item_name'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_price'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_discount'); ?></th>
<th style="width:15%;"><?php echo $this->lang->line('sales_total'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='8'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach($cart as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("sales/edit_item/$line");
	?>
		<tr>
		<td><?php echo anchor("sales/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>
		<td><?php echo $item['item_number']; ?></td>
		<td style="align:center;"><?php echo $item['name']; ?><br /> [<?php echo $cur_item_info->quantity; ?> in stock]</td>



		<?php if ($items_module_allowed)
		{
		?>
			<td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php
		}
		else
		{
		?>
			<td><?php echo $item['price']; ?></td>
			<?php echo form_hidden('price',$item['price']); ?>
		<?php
		}
		?>

		<td>
		<?php
        	if($item['is_serialized']==1)
        	{
        		echo $item['quantity'];
        		echo form_hidden('quantity',$item['quantity']);
        	}
        	else
        	{
        		echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
        	}
		?>
		</td>

		<td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		<td><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?></td>
		</tr>
		<tr>
		<td style="color:#2F4F4F";><?php echo $this->lang->line('sales_description_abbrv').':';?></td>
		<td colspan=2 style="text-align:left;">

		<?php
        	if($item['allow_alt_description']==1)
        	{
        		echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20'));
        	}
        	else
        	{
				if ($item['description']!='')
				{
					echo $item['description'];
        			echo form_hidden('description',$item['description']);
        		}
        		else
        		{
        			echo 'None';
        			echo form_hidden('description','');
        		}
        	}
		?>
		</td>
		<td>&nbsp;</td>
		<td style="color:#2F4F4F";>
		<?php
        	if($item['is_serialized']==1)
        	{
				echo $this->lang->line('sales_serial').':';
			}
		?>
		</td>
		<td colspan=3 style="text-align:left;">
		<?php
        	if($item['is_serialized']==1)
        	{
        		echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20'));
			}
		?>
		</td>


		</tr>
		<tr style="height:3px">
		<td colspan=8 style="background-color:white"> </td>
		</tr>		</form>
	<?php
	}
}
?>
</tbody>
</table>
<?php
if(isset($customer))
{
?>
<div id="last_15_items_purchased">
	<h2>Last 15 items Purchased by <?php echo $customer;?></h2>
	<hr />
	<ul>
		<?php
		foreach($recent_purchased_items->result() as $item)
		{
		?>
			<li><?php echo $item->item_number. ' - '.$item->name. ' - '. $item->description; ?></li>
		<?php	
		}	
		?>
	</ul>

</div>
<?php
}
?>
</div>


<div id="overall_sale">
	<?php
	if(isset($customer))
	{
		echo $this->lang->line("sales_customer").': <b>'.$customer. '</b><br />';
		echo anchor("sales/delete_customer",'['.$this->lang->line('common_delete').' '.$this->lang->line('customers_customer').']');
	}
	else
	{
		echo form_open("sales/select_customer",array('id'=>'select_customer_form')); ?>
		<label id="customer_label" for="customer"><?php echo $this->lang->line('sales_select_customer'); ?></label>
		<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'30','value'=>$this->lang->line('sales_start_typing_customer_name')));?>
		</form>
		<div style="margin-top:5px;text-align:center;">
		<h3 style="margin: 5px 0 5px 0"><?php echo $this->lang->line('common_or'); ?></h3>
		<?php echo anchor("customers/view/-1/width:350",
		"<div class='small_button' style='margin:0 auto;'><span>".$this->lang->line('sales_new_customer')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_customer')));
		?>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

	<div id='sale_details'>
		<div class="float_left" style="width:55%;"><?php echo $this->lang->line('sales_sub_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($subtotal); ?></div>

		<?php foreach($taxes as $name=>$value) { ?>
		<div class="float_left" style='width:55%;'><?php echo $name; ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($value); ?></div>
		<?php }; ?>

		<div class="float_left" style='width:55%;'><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($total); ?></div>
	</div>




	<?php
	// Only show this part if there are Items already in the sale.
	if(count($cart) > 0)
	{
	?>

    	<div id="Cancel_sale">
		<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
		<div class='small_button' id='cancel_sale_button' style='margin-top:5px;'>
			<span><?php echo $this->lang->line('sales_cancel_sale'); ?></span>
		</div>
    	</form>
    	</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<?php
		// Only show this part if there is at least one payment entered and a customer has been chosen
		if(count($payments) > 0 && isset($customer))
		{
			if (isset($payments[$this->lang->line('sales_integrated_credit_card')]))
			{	
			?>
			<form method="post" action="<?php echo AuthorizeNetDPM::SANDBOX_URL;?>" id="credit_card_form">
			       
			      <?php echo $sim->getHiddenFieldString();
			    ?>
			      <fieldset>
			        <div>
			          <label>Credit Card Number</label>
			          <input type="text" class="text required creditcard" size="15" name="x_card_num" value="<?php echo isset($customer_info->cc_number) ? $customer_info->cc_number : ''; ?>"></input>
			        </div>
			        <div>
			          <label>Exp.</label>
			          <input type="text" class="text required" size="4" name="x_exp_date" value="<?php echo isset($customer_info->cc_expiration) ? $customer_info->cc_expiration : ''; ?>"></input>
			        </div>
			        <div>
			          <label>CCV</label>
			          <input type="text" class="text required" size="4" name="x_card_code" value="<?php echo isset($customer_info->cc_security_code) ? $customer_info->cc_security_code : ''; ?>"></input>
			        </div>
			      </fieldset>
			      <fieldset>
			        <div>
			          <label>First Name</label>
			          <input type="text" class="text required" size="15" name="x_first_name" value="<?php echo isset($customer_info->first_name) ? $customer_info->first_name : ''; ?>"></input>
			        </div>
			        <div>
			          <label>Last Name</label>
			          <input type="text" class="text required" size="14" name="x_last_name" value="<?php echo isset($customer_info->last_name) ? $customer_info->last_name : ''; ?>"></input>
			        </div>
			      </fieldset>
			      <fieldset>
			        <div>
			          <label>Address</label>
			          <input type="text" class="text required" size="26" name="x_address" value="<?php echo isset($customer_info->address_1) ? $customer_info->address_1 : ''; ?>"></input>
			        </div>
			        <div>
			          <label>City</label>
			          <input type="text" class="text required" size="15" name="x_city" value="<?php echo isset($customer_info->city) ? $customer_info->city : ''; ?>"></input>
			        </div>
			      </fieldset>
			      <fieldset>
			        <div>
			          <label>State</label>
			          <input type="text" class="text required" size="4" name="x_state" value="<?php echo isset($customer_info->state) ? $customer_info->state : ''; ?>"></input>
			        </div>
			        <div>
			          <label>Zip Code</label>
			          <input type="text" class="text required" size="9" name="x_zip" value="<?php echo isset($customer_info->billing_zip) ? $customer_info->billing_zip : ''; ?>"></input>
			        </div>
			        <div>
			          <label>Country</label>
			          <input type="text" class="text required" size="22" name="x_country" value="<?php echo isset($customer_info->country) ? $customer_info->country : ''; ?>"></input>
			        </div>
			      </fieldset>
			    </form>	
				
			<?php	
			}
			?>
			<div id="finish_sale">
				<?php echo form_open("sales/complete",array('id'=>'finish_sale_form')); ?>
				
				<?php
				if (!isset($payments[$this->lang->line('sales_integrated_credit_card')]))
				{	
				?>
					<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
					<?php echo form_textarea(array('name'=>'comment','value'=>'','rows'=>'4','cols'=>'23'));?>
				<?php
				}
				?>
				<br /><br />
				
				<table border="0">
					<tr>
						<td><label id="delivery_date_label" for="delivery_date"><?php echo $this->lang->line('sales_delivery_date'); ?>:</label></td>
						<td><?php echo form_input(array('name'=>'delivery_date','id'=>'delivery_date'));?></td>
					</tr>
						
					<tr>
						<td><label id="delivery_time_label" for="delivery_time"><?php echo $this->lang->line('sales_delivery_time'); ?>:</label></td>
						<td><?php echo form_dropdown('delivery_time',array('AM' => 'AM', 'PM' => 'PM'), '', "id='delivery_time'");?></td>
					</tr>
					
					<tr>
						<td><label id="balance_label" for="balance"><?php echo $this->lang->line('sales_balance'); ?>:</label></td>
						<td><?php echo form_input(array('name'=>'balance','id'=>'balance', 'value' => '0.00', 'size' => 8));?></td>
					</tr>					
				</table>
				<?php echo "<div class='small_button' id='finish_sale_button' style='float:left;margin-top:5px;'><span>".$this->lang->line('sales_complete_sale')."</span></div>";
				?>
			</div>
			</form>
		<?php
		}
		?>
    <table width="100%"><tr>
    <td style="width:55%; "><div class="float_left"><?php echo 'Payments Total:' ?></div></td>
    <td style="width:45%; text-align:right;"><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($payments_total); ?></div></td>
	</tr>
	<tr>
	<td style="width:55%; "><div class="float_left" ><?php echo 'Amount Due:' ?></div></td>
	<td style="width:45%; text-align:right; "><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_due); ?></div></td>
	</tr></table>

	<div id="Payment_Types" >

		<div style="height:100px;">

			<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form')); ?>
			<table width="100%">
			<tr>
			<td>
				<?php echo $this->lang->line('sales_payment').':   ';?>
			</td>
			<td>
				<?php echo form_dropdown('payment_type',$payment_options,array(), 'id="payment_types"');?>
			</td>
			</tr>
			<tr>
			<td>
				<span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered').': ';?></span>
			</td>
			<td>
				<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'size'=>'10'));	?>
			</td>
			</tr>
        	</table>
			<div class='small_button' id='add_payment_button' style='float:left;margin-top:5px;'>
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
		</div>
		</form>

		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
	    	<table id="register">
	    	<thead>
			<tr>
			<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
			<th style="width:60%;"><?php echo 'Type'; ?></th>
			<th style="width:18%;"><?php echo 'Amount'; ?></th>
			</tr>
			</thead>
			<tbody id="payment_contents">
			<?php
				foreach($payments as $payment_id=>$payment)
				{
				echo form_open("sales/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor("sales/delete_payment/$payment_id",'['.$this->lang->line('common_delete').']');?></td>


				<td><?php echo  $payment['payment_type']    ?> </td>
				<td style="text-align:right;"><?php echo  to_currency($payment['payment_amount'])  ?>  </td>


				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
		    <br>
		<?php
		}
		?>



	</div>

	<?php
	}
	?>
</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	var today = new Date();
	$('#delivery_date').datePicker({startDate: '01/01/1970'});
	$("#delivery_date").val(today.asString());
	
	
    $("#item").autocomplete('<?php echo site_url("sales/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
    	selectFirst: false,
       	delay:10,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });

    $("#customer").autocomplete('<?php echo site_url("sales/customer_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#customer").result(function(event, data, formatted)
    {
		$("#select_customer_form").submit();
    });

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_finish_sale"); ?>'))
    	{
			<?php if (isset($payments[$this->lang->line('sales_integrated_credit_card')]))
			{
			?>
				$.post("<?php echo site_url('sales/set_delivery_infomation'); ?>", {
					'delivery_date': $("#delivery_date").val(),
					'delivery_time': $("#delivery_time").val(),
					'balance': $("#balance").val()
				}, function()
				{
					$('#credit_card_form').submit();
				});
			<?php
			}
			else
			{
			?>
    			$('#finish_sale_form').submit();
			<?php
			}
			?>
    	}
    });

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_cancel_sale"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });

	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });

	$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard)
});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#customer").attr("value",response.person_id);
		$("#select_customer_form").submit();
	}
}

function checkPaymentTypeGiftcard()
{
	if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>")
	{
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
	}
	else
	{
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");		
	}
}

</script>
