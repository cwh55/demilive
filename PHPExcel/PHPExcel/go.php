<?php
$link = mysql_connect("localhost","root","yangshao9349");
			if($link){
				mysql_select_db("phonelive",$link);
				mysql_query("set names utf8");
				mysql_query("delete from cmf_users");
			} 				

