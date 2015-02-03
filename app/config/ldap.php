<?php
	return array(
		'account_suffix'        =>  "@dominio",
		'domain_controllers'    =>  array("ip_dominio", "nombre_dominio"), // Load balancing domain controllers
		'base_dn'               =>  'DC=dominio',
		'admin_username'        =>  'usuario_busqueda',    // Just needs to be an valid account to query other users if they exists
		'admin_password'        =>  'contrasena'
	);
?>
