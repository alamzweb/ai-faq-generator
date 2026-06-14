<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Deactivator {
	public static function deactivate() {
		flush_rewrite_rules();
	}
}