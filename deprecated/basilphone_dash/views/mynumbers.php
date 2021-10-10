<?php 
$user_id        = get_current_user_id();
$country        = get_user_meta($user_id, 'country',true); 
$page_slug      = getSlug(); 
$office_number                 = get_user_meta($user_id,'office_number',true);
$office_areacode               = get_user_meta($user_id,'office_areacode',true);
$office_friendly               = get_user_meta($user_id,'office_friendly',true);
$conference_number             = get_user_meta($user_id,'conference_number',true);
$conference_areacode           = get_user_meta($user_id,'conference_areacode',true);
$conference_friendly           = get_user_meta($user_id,'conference_friendly',true);

$showoffice_number=$office_areacode." ".$office_friendly;
$showconference_number=$conference_areacode." ".$conference_friendly;
?>
<section class="content-header">
    <div class="app-title"> <h1><?php echo $pagetitle;?></h1> </div>
   
   <?php $get_countrylists ?>
   
   <?php if($page_slug=='home'): ?>
    <h2 class="country-title"><span class="txt"> Country : </span><span class="code_name"><?php echo $get_countrylists[$country];?></span></h2>
   <?php else: ?>
    <ul class="my-numbers">
        <li><strong>Conference Number :</strong>  <?php echo $showoffice_number; ?></li>
        <li><strong>Reception Number:</strong>     <?php echo $showconference_number; ?></li>
    </ul>
   <?php endif;?>
</section>