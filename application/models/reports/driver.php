<?php
require_once("report.php");
class Driver extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_item'), $this->lang->line('reports_quanitity_needed'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select("items.name, SUM(quantity_purchased) as quantity_purchased", false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');
		$this->db->where('delivery_date = "'. $inputs['delivery_date'].'"');
		$this->db->where('delivery_time = "'. $inputs['delivery_time'].'"');
		$this->db->where('zone = "'. $inputs['zone'].'"');
		$this->db->group_by('sales_items_temp.item_id');
		$this->db->order_by('items.name');
		
		return $this->db->get()->result_array();
	}
	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>