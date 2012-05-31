<?php

class Application_Model_Datatables {
    
    /*
     * This is just avoiding anything to do with seraching right now since the saved settings
     * always remove any search terms
     */
    public static function createRequestFromSettings($columns, $settings) 
    {
        $request = array();
        
        $numColumns = count($columns);
        for ($i = 0; $i < $numColumns; $i++) {
            $request["mDataProp_".$i] = $columns[$i]["mDataProp"];
        }
        
        $numSortingColumns = count($settings["aaSorting"]);
        for ($i = 0; $i < $numSortingColumns; $i++) {
            $request["iSortCol_".$i] = $settings["aaSorting"][$i][0];
            $request["sSortDir_".$i] = $settings["aaSorting"][$i][1];
        }
                
        $request["iSortingCols"] = $numSortingColumns;
        $request["iDisplayStart"] = $settings["iStart"];
        $request["iDisplayLength"] = $settings["iLength"];
        $request["iColumns"] = $numColumns;
        
        $request["type"] = 0;
        $request["sEcho"] = 0;
        $request["sSearch"] = "";
        
        return $request;
    }
	
	/*
	 * query used to return data for a paginated/searchable datatable.
	 */
	public static function findEntries($con, $displayColumns, $fromTable, $data, $dataProp = "aaData")
	{
		$where = array();
		
		if ($data["sSearch"] !== "") {
			$searchTerms = explode(" ", $data["sSearch"]);
		}
	
		$selectorCount = "SELECT COUNT(*) ";
		$selectorRows = "SELECT ".join(",", $displayColumns)." ";
	
		$sql = $selectorCount." FROM ".$fromTable;
		$sqlTotalRows = $sql;
	
		if (isset($searchTerms)) {   
			$searchCols = array();
			for ($i = 0; $i < $data["iColumns"]; $i++) {
				if ($data["bSearchable_".$i] == "true") {
					$searchCols[] = $data["mDataProp_{$i}"];
				}
			}
	
			$outerCond = array();
	
			foreach ($searchTerms as $term) {
				$innerCond = array();
	
				foreach ($searchCols as $col) {
					$escapedTerm = pg_escape_string($term);
					$innerCond[] = "{$col}::text ILIKE '%{$escapedTerm}%'";
				}
				$outerCond[] = "(".join(" OR ", $innerCond).")";
			}
			$where[] = "(".join(" AND ", $outerCond).")";
		}
		// End Where clause
	
		// Order By clause
		$orderby = array();
		for ($i = 0; $i < $data["iSortingCols"]; $i++){
			$num = $data["iSortCol_".$i];
			$orderby[] = $data["mDataProp_{$num}"]." ".$data["sSortDir_".$i];
		}
		$orderby[] = "id";
		$orderby = join("," , $orderby);
		// End Order By clause
	
		$displayLength = intval($data["iDisplayLength"]);
		if (count($where) > 0) {
			$where = join(" AND ", $where);
			$sql = $selectorCount." FROM ".$fromTable." WHERE ".$where;
			$sqlTotalDisplayRows = $sql;
	
			$sql = $selectorRows." FROM ".$fromTable." WHERE ".$where." ORDER BY ".$orderby;
			
			//limit the results returned.
			if ($displayLength !== -1) {
				$sql .= " OFFSET ".$data["iDisplayStart"]." LIMIT ".$displayLength;
			}	
		}
		else {
			$sql = $selectorRows." FROM ".$fromTable." ORDER BY ".$orderby;
			
			//limit the results returned.
			if ($displayLength !== -1) {
				$sql .= " OFFSET ".$data["iDisplayStart"]." LIMIT ".$displayLength;
			}
		}
	
		try {
			$r = $con->query($sqlTotalRows);
			$totalRows = $r->fetchColumn(0);
	
			if (isset($sqlTotalDisplayRows)) {
				$r = $con->query($sqlTotalDisplayRows);
				$totalDisplayRows = $r->fetchColumn(0);
			}
			else {
				$totalDisplayRows = $totalRows;
			}
	
			$r = $con->query($sql);
			$r->setFetchMode(PDO::FETCH_ASSOC);
			$results = $r->fetchAll();
		}
		catch (Exception $e) {
			Logging::debug($e->getMessage());
		}
        
		return array(
			"sEcho" => intval($data["sEcho"]), 
			"iTotalDisplayRecords" => intval($totalDisplayRows), 
			"iTotalRecords" => intval($totalRows), 
			$dataProp => $results
		);
	}
}
