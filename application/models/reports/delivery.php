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
		$this->db->select('sale_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, CONCAT(customer.address_1, " ", customer.address_2) as address, payment_type, sum(total) as total, comment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
		$this->db->where('delivery_date = "'. $inputs['delivery_date'].'"');
		$this->db->where('delivery_time = "'. $inputs['delivery_time'].'"');
		$this->db->where('zone = "'. $inputs['zone'].'"');
		$this->db->group_by('sale_id');
		
		if ($inputs['sort_by_street_name'])
		{
			$this->db->order_by('customer.address_2');
		}
		else
		{
			$this->db->order_by('customer_id');
		}
		$result = $this->db->get()->result_array();
		
		for ($k=0;$k<count($result); $k++)
		{
			
			$result[$k]['items_purchased'] = $this->getItemSummaryForSale($result[$k]['sale_id']);
		}
		
		return $result;
	}
	
	private function getItemSummaryForSale($sale_id)
	{
		$this->db->select('items.item_number, quantity_purchased');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
		$this->db->where('sale_id', $sale_id);
		
		$result = array();
		foreach($this->db->get()->result_array() as $row)
		{
			$result[] = $row['item_number'].'*'. $row['quantity_purchased'];
		}
		
		return implode(', ', $result);
	}
	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>