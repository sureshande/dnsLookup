PHP IpBlockList

Author:		Robert Mahan
Email:		bjtexas@swbell.net

Usage:

	filename1 = 'whitelist filename';
	filename2 = 'blacklist filename';

	$list = new IpBlockList( filename1, filename2 );
	$ip = 'a.b.c.d';
	boolean $result = $list->ipPass( $ip );
	$msg = $list->message();
	
Description:

	This class reads in files containing a list of addresses/address ranges to
	be whitelisted and/or blacklisted.  The filenames may be specified at the
	creation of the IpBlockList Object.  If they are not specified, default
	filenames of '_whitelist.dat' and '_blacklist.dat' will be used.  The 
	ip addresses may be specifed in the files as single ip addresses or a range
	of ip addresses, one value per line.  The files may contain comment lines
	or inline comments beginning with '#', and blank lines for readability.
	
	See the included example '_whitelist.dat' and '_blacklist.dat' files.
	
	The whitelist should include ip addresses that should not be blocked, the
	blacklist should contain ip addresses to be blocked. The whitelist is 
	checked first and will overide the blacklist ip addresses.
	
	If an ip address is found in the whitelist file, the function ipPass() will
	immediately abort the check and return a value of True. If an ip address is 
	found in he blacklist file, the function ipPass() will return False.  If the
	ip address is not found in either the whitelist or the blacklist files, the
	function ipPass() will return the value True.
	
	After the function ipPass() completes a result message may be retrieved using
	the function message().

	
Whitelist/Blacklist file Format:

  IPv4 Addresses:
  
	ipaddress (single):
		'25.25.25.0'
		
	wildcard:
		'25.25.25.*'

	startip-endip:
		'25.25.25.0-25.25.25.128'
		
	ipaddress/netmask (CIDR):
		'255.255.255.0/255.255.255.128'
		'255.255.255.0/25'
		
  IPv6 Addresses:
  
    ipaddress (single):
        2001:0db8:85a3:0042:1000:8a2e:0370:7334
        
    startip-endip:
		2001:0db8:85a3:0042:1000:8a2e:0370:7000-2001:0db8:85a3:0042:1000:8a2e:0370:7400
    
    ipaddress/netmask (CIDR){
        2001:0db8:85a3:0042:1000:8a2e:0370:7334/ffff:ffff:ffff:ffff::
        2001:0db8:85a3:0042:1000:8a2e:0370:7334/64
        
	Read the example files for more details.
		

Class Methods:

    boolean IpBlockList::ipPass( string ipaddress )
    
        Returns true or false.  If found in the 'whitelist' or
        not found in either list true is returned.  If found
        in the 'blacklist' list false is returned.
        
    string IpBlockList::message() 
       
        Returns a string describing the reason for the results of
        the check. Is valid following a call to ipPass().  This
        is useful for logging results.
        
    integer IpBlockList::status()
    
        Returns an integer showing the status of the last call to
        ipPass(). Returns 1 if the ipaddress was found in the whitelist,
        -1 if the ipaddress was found in the blacklist, and 0 if not 
        found in either list.
