<!-- Analytics View Page -->
<?php 
global $extensions;
$total_extensions=$extensions->getExtensionsCount();
$total_extensions=$total_extensions[0]

?>
<section class="content">
     <div class="col-md-12">
                                    <!--form control starts-->
                                    <div class="panel panel-success" id="hidepanel6">
                                        <div class="panel-body">
                                           <div class="row">
                                               <div class="col-md-12">
                                                <h2 class="panel-title">
                                                    Minute Usage
                                                </h2>
                                               </div>
                                               
                                               <div class="col-md-12">
                                               <div class="minute-progress">
                                                  <div class="minute-progressinner"></div>
                                               </div>
                                               </div>
                                               <div class="minute-info">
                                                        <div class="col-md-4 minuteitem">
                                                          <span class="count"><?php    echo ($total_extensions->total_ext>0)  ?  $total_extensions->total_ext :  '100';?></span>
                                                          <span class="count_txt">Extensions</span>
                                                        </div>
                                                        <div class="col-md-4 minuteitem">
                                                            <span class="count">24</span>
                                                            <span class="count_txt">Total Calls</span>
                                                        </div>
                                                        <div class="col-md-4 minuteitem">
                                                            <span class="count">00:00:58</span>
                                                            <span class="count_txt">Avg.Call Time</span>
                                                        </div>
                                               </div>
                                                 <!-- Call Log -->
                                                 <div class="col-lg-6 call-log pull-left">
                                                 <div class="portlet-body">
                                                  <h3 class="panel-inner-title">Call Log</h3>
                                                <div class="table-scrollable">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Caller</th>
                                                            <th>Call Time</th>
                                                            <th>Call Date</th>
                                                            <th>length of call</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Airi Satou</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Angelica</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ashton</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bradley</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Airi Satou</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Angelica</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ashton</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bradley</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Airi Satou</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Angelica</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ashton</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bradley</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Airi Satou</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Angelica</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ashton</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bradley</td>
                                                            <td>09.35</td>
                                                            <td>12-04-2017</td>
                                                            <td>04.35</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                                 </div>
                                                 <!-- Call Volume -->
                                                 <div class="col-lg-6 pull-right">
                                                        <!-- toggling series charts strats here-->
                                                        <div class="panel panel-primary callvolume">
                                                            <div class="panel-body">
                                                                <h3 class="panel-inner-title">Call Volume</h3>
                                                                <div id="line-chart" class="flotChart"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                          
                                        </div>
                                    </div>
                                </div>
</section>
