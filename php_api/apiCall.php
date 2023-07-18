<?php


header('Content-Type: application/json');

include "apiConnection.php";
$url = site_url();

$split = explode('/', $url);

$newURL = $split[0]."//".$split[2];


$PostData = json_decode(file_get_contents('php://input'), true);


function get_product_image($row18){
	global $conn;
	global $dbname;
	$outputData;
	// $sql18_2 = 'SELECT postmeta.meta_value FROM '.$dbname.'.pw_posts post join '.$dbname.'.pw_postmeta postmeta on postmeta.post_id = post.ID WHERE post.post_title LIKE "%'.$row18['sku'].'%" and postmeta.meta_key = "_wp_attached_file"';
	$sql18_2 = 'select pMeta.meta_value from '.$dbname.'.pw_postmeta pMeta where post_id in (select p_meta.meta_value from '.$dbname.'.pw_wc_product_meta_lookup p_l join '.$dbname.'.pw_postmeta p_meta on p_meta.post_id = p_l.product_id where sku = "'.$row18['sku'].'" and p_meta.meta_key = "_thumbnail_id") and pMeta.meta_key = "_wp_attached_file";';
	$result18_2 = $conn->query($sql18_2);
	$row18['imageURL'] = array();
	if($result18_2-> num_rows>0){
		while($row18_2 = $result18_2-> fetch_assoc()){
			$row18['imageURL'] = array(...$row18['imageURL'], $newURL."/wp-content/uploads/".$row18_2['meta_value']);
		}
		$outputData =   $row18;
	}else if($result18_2 -> num_rows == 0){
		$row18['imageURL'] = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
		$outputData = $row18;
	}
	return $outputData;
}

function getChildCategory($childCategory){
	global $conn;
	global $dbname;
	$sql19 = 'SELECT * from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value  JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value  where meta.post_id in (select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in (select meta.post_id from '.$dbname.'.pw_postmeta meta left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where meta_value in (
		SELECT term_id FROM 
		'.$dbname.'.pw_terms 
		WHERE 
		slug = "'.$childCategory.'") and posts.post_status = "publish" ) and meta.meta_key = "_menu_item_menu_item_parent") and meta.meta_key = "_menu_item_object_id";';
		$sql19 = 'select distinct(t2.term_id), t2.name, t2.slug, tax2.* , t.term_id as post_parent from '.$dbname.'.pw_terms t left join '.$dbname.'.pw_postmeta pmeta on pmeta.meta_value = t.term_id left join '.$dbname.'.pw_postmeta pmeta3 on pmeta3.meta_value = pmeta.post_id and pmeta3.meta_key = "_menu_item_menu_item_parent"  left join '.$dbname.'.pw_postmeta pmeta2 on pmeta2.post_id = pmeta3.post_id and pmeta2.meta_key = "_menu_item_object_id" left join '.$dbname.'.pw_term_taxonomy tax on tax.parent = t.term_id join '.$dbname.'.pw_terms t2 on coalesce(t2.term_id = pmeta2.meta_value , t2.term_id = tax.term_id) left join '.$dbname.'.pw_term_taxonomy tax2 on tax2.term_id = t2.term_id where t.slug = "'.$childCategory.'"';
	$result19 = $conn->query($sql19);
	$outputData = array();
	if($result19-> num_rows>0){
		while($row19 = $result19-> fetch_assoc()){

			
			
			// $sql19_3 = "SELECT meta_value FROM ".$dbname.".pw_postmeta meta join ".$dbname.".pw_posts post on post.ID = meta.post_id where post_id  in (select meta.meta_value  from ".$dbname.".pw_postmeta meta join ".$dbname.".pw_term_relationships relation on relation.object_id = meta.post_id where meta.post_id = '".$row19["post_id"]."'  and meta_key = '_menu_item_menu_item_parent') and meta.meta_key = '_menu_item_object_id' and post.post_status = 'publish'";
			
			// $result19_3 = $conn->query($sql19_3); 
			
			// if($result19_3-> num_rows > 0 ){
			// 	while($row19_3 = $result19_3->fetch_assoc()){
					
			// 		$row19['post_parent'] = $row19_3['meta_value'];
					
			// 	}
			// }else{
			// 	$row19['post_parent'] = 0;
			// }
			
			$sql19_2 = 'SELECT * FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value WHERE termmeta.term_id IN ('.$row19['term_id'].') and postmeta.meta_key = "_wp_attached_file"';

			$result19_2 = $conn->query($sql19_2); 
			 $row19['imageURL'] = array();
			 if($result19_2-> num_rows>0){
				  while($row19_2 = $result19_2-> fetch_assoc()){
					$row19['imageURL'] = array(...$row19['imageURL'], $newURL."/wp-content/uploads/".$row19_2['meta_value']);
				}

			

				// if(isset($PostData['getCategoryDetail'])){
					$row19["child"] = getChildCategory($row19["slug"]);
					$outputData =  array(...$outputData, $row19);
				// }else {
				// 	$outputData =  array(...$outputData, $row19);
				// }
			}else if($result19_2 -> num_rows == 0){
				$row19['imageURL'] = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
				// if(isset($PostData['getCategoryDetail'])){
					$row19["child"] = getChildCategory($row19["slug"]);
					$outputData =  array(...$outputData, $row19);
				// }else {
				// $outputData =  array(...$outputData, $row19);
				// }
			}
		}
	}

	

	return $outputData;
}

function getImage($term_id = ''){
	global $conn;
	global $dbname;
    global $newURL;
	$sql15 = 'SELECT * FROM '.$dbname.'.pw_postmeta WHERE post_id IN (SELECT ID FROM '.$dbname.'.pw_termmeta termMeta JOIN '.$dbname.'.pw_posts post ON termMeta.meta_value = post.ID where termMeta.term_id ='.$term_id.')';
	$result15 = $conn->query($sql15); 
	$imageOutput = array();
	if($result15-> num_rows > 0 ){
		while($row2 = $result15->fetch_assoc()){
			if($row2['meta_key'] == "_wp_attached_file"){
				$imageOutput = array(...$imageOutput,$newURL."/wp-content/uploads/".$row2['meta_value']);
			}
		}
	}else if($result15 -> num_rows == 0){
		$imageOutput = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
	}

	return $imageOutput;

}

if(isset($PostData['menuType'])){

	$type =  $PostData['menuType'];
	$sql14 = 'SELECT name , post_parent , post_id , term.term_id , slug, count from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_posts posts  on posts.ID = relation.object_id join '.$dbname.'.pw_postmeta meta on meta.post_id = posts.ID join '.$dbname.'.pw_terms term on term.term_id = meta.meta_value join '.$dbname.'.pw_term_taxonomy tax on term.term_id = tax.term_id  where relation.term_taxonomy_id =(SELECT t.term_id FROM pw_term_taxonomy as tax 
	LEFT JOIN pw_terms as t ON tax.term_id = t.term_id WHERE taxonomy = "nav_menu" and name like "%'.$type.'%") and meta.meta_key = "_menu_item_object_id" and posts.post_status = "publish"';
	$result14 = $conn->query($sql14); 
	$data = array();
	if($result14-> num_rows > 0 ){
		while($row1 = $result14->fetch_assoc()){
			
			// $sql15 = 'SELECT * FROM '.$dbname.'.pw_postmeta WHERE post_id IN (SELECT ID FROM '.$dbname.'.pw_termmeta termMeta JOIN '.$dbname.'.pw_posts post ON termMeta.meta_value = post.ID where termMeta.term_id ='.$row1["term_id"].')';
			// $result15 = $conn->query($sql15); 
			// $row1['imageURL'] = array();
			// if($result15-> num_rows > 0 ){
			// 	while($row2 = $result15->fetch_assoc()){
			// 		if($row2['meta_key'] == "_wp_attached_file"){
			// 		$row1['imageURL'] = array(...$row1['imageURL'],$newURL."/wp-content/uploads/".$row2['meta_value']);
			// 		}
			// 	}
			// }else if($result15 -> num_rows == 0){
			// 	$row1['imageURL'] = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
			// }

			$row1['imageURL'] = getImage($row1["term_id"]);
			
			
			$sql15_2 = "SELECT meta_value FROM ".$dbname.".pw_postmeta meta join ".$dbname.".pw_posts post on post.ID = meta.post_id where post_id  in (select meta.meta_value  from ".$dbname.".pw_postmeta meta join ".$dbname.".pw_term_relationships relation on relation.object_id = meta.post_id where meta.post_id = '".$row1["post_id"]."'  and meta_key = '_menu_item_menu_item_parent') and meta.meta_key = '_menu_item_object_id' and post.post_status = 'publish'";
			
			$result15_2 = $conn->query($sql15_2); 
			
			if($result15_2-> num_rows > 0 ){
				while($row2_2 = $result15_2->fetch_assoc()){
					
						$row1['post_parent'] = $row2_2['meta_value'];
					
				}
			}else{
				$row1['post_parent'] = 0;
			}
			
			$data = array(...$data, $row1);
		}
	}

	  echo json_encode($data);

}else if(isset($PostData['brand'])){


	function filter($object, $FilterBY){
		$outputObject =  array();
		for( $i = 0 ; $i < sizeof($object) ; $i++){
			$contion = true;
			for( $x = 0 ; $x < sizeof($outputObject) ; $x++){
				if($object[$i][$FilterBY] == $outputObject[$x][$FilterBY]){
					$contion = false;
				}	
			}
			if( $contion ){
				$outputObject = array(...$outputObject, $object[$i]);
			}
		}
		return $outputObject ;
	}
	
	function getBrand ($slug) {
		global $conn;
		global $dbname;
		$sql20 = '   select * from  '.$dbname.'.pw_term_taxonomy termtax join '.$dbname.'.pw_terms terms on terms.term_id = termtax.term_id where termtax.term_id in (select relation.term_taxonomy_id  from '.$dbname.'.pw_term_relationships relation  where relation.object_id in (select relation.object_id from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in (SELECT term_id FROM 
		'.$dbname.'.pw_terms 
		WHERE 
		slug = "'.$slug.'"))) and taxonomy = "pa_brands";';
	
		$result20 = $conn->query($sql20);
		$outputData = array();
		if($result20-> num_rows>0){
			while($row20 = $result20-> fetch_assoc()){
	
				$split = explode(',', $row20['name']);
				$rowdata;
				foreach ($split as $key => $value) {
					$rowdata = $row20;
					$rowdata['name'] = $value;
					$outputData =  array(...$outputData, $rowdata);
				}
				
				
				$outputData = filter($outputData, "name");
				$afterImgAdd = array();
				foreach ($outputData as $key => $row21) {
						$sql20_2 = 'SELECT postmeta.meta_value FROM '.$dbname.'.pw_posts post join '.$dbname.'.pw_postmeta postmeta on post.ID = postmeta.post_id  where post.post_title like "%'.$row21['name'].'-logo%" and postmeta.meta_key = "_wp_attached_file"';
	
						$result20_2 = $conn->query($sql20_2);
						$row21["imageURL"] = array();
	
						if($result20_2-> num_rows>0){
							while($row20_2 = $result20_2-> fetch_assoc()){
								$row21["imageURL"] = array(...$row21["imageURL"], $newURL."/wp-content/uploads/".$row20_2['meta_value']);
							}
						}else if($result20_2-> num_rows == 0){
							$row21["imageURL"] = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
							
						}
						
						$afterImgAdd = array(...$afterImgAdd, $row21);
				}
				$outputData = $afterImgAdd;
			}
		}
	
	
		return $outputData;
	}


	echo json_encode(getBrand($PostData['brand']));
	
}else if(isset($PostData['product'])){
	$GlobaloutputData = array();


		$sql18 = 'SELECT *, pmeta.meta_value as regular_price  FROM 
		'.$dbname.'.pw_term_relationships relation 
		JOIN 
		'.$dbname.'.pw_term_taxonomy tax 
		ON 
		tax.term_id = relation.term_taxonomy_id 
		JOIN 
		'.$dbname.'.pw_wc_product_meta_lookup lookup 
		ON 
		relation.object_id = lookup.product_id 
		JOIN 
		'.$dbname.'.pw_posts posts 
		ON 
		posts.ID = lookup.product_id 
		JOIN 
		'.$dbname.'.pw_terms terms 
		ON 
		terms.term_id = relation.term_taxonomy_id
		JOIN 
		'.$dbname.'.pw_postmeta pmeta 
		ON 
		pmeta.post_id = posts.ID
		WHERE 
		relation.object_id 
		IN 
		(SELECT relation.object_id FROM 
		'.$dbname.'.pw_term_relationships relation  
		WHERE 
		relation.term_taxonomy_id in (select tax2.term_id from '.$dbname.'.pw_term_taxonomy tax2 where tax2.parent in (
			select term_id from 
			'.$dbname.'.pw_terms t3
			where 
			slug = "'.$PostData['product'].'") or tax2.term_id in (select term_id from 
			'.$dbname.'.pw_terms t3
			where 
			slug = "'.$PostData['product'].'"))) 
			and 
			tax.description = "" 
			and tax.taxonomy = "product_cat" and posts.post_status = "publish" and pmeta.meta_key = "_regular_price"';

   
	
			$result18 = $conn->query($sql18);
			$outputData = array();
			if($result18-> num_rows>0){
				while($row18_3 = $result18-> fetch_assoc()){
   
					if($row18_3["term_id"] ==  "395" && $PostData['product'] == "subscription"){
						
					
						$outputData =  array(...$outputData, get_product_image($row18_3));
						
					}else if($PostData['product'] != "subscription"){
						$outputData =  array(...$outputData, get_product_image($row18_3));
					
					
						
					}
					
						
				}
			}

			$result25 = $outputData;
		$brandFilterProduct2=  array();
		for($x=0; $x < count($result25); $x++){


			$sql30 = 'SELECT * FROM 
			'.$dbname.'.pw_term_relationships relation 
			JOIN 
			'.$dbname.'.pw_term_taxonomy tax 
			ON 
			tax.term_id = relation.term_taxonomy_id 
			JOIN 
		'.$dbname.'.pw_terms terms 
		ON 
		terms.term_id = relation.term_taxonomy_id
			WHERE 
			relation.object_id 
			IN 
			('.$result25[$x]['object_id'].') 
				and 
				tax.description <> "" 
				and tax.taxonomy = "product_cat"';
				$result30 = $conn->query($sql30);

				$result25[$x]["category"] = array();
				if($result30->num_rows >0 ){
					while($row30_2 = $result30-> fetch_assoc()){
					$result25[$x]["category"] = array(...$result25[$x]["category"],$row30_2);
				}
				$category = array(...$result25[$x]["category"]);
				for($i = 0; $i < count($result25[$x]["category"]); $i++){
					for($p = 0; $p < count($result25[$x]["category"]); $p++){
						if($category[$p]["parent"] == $category[$i]["term_id"] ){
							$old = $category[$i];
							$category[$i] = $category[$p];
							$category[$p] = $old;
					}
					}
				}

				$result25[$x]["category"] = $category;
				// echo print_r($category);

			}


			$sql26 = ' SELECT * FROM 
			'.$dbname.'.pw_terms terms 
			where 
			terms.term_id = (select term_id from 
			'.$dbname.'.pw_term_relationships relation 
			join 
			'.$dbname.'.pw_term_taxonomy tax 
			on 
			tax.term_id = relation.term_taxonomy_id 
			where 
			object_id ='.$result25[$x]["object_id"].' and tax.taxonomy = "pa_brands") and terms.slug like "%'.$PostData['filterBy'].'%"';
			
			$result26 = $conn->query($sql26);

			$result25[$x]["brand"] = array();
			if($result26->num_rows >0 ){
				while($row26_2 = $result26-> fetch_assoc()){
				$result25[$x]["brand"] = array(...$result25[$x]["brand"],$row26_2);
				$brandFilterProduct2 = array(...$brandFilterProduct2,$result25[$x] );
				}
			}else{
				
				$brandFilterProduct2 = array(...$brandFilterProduct2,$result25[$x] );
			}




		}

		$outputData = $brandFilterProduct2;
			
	

	if(isset($PostData['filterBy'])){
		$result25_2 = $outputData;
		$brandFilterProduct=  array();
		for($x=0; $x < count($result25_2); $x++){
			$sql26_2 = ' SELECT * FROM 
			'.$dbname.'.pw_terms terms 
			where 
			terms.term_id = (select term_id from 
			'.$dbname.'.pw_term_relationships relation 
			join 
			'.$dbname.'.pw_term_taxonomy tax 
			on 
			tax.term_id = relation.term_taxonomy_id 
			where 
			object_id ='.$result25_2[$x]["object_id"].' and tax.taxonomy = "pa_brands") and terms.slug like "%'.$PostData['filterBy'].'%"';
			
			$result26_2 = $conn->query($sql26_2);

			if($result26_2->num_rows >0 ){
				$brandFilterProduct = array(...$brandFilterProduct,$result25_2[$x]);
			}
		}

		if(isset($PostData['productsFilter'])){
			$data2 = $brandFilterProduct;
			$dataCount = count($brandFilterProduct);
			$productFilterData = array();
			for($i = 0; $i < $dataCount; $i++){
				if(strtolower($data2[$i]["slug"]) == strtolower($PostData['productsFilter'])){
					$productFilterData = array(...$productFilterData,$data2[$i]);
				}
			}
		
			echo json_encode($productFilterData);
		}else{
			echo json_encode($brandFilterProduct);
		}
		
	}else if(!isset($PostData['filterBy'])){

		if(isset($PostData['productsFilter'])){
			$data2 = $outputData;
			$dataCount = count($outputData);
			$productFilterData = array();
			for($i = 0; $i < $dataCount; $i++){
				if(strtolower($data2[$i]["slug"]) == strtolower($PostData['productsFilter'])){
					$productFilterData = array(...$productFilterData,$data2[$i]);
				}
			}
			echo json_encode($productFilterData);
		}else{
			
			echo json_encode($outputData);	
		}
	}
	
}else if(isset($PostData['childCategory'])){
	// function getChildCategory($slug){
		// getChildCategory($PostData['childCategory'])
		$outputData = array();
		$outputData = getChildCategory($PostData['childCategory']);
	
		// return $outputData;
	// }
	echo json_encode($outputData);	
}





if(isset($PostData['getCategoryDetail'])){

	$detailview = array();
	function gettheparentData($getCategoryDetail){

		global $conn;
        global 	$detailview;
		global $dbname;
		$sql27 = 'SELECT * from 
		'.$dbname.'.pw_terms terms 
		join 
		'.$dbname.'.pw_term_taxonomy tax 
		on 
		tax.term_id = terms.term_id 
		where 
		terms.term_id 
		in 
		(select tax.parent from 
		'.$dbname.'.pw_terms terms 
		join 
		'.$dbname.'.pw_term_taxonomy tax 
		on 
		tax.term_id = terms.term_id 
		where 
		terms.slug = "'.$getCategoryDetail.'")';
		$result27 = $conn->query($sql27);
		$outputData = array();
		if($result27-> num_rows>0){
			while($row27 = $result27-> fetch_assoc()){
				$outputData= array(...$outputData, $row27);
			}
		}

		if($outputData[0]["parent"] > 0){
			$output = gettheparentData($outputData[0]["slug"]);
			// $output[0]["child"] = getChildCategory($outputData[0]['slug']); 
			// $detailview = array(...$detailview, $output[0]);
			return $output;
		}else if($outputData[0]["parent"] == 0){
			// $outputData[0]["child"] = getChildCategory($outputData[0]['slug']); 
			// $outputData = array(...$detailview, $outputData[0]);
			return $outputData;
		}
	}
	$output = array();
	$getdata = $PostData['getCategoryDetail'];
	// echo json_encode(array($getdata));

		$output =  gettheparentData($getdata);
		if($output[0] !=null){
			$output[0]["child"] = getChildCategory($output[0]['slug']); 
			echo json_encode($output);
		}else{

			$sql28 = 'SELECT * from 
		'.$dbname.'.pw_terms terms 
		join 
		'.$dbname.'.pw_term_taxonomy tax 
		on 
		tax.term_id = terms.term_id 
		where 
		terms.term_id 
		in 
		(select tax.term_id from 
		'.$dbname.'.pw_terms terms 
		join 
		'.$dbname.'.pw_term_taxonomy tax 
		on 
		tax.term_id = terms.term_id 
		where 
		terms.slug = "'.$getdata.'")';
		$result28 = $conn->query($sql28);
		$output2 = array();
		if($result28-> num_rows>0){
			while($row28 = $result28-> fetch_assoc()){
				$output2= array(...$output2, $row28);
			}
		}
		$output2; 
		if($output2[0]["parent"] > 0){
			$output2 = gettheparentData($outputData1[0]["slug"]);
			// $output[0]["child"] = getChildCategory($outputData[0]['slug']); 
			// $detailview = array(...$detailview, $output[0]);
		    
		}else if($output2[0]["parent"] == 0){
			// $outputData[0]["child"] = getChildCategory($outputData[0]['slug']); 
			// $outputData = array(...$detailview, $outputData[0]);
			$output2[0]["child"] = getChildCategory($getdata);
		}
	
		echo json_encode($output2);
			
		}

	
}


if(isset($PostData['brandFilter'])){

	$sql29 = 'SELECT * FROM 
	'.$dbname.'.pw_term_relationships relation 
	JOIN 
	'.$dbname.'.pw_term_taxonomy tax 
	ON 
	tax.term_id = relation.term_taxonomy_id 
	JOIN 
	'.$dbname.'.pw_wc_product_meta_lookup lookup 
	ON 
	relation.object_id = lookup.product_id 
	JOIN 
	'.$dbname.'.pw_posts posts 
	ON 
	posts.ID = lookup.product_id 
	JOIN 
	'.$dbname.'.pw_terms terms 
	ON 
	terms.term_id = relation.term_taxonomy_id
	WHERE 
	relation.object_id 
	IN  (select relation.object_id from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in (select terms.term_id from '.$dbname.'.pw_terms terms where terms.name like "%'.$PostData['brandFilter'].'%")) and tax.description = ""  and tax.taxonomy = "product_cat" and posts.post_status = "publish"';


     $result29 = $conn->query($sql29);
		$output3 = array();
		$outputData = array();
		if($result29-> num_rows>0){
			while($row29 = $result29-> fetch_assoc()){


				$sql29_2 = 'SELECT postmeta.meta_value FROM '.$dbname.'.pw_posts post join '.$dbname.'.pw_postmeta postmeta on postmeta.post_id = post.ID WHERE post.post_title LIKE "%'.$row29['sku'].'%" and postmeta.meta_key = "_wp_attached_file"';
					$result29_2 = $conn->query($sql29_2);
					$row29['imageURL'] = array();
					if($result29_2-> num_rows>0){
						while($row29_2 = $result29_2-> fetch_assoc()){
							$row29['imageURL'] = array(...$row29['imageURL'], $newURL."/wp-content/uploads/".$row29_2['meta_value']);
						}
						$output3= array(...$output3, $row29);
					}else if($result29_2 -> num_rows == 0){
						$row29['imageURL'] = array($newURL."/wp-content/uploads/2022/12/placeholder-150x150-1-150x113.png");
						$output3= array(...$output3, $row29);
					}

			}
		}
		// echo json_encode($output3);

}

// function discount_amount($total, $percentage) {
// 	if($total!=0){
// 		$discount=round($total/$percentage);
// 	}
// 	return ($total-$discount);
// 	}


function findtermID($slug){
		global $conn;
		global $dbname;
       $sqlQuery = 'SELECT * FROM '.$dbname.'.pw_terms where slug ="'.$slug.'"';
       $brandId = array();
	   $result = $conn->query($sqlQuery);
	   if($result->num_rows>0){
		  while($row = $result->fetch_assoc()){
			$brandId[] = $row;
			}
			return $brandId;
	   }

}

function findProductID($category = '', $brand = ''){
	global $conn;
	global $dbname;

	$sqlQuery = 'SELECT 
            relation.object_id
        FROM
		 '.$dbname.'.pw_terms term
                JOIN
				'.$dbname.'.pw_term_relationships relation ON relation.term_taxonomy_id = term.term_id
        WHERE
            term.slug = "'.$category.'"
                OR term.slug LIKE "%'.$brand.'%"
        GROUP BY relation.object_id
        HAVING relation.object_id > 1';

		$productId = array();
		$result = $conn->query($sqlQuery);
		if($result->num_rows>0){
		   while($row = $result->fetch_assoc()){
			 $productId[] = $row["object_id"];
			 }
			 return $productId;
		}
}






if(isset($PostData['getBrandCategory']) && isset($PostData['brandName'])){


// $categoryName = $PostData['getBrandCategory'];
// $brandName = $PostData['brandName'];

// function returnBindQuery($categrory,$brand){


// return 'with custom_table as (
// 	select 
// 	  term.*, 
// 	  relation.*, 
// 	  tax.taxonomy, 
// 	  tax.description 
// 	from 
// 	  '.$dbname.'.pw_terms term 
// 	  inner join '.$dbname.'.pw_term_relationships relation on relation.term_taxonomy_id = term.term_id 
// 	  inner join '.$dbname.'.pw_term_taxonomy tax on tax.term_taxonomy_id = term.term_id 
// 	where 
// 	  relation.object_id in (
// 		select 
// 		  relation.object_id 
// 		from 
// 		  '.$dbname.'.pw_terms term 
// 		  inner join '.$dbname.'.pw_term_relationships relation on relation.term_taxonomy_id = term.term_id 
// 		where 
// 		  (
// 			term.slug = "'.$categrory.'" 
// 			or term.slug like "%'.$brand.'%"
// 		  ) 
// 		group by 
// 		  relation.object_id 
// 		having 
// 		  relation.object_id > 1
// 	  )
//   ), 
//   obj_id as (
//   select 
// 	tables.object_id
//   from 
// 	custom_table tables 
//   where 
// 	(
// 	  (
// 		tables.taxonomy = "product_cat" 
// 		and tables.slug = "'.$categrory.'"
// 	  ) 
// 	  or (
// 		tables.taxonomy = "pa_brands" 
// 		and tables.slug like "%'.$brand.'%"
// 	  )
// 	) 
//   group by 
// 	tables.object_id 
//   having 
// 	count(tables.object_id) > 1
//   )
//   ,p_id as (select meta.post_id, relation.* from obj_id ob 
//   inner join '.$dbname.'.pw_term_relationships relation on relation.object_id = ob.object_id 
//   inner join '.$dbname.'.pw_postmeta meta on meta.meta_value = relation.term_taxonomy_id and meta.meta_key = "_menu_item_object_id"
//   inner join '.$dbname.'.pw_term_taxonomy tax on tax.term_taxonomy_id = relation.term_taxonomy_id and tax.taxonomy = "product_cat" and tax.description <> "" 
//   ), post_data as 
//   (select 
// 	 meta.post_id,
// 	 group_concat(distinct p_id.object_id order by p_id.object_id separator ",") as object_id,
// 	group_concat(distinct meta.meta_value order by meta.meta_value separator ",") as meta_value,
// 	group_concat(distinct term.name order by term.name separator ",") as termName
//   from p_id 
//    inner join '.$dbname.'.pw_postmeta meta on meta.post_id = p_id.post_id 
//    and meta.meta_key = "_menu_item_menu_item_parent" 
//   inner join '.$dbname.'.pw_terms term on term.term_id = p_id.term_taxonomy_id
//   inner join '.$dbname.'.pw_posts post on post.ID = p_id.post_id and post.post_content <> ""
//   group by meta.post_id )
//   select * from '.$dbname.'.pw_postmeta meta inner join '.$dbname.'.pw_terms term on term.term_id = meta.meta_value where meta.post_id in (select post_data.post_id from post_data 
//   where post_data.meta_value in 
//   (select post_data.post_id from post_data where post_data.meta_value = 0))';
// }
// $output;
//  foreach( $categoryName as $key => $value){
// 	if($value["post_parent"] == 0){
		
// 		$categrory = $value["slug"];
// 		$brand = $brandName;
// 		$result = $conn->query(returnBindQuery($categrory,$brand));
// 		$value["childCount"] = $result->num_rows;
// 	    $value["child"] = array();
// 		if($result->num_rows>0){
// 			while($row = $result->fetch_assoc()){
// 			 $value["child"] = array(...$value["child"],$row);
// 			}
// 		}
// 		$output[] =$value ;
// 	 }
	 
//  }

//  echo json_encode($output);
  
	
// 	$categoryName = $PostData['getBrandCategory'];
// $brandName = $PostData['brandName'];

// $brandId = findtermID($brandName);
// $categoryId = findtermID($categoryName);

// $sql = 'SELECT post.*, term.slug as term_slug
// 		FROM '.$dbname.'.pw_posts post
// 		INNER JOIN '.$dbname.'.pw_term_relationships relation ON relation.object_id = post.ID
// 		INNER JOIN '.$dbname.'.pw_term_taxonomy tax ON tax.term_taxonomy_id = relation.term_taxonomy_id
// 		INNER JOIN '.$dbname.'.pw_terms term ON term.term_id = tax.term_id
// 		WHERE (term.slug = "'.$categoryName.'" OR term.slug LIKE "%'.$brandName.'%")
// 			AND post.ID IN (
// 				SELECT meta.post_id
// 				FROM '.$dbname.'.pw_postmeta meta
// 				WHERE meta.meta_value = tax.term_taxonomy_id
// 					AND meta.meta_key = "_menu_item_object_id"
// 				GROUP BY meta.post_id
// 				HAVING meta.post_id > 1
// 			)
// 			AND post.post_content <> ""
// 			AND (tax.term_id = '.$categoryId[0]['term_id'].' OR tax.term_id IN ('.implode(", ", array_column($brandId, "term_id")).'))
// 			AND NOT EXISTS (
// 				SELECT 1
// 				FROM '.$dbname.'.pw_postmeta meta2
// 				WHERE meta2.meta_value = post.ID
// 					AND meta2.meta_key = "_menu_item_menu_item_parent"
// 			)';
// $result = $conn->query($sql);

// $outputArray = array();

// if ($result->num_rows > 0) {
// 	while ($row = $result->fetch_assoc()) {
// 		$row['imageURL'] = getImage($row["term_id"]);
// 		$outputArray[] = $row;
// 	}
// }

// echo json_encode($outputArray);

	
	
	
	// $categoryName = $PostData['getBrandCategory'];
	// $brandName = $PostData['brandName'];

	//     $brandId = findtermID($brandName);
	
	// 	$categoryId = findtermID($categoryName);
	// 	$object2 = array();
	// 	$object = array();
	// 	$sql31 = 'SELECT * from '.$dbname.'.pw_term_relationships relation WHERE
	// 	relation.object_id IN (SELECT 
	// 			relation.object_id
	// 		FROM
	// 			'.$dbname.'.pw_terms term
	// 				JOIN
	// 			'.$dbname.'.pw_term_relationships relation ON relation.term_taxonomy_id = term.term_id
	// 		WHERE
	// 			term.slug = "'.$categoryName.'"
	// 				OR term.slug LIKE "%'.$brandName.'%"
	// 		GROUP BY relation.object_id
	// 		HAVING relation.object_id > 1)';
	// 		$result = $conn->query($sql31);
	// 		$output31 = array();
	// 	if($result-> num_rows>0){
	// 		while($row =  $result-> fetch_assoc()){
	// 			$object[] =  $row;
	// 		}

	// 	}else{
	// 		echo json_encode(array($sql31));
	// 	}
		
	// 	$findone2 = true;
	// 	$object2 = array();
	// 	for($i = 0; $i< count($object); $i++){
	// 		$findone = 0;
	// 		if(count($object2)>0){
	// 		for($j = 0; $j< count($object2); $j++){
	// 				if($object[$i]['object_id'] == $object2[$j][0]['object_id']){
	// 					 $findone = $j;
	// 				}
	// 			}
	// 		}
	// 		if($findone2 && $findone == 0){
	// 			foreach($brandId as $key=> $value){
	// 				   $object2[][] = $object[$i];  
	// 				   $findone2 == false;
	// 			       break;
	// 			}
	// 		}else{
	// 			foreach($brandId as $key=> $value){
	// 					$object2[$findone][] = $object[$i];
	// 					break;
	// 			}
	// 		}
		
	// 	}
	// 	$object4 = array();

		
	// 	foreach($object2 as $key => $value){
	// 		 if(count($value)>1 ){
	// 			$brand = false;
	// 			$category = false;

	// 			foreach($brandId as $key=> $value2){
	// 					foreach($value as $key=> $value3){
	// 					if($value2["term_id"] == $value3["term_taxonomy_id"]){
	// 						$brand = true;
	// 					}
	// 				}

	// 			}
	// 			foreach($value as $key=> $value3){
	// 			if( $categoryId[0]['term_id'] == $value3["term_taxonomy_id"] ){
	// 				$category = true;
	// 			}
	// 		}

	// 			if($brand && $category){
	// 				$object4[] = $value;
	// 			}
	// 		 }
	// 	}
	
	// 		$outputArray = array();
		
	// 	if(count($object4)>0){
	// 		foreach($object4 as $key => $value2){
	// 			foreach($value2 as $key => $value){
	// 			$sql4 = 'SELECT * FROM '.$dbname.'.pw_posts post  join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = "'.$value["term_taxonomy_id"].'" join '.$dbname.'.pw_terms term on term.term_id = tax.term_id where post.ID in (SELECT meta.post_id FROM '.$dbname.'.pw_postmeta meta where meta.meta_value = "'.$value["term_taxonomy_id"].'" and meta.meta_key = "_menu_item_object_id" group by meta.post_id having meta.post_id > 1 ) and post.post_content <> ""';
	// 			$result6 = $conn->query($sql4);
	// 			if($result6->num_rows>0){
	// 				while($row = $result6->fetch_assoc()){

	// 					$sql5 = 'SELECT * FROM '.$dbname.'.pw_postmeta meta where meta.meta_value = "'.$row["ID"].'" and meta.meta_key = "_menu_item_menu_item_parent"';
	// 					$result7 = $conn->query($sql5);
	// 					if($result7->num_rows<=0){
	// 					    $contion = true;
	// 						foreach($outputArray as $key => $value4){
	// 							global $contion;
	// 							if($value4["ID"] == $row['ID']){
	// 								$contion = false;
	// 								break;
	// 							}
	// 						}
	// 						if($contion){
	// 							$row['imageURL'] = getImage($row["term_id"]);
	// 							$outputArray[] = $row;
	// 						}
							
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// }
	// echo json_encode($outputArray);

	
		
}


include './productFrequency.php';

if(isset($_GET['return'])){

	echo json_encode(array('name'=>'ganesh'));
}

$conn->close();

?>

