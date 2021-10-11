<?php if(! defined('ABSPATH')){ return; }
/**
* Template Name: Messages
*
* @package  Kallyas
* @author   Team Hogash
*/
get_header('custom');
WpkPageHelper::zn_get_subheader();
// Check to see if the page has a sidebar or not
/**$main_class = zn_get_sidebar_class('page_sidebar');
if( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false ) { $zn_config['sidebar'] = true; } else { $zn_config['sidebar'] = false; }
$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-9' : 'col-sm-12';
**/
?>
<!--// Main Content: page content from WP_EDITOR along with the appropriate sidebar if one specified. -->
<section id="content" class="site-content yo-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h1 class="page-title">My Reception</h1></div>
            <div class="col-md-4 yo-section section-left">
                <div class="profile-wrapper">
                    <div class="profile-cover">
                        <div class="profile-img">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/user.png">
                            <a href="javascript:void(0);" class="edit-profile-img"><i class="fa fa-edit"></i></a>
                        </div>
                        <div class="profile-detail">
                            <h4 class="username">User Name</h4>
                            <p class="emailid">user@yoptio.com</p>
                        </div>
                    </div>
                    <div class="user-activitiy">
                        <div class="dt_t">
                            <div class="dt_tc">
                                <i class="fa fa-clock-o color-blue"></i>
                                <h4 class="color-blue">Minutes Used</h4>
                                <span class="rate tweetrate">125</span>
                            </div>
                            <div class="dt_tc">
                                <i class="fa fa-comment-o color-pink"></i>
                                <h4 class="color-pink">Unread Message</h4>
                                <span class="rate usagerate">556</span>
                            </div>
                        </div>
                        <div class="contact-info">
                            <h5>Reception Number</h5>
                            <span class="contacts"><i class="fa fa-mobile"></i> <span>1888-123-1234</span></span>
                        </div>
                    </div>
                    <div class="line-hz"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/line.png"></div>
                </div>
                <div class="white-box followup-wrapper">
                    <h4 class="box-title">Follow-Up</h4>
                    <form class="form-horizontal">
                        <div class="followup-checklist">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox1" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox1">Schedule meeting</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox2" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox2">Call clients for follow-up</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox3" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox3">Book flight for holiday</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox4" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox4">Forward important tasks</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox5" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox5">Recive shipment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-danger">
                                        <input id="checkbox6" type="checkbox" class="follow-up-checks">
                                        <label for="checkbox6">Important tasks</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group m-t-10 add-new-followup">
                                    <input type="text" class="form-control followup-input" placeholder="Add New">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info add-followup" onclick="addFollowUp(this); return false;">Add</button>
                                    </span>
                                </div>
                            </div>
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">+1 415 456 1234</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" class="close-panel" data-perform="panel-dismiss" onclick="removeMessage(this); return false;"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                                            <div class="text-right"><a class="btn btn-info m-t-10 msg-marker" onclick="markReadUnread(this); return false;" onclick="markReadUnread(this); return false;">Mark Read</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">Monday, May 3 - 8:35pm</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">+1 856 652 1234</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" class="close-panel" data-perform="panel-dismiss" onclick="removeMessage(this); return false;"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                                            <div class="text-right"><a class="btn btn-info m-t-10 msg-marker" onclick="markReadUnread(this); return false;">Mark Read</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">Friday, May 6 - 11:20am</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">+1 586 445 1254</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" class="close-panel" data-perform="panel-dismiss" onclick="removeMessage(this); return false;"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                                            <div class="text-right"><a class="btn btn-info m-t-10 msg-marker" onclick="markReadUnread(this); return false;">Mark Read</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">Friday, June 26 - 10:15pm</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">+1 222 455 8985</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" class="close-panel" data-perform="panel-dismiss" onclick="removeMessage(this); return false;"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                                            <div class="text-right"><a class="btn btn-info m-t-10 msg-marker" onclick="markReadUnread(this); return false;">Mark Read</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">Saturday, April 18 - 1:50pm</span></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .row -->
                        <div class="clearfix"></div>
                    </div><!-- .tab-panel -->
                    <div role="tabpanel" class="tab-pane fade read-pane" id="read">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">+1 222 455 8985</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" class="close-panel" data-perform="panel-dismiss" onclick="removeMessage(this); return false;"><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                                            <div class="text-right"><a class="btn btn-info m-t-10 msg-marker" onclick="markReadUnread(this); return false;">Mark Unread</a></div>
                                        </div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">Saturday, April 18 - 1:50pm</span></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .row -->
                        <div class="clearfix"></div>
                    </div><!-- .tab-panel -->
                    <div role="tabpanel" class="tab-pane fade collog-pane" id="calllogs">
                        <div class="row">
                            <div class="col-md-12 calllogs-panel">
                                <div class="white-box">
                                    <h4 class="box-title m-b-0">Call Log</h4>
                                    <p class="text-muted m-b-20">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
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
                                            <?php 
                                                $records = array();
                                                $data_details = array(date('2017-04-22'),date('H:i'),'+1 435 468 1553','1:00','Message');
                                                $records [] = $data_details;
                                                $j = 1;
                                                for($i =0; $i<=30; $i++){
                                                    $j = rand(1,5);
                                                    $data_details[0] = date('Y-m-d',strtotime($data_details[0] . ' +'.$j.' day'));
                                                    $data_details[2] = '+1 '.rand(111,1000).' '.rand(111,1000).' '.rand(1111,10000);
                                                    if(($i % 5 ) == 2 || ($i % 5 ) == 4) {
                                                        $data_details[4] = 'Message';
                                                    } else {
                                                        $data_details[4] = '';
                                                    }
                                                    $records [] = $data_details;
                                                }
                                            ?>
                                            <tbody>
                                            <?php foreach($records as $details){ ?>
                                                <tr>
                                                    <td><span class="text-muted"><?php echo date('m/d/Y',strtotime($details[0]));?></td>
                                                    <td><span class="text-muted"><?php echo $details[1];?></td>
                                                    <td><?php echo $details[2];?></td>
                                                    <td><span class="text-muted"><?php echo $details[3];?></td>
                                                    <td><div class="label label-table label-success"><?php echo $details[4];?></div></td>
                                                </tr>
                                            <?php }?>
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
<?php get_footer('custom'); ?>
<script>
    var table = jQuery('#call_logs').DataTable({
        scrollY: "450px",
        scrollCollapse: true
    });
    function markReadUnread(seLector) {
        var active_tab = jQuery('.section-right .tab-pane.active').attr('id');
        var $msg_panel = jQuery(seLector).closest('.message-panel');
        console.log('Active tab ==> ' + active_tab);
        if (active_tab == 'read') {
            $msg_panel.fadeOut(function() {
                jQuery('#unread .row').prepend($msg_panel);
                $msg_panel.find('a.msg-marker').text('Mark Read').end().fadeIn();
            });
            console.log('Moved to Unread');
        } else if ('unread') {
            $msg_panel.fadeOut(function() {
                jQuery('#read .row').prepend($msg_panel);
                $msg_panel.find('a.msg-marker').text('Mark Unread').end().fadeIn();
            });
            console.log('Moved tab Read');
        }
    }

    function removeMessage(seLector) {
        jQuery(seLector).closest('.message-panel').fadeOut(function() {
            jQuery(seLector).remove();
        });
    }

    function addFollowUp(seLector) {
        var folloupval = jQuery('.followup-input').val();
        var checkbox_len = jQuery('.follow-up-checks').length;
        checkbox_len = (checkbox_len + 1);
        if (folloupval.trim() != '') {
            jQuery(seLector).closest('form.form-horizontal').prepend('<div class="form-group"> <div class="col-sm-12"> <div class="checkbox checkbox-danger"> <input id="checkbox' + checkbox_len + '" type="checkbox" class="follow-up-checks"> <label for="checkbox' + checkbox_len + '">' + folloupval + '</label> </div> </div> </div>');
            jQuery('.followup-input').val('');
        }
    }
</script>