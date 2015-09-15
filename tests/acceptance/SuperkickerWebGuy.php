<?php
/**
 * Created by PhpStorm.
 * User: timoschmidt
 * Date: 03.09.15
 * Time: 23:20
 */

require_once (dirname(__FILE__) . "/WebGuy.php");

class SuperkickerWebGuy extends WebGuy {

	/**
	 * @return void
	 */
	public function resetSystem() {
		$modulesConf = \Codeception\Configuration::suiteSettings('acceptance',\Codeception\Configuration::config());
		$bootstrapSettings = $modulesConf["modules"]["config"]["SshBootstrap"];
		$connection = ssh2_connect($bootstrapSettings['host']);
		ssh2_auth_password($connection, $bootstrapSettings['user'], $bootstrapSettings['password']);
		foreach($bootstrapSettings['commands'] as $command) {
			ssh2_exec($connection, $command);
			sleep(2);
		}
	}
}