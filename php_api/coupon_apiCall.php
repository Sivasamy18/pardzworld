<?php
require '../vendor/autoload.php';
include "apiConnection.php";
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

// $PostData = json_decode(file_get_contents('php://input'), true);
$postName = json_decode(file_get_contents('php://input'),true);
if(isset($postName['couponCode'])){
  $couponCode = $postName['couponCode']['couponCode'];
  $X_Correlation_Id = $postName['couponCode']['X_Correlation_Id'];
  $X_Client_Id = $postName['couponCode']['X_Client_Id'];
  $X_Client_Secret = $postName['couponCode']['X_Client_Secret'];

$client = new Client([
  'verify' => false
]);



$headers = [
  'X-Correlation-Id' => $X_Correlation_Id,
  'X-Client-Id' => $X_Client_Id,
  'X-Client-Secret' => $X_Client_Secret,
  'Content-Type' => 'application/json'
];
$request = new Request('GET', 'https://xp-voucher-stg-sg-v1.sg-s1.cloudhub.io/api/vouchers/ETX_001-'.$couponCode , $headers);
try {
  $res = $client->send($request);
  echo json_encode($res->getBody()->getContents());
} catch (RequestException $e) {
  if ($e->hasResponse()) {
      $response = $e->getResponse();
      $statusCode = $response->getStatusCode();
      $errorBody = json_decode($response->getBody(), true);

      // Handle the error response
      // You can access the error details from $statusCode and $errorBody

      echo json_encode([
          'error' => [
              'statusCode' => $statusCode,
              'message' => $errorBody['meta']['messages'][0]['text'],
          ],
          'meta' => [
            'status'=> 'error'
            ]
      ]);
  } else {
      echo json_encode(['error' => 'An error occurred']);
  }
}





}else if(isset($postName['addCart'])){
$servername = constant("DB_HOST");
$username   = constant("DB_USER");
$password   = constant("DB_PASSWORD");
$dbname     = constant("DB_NAME");


// Create connection
$conn2 = new mysqli($servername, $username, $password, $dbname);

  $titleParam2 = $postName['addCart'];





 $value2 = $conn2->query("SELECT * FROM ".$dbname.".pw_posts where post_title ='".$titleParam2."'"); 
//  $value2->bind_param('s',$titleParam2);
//  $value2->execute();

 
  // $result = $value2->get_result();
//  echo json_encode(array($dbname, "SELECT * FROM ".$dbname.".pw_posts where post_title ='".$titleParam2."'",$value2));
 if($value2-> num_rows>0){
while ($row = $value2->fetch_assoc()) {
  // Process each row of the result
  // Access column values using $row['column_name']
  echo json_encode($row); // Output the row data
  // WC()->cart->apply_coupon($titleParam2);
}
 }else {
  $value = $conn->query("SELECT MAX(ID)+1 FROM ".$dbname.".pw_posts");
  // echo print_r($value);
  
    if($value-> num_rows>0){
      while($value2 = $value-> fetch_assoc()){
              $maxValue =  $value2['MAX(ID)+1'];
  
              
  $query = $conn->prepare("INSERT INTO ".$dbname.".pw_posts (ID,post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count) 
  VALUES (?,?,current_timestamp(),current_timestamp(),?,?,?,?,?,?,?,?,?,?,current_timestamp(),current_timestamp(),?,?,?,?,?,?,?)
  ");
  
  $query->bind_param('iisssssssssssisissi', $idParam, $authorParam,$nullString, $titleParam,$nullString, $statusParam, $commentStatusParam, $pingStatusParam,$nullString,$postNameParam,$nullString,$nullString,$nullString,$zeroValue, $guidParam,$zeroValue, $postTypeParam,$nullString, $zeroValue);
  
     
               $idParam = $maxValue;
               $authorParam = 1;
               $titleParam =  $titleParam2;
               $statusParam = "publish";
               $commentStatusParam = "closed";
               $pingStatusParam = "closed";
               $postNameParam = $maxValue;
               $guidParam = "http://pardzworld2.com/?post_type=shop_coupon&#038;p=" . $maxValue;
               $postTypeParam = "shop_coupon";
               $zeroValue = 0;
               $nullString = '';


               $result2=  $query->execute();

           
               if($postName['postMetaKey']){
             $postMetaKeyOnPost = $postName['postMetaKey'];
                 $postMetaKey = array(
                   '_edit_last',
                   '_wp_old_date',
                   'discount_type',
                   'coupon_amount',
                   'individual_use',
                   'usage_limit',
                   'usage_limit_per_user',
                   'limit_usage_to_x_items',
                   'usage_count',
                   'date_expires',
                   'free_shipping',
                   'exclude_sale_items',
                   '_edit_lock',
                   '_used_by'
                 );    

                 $ExcludeProductId = '';

                 $resutl4 = $conn->query("select object_id from ".$dbname.".pw_term_relationships relations where relations.term_taxonomy_id in 
                 (select term.term_id from ".$dbname.".pw_term_taxonomy tax join ".$dbname.".pw_terms term on tax.term_id = term.term_id where tax.taxonomy = 'pa_brands' and term.slug <> 'calidad')");
                 
                 if( $resutl4-> num_rows>0){
                  while( $resutl4_2 =  $resutl4-> fetch_assoc()){
                    $ExcludeProductId =  $ExcludeProductId.$resutl4_2['object_id'].','; 
                  }
                }


                   $postMeta2 = $conn->prepare("INSERT INTO ".$dbname.".pw_postmeta (post_id, meta_key, meta_value) VALUES (?,?,?)");
                   $postMeta2->bind_param('iss',$maxValue,$metaKey,$metaValue);
                  $executeCount = 0;
                   for($i = 0; $i < count($postMetaKey); $i++){
                    $metaKey = $postMetaKey[$i];
                    $metaValue = $postMetaKeyOnPost[$postMetaKey[$i]];
                    $result3 = $postMeta2->execute();
                    if($result3){
                      $executeCount += 1; 
                    }
                   }
                   $metaKey = 'exclude_product_ids';
                   $metaValue = $ExcludeProductId;
                   $result3 = $postMeta2->execute();

                   if($result2 && $executeCount == count($postMetaKey) ){
              
                    echo json_encode(array('data'=>$titleParam2.' and '.$maxValue. ' successfully added to db','status'=>'success')); // Output the row data
                      //  WC()->cart->apply_coupon($titleParam2);
                   }
                  }

                  $postMeta2->close();
            
      }
  }

  

 }

 
//  
}else if(isset($postName['removeCart'])){
  $coupon = $postName['removeCart'];

  $result = $conn->query('SELECT EXISTS(SELECT * FROM '.$dbname.'.pw_posts where post_title ="'.$coupon.'")');
  // $output = array();
  // if($result->num_rows > 0){
  //     while($row = $result-> fetch_assoc()){
  //         $output = array(...$output, $row);
  //     }

  // }

  if($result){
    $conn->query('SET SQL_SAFE_UPDATES=0');
    $conn->query('UPDATE '.$dbname.'.pw_posts SET post_status = "draft" where post_title ="'.$coupon.'"');
    $conn->query('SET SQL_SAFE_UPDATES=1');
    echo json_encode(array('coupon exist in db'));
  }else{
    echo json_encode(array('coupon not exist in db'));
  }





}