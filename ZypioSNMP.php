<?php
/*****************************************************
 * Package ZypioSNMP
 * Description ZypioSNMP is used to datafill SNMP OID's
 *             using SNMP's pass functionality
 * Author Mike Mackintosh < m@zyp.io >
 * Date 20130708
 * Version 1.2
 *
 * Requires >= PHP5.4
 *
 ****************************************************/

/**
 * 
 */
class ZypioSNMP{

	private $oid,
			$tree = [];

	const INTEGER = "integer";
	const STRING = "string";
	const IPADDR = "ipaddress";
	const NETADDR = "NetworkAddress";
	const GAUGE = "gauge";
	const COUNTER = "counter64";
	const TIME = "timeticks";
	const OBJ = "objectid";
	const OPAQUE = "opaque";


	/**
	 * Create Class with Base OID
	 * 
	 * @param [type] $oid [description]
	 */
	public function __construct( $oid ){

		if(strpos($oid, ".") !== 0){
			$oid = ".{$oid}";
		}

		// Store base OID locally
		$this->oid = $oid;

	}

	/**
	 * Add an OID to your base OID
	 * 
	 * @param string $oid   The OID you wish to store data for
	 * @param string $type  Your OID type, STRING,Integer, etc
	 * @param multiple $value The value of your OID
	 */
	public function addOid( $oid, $type = STRING, $value= NULL, $allowed = [] ){

		$this->tree[$oid] = [ 'type' => $type, 'value' => $value, 'allowed' => $allowed ];

		return $this;
	}

	/**
	 * [getNextOid description]
	 * 
	 * @param  string $requested_oid
	 * 
	 * @return NULL
	 */
	public function getNextOid( $requested_oid ){

		/*
		file_put_contents("/usr/share/nginx/www/log", "Getting $requested_oid\n", FILE_APPEND);
		//*/
		
		// Get remainder
		preg_match("`{$this->oid}(.*)`", $requested_oid , $matches);

		/*
		file_put_contents("/usr/share/nginx/www/log", "\t$matches[1]\n", FILE_APPEND);
		//*/

		// Sort Response
		$tree = array_keys($this->tree);
		natsort($tree);
		$this->tree = array_merge($tree, $this->tree);

		// 
		$local_oids = array_reverse($tree);

		// Loop Through now
		for($i=0;$i<sizeof($local_oids);$i++){

			/*
			echo "Looking for OID: $local_oids[$i]\n";
			//*/

			if( $requested_oid == $this->oid ){
				
				$end = end($local_oids);

				echo "{$this->oid}{$end}".PHP_EOL;
				echo $this->tree[ $end ]['type'] .PHP_EOL;
				echo $this->tree[ $end ]['value'] .PHP_EOL;
				exit(0);

			}
			else if( version_compare( $requested_oid, $this->oid . $local_oids[$i], ">=")) {

				/*
				echo "$requested_oid is greater than or equal to ". $this->oid . $local_oids[$i]."\n";
				echo "We should return ". $this->oid . $local_oids[$i-1]."\n";
				//*/

				if( array_key_exists( $i-1 , $local_oids )){

					echo "{$this->oid}{$local_oids[$i-1]}".PHP_EOL;
					echo $this->tree[ $local_oids[$i-1] ]['type'] .PHP_EOL;
					echo $this->tree[ $local_oids[$i-1] ]['value'] .PHP_EOL;
						
				}
				else{
					echo "NONE".PHP_EOL;
				}

				exit(0);
				
			}

		}

		// Per RFC, if there is nothing left, respond with NONE
		echo "NONE".PHP_EOL;
		exit(0);

	}

	public function getOid( $requested_oid ){

		/*
		file_put_contents("/usr/share/nginx/www/log", "Getting $requested_oid\n", FILE_APPEND);
		//*/
		
		// Get remainder
		preg_match("`{$this->oid}(.*)`", $requested_oid , $matches);
		
		// Set relative OID
		$oid = $matches[1];

		/*
		file_put_contents("/usr/share/nginx/www/log", "\t$matches[1]\n", FILE_APPEND);
		//*/

		if( array_key_exists( $oid, $this->tree )){
	
				echo "{$requested_oid}".PHP_EOL;
				echo $this->tree[ $oid ]['type'] .PHP_EOL;
				echo $this->tree[ $oid ]['value'] .PHP_EOL;
				exit(0);

		}

		// Per RFC, if there is nothing left, respond with NONE
		echo "NONE".PHP_EOL;
		exit(0);

	}

	public function respond(){

		// This checks for a GET/GETNEXT or SET
		// NOTE: Only GET/GETNEXT is support ATM
		if( $_SERVER['argv'][1] == "-n" ){

			/*
			file_put_contents("/var/log/zypiosnmp.log", "Requesting an OID GETNEXT with -n --{$_SERVER['argv'][2]}\n", FILE_APPEND);
			//*/

			$this->getNextOid( $_SERVER['argv'][2] );

		}
		else if( $_SERVER['argv'][1] == "-g" ){

			/*
			file_put_contents("/var/log/zypiosnmp.log", "Requesting an OID GET with -g --{$_SERVER['argv'][2]}\n", FILE_APPEND);
			//*/

			$this->getOid( $_SERVER['argv'][2] );

		}

	}

}

/**
 * @CHANGELOG
 * 
 * Version 1.2
 * 		Fixed issue with get next and comparing OID's
 * 		Added changelog to end of file
 * 		Fixed mispelled Gauge (from Guage)
 * 		Added opaque and netaddr as obj def
 * 		
 * Version 1.1
 * 		Moved SNMP cmd to respond method
 *
 * Version 1.0
 *		Initial
 * 
 */