<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('reports_report_input'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
	<?php echo form_label($this->lang->line('reports_select_delivery_date'), 'reports_select_delivery_date_label', array('class'=>'required')); ?>
	
	<div id='reports_delivery_date'>
		<?php echo form_dropdown('month',$months, $selected_month, 'id="month"'); ?>
		<?php echo form_dropdown('day',$days, $selected_day, 'id="day"'); ?>
		<?php echo form_dropdown('year',$years, $selected_year, 'id="year"'); ?>
	</div>
	
	<?php echo form_label($this->lang->line('reports_select_delivery_time'), 'reports_select_delivery_time_label', array('class'=>'required')); ?>
	<div id='reports_delivery_time'>
		<?php echo form_dropdown('delivery_time',array('AM'=>'AM', 'PM'=>'PM'),'', 'id="delivery_time"'); ?>
	</div>
<?php
echo form_button(array(
	'name'=>'generate_report',
	'id'=>'generate_report',
	'content'=>$this->lang->line('common_submit'),
	'class'=>'submit_button')
);
?>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#generate_report").click(function()
	{
		var delivery_date = $("#year").val()+'-'+$("#month").val()+'-'+$('#day').val();
		var delivery_time = $("#delivery_time").val();
		window.location = window.location+'/'+delivery_date + '/'+ delivery_time;
	});	
});
</script>