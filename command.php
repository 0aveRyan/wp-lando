<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

spl_autoload_register(
	function( $class ) {
			$class = ltrim( $class, '\\' );
		if ( 0 !== stripos( $class, 'Lando\\Cloud_City\\' ) ) {
			return;
		}
			$parts = explode( '\\', $class );
			array_shift( $parts );
			array_shift( $parts );
			$last    = array_pop( $parts ); // File should be 'class-[...].php'
			$last    = 'class-' . $last . '.php';
			$parts[] = $last;
			$file    = dirname( __FILE__ ) . '/inc/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

WP_CLI::add_command( 
    'lando', 
    'Lando\\Cloud_City\\Command', 
    [
        'when' => 'before_wp_load'
    ] 
);
