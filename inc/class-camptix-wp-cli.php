<?php

class Camptix_Command extends WP_CLI_Command {

	/**
	 * @synposis ticket-post-id csv-filename
	 * @subcommand add-reservations
	 */
	function add_reservations( $args, $assoc_args ) {
		global $camptix;
		list( $post_id, $file_name ) = $args;
		$test_mode = isset( $assoc_args['test'] );
		if ( 'tix_ticket' != get_post_type( $post_id ) ) {
			WP_CLI::error( "There's no ticket with post_id '$post_id'." );
		}
		$csv = fopen( $file_name, 'r' );
		if ( !$csv ) {
			WP_CLI::error( "Error opening CSV file '$file_name'." );
		}
		$reservations = array();
		while ( ( $entry = fgetcsv( $csv, 1000, "\t" ) ) !== false ) {
			$reservations[] = array( 'name' => trim( $entry[0] ), 'email' => trim( $entry[1] ), 'quantity' => isset( $entry[4] )? ( $entry[4] ): ( intval( $entry[3] )? $entry[3] : 0 ) );
		}
		foreach( $reservations as $reservation ) {
			if ( !$reservation['name'] || !$reservation['quantity'] ) continue;
			if ( !$reservation['email'] ) {
				WP_CLI::line( "Skipping {$reservation['name']}, because it doesn't have an e-mail." );
				continue;
			}
			$test_text = $test_mode? 'test' : '';
			WP_CLI::line( "Creating a $test_text reservation for '{$reservation['name']}' for {$reservation['quantity']} tickets." );
			if ( !$test_mode ) {
				$camptix->create_reservation( $post_id, $reservation['name'], $reservation['quantity'] );
			}
		}
	}
}

WP_CLI::add_command( 'camptix', 'Camptix_Command' );
