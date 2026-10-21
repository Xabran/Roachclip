<?php
    require("../constants.php");
    require_once 'excel_reader2.php';
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);
    error_reporting(E_ALL ^ E_NOTICE);

   $data = new Spreadsheet_Excel_Reader("partydata.xls");
   //$data->rowspan($row,$col,$sheet=0) $data->colspan($row,$col,$sheet=0)
   //$data->val(10,'AZ')
   //$data->rowcount($sheet_index=0) $data->colcount($sheet_index=0)

   $rows = $data->rowcount($sheet_index=0);
   $cols = $data->colcount($sheet_index=0);

   for($i=1;$i<$rows;$i++)
   {//rows
       $sql = "INSERT INTO parties (EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Link) VALUES ('";
       for($j=0;$j<$cols;$j++)
       {//columns
           $val = $data->val($i,$j);
           if($cols == 2){

           }else{
               $sql .= prepareforDB($data->val($i,$j))."','";
           }

       }
       $sql = substr($sql,0,strlen($sql)-1).")";
   }

	function prepareforDB($boolVal)
	{
		return str_replace("'","''",$boolVal);
	}
?>