<?php
class PhpInfoController extends Doer {
	function out($void = "") {
		$login = new Login;
		if($login->superuser()) {
			phpinfo();
		} else {
			throw new Exception(master_lang::unauthorized_access);
		}
	}
}