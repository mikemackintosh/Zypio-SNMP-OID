
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

    sudo chmod +x /usr/local/zypiosnmp/example.php

### Step 3: Update your `snmpd.conf` file

Add the following statement to your `snmpd.conf` file, most likely located in `/etc/snmp/`:

	pass .1.3.6.1.4.1.38741 /usr/local/zypiosnmp/example.php

**Note**: Depending on your security levels and community configuration, you may need to add a view for your OID:

	view   all  included   .1.3.6.1.4.1.38741

# Usage

Look at `example.php` for an example as to how to use ZypioSNMP.

**Note:** It is important that you update the interpreter line to match your systems configuration. This should point to your systems PHP binary.

# Performance

The nature of the `pass` command in `snmpd.conf` means that the script is run on every request. You may use `pass_persist` to cache the response and save some system resources.   

# Credits

This source was written by Mike Mackintosh <*m@zyp.io*> who authors a blog called [HighOnPHP](http://www.highonphp.com)