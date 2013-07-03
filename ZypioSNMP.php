<?php
/*****************************************************
 * Package ZypioSNMP
 * Description ZypioSNMP is used to datafill SNMP OID's
 *             using SNMP's pass functionality
 * Author Mike Mackintosh < m@zyp.io >
 * Date 20130703
 * Version 0.5
 *
 * Requires >= PHP5.4
 *
 ****************************************************/
#!<PATH_TO_PHP_INTERPRETER>

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

/**
 * 
 */
class ZypioSNMP{

	private $oid,
			$tree = [];

	const INTEGER = "integer";
	const STRING = "string";
	const IPADDR = "ipaddress";
	const GUAGE = "guage";
	const COUNTER = "counter";
	const TIME = "timeticks";
	const OBJ = "objectid";

	/**
	 * Create Class with Base OID
	 * 
	 * @param [type] $oid [description]
	 */
	public function __construct( $oid ){

		$this->oid = $oid;

	}

	/**
	 * Add an OID to your base OID
	 * 
	 * @param string $oid   The OID you wish to store data for
	 * @param string $type  Your OID type, STRING,Integer, etc
	 * @param multiple $value The value of your OID
	 */
	public function addOid( $oid, $type = STRING, $value= NULL ){

		$this->tree[$oid] = [ 'type' => $type, 'value' => $value ];
		ksort($this->tree);

		return $this;
	}

	/**
	 * [getOid description]
	 * 
	 * @param  string $requested_oid
	 * 
	 * @return NULL
	 */
	public function getOid( $requested_oid ){

		file_put_contents("/usr/share/nginx/www/log", "Getting $requested_oid\n", FILE_APPEND);

		preg_match("`{$this->oid}(.*)`", $requested_oid , $matches);

		file_put_contents("/usr/share/nginx/www/log", "\t$matches[1]\n", FILE_APPEND);


		foreach( $this->tree as $oid => $v ) {
			if( $requested_oid < "{$this->oid}{$oid}" ){
				
				echo "{$this->oid}{$oid}".PHP_EOL;
				echo $this->tree[ $oid ]['type'] .PHP_EOL;
				echo $this->tree[ $oid ]['value'] .PHP_EOL;
				exit(0);
			}
			else{
				continue;
			}
		}

		echo "NONE".PHP_EOL;
		exit(0);

	}

}