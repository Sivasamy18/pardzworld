<?php
/**
 Template Name: brand-page
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package electro
 */
// Password to be hashed

$path = constant('ABSPATH');
$dbname = constant("DB_NAME");
require_once $path.'vendor/autoload.php';

include_once __DIR__."/inc/functions/component/brandviseFilter.php";
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use customeClassComponent\customQuery;
$page_meta_values = get_post_meta($post->ID, '_electro_page_metabox', true);

$header_style = '';
if (isset($page_meta_values['site_header_style']) && !empty($page_meta_values['site_header_style'])) {
    $header_style = $page_meta_values['site_header_style'];
}

?>
<link rel="stylesheet" type="text/css" href=<?php echo '"'.site_url().'/wp-content/themes/electro/assets/css/brand-page.css'.'"'?> />
<div class="animation"><span class="animationSapn"><img src="<?php echo site_url();?>/wp-content/plugins/revslider/public/assets/assets/loader.gif"></span></div>
<?php
electro_get_header($header_style); 
$brand = array();
if(isset($_GET['num'])){
    for($i = 1; $i<= $_GET['num']; $i++){
        if(isset($_GET['brand'.$i])){
            $brand[]= $_GET['brand'.$i];
        }
    }
}
$categorySpare2;
$categorySpare;
$categoryQuery = 'select distinct(c.term_id), c.name, c.slug, img.meta_value as image_url from ( select relation.object_id, terms.*, tax.taxonomy, tax.description from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = relation.term_taxonomy_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id where object_id in ( select object_id from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in ( SELECT terms.term_id from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) ) and tax.taxonomy = "pa_brands" and terms.slug like "%?%" ) brand left join '.$dbname.'.pw_term_relationships relation on relation.object_id = brand.object_id inner join ( SELECT tax.*, terms.name, terms.slug, meta.* from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) c on c.term_id = relation.term_taxonomy_id join ( SELECT termmeta.term_id, postmeta.post_id, postmeta.meta_value, postmeta.meta_key FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value ) img on img.term_id = c.term_id and img.meta_key = "_wp_attached_file";';
$filterByQuery = 'select distinct(c.term_id), c.name, c.slug, img.meta_value as image_url from ( select relation.object_id, terms.*, tax.taxonomy, tax.description from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = relation.term_taxonomy_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id where object_id in ( select object_id from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in ( SELECT terms.term_id from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) ) and tax.taxonomy = "product_cat" and tax.description = "" and terms.slug like "%?%" ) brand left join '.$dbname.'.pw_term_relationships relation on relation.object_id = brand.object_id inner join ( SELECT tax.*, terms.name, terms.slug, meta.* from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) c on c.term_id = relation.term_taxonomy_id join ( SELECT termmeta.term_id, postmeta.post_id, postmeta.meta_value, postmeta.meta_key FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value ) img on img.term_id = c.term_id and img.meta_key = "_wp_attached_file";';
$sapreQuery = 'select terms.name, group_concat(distinct terms.slug order by terms.slug SEPARATOR "," ) as slug,group_concat(distinct terms.term_id order by terms.term_id SEPARATOR "," ) as termID, group_concat(distinct relation.object_id order by relation.object_id SEPARATOR "," ) as products , COALESCE(group_concat(distinct postmeta.meta_value order by postmeta.meta_value separator ","),"2022/12/placeholder-150x150-1-150x113.png") as image from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_taxonomy_id = relation.term_taxonomy_id join '.$dbname.'.pw_posts posts on posts.ID = relation.object_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id left join '.$dbname.'.pw_termmeta termmeta on termmeta.term_id = terms.term_id and termmeta.meta_key = "thumbnail_id" left join '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value and postmeta.meta_key = "_wp_attached_file" where relation.object_id in (select relation.object_id from '.$dbname.'.pw_term_relationships relation where relation.object_id in (select relation.object_id from '.$dbname.'.pw_terms terms join '.$dbname.'.pw_term_relationships relation on relation.term_taxonomy_id = terms.term_id where slug = "?" ) and relation.term_taxonomy_id in (select terms.term_id from '.$dbname.'.pw_terms terms where slug = "?" )) and (tax.taxonomy = "?" ) and tax.description = "" and posts.post_status = "publish" group by terms.name';
$menuQuery = 'select * from ( SELECT name, post_parent, meta.post_id, term.term_id, slug, count, postmeta.meta_value as imgurl from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_posts posts on posts.ID = relation.object_id join '.$dbname.'.pw_postmeta meta on meta.post_id = posts.ID join '.$dbname.'.pw_terms term on term.term_id = meta.meta_value join '.$dbname.'.pw_term_taxonomy tax on term.term_id = tax.term_id join '.$dbname.'.pw_termmeta termmeta on termmeta.term_id = tax.term_id join '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value where relation.term_taxonomy_id =( SELECT t.term_id FROM pw_term_taxonomy as tax LEFT JOIN pw_terms as t ON tax.term_id = t.term_id WHERE taxonomy = "nav_menu" and name like "%?%" ) and meta.meta_key = "_menu_item_object_id" and posts.post_status = "publish" and posts.post_parent = 0 and postmeta.meta_key = "_wp_attached_file" ) category;';

if(isset($_GET['category'])){
  

  $queryToExecute  = '';
  $toFilter = '';
  $passingtoURL = "";
  if(isset($_GET['filterBy'])){
   $queryToExecute = $filterByQuery;
   $toFilter = $_GET['filterBy'];
   $passingtoURL = "filterBy";
   }else{
   $queryToExecute =  $categoryQuery;
   $toFilter = $brand[0];
   $passingtoURL = "brand1";
 }

  $categoryoutput2 = new customQuery();
  $categoryoutput2->prepare($queryToExecute);
  $categoryoutput2->bind_Param($_GET['category'],$_GET['category'], $toFilter , $_GET['category'], $_GET['category']);
  $categorySpare2 = $categoryoutput2->execute();

  if(gettype($categorySpare2) == "array"){
    if(count($categorySpare2)==1){
     $findout = '';
      if(isset($_GET['filterBy'])){
        $findout = "pa_brands";
        
      }else{
        $findout = "product_cat";
      }
  $categoryoutput = new customQuery();
  $categoryoutput->prepare($sapreQuery);
  $categoryoutput->bind_Param($_GET['category'], $toFilter, $findout );
  $categorySpare = $categoryoutput->execute();
  if(isset($_GET['filterBy'])){
  if (count($categorySpare) == 0 ){
   ?>
    <script type="text/javascript">
        window.location.href ="<?php echo site_url();?>"+"/product-page/?category="+<?php echo "'".$_GET['category']."'" ?>+"&brand=&products=<?php echo $_GET['filterBy']; ?>";   
  </script>
  <?php
  
  }
};
  }else{
    $categorySpare =  $categorySpare2;
  }
}else if(gettype($categorySpare2)=="string"){
  echo print_r($categorySpare2);
}
}else{


// $sql5 = 'with category as (SELECT name , post_parent , post_id , term.term_id , slug, count from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_posts posts on posts.ID = relation.object_id join '.$dbname.'.pw_postmeta meta on meta.post_id = posts.ID join '.$dbname.'.pw_terms term on term.term_id = meta.meta_value join '.$dbname.'.pw_term_taxonomy tax on term.term_id = tax.term_id where relation.term_taxonomy_id =(SELECT t.term_id FROM pw_term_taxonomy as tax LEFT JOIN pw_terms as t ON tax.term_id = t.term_id WHERE taxonomy = "nav_menu" and name like "%?%") and meta.meta_key = "_menu_item_object_id" and posts.post_status = "publish" and posts.post_parent = 0), image as (SELECT termmeta.term_id, postmeta.post_id, postmeta.meta_value as imgurl FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value WHERE termmeta.term_id IN (select category.term_id from category) and postmeta.meta_key = "_wp_attached_file") select * from category join image on category.term_id = image.term_id';
$menuDataoutput = new customQuery();
$menuDataoutput->prepare($menuQuery);
$menuDataoutput->bind_Param("appliance");
$menuData = $menuDataoutput->execute();
// echo print_r($menuData);


$outputData;
$count = 0;
  // foreach($menuData as $key=> $value){
  //   if($value->post_parent == 0){


      //  $sql2 = 'with custom_table as ( select term.*, relation.*, tax.taxonomy, tax.description from '.$dbname.'.pw_terms term inner join '.$dbname.'.pw_term_relationships relation on relation.term_taxonomy_id = term.term_id inner join '.$dbname.'.pw_term_taxonomy tax on tax.term_taxonomy_id = term.term_id where relation.object_id in ( select relation.object_id from '.$dbname.'.pw_terms term inner join '.$dbname.'.pw_term_relationships relation on relation.term_taxonomy_id = term.term_id where ( term.slug = "?" or term.slug like "%?%" ) group by relation.object_id having relation.object_id > 1 ) ), obj_id as ( select tables.object_id from custom_table tables where ( ( tables.taxonomy = "product_cat" and tables.slug = "?" ) or ( tables.taxonomy = "pa_brands" and tables.slug like "%?%" ) ) group by tables.object_id having count(tables.object_id) > 1 ), p_id as ( select meta.post_id, relation.* from obj_id ob inner join '.$dbname.'.pw_term_relationships relation on relation.object_id = ob.object_id inner join '.$dbname.'.pw_postmeta meta on meta.meta_value = relation.term_taxonomy_id and meta.meta_key = "_menu_item_object_id" inner join '.$dbname.'.pw_term_taxonomy tax on tax.term_taxonomy_id = relation.term_taxonomy_id and tax.taxonomy = "product_cat" and tax.description <> "" ) , post_data as ( select meta.post_id as parent_id, group_concat( distinct p_id.object_id order by p_id.object_id separator "," ) as object_id, group_concat( distinct meta.meta_value order by meta.meta_key separator "," ) as meta_value, group_concat( distinct term.name order by term.name separator "," ) as parent_Name, group_concat( distinct term.term_id order by term.term_id separator "," ) as parent_term_ID , group_concat( distinct post.post_parent order by post.post_parent separator "," ) as post_parent_id from p_id inner join '.$dbname.'.pw_postmeta meta on meta.post_id = p_id.post_id and meta.meta_key = "_menu_item_menu_item_parent" inner join '.$dbname.'.pw_terms term on term.term_id = p_id.term_taxonomy_id inner join '.$dbname.'.pw_posts post on post.ID = p_id.post_id and post.post_content <> "" group by meta.post_id ) , outputs as ( select * from '.$dbname.'.pw_postmeta meta inner join '.$dbname.'.pw_terms term on term.term_id = meta.meta_value where meta.post_id in ( select post_data.parent_id from post_data where post_data.post_parent_id in ( select post_data.parent_term_ID from post_data where post_data.post_parent_id = 0 ) or post_data.post_parent_id = 0 ) ) , sample as( SELECT postmeta.*,termmeta.term_id as termmeta_id,termmeta.meta_value as termvalue FROM '.$dbname.'.pw_postmeta postmeta join '.$dbname.'.pw_termmeta termmeta on termmeta.meta_value = postmeta.post_id WHERE post_id IN ( SELECT ID FROM '.$dbname.'.pw_termmeta termMeta JOIN '.$dbname.'.pw_posts post ON termMeta.meta_value = post.ID where termMeta.term_id in (select outputs.term_id from outputs ) ) ) select outputs.term_id, group_concat(distinct(outputs.post_id ) order by outputs.post_id separator ",") as post_id, group_concat(distinct(outputs.name) order by outputs.name separator ",") as name, group_concat(distinct(outputs.slug) order by outputs.slug separator ",") as slug, group_concat(distinct(sample.meta_value) order by sample.meta_value separator ",") as image, group_concat(distinct(outputs.meta_value) order by outputs.meta_value separator ",") as meta_value from outputs left join sample on sample.termmeta_id = outputs.term_id and sample.meta_key = "_wp_attached_file" group by outputs.term_id';
      //  $sql2 = 'with categorys as (SELECT tax.* , terms.name, terms.slug, meta.* from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in (select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in (select meta.post_id from '.$dbname.'.pw_postmeta meta left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where meta_value in ( SELECT term_id FROM '.$dbname.'.pw_terms WHERE slug = "?") and posts.post_status = "publish" ) and meta.meta_key = "_menu_item_menu_item_parent") and meta.meta_key = "_menu_item_object_id") , object as (select * from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in (select categorys.term_id from categorys))  , brand as (select relation.object_id, terms.*, tax.taxonomy, tax.description from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = relation.term_taxonomy_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id where object_id in (select object.object_id from object) and tax.taxonomy = "pa_brands" and terms.slug like "%?%" ) select distinct(c.term_id),c.* from brand left join '.$dbname.'.pw_term_relationships relation on relation.object_id = brand.object_id inner join categorys c on c.term_id = relation.term_taxonomy_id;' ;
      //  $sql2 = 'with post_id as ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug ="?" and posts.post_status = "publish" and posts.post_content <> "" ), categorys as ( SELECT tax.*, terms.name, terms.slug, meta.* from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in (select post_id from post_id) or meta.post_id in (select post_id from post_id) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) , object as ( select * from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in ( select categorys.term_id from categorys ) ) , brand as ( select relation.object_id, terms.*, tax.taxonomy, tax.description from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = relation.term_taxonomy_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id where object_id in ( select object.object_id from object ) and tax.taxonomy = "pa_brands" and terms.slug like "%?%" ) ,allcategorys as (select distinct(c.term_id), c.name , c.slug from brand left join '.$dbname.'.pw_term_relationships relation on relation.object_id = brand.object_id inner join categorys c on c.term_id = relation.term_taxonomy_id) , image as ( SELECT termmeta.term_id, postmeta.post_id, postmeta.meta_value FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value WHERE termmeta.term_id IN (select allcategorys.term_id from allcategorys) and postmeta.meta_key = "_wp_attached_file" ) select allcategorys.*, image.meta_value as image from allcategorys join image on allcategorys.term_id = image.term_id;';
      // $sql2 = 'select distinct(c.term_id), c.name, c.slug, img.meta_value as image_url from ( select relation.object_id, terms.*, tax.taxonomy, tax.description from '.$dbname.'.pw_term_relationships relation join '.$dbname.'.pw_term_taxonomy tax on tax.term_id = relation.term_taxonomy_id join '.$dbname.'.pw_terms terms on terms.term_id = tax.term_id where object_id in ( select object_id from '.$dbname.'.pw_term_relationships relation where relation.term_taxonomy_id in ( SELECT terms.term_id from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) ) and tax.taxonomy = "pa_brands" and terms.slug like "%?%" ) brand left join '.$dbname.'.pw_term_relationships relation on relation.object_id = brand.object_id inner join ( SELECT tax.*, terms.name, terms.slug, meta.* from '.$dbname.'.pw_postmeta meta JOIN '.$dbname.'.pw_term_taxonomy tax on tax.term_id = meta.meta_value JOIN '.$dbname.'.pw_terms terms ON terms.term_id = meta.meta_value where meta.post_id in ( select meta.post_id from '.$dbname.'.pw_postmeta meta where meta.meta_value in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) or meta.post_id in ( select meta.post_id from '.$dbname.'.pw_terms term left join '.$dbname.'.pw_postmeta meta on meta.meta_value = term.term_id left join '.$dbname.'.pw_posts posts on posts.ID = meta.post_id where term.slug = "?" and posts.post_status = "publish" and posts.post_content <> "" ) and meta.meta_key = "_menu_item_menu_item_parent" ) and meta.meta_key = "_menu_item_object_id" ) c on c.term_id = relation.term_taxonomy_id join ( SELECT termmeta.term_id, postmeta.post_id, postmeta.meta_value, postmeta.meta_key FROM '.$dbname.'.pw_termmeta termmeta JOIN '.$dbname.'.pw_postmeta postmeta ON postmeta.post_id = termmeta.meta_value ) img on img.term_id = c.term_id and img.meta_key = "_wp_attached_file";';
      $queryToExecute  = '';
      $toFilter = '';
      if(isset($_GET['filterBy'])){
       $queryToExecute = $filterByQuery;
       $toFilter = $_GET['filterBy'];
       $passingtoURL = "filterBy";
       }else{
       $queryToExecute =  $categoryQuery;
       $toFilter = $brand[0];
       $passingtoURL = "brand1";
     }
      $categoryValue = "";
$outputValue = new customQuery();
foreach($menuData as $key=> $value){
  if($value["post_parent"] == 0){
    $class = $outputValue->prepare( $queryToExecute);
    $class->bind_Param($value["slug"],$value["slug"], $toFilter,$value["slug"],$value["slug"] );
    $value["menuItem"] = $class->execute();
    $outputData[] = $value; 
    }
  }

}
?>

<div id="primary" class="content-area">
    <main id="main" >


    
    <?php

while (have_posts()):
    the_post();


    do_action('electro_page_before');
    get_template_part('templates/contents/content', 'page');

    /**
     * @hooked electro_display_comments - 10
     */
    do_action('electro_page_after');

endwhile; // end of the loop.

?>


</main><!-- #main -->
</div><!-- #primary -->
<script>
       <?php   

if(!isset($_GET['category'])){

  ?>
    let array = <?php   echo json_encode( $menuData );?>;
    let output = <?php   echo json_encode( $outputData);?>;
    console.log(array, output);
    <?php
}else {
  ?>
      let output = <?php   echo json_encode( $categorySpare);?>;
    console.log(output);
  <?php
}
    ?>

   let value = true;
   let sideBar = document.querySelector('ul.product-categories');
    sideBar.children[0].children[0].innerHTML = '<span>show all categories <i class="fa fa-angle-right"></i></span>';
   sideBar.children[0].children[1].classList.add('hide');
   let sideBarContent = document.querySelector('ul.product-categories > .product_cat > ul');
   sideBar.children[0].children[0].addEventListener("click",()=>{
    if(value){
      value = !value;
      sideBar.children[0].children[0].innerHTML = '<span>Browse Categories <i class="fa fa-angle-down"></i></span>'
      sideBarContent.classList.remove('hide');
    }else{
      value = !value;
      sideBar.children[0].children[0].innerHTML = '<span>show all categories <i class="fa fa-angle-right"></i></span>';
      sideBarContent.classList.add('hide')
    }
   })
    document.querySelector('.animation').remove();
    // document.body.innerHTML = output[0];
    function routing(slug){

      <?php  if(isset($_GET['category'])){
          

                
          if(count($categorySpare2)==1){

            
      


            if(isset($_GET['filterBy'])){
          ?>
          window.open("<?php echo site_url();?>"+"/product-page/?category="+<?php echo "'".$_GET['category']."'" ?>+"&brand="+slug+"&products=<?php echo $_GET['filterBy']; ?>", "_blank");
          <?php
          }else {
            ?>
            window.open("<?php echo site_url();?>"+"/product-page/?category="+<?php echo "'".$_GET['category']."'" ?>+"&brand="+"<?php echo $brand[0];?>&products="+slug, "_blank");
            <?php

          } 
          
        }else {
            ?>
               window.open("<?php echo site_url();?>"+"/filters/?"+<?php echo "'".$passingtoURL."=".$toFilter."'";?>+"&category="+slug+"&num=1", "_blank");
            <?php
          }
        } else {
            ?>
          window.open("<?php echo site_url();?>"+"/filters/?"+<?php echo "'".$passingtoURL."=".$toFilter."'";?>+"&category="+slug+"&num=1", "_blank");
          <?php } 
            ?>

        }
     
    document.getElementById('Brand_Area').innerHTML = <?php  if(!isset($_GET['category'])){ ?> output.map((e)=>{
        if(e.menuItem.length > 0){
      

          return ` <div class="headingLine"><span>${e.name}</span></div> <div class="menuSection">${e.menuItem.length > 1 ? e.menuItem.map(s=>{
              if(s.term_id != e.term_id){

                  for(let o = 0; o<output.length; o++){
                    if(output[o].term_id == s.term_id && output[o].post_parent == 0 ){
                      return "";
                    }
                  }  
                  
                
                return `<div class="menuDiv" onclick="routing('${s.slug}')">
				<img src="<?php echo site_url()."/wp-content/uploads/";?>${s.image_url}" width="150px" height="150px">
				<a>${s.name}</a>
				</div> `;
                }
            }).join(' '): `<div class="menuDiv" onclick="routing('${e.slug}')">
				<img src="<?php echo site_url()."/wp-content/uploads/";?>${e.imgurl}" width="150px" height="150px">
				<a>${e.name}</a>
				</div> `
                }
          </div>`;
        }



    }).join(' ');

    <?php } else { 
        if(count($categorySpare2)==1){

        
      ?> `<div class="headingLine"><span>${"<?php echo $categorySpare2[0]["name"];?>"}</span></div><div class="menuSection"> ${output.map((e)=>{
        return `<div class="menuDiv" onclick="routing('${e.slug}')">
                  <img src="<?php echo site_url()."/wp-content/uploads/";?>${e.image}" width="150px" height="150px">
                     <a>${e.name}</a>
                </div> `
              }).join(' ')}</div>`
  <?php 
  
    }else{ 
      
   ?>`<div class="headingLine"><span>${"<?php echo $categorySpare2[0]["name"];?>"}</span></div><div class="menuSection"> ${output.map((e)=>{
    if(e.slug != "<?php  echo $_GET['category'];?>"){
    return `<div class="menuDiv" onclick="routing('${e.slug}')">
              <img src="<?php echo site_url()."/wp-content/uploads/";?>${e.image_url}" width="150px" height="150px">
                 <a>${e.name}</a>
            </div> `
    }
          }).join(' ')}</div>`      
   <?php 
    }
  } 
  ?>

    
</script>

<?php get_footer();?>