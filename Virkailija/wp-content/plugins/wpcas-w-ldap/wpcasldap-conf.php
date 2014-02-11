<?php
/*
// Optional configuration file for wpCASLDAP plugin
// 
// Settings in this file override any options set in the 
// wpCASLDAP menu in Options. Any settings added to the
// $wpcasldap_options array, will not show up on the
// Options Page. 
//
// I would suggest commenting out the settings you want 
// to appear on the options page.
//
*/


// the configuration array
$wpcasldap_options = array (
	'cas_version' => '2.0',
	'include_path' => '/home/ansal1/workspace/oph/Git/Virkailija/wp-content/plugins/CAS/CAS.php',
	'server_hostname' => 'test-virkailija.oph.ware.fi',
	'server_port' => '443',
	'server_path' => '/cas/',

	'ldaphost' => 'reppu.hard.ware.fi',
	'ldapport' => '10389',
	'ldapbasedn' => 'dc=example,dc=com',

	'useradd' => 'yes',
	'useldap' => 'yes',
	'email_suffix' => 'oph.local',
	'userrole' => 'administrator'
);
		
?>
