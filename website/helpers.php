<?php
function prepareforDB($boolVal)
{
    if($boolVal == '' || $boolVal==NULL){
        return NULL;
    }
    else{
        return str_replace("'","''",$boolVal);
    }
}
function checkForActive($val1, $val2, $ifTrueClass, $ifFalseClass) {
	if($val1 == $val2)
	{
		echo $ifTrueClass;
	}
	else
	{
		echo $ifFalseClass;
	}
}

function IsSelected($val1,$val2)
{
    	if($val1 == $val2)
	{
		echo "selected='selected'";
	}
	else
	{
		echo "";
	}
}
?>