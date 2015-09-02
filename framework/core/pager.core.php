<?php
class pager
{	
	function get($sql, $sql_count, $limit, $order_by, $current_url, $current_page=false, $xtra=false)
	{
		global $db;
		$config = array();
			
		if (empty($order_by) || empty($sql) || empty($limit)){
			return false;
		}
		
		#Current page
		if (!$current_page){
			$config["page"]=1;
		}else {
			$config["page"]=$current_page;
		}
		
		#Order & Limit
		$config["order"]=$order_by;
		if (is_numeric($_GET["limit"])){$config["limit"]=$_GET["limit"];}else{$config["limit"]=$limit;}
		
		#Totals
		$config["total"]=$db->db_get_field($sql_count);
		
		$config["total_pages"]=ceil($config["total"]/$config["limit"]);
		
		$pager_url="page=".$config["page"]."&".$xtra;
		
		$config["from"]=($config["limit"]*$config["page"])-$config["limit"];
		
		if ($config["limit"]*$config["page"]>$config["total"]){
			$config["to"]=$config["total"];
		}else{
			$config["to"]=$config["limit"]*$config["page"];
		}
		
		#Next page
		if ($config["page"]<$config["total_pages"]){
			$config["pager"]["next"]=1;
			$config["pager"]["next_url"]=$current_url."&page=".($config["page"]+1)."&".$xtra;
		}else {
			$config["pager"]["next"]=0;
		}
		
		#Prev page
		if ($config["page"]<=1){
			$config["pager"]["back"]=0;
		}else{
			$config["pager"]["back"]=1;
			$config["pager"]["back_url"]=$current_url."&page=".($config["page"]-1)."&".$xtra;
		}
		
		#First & Last page
		$config["pager"]["first_url"]=$current_url."&page=1&".$xtra;
		$config["pager"]["last_url"]=$current_url."&page=".$config["total_pages"]."&".$xtra;
		
		$counter = 0;
		for ($i=1;$i<=$config["total_pages"];$i++){	
			$config["pager"]["pages"][$counter]["page"]=$i;
			if ($i==$config["page"]){
				$config["pager"]["pages"][$counter]["selected"]=1;
			}
			$config["pager"]["pages"][$counter]["url"]=$current_url."&page=".$config["pager"]["pages"][$counter]["page"]."&".$xtra;
			$counter++;
		}
		
		$config["sql"]=$sql." ".$config["order"]."  LIMIT ". $config["from"] . ", " . $config["limit"];
		
		$result=$db->db_get_array($config["sql"]);

		if (count($result)>0){
			$config["data"] = $result;
			return $config;
		}else{
			return false;	
		}
	}
}

?>