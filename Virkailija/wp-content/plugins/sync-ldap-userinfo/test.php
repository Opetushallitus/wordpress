<?php
get_ldap_user('augkilp');

function get_ldap_user($uid) {
	global $wpcasldap_use_options;
	$ds = ldap_connect('koe.hard.ware.fi','10389');
	
		
	
	//Can't connect to LDAP.
	if(!$ds) {
		$error = 'Error in contacting the LDAP server.';
	} else {	
		echo "<h2>Connected</h2>";
		//exit;
		// Make sure the protocol is set to version 3
		//if(
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		//) 
		//{
			$error = 'Failed to set protocol version to 3.';
		//} else {
			//Connection made -- bind anonymously and get dn for username.
			
			error_log('Connected!');
			
			$bind = @ldap_bind($ds);
			//, $wpcasldap_use_options['AuthLDAPBindDN'], $wpcasldap_use_options['AuthLDAPBindPassword']);
			
			$dumptest = print_r($bind);
			$dumptest2 = print_r($ds);
			
			error_log('Bind tehty');
			
			
			error_log($dumptest);
			error_log($dumptest2);
			
			//Check to make sure we're bound.
			//if(!$bind) {
				$error = 'Anonymous bind to LDAP failed.';
				
				error_log('LDAP bind anonymous failed...');
				
			//} else {
			
				error_log('LDAP bind anonymous successful...');
			
				$search = ldap_search($ds, 'dc=example,dc=com', "uid=augkilp");
				$info = ldap_get_entries($ds, $search);
				
				ldap_close($ds);
				return new wpcasldapuser($info);
			//}
			
			error_log('suljetaan yhteys!!');
			ldap_close($ds);
		//}
	}
	return FALSE;
}
?>