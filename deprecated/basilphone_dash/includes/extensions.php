<?php 
class extensions{
	
	public function __construct() {
    }
	public function add($data=array()){
		global $wpdb;
		$date           = date("y-m-d"); 
		
		// INSERT EXTENSIONS DATA
		$wpdb->insert($wpdb->prefix.'user_extensions', array(
					'user_id'         => $this->getUserId(),
					'id'              => $data['extensions_id'],
					'conference_id'   => $data['conference_id'],
					'first_name'      => $data['first_name'],
					'last_name'       => $data['last_name'],
					'email'           => $data['email'],
                                        'mobile_prefix'   => $data['areacode'], 
					'mobile_number'   => $data['mobile_number'],
					'modified_date'   => $date,
					'status'          => 1 
					));
		
		//PASS THE NEXT EXTENSION ID
		return $this->getExtensionid();
	}
	public function addgreetings($data=array()){
		global $wpdb;
		$date           = date("y-m-d"); 
		
		
		if(empty($data['greetingid'])){
					
		// INSERT GREETINGS  DATA
		$getgreetings=$wpdb->insert($wpdb->prefix.'app_userdata', array(
							'user_id'                    => $this->getUserId(),
							'greeting_type'              => $data['optionsRadios'],
							'sales_users'                => $data['sale_users_id'],
							'support_users'              => $data['support_users_id'],
							'custom_text'                => $data['custom_text'],
							'status'                     => 1 
					));
		
		}else{
		// UPDATE GREETINGS  DATA	
        $getgreetings=$wpdb->update( 
    	$wpdb->prefix.'app_userdata',
						array(
											'user_id'                    => $this->getUserId(),
											'greeting_type'              => $data['optionsRadios'],
											'sales_users'                => $data['sale_users_id'],
											'support_users'              => $data['support_users_id'],
											'custom_text'                => $data['custom_text'],
											'status'                     => 1 
						   ),
						array('id' =>$data['greetingid'])
						);
		}
		return $getgreetings;
	}
	
	public function getUserId(){
		$user_id = get_current_user_id();
		return $user_id;
	}
	public function get($id=array()){
		global $wpdb;

		if(!empty($id)){
		   $get_query   = 'SELECT * FROM '.$wpdb->prefix.'user_extensions WHERE id IN ('.$id.') AND status=1 order by id desc';
		}else{
		   $user_id =$this->getUserId();
		   $get_query   = 'SELECT * FROM '.$wpdb->prefix.'user_extensions WHERE user_id ="'.$user_id.'" AND status=1 order by id desc';
		}
		$results     = $wpdb->get_results( $get_query, OBJECT );
		return       $results;
	}
	public function getgreetings(){
		global $wpdb;
		$user_id =$this->getUserId();
		$get_query   = 'SELECT * FROM '.$wpdb->prefix.'app_userdata WHERE user_id ='.$user_id.' AND status=1 order by id desc limit 1';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		return   $results ;     
	}
	
	
	public function getExtensionsCount(){
		global $wpdb;
		$user_id =$this->getUserId();
		$get_query   = 'SELECT count(*) as total_ext FROM '.$wpdb->prefix.'user_extensions WHERE user_id ='.$user_id.' AND status=1 order by id desc';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		return       $results;
	}
	public function remove($id=''){
		global $wpdb;
		$table_name=$wpdb->prefix.'user_extensions';
		$track_table=$wpdb->prefix.'user_extensions_track';

		$sql="INSERT INTO ".$track_table." SELECT * FROM ".$table_name." WHERE id=".$id;
		$results = $wpdb->query($sql); 
		if($results){
		$delet_sql="DELETE FROM ".$table_name." WHERE  id=".$id;     
		$results = $wpdb->query($delet_sql);
		}
		return  $this->getExtensionid();
	}
	public function search($data=array()){
		global $wpdb;
	}
	public function find_unusedid(){
		global $wpdb;
		$table_name=$wpdb->prefix.'user_extensions';
		
		$checkid_sql="SELECT id as free_id FROM ".$table_name." WHERE id =100";
	    $checkid_results = $wpdb->get_results($checkid_sql,OBJECT);
		if(!empty($checkid_results[0])){
			$findid_sql="SELECT MIN(id + 1) as free_id FROM ".$table_name." WHERE id + 1 NOT IN ( SELECT id FROM ".$table_name." WHERE id > 0 ) ORDER BY id ASC";
			$results = $wpdb->get_results($findid_sql,OBJECT);
			$free_id=$results[0]->free_id;
		}else{
         
		    $free_id=100;
		}
		
		return $free_id;
	}
	public function getExtensionid(){
		$find_unusedid = $this->find_unusedid();
		
                if(!empty($find_unusedid) && $find_unusedid!=100){
		    $unusedid = $find_unusedid;
		}else{
			global $wpdb;
			$get_query   = 'SELECT id FROM '.$wpdb->prefix.'user_extensions  order by id desc limit 1';
			$results     = $wpdb->get_results( $get_query, OBJECT );
			$unusedid =  intval($results[0]->id+1);
		}
		return  $unusedid;
	}
	public function isValid($data=array(),$field){
		global $wpdb;
		$check_query ='SELECT * FROM '.$wpdb->prefix.'user_extensions WHERE '.$field.' ="'.$data[$field].'"';
		$results     = $wpdb->get_results( $check_query, OBJECT );
	    $rowcount    = $wpdb->num_rows;
		return       ($rowcount>0) ? 1 : 0 ;
	}
	public function cleandata($data=array()){
		$user_data  = array();
		if(!empty($data)):
		  foreach($data as $formdata):
		   $user_data[$formdata['name']]=$formdata['value'];
		  endforeach;
		endif;
		return $user_data;
   }
}
$GLOBALS['extensions'] =  new extensions();
?>