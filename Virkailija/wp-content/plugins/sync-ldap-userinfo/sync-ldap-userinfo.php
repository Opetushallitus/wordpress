<?php
/* Plugin Name: Sync user info
Plugin URI: http://google.com
Description: Sync user info through LDAP
Version: 1.0
Author: Anniina Salmi
Author URI: http://google.com
License: GPLv2 or later
*/

function ldap_query($ldap_mail) {
    
// get currently logged user indo    
$current_user = wp_get_current_user();

//$current_user = get_user_by($login);    
$user_mail = $current_user->display_name;
$user_id = $current_user->ID;

// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connection

$ds=ldap_connect("luokka.hard.ware.fi:10389");  // must be a valid LDAP server!

if ($ds) { 
    $r=ldap_bind($ds);     // this is an "anonymous" bind, typically
                           // read-only access
   
    // Search surname entry
    $sr=ldap_search($ds, "dc=example,dc=com", "uid=".$user_mail);  
   // echo "Search result is " . $sr . "<br />";

    //echo "Number of entries returned is " . ldap_count_entries($ds, $sr) . "<br />";

    //echo "Getting entries ...<p>";
    $info = ldap_get_entries($ds, $sr);
       
    //echo "Data for " . $info["count"] . " items returned:<p>";

    for ($i=0; $i<$info["count"]; $i++) {
        
        $ldap_cn = $info[$i]["cn"][0];
        $ldap_dn = $info[$i]["dn"];
        $ldap_sn = $info[$i]["sn"][0];
        $ldap_mail = $info[$i]["mail"][0];
        
        //echo "dn is: " . $ldap_dn . "<br />";
        //echo "first cn entry is: " . $ldap_cn . "<br />";
        //echo "first email entry is: " . $ldap_mail . "<br /><hr />";      
        
    }
    
    //echo $current_user->display_name.': ';
    //echo date('l, j.n.Y, G:i');
    
    if($user_mail == $ldap_mail){
        echo $ldap_mail.'<br>';
        echo $ldap_cn.'<br>';
        echo $ldap_sn.'<br>';
        echo $ldap_dn.'<br>';
        
        wp_update_user( array ('ID' => $user_id, 'last_name' => $ldap_sn ) );
        
    } else {
        echo $user_mail.' not found :|';
    }
    
        echo "<br>Closing connection";
    ldap_close($ds); 

} else {
    echo "<h4>Unable to connect to LDAP server</h4>";
}  

}   
    
//Hooks a function to a filter action, 'the_content' being the action, 'hello_world' the function.
add_action( 'wp_login', 'ldap_query' );

