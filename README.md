
ZypioSNMP
================================================
An extension of SNMP OID's using PHP

# Description

This script was designed to serve OID's from SNMPD via PHP. It uses the `pass` command from SNMP to handle the requested base OID. You can populate the data using PHP.

# Requirements

**>= PHP5.4** is required

# Installation

### Step 1: Download the Source
To use the script, navigate to your desired source, most commonly `/usr/local` and run:

    git clone https://github.com/mikemackintosh/Zypio-SNMP-OID.git zypiosnmp

This will create the directory `/usr/local/zypiosnmp/`.

### Step 2: Chmod the example

For SNMPD to call your file, it needs to be executable:

    sudo chmod 0777 /usr/local/zypiosnmp/example.php

### Step 3: Update your `snmpd.conf` file

Add the following statement to your `snmpd.conf` file, most likely located in `/etc/snmp/`:

	pass .1.3.6.1.4.1.38741 /usr/local/zypiosnmp/example.php

**Note**: Depending on your security levels and community configuration, you may need to add a view for your OID:

	view   all  included   .1.3.6.1.4.1.38741

# Usage

Look at `example.php` for an example as to how to use ZypioSNMP.

**Note:** It is important that you update the interpreter line to match your systems configuration. This should point to your systems PHP binary.

# Example

    snmpwalk -On -v 2c -c public dev.ve.zyp.io .1.3.6.1.4.1.38741
    .1.3.6.1.4.1.38741.1.0 = STRING: "ZypioPHP"
    .1.3.6.1.4.1.38741.1.1 = INTEGER: 1
    .1.3.6.1.4.1.38741.1.1.4.0.9.8.8.0 = STRING: "This is nice and long"
    .1.3.6.1.4.1.38741.7.1.3.0 = IpAddress: 10.211.53.3
    .1.3.6.1.4.1.38741.8.1.8.0 = STRING: "Here is another string"

# Performance

The nature of the `pass` command in `snmpd.conf` means that the script is run on every request. You may use `pass_persist` to cache the response and save some system resources.   

# Troubleshooting

If you are having an issue with your OID's not responding (`No Such Instance currently exists at this OID`), there is most likely a permissions issue.

You can debug SNMPD by stopping it, and then invoking it directly, using:

    sudo snmpd -f -Lo -Ducd-snmp/pass

This would look something like:

    splug@dev.ve.zyp.io:~$ sudo snmpd -f -Lo -Ducd-snmp/pass
    registered debug token ucd-snmp/pass, 1
    iquerySecName has not been configured - internal queries will fail
    Turning on AgentX master support.
    NET-SNMP version 5.4.3
    Connection from UDP: [10.211.55.2]:52940->[10.211.55.3]
    ucd-snmp/pass: pass-running:  /usr/local/zypiosnmp/example.php -n .1.3.6.1.4.1.38741
    Connection from UDP: [10.211.55.2]:52940->[10.211.55.3]
    ucd-snmp/pass: pass-running:  /usr/local/zypiosnmp/example.php -n .1.3.6.1.4.1.38741.1.0

You can test your script by executing what is located after `ucd-snmp/pass: pass-running:`. Example:

    /usr/local/zypiosnmp/example.php -n .1.3.6.1.4.1.38741.1.0

If this returns a file not found, or permissions issues, you can correct them with normal command line foo.

# Credits

This source was written by Mike Mackintosh <*m@zyp.io*> who authors a blog called [HighOnPHP](http://www.highonphp.com)