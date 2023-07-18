<?php 


// if(isset($_POST['csvFileUpload'])){
// 	echo print_r($_FILES);
//     $fileName = $_FILES['file']["tmp_name"];
//    $output = array();
// 	if($_FILES["file"]["size"]>0){
// 		$file = fopen($fileName, 'r');
//         $outputCSV = array();
// 		$value = 0;
// 		$MRP = 0;
// 		$spareCode = 0;
// 		$Spare_Name = 0;
// 		$SpareDesc = 0;
// 		$k=0;
// 		while(($column = fgetcsv($file, 10000, ",")) !== false){
// 			for($i = 0; $i < count($column); $i++){
// 				if(strtolower($column[$i])=="subscription"){
// 					$value = $i;
// 				}
// 				if(strtolower($column[$i])=="mrp"){
// 					$MRP = $i;
// 				}
// 				if(strtolower($column[$i])=="spare_code"){
// 					$spareCode = $i;
// 				}
// 				if(strtolower($column[$i])=="spare_name"){
// 					$Spare_Name = $i;
// 				}
// 				if(strtolower($column[$i])=="sparedesc"){
// 					$SpareDesc = $i;
// 				}
// 				// if(strtolower($column[$i])=="discount3"){
// 				// 	$discount3 = $i;
// 				// }
// 				// if(strtolower($column[$i])=="discount6"){
// 				// 	$discount6 = $i;
// 				// }
// 				// if(strtolower($column[$i])=="discount12"){
// 				// 	$discount12 = $i;
// 				// }
// 				// if(strtolower($column[$i])=="discount24"){
// 				// 	$discount24 = $i;
// 				// }
// 				$outputCSV = array(...$outputCSV, $column);
// 			}
// 			// if($column[$discount3]){
// 			// 	$total=intval($column[$MRP])*3;
// 			// 	$percentage=intval($column[$discount3])/100;
// 			// 	if($total!=0){
// 			// 		$discount=round($total*$percentage);
// 			// 		$column[$discount3]=$total-$discount;
// 			// 	}
// 			// }

// 			// if($column[$discount6]){
// 			// 	$total=intval($column[$MRP])*6;
// 			// 	$percentage=intval($column[$discount6])/100;
// 			// 	if($total!=0){
// 			// 		$discount=round($total*$percentage);
// 			// 		$column[$discount6]=$total-$discount;
// 			// 	}
// 			// }
// 			// if($column[$discount12]){
// 			// 	$total=intval($column[$MRP])*12;
// 			// 	$percentage=intval($column[$discount12])/100;
// 			// 	if($total!=0){
// 			// 		$discount=round($total*$percentage);
// 			// 		$column[$discount12]=$total-$discount;
// 			// 	}
// 			// }
// 			// if($column[$discount24]){
// 			// 	$total=intval($column[$MRP])*24;
// 			// 	$percentage=intval($column[$discount24])/100;
// 			// 	if($total!=0){
// 			// 		$discount=round($total*$percentage);
// 			// 		$column[$discount24]=$total-$discount;
// 			// 	}
// 			// }
// 			if(strtolower($column[$value]) == "yes"){
// 				$sql32 = 'SELECT ID from '.$dbname.'.pw_posts where post_title = "'.$column[$Spare_Name].'" and post_content = "'.$column[$SpareDesc].'" and post_type = "product"';
// 				$sql32 = 'select * from '.$dbname.'.pw_postmeta where post_id = "11805" and meta_key = "_product_attributes"';
// 				$result32 = $conn->query($sql32);
// 				$output32 = array();
// 				if($result32-> num_rows>0){
// 					while($row32 = $result32-> fetch_assoc()){
// 						$output32 = array(...$output32,$row32);
// 					}
// 				}
// 				$output = array(...$output, array($column[$value],$column[$MRP],$column[$spareCode],$output32));
// 				// $output = array(...$output, array($column[$MRP],$column[$discount3],$column[$discount6],$column[$discount12],$column[$discount24]));	
// 			}
			
// 		}
// 		// echo("working");
	 
// 		echo json_encode($output);

// // 		$sql31_2 = 'SELECT max(post.ID) as maxNUM FROM '.$dbname.'.pw_posts post ';
// // 		$result31_2 = $conn->query($sql31_2);
		
// // 		if($result31_2-> num_rows>0){
// // 			while($row31_2 = $result31_2-> fetch_assoc()){
// // 				// $output = array(...$output, $row31_2["maxNUM"]);
// // 			}
// // 		}
// // 		// ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count
// // 	}

// // 	$sql32 = 'select * from '.$dbname.'.pw_postmeta where post_id = "100231619" and meta_key = "_product_attributes"';
// // 				$result32 = $conn->query($sql32);
// // 				$output32 = array();
// // 				if($result32-> num_rows>0){
// // 					while($row32 = $result32-> fetch_assoc()){
// // 						$output32 = $row32["meta_value"];
// // 						$existing_attributes = unserialize($output32);
// // 						echo print_r($existing_attributes);
// // // Update the frequency attribute with new values
// // if(isset($existing_attributes['frequency']['value'])){

// // 	$existing_attributes['frequency']['value'] = 'None | 3 Month | 6 Month | 12 Month | 24 Month';
// // }else{
// // 	$existing_attributes['frequency']["name"] = "frequency" ;
// // 	$existing_attributes['frequency']['value'] = 'None | 3 Month | 6 Month | 12 Month | 24 Month';
// // 	$existing_attributes['frequency']["position"] = 2;
// // 	$existing_attributes['frequency']["is_visible"] = 1;
// // 	$existing_attributes['frequency']["is_variation"] = 1;
// // 	$existing_attributes['frequency']["is_taxonomy"] = 0;
// // }


// // // Serialize the updated attributes data
// // $updated_attributes = serialize($existing_attributes);
// // 						// $output32 = array(...$output32,$row32);
// // 			// $safeUpdate = 'SET SQL_SAFE_UPDATES=1;';
// // 			//  $conn->query($safeUpdate);		
// // 			$sql32_3 = "UPDATE '.$dbname.'.pw_postmeta SET meta_value = '".$updated_attributes."' where post_id = '100231619' and meta_key = '_product_attributes'";
// // 			 if (!$conn->query($sql32_3)){
// // 				echo("Error description: " . $conn -> error);
			  
				
// // 			 };	
// // 			// if($result32_2-> num_rows>0){
// // 			// 	while($row32_3 = $result32_2-> fetch_assoc()){
// // 					// echo print_r($result32_2 );
// // 				// }}	
// // 			// 	$safeUpdateoff = 'SET SQL_SAFE_UPDATES=0;';
// // 			//  $conn->query($safeUpdateoff);		
			
// // 						echo print_r($updated_attributes);
// // 					}

					

// 				}

// }


   


// ?>