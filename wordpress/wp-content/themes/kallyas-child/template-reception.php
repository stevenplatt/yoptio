<?php if(! defined('ABSPATH')){ return; }
/**
* Template Name: Messages
*
*/
get_header('custom');
WpkPageHelper::zn_get_subheader();

global $yoptioprofile;

$profile	= $yoptioprofile->profile();
$readMsg    = $profile->usage['read_message'];
$unreadMsg  = $profile->usage['unread_message'];
$call_logs  = $profile->usage['call_logs'];

$per_page = 10;
if(!empty($profile->usage['readmsg_count'])){
$read_pagelimit = ceil($profile->usage['readmsg_count']/$per_page);
}
if(!empty($profile->usage['unreadmsg_count'])){
 $unread_pagelimit = ceil($profile->usage['unreadmsg_count']/$per_page);
}
?>
<section id="content" class="site-content yo-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h1 class="page-title"><?php echo  get_the_title();?></h1></div>
            <div class="col-md-4 yo-section section-left">
                <div class="profile-wrapper">
                    <div class="profile-cover">
                        <div class="profile-img">
                            <img src="<?php echo $profile->profilepic; ?>" align="left" class="authorimage" />
                            <a href="javascript:void(0);" class="edit-profile-img"><i class="fa fa-edit"></i></a>
                            <span id="status"><i class="fa fa-spinner"></i></span>
                        </div>
                        <form name="upload_form" id="upload_form" method="POST" enctype="multipart/form-data">
                            <input type="file" name="images[]" id="images" accept="image/*" multiple>
                            <?php wp_nonce_field('image_upload', 'image_upload_nonce');?>
                            <input type="submit" value="upload" class="submit-btn">
                        </form>
                        <ul id="images_wrap">
                        <!-- Images will be added here -->
                        </ul>
                        <div class="profile-detail">
                            <h4 class="username"><?php echo  $profile->name ;?></h4>
                            <a class="emailid" href="mailto:<?php echo  $profile->email;?>"><?php echo $profile->email;?></a>
                        </div>
                    </div>
                    <div class="user-activitiy">
                        <div class="dt_t">
                            <div class="dt_tc">
                                <i class="fa fa-clock-o color-blue"></i>
                                <h4 class="color-blue">Minutes Used</h4><span class="rate tweetrate"><?php echo $profile->usage['minutes_used'];?></span>
                            </div>
                            <div class="dt_tc">
                                <i class="fa fa-comment-o color-pink"></i>
                                <h4 class="color-pink">Unread Message</h4>
                                <span class="rate usagerate unreadcount"><?php echo count($profile->usage['unread_message']);?></span>
                            </div>
                        </div>
                        <div class="contact-info">
                            <h5>Reception Number</h5>
                            <span class="contacts"><i class="fa fa-mobile"></i> <span><?php echo $profile->reception_number;?></span></span>
                            <div class="line-hz"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/line.png"></div>
                        </div>
                    </div>
                </div>
                <div class="white-box followup-wrapper">
                    <h4 class="box-title">Follow Up</h4>
                    <form class="form-followup">
                        <ul class="followup-checklist list-group">
                            <?php
                                $followups = $profile->usage['follow_up'];
                                if(!empty($followups)):
                                    foreach ($followups as $followup):
                            ?> 
                            <li class="list-group-item list-group-item-<?php echo $followup->id;?>">
                                <div class="checkbox checkbox-success followup-<?php echo $followup->id;?>">
                                    <input id="checkbox<?php echo $followup->id;?>" type="checkbox" class="follow-up-checks" data-id="<?php echo $followup->id;?>">
                                    <label class="clicktostrick"><span><?php echo $followup->followup_text;?></span></label>
                                    <a href="javascript:void(0);" class="followup-close" data-id="<?php echo $followup->id;?>"><i class="fa fa-close"></i></a>
                                </div>
                            </li>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </ul>
                        <div class="input-group add-new-followup">
                            <input type="text" class="form-control followup-input" placeholder="Add new Follow Up">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info add-followup">Add</button>
                                <!--  onclick="addFollowUp(); return false;" -->
                            </span>
                        </div>
                    </form>
                </div><!-- .white-box -->
            </div>
            <div class="col-md-8 yo-section section-right">
                <ul class="nav customtab nav-tabs" role="tablist">
                    <li role="presentation" class=""><a href="#calllogs" aria-controls="calllogs" role="tab" data-toggle="tab" aria-expanded="true"><span>Call Log</span></a></li>
                    <li role="presentation" class=""><a href="#read" aria-controls="read" role="tab" data-toggle="tab" aria-expanded="false"><span>Read</span></a></li>
                    <li role="presentation" class="active"><a href="#unread" aria-controls="unread" role="tab" data-toggle="tab" aria-expanded="true"><span>Unread</span></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade unread-pane active in" id="unread">
                        <div class="row">
                            <?php
                                if(!empty($unreadMsg)):
                                    foreach ($unreadMsg as $message):
									    $getstartdate=$message->received_date;
                                        $unreaddate  = date('l M d - h:ia',strtotime($getstartdate)); ?>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel message-panel<?php echo $message->message_id;?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue"><?php echo $message->from_number;?></span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" data-id="<?php echo $message->message_id;?>" class="close-panel remove-message" data-perform="panel-dismiss" ><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <?php echo str_replace("Sent from your Twilio trial account - ", "",stripcslashes($message->message_text));?>
                                        </div>
                                        <div class="text-right"><a class="btn btn-info m-t-10  m-t-<?php echo $message->message_id;?> msg-marker read-btn" data-id="<?php echo $message->message_id;?>">Mark Read</a></div>
                                        <div class="panel-footer"><span class="dayofcall color-pink"><?php echo $unreaddate;?></span></div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                    endforeach;
                                else:
                            ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message-panel">									<p class="text-center">No Message(s) found.</p>                                </div>
                            <?php
                                endif;
                            ?>
                        </div><!-- .row -->
                        <?php if(isset($unread_pagelimit) && $unread_pagelimit > 0 ) : ?> 						<div class="ajax-loadmore">
                             <a class="ajax-loadmorebtn" data-status="unread" data-limit="<?php echo $unread_pagelimit;?>" data-page="1" class="btn btn-info">Load More</a>
                        </div>						<?php endif; ?>
                        <div class="clearfix"></div>
                    </div><!-- .tab-panel -->
                    <div role="tabpanel" class="tab-pane fade read-pane" id="read">
                        <div class="row">
                            <?php 
                                $readmessages = $yoptio->message(array('action' => 'get','status'=>1));
								if(!empty($readMsg)):
                                    foreach ($readMsg as $readmessage):
                                        $getstartdate=$message->received_date;
                                        $readdate  = date('l M d - h:ia',strtotime($getstartdate)); ?>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel message-panel<?php echo $readmessage->message_id?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue"><?php echo $readmessage->from_number;?></span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" data-id="<?php echo $readmessage->message_id;?>" class="close-panel remove-message" data-perform="panel-dismiss" ><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <?php echo str_replace("Sent from your Twilio trial account - ", "",stripcslashes($readmessage->message_text));?>
								        <div class="text-right"><a class="btn btn-info m-t-10  m-t-<?php echo $readmessage->message_id;?> msg-marker unread-btn" data-id="<?php echo $readmessage->message_id;?>">Mark Unread</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink"><?php echo $readdate;?></span></div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                    endforeach;
                                else:
                            ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message-panel">									<p class="text-center">No Message(s) found.</p>
                                </div>
                            <?php
                                endif;
                            ?>
                        </div><!-- .row -->
                        <?php if(isset($read_pagelimit) && $read_pagelimit > 0 ) : ?> 						 <div class="ajax-loadmore">
                             <a class="ajax-loadmorebtn" data-status="read" data-limit="<?php echo $read_pagelimit;?>" data-page="1" class="btn btn-info">Load More</a>
                        </div>                        <?php endif;?> 
                        <div class="clearfix"></div>
                    </div><!-- .tab-panel -->
                    <div role="tabpanel" class="tab-pane fade collog-pane" id="calllogs">
                        <div class="row">
                            <div class="col-md-12 calllogs-panel">
                                <div class="white-box">
                                    <h4 class="box-title m-b-0">Call Log</h4>
                                    <p class="text-muted m-b-20">
                                        <?php 
                                            global $post;
                                            echo  $post->post_content;
                                        ?>
                                    </p>
                                    <div class="table-responsive">
                                        <table id="call_logs" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Number</th>
													<th>Duration</th>
                                                    <th>Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                                if(!empty($call_logs)) {
													$minutes=0;
                                                foreach ($call_logs as $calllog){
													$callsid=$calllog->sid;
													$getstartdate=(array)$calllog->startTime;
										            $call_startdate = date('m/d/Y',strtotime($getstartdate['date']));
                                                    $call_received_time  = date('h:ia',strtotime($getstartdate['date']));
													$getenddate=(array)$calllog->endTime;
													
                                                    $time_diff =$yoptioapi->datetimeDiff($getstartdate['date'],$getenddate['date']);
												    $minutes+=$calllog->duration;
													
													// To Check if the message is sent or not.
													$getyoptiomessage=$yoptioapi->yoptiomessage(array('action'=>'get','callsid'=>$callsid));
												    $is_sent=(empty($getyoptiomessage)) ? 0 : 1 ;
												  ?>
                                                <tr data-callid='<?php echo $callsid;?>'>
                                                    <td><span class="text-muted"><?php echo $call_startdate;?></td>
                                                    <td><span class="text-muted"><?php echo $call_received_time;?></td>
                                                    <td><?php echo $calllog->from;?></td>
                                                	<td><span class="text-muted"><?php echo $time_diff; ?></td>
													<td>    
                                                    <?php if($is_sent) : ?>
                                                      <div class="label label-table label-success"><?php echo $calllog->status;?></div></td>
                                                    <?php endif;?>
                                                </tr>
                                            <?php } } ?>
                                         </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- .tab-content -->
            </div><!-- .section-right -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!--// #content -->
<?php 
function remainder($dividend, $divisor) {
    if ($dividend == 0 || $divisor == 0) return 0;

    $dividend .= '';
    $remainder = 0;
    $division = '';
    
    // negative case
    while ($dividend < 0) {
        $dividend += $divisor;
        if ($dividend >= 0) return $dividend;
    }
    
    // positive case
    while (($remainder.$dividend)*1 > $divisor) {
        // get remainder big enough to divide
        while ($remainder*1 < $divisor) {
            $remainder .= $dividend[0];
            $remainder *= 1;
            $dividend = substr($dividend, 1);
        }
        
        // get highest multiplicator for remainder
        $mult = floor($remainder / $divisor);

        // add multiplicator to division
        $division .= $mult.'';

        // subtract from remainder
        $remainder -= $mult*$divisor;
    }
    
    // add remaining zeros if any, to division
    if (strlen($dividend) > 0 && $dividend*1 == 0) {
        $division .= $dividend;
    }
    
    return $remainder;
}


get_footer('custom'); ?>
