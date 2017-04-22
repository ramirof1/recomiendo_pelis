<?php

global $sgrb;
$sgrb->includeLib('ReviewList');

class SGRB_Review extends SGRB_ReviewList
{
	protected $id = '';
	protected $columns = array();
	protected $displayColumns = array();
	protected $sortableColumns = array();
	protected $tablename = '';
	protected $rowsPerPage = 10;
	protected $initialOrder = array();

	public function __construct($id)
	{
		global $sgrb;
		$this->id = $id;
		parent::__construct(array(
			'singular'=> 'wp_'.$sgrb->prefix.'_'.$id, //singular label
			'plural' => 'wp_'.$sgrb->prefix.'_'.$id.'s', //plural label
			'ajax' => false
		));
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setRowsPerPage($rowsPerPage)
	{
		$this->rowsPerPage = $rowsPerPage;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	public function setDisplayColumns($displayColumns)
	{
		$this->displayColumns = $displayColumns;
	}

	public function setSortableColumns($sortableColumns)
	{
		$this->sortableColumns = $sortableColumns;
	}

	public function setTablename($tablename)
	{
		global $sgrb;
		$this->tablename = $sgrb->tablename($tablename);
	}

	public function setInitialSort($orderableColumns)
	{
		$this->initialOrder = $orderableColumns;
	}

	public function get_columns()
	{
		return $this->displayColumns;
	}

	public function prepare_items()
	{
		global $wpdb;
		$table = $this->tablename;

		$query = "SELECT COUNT(*) FROM ".$table;

		$totalItems = (int)$wpdb->get_var($query); //return the total number of affected rows
		$perPage = $this->rowsPerPage;

		$totalPages = ceil($totalItems/$perPage);

		$query = "SELECT ".implode(', ', $this->columns)." FROM ".$table;
		$this->customizeQuery($query);

		$orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : 'ASC';
		$order = isset($_GET["order"]) ? $_GET["order"] : '';

		if(isset($this->initialOrder) && empty($order)){
			foreach($this->initialOrder as $key=>$val){
				$order = $val;
				$orderby = $key;
			}
		}

		if (!empty($orderby) && !empty($order)) {
			$query .= ' ORDER BY '.$orderby.' '.$order;
		}

		$paged = isset($_GET["paged"]) ? (int)$_GET["paged"] : '';

		if (empty($paged) || !is_numeric($paged) || $paged<=0) {
			$paged = 1;
		}

		//adjust the query to take pagination into account
		if(!empty($paged) && !empty($perPage)) {
			$offset = ($paged-1) * $perPage;
			$query .= ' LIMIT '.(int)$offset.','.(int)$perPage;
		}

		$this->set_pagination_args(array(
			"total_items" => $totalItems,
			"total_pages" => $totalPages,
			"per_page" => $perPage,
		));

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $wpdb->get_results($query, ARRAY_N);
	}

	public function get_sortable_columns() {
		return $this->sortableColumns;
	}

	public function display_rows()
	{
		//get the records registered in the prepare_items method
		$records = $this->items;

		//get the columns registered in the get_columns and get_sortable_columns methods
		list($columns, $hidden) = $this->get_column_info();

		if (!empty($records)) {
			foreach($records as $rec) {
				echo '<tr>';

				$this->customizeRow($rec);
				for ($i = 0; $i<count($rec); $i++) {
					echo '<td>'.stripslashes($rec[$i]).'</td>';
				}

				echo '</tr>';
			}
		}
	}

	public function customizeRow(&$row)
	{

	}

	public function customizeQuery(&$query)
	{

	}

	public function setViews($actions) {
		$this->actions = $actions;
		return $this->views();

	}

	public function __toString()
	{
		$this->prepare_items();
		$this->display();
		return '';
	}

	public function getTablename()
	{
		return $this->tablename;
	}

}
