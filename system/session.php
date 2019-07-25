<?php

	namespace App\System;

	class Session {

		private static $started = false;

		public static function start() {
			if(!Session::$started) {
				session_start();
				Session::$started = true;
			}
		}

		public static function get(string $var) {
			$flash = Session::getFlash($var);
			if(isset($_SESSION[$flash])) {
				$val = $_SESSION[$flash];
				unset($_SESSION[$flash]);
				return $val;
			}
			return isset($_SESSION[$var]) ? $_SESSION[$var] : null;
		}

		public static function set(string $var, $val) {
			$_SESSION[$var] = $val;
		}

		public static function flash(string $var, $val) {
			$_SESSION[Session::getFlash($var)] = $val;
		}

		private static function getFlash(string $var) {
			return 'flash__' . session_id() . '__' . $var;
		}

	}

?>
