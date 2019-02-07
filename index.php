<?php
/*
  Plugin Name: Saksh WP SMTP
  Version: 4.1.1
  Plugin URI: #
  Author: susheelhbti
  Author URI: http://www.sakshamappinternational.com/
  Description: Integrate wordpress to your mandrill , sendgrid , getresponse, email-marketing247 SMTP Server, Amazon SES or any SMTP Server.
 */
 
 
  



 add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page() {


  //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
  
  
  
    
    add_menu_page('Attendance', 'Attendance', 'manage_options', 'aistore2030_full_attendance','aistore2030_full_attendance');
    
    
add_submenu_page( 'aistore2030_full_attendance', 'Attendance Page', 'Attendance Page',
    'manage_options', 'aistore2030_daily_attendance', 'aistore2030_daily_attendance');
    
    
    
    
add_submenu_page( 'aistore2030_full_attendance', 'Punch in/out', 'Punch in/out',
    'manage_options', 'saksh_punch_in_punch_out', 'saksh_punch_in_punch_out');
    
     
      
}


function register_custom_menu_page() {
    //add_menu_page('custom menu title', 'custom menu', 'add_users', 'custompage', '_custom_menu_page', null, 6); 
}
add_action('admin_menu', 'register_custom_menu_page');

function _custom_menu_page($args = null){
     echo "<pre>";
    $defaults = array(
        'numberposts' => 5,
        'category' => 0, 
        'orderby' => 'date',
        'order' => 'DESC', 
        'include' => array(),
        'exclude' => array(),
        'meta_key' => '',
        'meta_value' =>'', 
        'post_type' => 'todaystask',
        'suppress_filters' => true
    );
 
    $r = wp_parse_args( $args, $defaults );
    if ( empty( $r['post_status'] ) )
        $r['post_status'] = ( 'attachment' == $r['post_type'] ) ? 'inherit' : 'publish';
    if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
        $r['posts_per_page'] = $r['numberposts'];
    if ( ! empty($r['category']) )
        $r['cat'] = $r['category'];
    if ( ! empty($r['include']) ) {
        $incposts = wp_parse_id_list( $r['include'] );
        $r['posts_per_page'] = count($incposts);  // only the number of posts included
        $r['post__in'] = $incposts;
    } elseif ( ! empty($r['exclude']) )
        $r['post__not_in'] = wp_parse_id_list( $r['exclude'] );
 
    $r['ignore_sticky_posts'] = true;
    $r['no_found_rows'] = true;
 
    $get_posts = new WP_Query;
     print_r( $get_posts->query($r));
     
     $p=$get_posts->query($r);
     
 foreach($p as $n)
echo $n->post_date;


   echo "Admin Page Test";  
}





 add_action('admin_menu', 'mnm_admin_actions_datewise_all');
  function mnm_admin_actions_datewise_all() 
  {
    //  add_options_page( 'Daily Attandace', 'Daily Attandace', 'manage_options', 'aistore2030_daily_attendance', 'aistore2030_daily_attendance', 'aistore2030_daily_attendance');
  }


  function aistore2030_daily_attendance()
  {   
      
      
      
      
      $user = wp_get_current_user();
      
      $id=$user->ID ;
      
      
    global $wpdb;
 

  $month=date('m');
 if (isset($_REQUEST['month'])) {
    $month=$_REQUEST['month'];
} 

$result = $wpdb->get_results("SELECT user_id,display_name,count(user_id ) as working_days FROM `wp1f_attendance` WHERE MONTH(adate)=".$month." GROUP by user_id,display_name");

echo "<h2>Employee working days report monthly for salary preparation </h2>";


echo "<a href='/wp-admin/admin.php?page=aistore2030_daily_attendance&month=".date('m', strtotime('-4 month')) . "'>".date('F', strtotime('-4 month')) . "</a>  ";
 
echo "<a href='/wp-admin/admin.php?page=aistore2030_daily_attendance&month=".date('m', strtotime('-3 month')) . "'>".date('F', strtotime('-3 month')) . "</a>  ";
 
 
echo "<a href='/wp-admin/admin.php?page=aistore2030_daily_attendance&month=".date('m', strtotime('-2 month')) . "'>".date('F', strtotime('-2 month')) . "</a>  ";
echo "<a href='/wp-admin/admin.php?page=aistore2030_daily_attendance&month=".date('m', strtotime('-1 month')) . "'>".date('F', strtotime('-1 month')) . "</a>  ";
 
 echo "<a href='/wp-admin/admin.php?page=aistore2030_daily_attendance&month=".date('m') . "'>".date('F') . "</a>  <br /> ";
    
	
	
echo '<table class="widefat">';
echo '

 <thead>
    <tr>
         <th>User Id</th>  
        <th>Full Name</th>
              
        <th>Working Days</th>
    </tr>
</thead>';

foreach($result as $wp_formmaker_submits){

 
  echo "<tr>";
    echo "<td>".$wp_formmaker_submits->user_id."</td>";
    echo "<td>".$wp_formmaker_submits->display_name."</td>";
    echo "<td>".$wp_formmaker_submits->working_days."</td>";
      
	 
   echo "</tr>";
}
  

    echo "</table>";
}











 add_action('admin_menu', 'mnm_admin_actions_all');
  function mnm_admin_actions_all() 
  {
 //     add_options_page( 'Full Attandace', 'Full Attandace', 'manage_options', 'aistore2030_full_attendance', 'aistore2030_full_attendance', 'aistore2030_full_attendance');
  }


  function aistore2030_full_attendance()
  {   
      echo "<div class='wrap'>";
      $user = wp_get_current_user();
      
      $id=$user->ID ;
      
      
    global $wpdb;

 


$user_id=$id;

 if (isset($_REQUEST['user_id'])) {
    $user_id=$_REQUEST['user_id'];
}



echo "<h2>Full Attendance  sheet of the company Recent 200 Records </h2>";



$result = $wpdb->get_results("SELECT distinct display_name,user_id FROM wp1f_attendance     order by id desc");

$i=0;

echo '<table class="widefat"> <tr>';

foreach($result as $display_name){

 $i=$i+1;

    echo "<td><a href='admin.php?page=aistore2030_full_attendance&user_id=$display_name->user_id' >".$display_name->display_name."</a></td>";
    
    if($i==3)
    {
    echo "</tr><tr>";
    $i=0;
    }
    
    
    
}
 echo "</tr></table>";


$result = $wpdb->get_results("SELECT * , TIMESTAMPDIFF(HOUR, entrytime, entrytime) AS hours_different FROM wp1f_attendance  where user_id=$user_id   limit 200");
echo '<table class="widefat">';
echo '

 <thead>
    <tr>
        <th>id</th>
        <th>Date</th>       
        <th>Name </th>
        <th>Entry Time </th>
        <th>Entry IP Address </th>
        <th>Exit Time </th>
        <th>Exit IP Address </th>
        <th>Hourly Exit Reason</th>
        
         <th>Hours Different</th>       
         
    </tr>
</thead>';

foreach($result as $wp_formmaker_submits){

 
  echo "<tr>";
    echo "<td>".$wp_formmaker_submits->id."</td>";
    echo "<td>".$wp_formmaker_submits->adate."</td>";
    echo "<td>".$wp_formmaker_submits->display_name."</td>";
     
     echo "<td>".$wp_formmaker_submits->entrytime."</td>";
	 
     echo "<td>".$wp_formmaker_submits->entry_ip_address."</td>";
     echo "<td>".$wp_formmaker_submits->exittime."</td>";
	 
     echo "<td>".$wp_formmaker_submits->exit_ip_address."</td>";
	 
	 
     echo "<td>".$wp_formmaker_submits->early_exit_reason."</td>";
	 
	 
     echo "<td>".$wp_formmaker_submits->hours_different."</td>";
	 
   echo "</tr>";
}
  

    echo "</table>";
    
     
     

}











 add_action('admin_menu', 'mnm_admin_actions');
  function mnm_admin_actions() 
  {
     // add_options_page( 'Manage Punch in/out', 'Punch in/out', 'manage_options', 'aistore2030_attendance', 'saksh_punch_in_punch_out', 'saksh_punch_in_punch_out');
  }


  function saksh_punch_in_punch_out()
  {   
  //CREATE UNIQUE INDEX wp1f_attendance_index ON wp1f_attendance (user_id, adate);
  
  
      
      $user = wp_get_current_user();
      
      $id=$user->ID ;
      
        $display_name=$user->display_name  ;
        $ip_address=getRealIpAddr();
        
    global $wpdb;
    
    
    if ( 
    ! isset( $_POST['punch_nonce'] ) 
    || ! wp_verify_nonce( $_POST['punch_nonce'], 'punch_nonce' ) 
) {
 
    

} else {


if($_REQUEST['type']=="in")
{
	
	
 $wpdb->query( $wpdb->prepare( "INSERT INTO wp1f_attendance (user_id,adate,
display_name,entrytime,entry_ip_address ) VALUES (%d,date(now()),%s,now() ,%s)",array($id, $display_name,$ip_address )));
}
elseif($_REQUEST['type']=="out")
{
	
	
 $wpdb->query(  "update wp1f_attendance 
 set
 early_exit_reason= '".$_REQUEST['early_exit_reason']."' ,
  exittime= now()  ,
  exit_ip_address =  '$ip_address'
 where
  user_id =   $id and 
  adate=  date(now())
  "
 );
/*
	
	$execut= 	$wpdb->update( 
    'wp1f_attendance', 
    array( 
        'early_exit_reason' => $_REQUEST['early_exit_reason'],
		 'exit_ip_address' =>  $ip_address
    ), 
    array( 'user_id' =>  $id , 'adate' => date() )
);
 
 */

}

}




 
echo "<h2> Attendance  sheet  </h2>";

 
 
 
    ?><table width="50%" border=1>
        
        <tr><th>Punch IN</th><th>Punch Out</th></tr
        
        <tr>
        
        <td>
            
            <form method="post" action="">
   <!-- some inputs here ... -->
   <input type="hidden" name="type" value="in" />
   
   <?php wp_nonce_field( 'punch_nonce', 'punch_nonce' ); ?>
   
   <input type="submit" value="Punch In" />
</form>
        </td>
        
        <td>
             <form method="post" action="">
   <!-- some inputs here ... -->
   <input type="hidden" name="type" value="out" />
   Early Exit Reason  
   <input type="text" name="early_exit_reason" value="Left as per scheduled" />
   
   <?php wp_nonce_field( 'punch_nonce', 'punch_nonce' ); ?>
 
   <input type="submit"   value="Punch Out"/>
   
  
  </form>
      
            
        </td></tr>
    </table>
    
       

       
   

<?php  
   
      
  
  

 $result = $wpdb->get_results("SELECT   *  FROM wp1f_attendance WHERE DATE(entrytime) = CURDATE()  order by id desc");



echo '<table class="widefat">';
echo '

 <thead>
    <tr>
        <th>Name</th>
               
   
       <th>Entry time</th>       
        <th>Exit time</th> <th>Early Exit Reason</th> 
    </tr>
</thead>';




 

foreach($result as $wp_formmaker_submits){

 
 echo "<tr>";
 
  
    echo "<td>".$wp_formmaker_submits->display_name."</td>";
     
     echo "<td>".$wp_formmaker_submits->entrytime." (";
	 
     echo "".$wp_formmaker_submits->entry_ip_address." )</td>";
     echo "<td>".$wp_formmaker_submits->exittime." ( ";
	 
     echo "".$wp_formmaker_submits->exit_ip_address." )</td>";
	 
	 
     echo "<td>".$wp_formmaker_submits->early_exit_reason."</td>";
	  
    echo "</tr>";
 
}
   

    echo "</table>";
}



function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


?>