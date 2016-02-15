<?php
	function logmessage($message) {
		shell_exec("sudo echo '" . $message . "' | sudo tee --append /var/log/RaspberryIPCamera.log");
	}
	
	function mask2cidr($mask){
	  $long = ip2long($mask);
	  $base = ip2long('255.255.255.255');
	  return 32-log(($long ^ $base)+1,2);
	
	  /* xor-ing will give you the inverse mask,
		  log base 2 of that +1 will return the number
		  of bits that are off in the mask and subtracting
		  from 32 gets you the cidr notation */
			
	}

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	function test_input_no_trim($data) {
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function hostapd_addbridge($action) {
		$hostapdconfig = parse_ini_file("/etc/hostapd/hostapd.conf");
		switch($action) {
			case "enable":
				if(!walk($hostapdconfig, 'bridge'))
					$hostapdconfig['bridge'] = "br0";
					write_hostapd_conf($hostapdconfig,"/etc/hostapd/hostapd.conf"); 
			break;
			case "disable":
				if(walk($hostapdconfig, 'bridge'))
					unset($hostapdconfig['bridge']);
					write_hostapd_conf($hostapdconfig,"/etc/hostapd/hostapd.conf"); 
			break;
		}
	}

  
	function write_php_ini($array, $file)
	{
		$res = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}
			else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
		}
		safefilerewrite($file, implode("\n", $res));
	}

	function write_camerasettings_conf($array, $file)
	{
		$res = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey=$sval";
			}
			else $res[] = "$key=$val";
		}
		safefilerewrite($file, implode("\n", $res));
	}


	function write_hostapd_conf($array, $file)
	{
		$res = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey=$sval";
			}
			else $res[] = "$key=$val";
		}
		safefilerewrite($file, implode("\n", $res));
	}
	
	
	
	
	function safefilerewrite($fileName, $dataToSave)
	{    if ($fp = fopen($fileName, 'w'))
		{
			$startTime = microtime();
			do
			{            $canWrite = flock($fp, LOCK_EX);
			   // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			   if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime()-$startTime) < 1000));
	
			//file was locked so now we can store information
			if ($canWrite)
			{            fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}
	}


	function walk($array, $key)
	{
	  if( !is_array( $array)) 
	  {
		  return false;
	  }
	  foreach ($array as $k => $v)
	  {
		  if($k == $key)
		  {
			  return True;
		  }
	  }
	return false;
	}


	function update_interfaces_file($select)
	{
	  $configurationsettings = parse_ini_file("/var/www/routersettings.ini");
	  $networksettings = array();
	  switch ($select) {
		  case "Router":
			//operationmode Router
			array_push($networksettings,"auto lo\n");
			array_push($networksettings,"iface lo inet loopback\n\n");
			if (strcmp($configurationsettings['lantype'],"dhcp") == 0) {
				array_push($networksettings,"auto eth0\n");
				if(!empty($configurationsettings['lanmtu'])) {
					array_push($networksettings,"iface eth0 inet dhcp\n");
					array_push($networksettings,"post-up ifconfig eth0 mtu " . $configurationsettings['lanmtu'] . "\n");
				}
				else {
					array_push($networksettings,"iface eth0 inet dhcp\n");
				}
			array_push($networksettings,"\n");
			}
			if (strcmp($configurationsettings['lantype'],"static") == 0) {
				array_push($networksettings,"auto eth0\n");
				array_push($networksettings,"iface eth0 inet static\n");
				array_push($networksettings,"address " . $configurationsettings['lanip'] . "\n");
				array_push($networksettings,"netmask " . $configurationsettings['lanmask'] . "\n");
				if(!empty($configurationsettings['langw']))
				  array_push($networksettings,"gateway " . $configurationsettings['langw'] . "\n");
				if(!empty($configurationsettings['dns1']) || !empty($configurationsettings['dns2'])) {
					if(!empty($configurationsettings['dns1']))
					  array_push($networksettings,"nameserver " . $configurationsettings['dns1'] . "\n");
					if(!empty($configurationsettings['dns2']))
					  array_push($networksettings,"nameserver " . $configurationsettings['dns2'] . "\n");
				}
				if(!empty($configurationsettings['lanmtu'])) 
					array_push($networksettings,"post-up ifconfig eth0 mtu " . $configurationsettings['lanmtu'] . "\n");
				array_push($networksettings,"\n");
			}
			$strdata = file_get_contents ("/boot/cmdline.txt");
			$arrdata = explode (" ",$strdata);
			foreach($arrdata as $key => $value) {
			  if (strpos($value, 'smsc95xx.macaddr=') !== FALSE) {
				unset($arrdata[$key]);
			  }
			}
			if(!empty($configurationsettings['lanmac'])) {
			  array_push($arrdata,"smsc95xx.macaddr=" . $configurationsettings['lanmac']);
			}
			else {
			  array_push($arrdata,"smsc95xx.macaddr=20:11:22:33:44:55");
			}
			
			$arrdata = str_replace("\n","",$arrdata);
			file_put_contents("/boot/cmdline.txt",implode(" ",$arrdata));
			//array_push($networksettings,"auto wlan0\n");
			array_push($networksettings,"iface wlan0 inet static\n");
			array_push($networksettings,"address " . $configurationsettings['wifiip'] . "\n");
			array_push($networksettings,"netmask " . $configurationsettings['wifimask'] . "\n");
			file_put_contents("/etc/network/interfaces",implode($networksettings));
		  break;
		 
		  case "Access Point":
			//operationmode access point - prepare interfaces file contents	
			//push the settings for the loopback adapter up the array
			array_push($networksettings,"auto lo\n");
			array_push($networksettings,"iface lo inet loopback\n\n");
			//push the settings for the wlan0 adapter up the array
			array_push($networksettings,"allow-hotplug wlan0\n");
			array_push($networksettings,"iface wlan0 inet manual\n");
			array_push($networksettings,"\n");
			//push the settings for the eth0 adapter up the array
			array_push($networksettings,"allow-hotplug eth0\n");
			array_push($networksettings,"iface eth0 inet manual\n\n");
			//configure access point for dhcp addressing
			if (strcmp($configurationsettings['lantype'],"dhcp") == 0) {
				array_push($networksettings,"auto br0\n");
				array_push($networksettings,"iface br0 inet dhcp\n");
				array_push($networksettings,"pre-up service hostapd stop\n");
				//array_push($networksettings,"pre-up iw dev wlan0 set 4addr on\n");
				array_push($networksettings,"pre-up service hostapd start\n");
				if(!empty($configurationsettings['lanmac'])) 
					array_push($networksettings,"hwaddress ether " . $configurationsettings['lanmac'] . "\n");
				else {
					array_push($networksettings,"hwaddress ether 20:11:22:33:44:55" . "\n");
					$strdata = file_get_contents ("/boot/cmdline.txt");
					$arrdata = explode (" ",$strdata);
					foreach($arrdata as $key => $value) {
					  if (strpos($value, 'smsc95xx.macaddr=') !== FALSE) {
						unset($arrdata[$key]);
					  }
					}
					array_push($arrdata,"smsc95xx.macaddr=20:11:22:33:44:56");
					$arrdata = str_replace("\n","",$arrdata);
					file_put_contents("/boot/cmdline.txt",implode(" ",$arrdata));
				}
				array_push($networksettings,"bridge_ports wlan0 eth0\n");
				if(!empty($configurationsettings['lanmtu'])) 
					array_push($networksettings,"post-up ifconfig eth0 mtu " . $configurationsettings['lanmtu'] . "\n");
				array_push($networksettings,"\n");
			}
			//configure access point for static addressing
			if (strcmp($configurationsettings['lantype'],"static") == 0) {
				array_push($networksettings,"auto br0\n");
				array_push($networksettings,"iface br0 inet static\n");
				//array_push($networksettings,"pre-up iw dev wlan0 set 4addr on\n");
				if(!empty($configurationsettings['lanmac'])) 
					array_push($networksettings,"hwaddress ether " . $configurationsettings['lanmac'] . "\n");
				else {
					array_push($networksettings,"hwaddress ether 20:11:22:33:44:55" . "\n");
					$strdata = file_get_contents ("/boot/cmdline.txt");
					$arrdata = explode (" ",$strdata);
					foreach($arrdata as $key => $value) {
					  if (strpos($value, 'smsc95xx.macaddr=') !== FALSE) {
						unset($arrdata[$key]);
					  }
					}
					array_push($arrdata,"smsc95xx.macaddr=20:11:22:33:44:56");
					$arrdata = str_replace("\n","",$arrdata);
					file_put_contents("/boot/cmdline.txt",implode(" ",$arrdata));
				}
				array_push($networksettings,"bridge_ports wlan0 eth0\n");
				if(!empty($configurationsettings['lanmtu'])) 
					array_push($networksettings,"post-up ifconfig eth0 mtu " . $configurationsettings['lanmtu'] . "\n");
				array_push($networksettings,"address " . $configurationsettings['lanip'] . "\n");
				array_push($networksettings,"netmask " . $configurationsettings['lanmask'] . "\n");
				if(!empty($configurationsettings['langw']))
				  array_push($networksettings,"gateway " . $configurationsettings['langw'] . "\n");
				if(!empty($configurationsettings['dns1']) || !empty($configurationsettings['dns2'])) {
					if(!empty($configurationsettings['dns1']))
					  array_push($networksettings,"nameserver " . $configurationsettings['dns1'] . "\n");
					if(!empty($configurationsettings['dns2']))
					  array_push($networksettings,"nameserver " . $configurationsettings['dns2'] . "\n");
				}
			}
			file_put_contents("/etc/network/interfaces",implode($networksettings));
		  break;
	  }
	}
?> 


