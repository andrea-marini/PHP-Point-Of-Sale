<?php
require_once("report.php");
class Delivery extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_sale_id'), $this->lang->line('reports_items_purchased'), $this->lang->line('reports_sold_to'), $this->lang->line('reports_total'), $this->lang->line('reports_payment_type'), $this->lang->line('reports_comments'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('sale_id, sum(quantity_purchased) as items_purchased, CONCAT(customer.first_name," ",customer.last_name) as customer_name, payment_type, sum(total) as total, comment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
		$this->db->where('delivery_date = "'. $inputs['delivery_date'].'"');
		$this->db->where('delivery_time = "'. $inputs['delivery_time'].'"');
		$this->db->where('zone = "'. $inputs['zone'].'"');
		$this->db->group_by('sale_id');
		$this->db->order_by('customer_id');
		
		return $this->db->get()->result_array();
	}	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>