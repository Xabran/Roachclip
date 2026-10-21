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
   //echo $data->dump($row_numbers=false,$col_letters=false,$sheet=0,$table_class='excel');
   //$rows = $data->rowcount($sheet_index=0);
   //$cols = $data->colcount($sheet_index=0);
   $cols = 12;
   $rows = 66;

   for($i=2;$i<=$rows;$i++)
   {//rows
       if($data->val($i,0) == "End"){break;}
       $sql = "INSERT INTO parties (EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Link) VALUES (";
       for($j=1;$j<=$cols;$j++)
       {//columns
           $val = $data->val($i,$j);
           if($j == 5 || $j == 7 || $j == 3 ){
                
           }
           else if($j == 4 || $j == 6 ){
               $sql .= "'".toMYSQLDate(prepareforDB($val))."',";
           }else{
               $sql .= "'".prepareforDB($val)."',";
           }
           //echo $data->val($i,$j);
       }
       $sql = substr($sql,0,strlen($sql)-1).")";
       echo $sql . "<br><br>";
       mysql_query($sql);
   }
   

	function prepareforDB($val)
	{
		return trim(str_replace("'","''",$val));
	}

        function toMYSQLDate($val)
        {
            return strftime("%Y/%m/%d",strtotime($val));
        }
?>