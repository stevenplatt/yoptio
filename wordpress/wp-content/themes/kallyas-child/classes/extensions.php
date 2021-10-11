<?php 
class yoptio{
	var $tst= '1lsdksjdsd';
	public function __construct() {
    
    }

	public function followup($data=array()){
		global $wpdb;
		$table_name = $wpdb->prefix.'yoptio_followup';
		
		

		if($data['action']=='add'):
		// INSERT FOLLOW UP DATA
		$date = date("Y-m-d"); 
		$data = array(
					'user_id'        => $this->getUserId(),
					'followup_text'  => $data['followup_text'],
					'created_date'   => $date,
					'status'         => 1 
					);
	    $wpdb->insert($table_name,$data);
		$results=$wpdb->insert_id;;
		
		elseif($data['action']=='remove'):
      
        $id = $data['id'];
        $delet_sql="DELETE FROM ".$table_name." WHERE  id=".$id;     
		$results = $wpdb->query($delet_sql);   
		
		elseif($data['action']=='get'):
		  $user_id =$this->getUserId();
		  $get_query   = 'SELECT * FROM '.$table_name.' WHERE user_id ='.$user_id.' AND status=1 order by id desc';
		  $results     = $wpdb->get_results( $get_query, OBJECT );
		
		endif;
	    //PASS THE FOLLOW UP ID
		return $results;
	}
  	public function message($data=array()){
		global $wpdb;
		$table_name = $wpdb->prefix.'yoptio_messages';
		
		 

		if($data['action']=='add'):
		// INSERT FOLLOW UP DATA
		$date = date("Y-m-d"); 
		$data = array(
					'user_id'        => $this->getUserId(),
					'followup_text'  => $data['followup_text'],
					'created_date'   => $date,
					'status'         => 1 
					);
	    $wpdb->insert($table_name,$data);
		$results=$wpdb->insert_id;;
		
		elseif($data['action']=='update'):
      
        $id = $data['id'];
        $status = $data['status'];
      
		$update_qry = "Update " . $table_name . " Set status = ".$status." Where id = ".$id;
        $results = $wpdb->query($update_qry);              
     
		elseif($data['action']=='remove'):
      
        $id = $data['id'];
        $delet_sql="DELETE FROM ".$table_name." WHERE  id=".$id;     
		$results = $wpdb->query($delet_sql);   
		
		elseif($data['action']=='get'):
		  $user_id =$this->getUserId();
		  $get_query   = 'SELECT * FROM '.$table_name.' WHERE user_id ='.$user_id.' AND status='.$data['status'].' order by id desc';
		  $results     = $wpdb->get_results( $get_query, OBJECT );
		
		endif;
	    //PASS THE FOLLOW UP ID
		return $results;
	}
	public function calllogs($data=array()){
		global $wpdb;
		$table_name = $wpdb->prefix.'yoptio_calllogs';
		
	
		if($data['action']=='add'):
		// INSERT FOLLOW UP DATA
		$date = date("Y-m-d"); 
		$data = array(
					'user_id'        => $this->getUserId(),
					'followup_text'  => $data['followup_text'],
					'created_date'   => $date,
					'status'         => 1 
					);
	    $wpdb->insert($table_name,$data);
		$results=$wpdb->insert_id;;
		
		elseif($data['action']=='update'):
      
        $id = $data['id'];
        $status = $data['status'];
      
		$update_qry = "Update " . $table_name . " Set status = ".$status." Where id = ".$id;
        $results = $wpdb->query($update_qry);              
     
		elseif($data['action']=='remove'):
      
        $id = $data['id'];
        $delet_sql="DELETE FROM ".$table_name." WHERE  id=".$id;     
		$results = $wpdb->query($delet_sql);   
		
		elseif($data['action']=='minuteused'):
          $user_id =$this->getUserId();
		  $get_query   = 'SELECT count(duration) as total_minuteused FROM '.$table_name.' WHERE user_id ='.$user_id.' AND status='.$data['status'];
		  $results     = $wpdb->get_results( $get_query, OBJECT );

		elseif($data['action']=='get'):
		  $user_id =$this->getUserId();
		  $get_query   = 'SELECT * FROM '.$table_name.' WHERE user_id ='.$user_id.' AND status='.$data['status'].' order by id desc';
		  $results     = $wpdb->get_results( $get_query, OBJECT );
		
		endif;
	    //PASS THE FOLLOW UP ID
		return $results;
	}
  	

  	


	public function getUserId(){
		$user_id = get_current_user_id();
		return $user_id;
	}	
}
$GLOBALS['yoptio'] =  new yoptio();
?>