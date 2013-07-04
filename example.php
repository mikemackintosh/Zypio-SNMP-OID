#!<PATH_TO_PHP_INTERPRETER>
<?php
/*****************************************************
 * Package ZypioSNMP
 * Description ZypioSNMP is used to datafill SNMP OID's
 *             using SNMP's pass functionality
 * Author Mike Mackintosh < m@zyp.io >
 * Date 20130703
 * Version 1.1
 *
 * Requires >= PHP5.4
 *
 ****************************************************/

require_once( __DIR__ . "/ZypioSNMP.php");


/**
 * NOTE: Replace #!<PATH_TO_PHP_INTERPRETER> with your systems PHP binary
 * Ex:
 * #!/usr/bin/php
 *     or
 * #!/usr/local/php5.5/bin/php
 */

// Log Request
/*
file_put_contents("/var/log/zypiosnmp.log", "Conneciton Received\n", FILE_APPEND);
//*/


// Instantiate Class using Base OID
// -- this base OID must match what you  
// added the pass statement in snmpd.conf
$snmp = new ZypioSNMP(".1.3.6.1.4.1.38741");

// The below syntax adds to the base .1.3.6.1.4.1.38741
///*
$snmp->addOid(".1.0", ZypioSNMP::STRING, "ZypioPHP"); // .1.3.6.1.4.1.38741.1.0
$snmp->addOid(".1.1", ZypioSNMP::INTEGER, 1); // .1.3.6.1.4.1.38741.1.1
$snmp->addOid(".1.1.4.0.9.8.8.0", ZypioSNMP::STRING, "This is nice and long"); // .1.3.6.1.4.1.38741.1.1.4.0.9.8.8.0
$snmp->addOid(".1.1.4.0.9.8.8.1", ZypioSNMP::STRING, "This is nice and long, with a continuance"); // .1.3.6.1.4.1.38741.1.1.4.0.9.8.8.1
$snmp->addOid(".6.1.3.0", ZypioSNMP::IPADDR, "10.211.53.3"); // .1.3.6.1.4.1.38741.6.1.3.0
$snmp->addOid(".7.1.8.0", ZypioSNMP::STRING, "Here is another string"); // .1.3.6.1.4.1.38741.7.1.8.0
$snmp->addOid(".8.2", ZypioSNMP::GUAGE, rand(0,100)); // .1.3.6.1.4.1.38741.8.2
$snmp->addOid(".9.2.0", ZypioSNMP::STRING, "unix timestamp"); // .1.3.6.1.4.1.38741.22.1.9.2.0
$snmp->addOid(".9.2.1", ZypioSNMP::COUNTER, time()); // .1.3.6.1.4.1.38741.22.1.9.2.1
//*/

// Respond
$snmp->respond();