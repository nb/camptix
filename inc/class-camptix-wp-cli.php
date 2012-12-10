<?php

class Camptix_Command extends WP_CLI_Command {
	const DEFAULT_QUANTITY = 1;

	/**
	 * @synposis ticket-post-id csv-filename
	 * @subcommand add-reservations
	 */
	function add_reservations( $args, $assoc_args ) {
		global $camptix;
		list( $post_id, $file_name ) = $args;
		if ( 'tix_ticket' != get_post_type( $post_id ) ) {
			WP_CLI::error( "There's no ticket with post_id '$post_id'." );
		}
		$csv = fopen( $file_name, 'r' );
		if ( !$csv ) {
			WP_CLI::error( "Error opening CSV file '$file_name'." );
		}
		$reservations = array();
		while ( ( $entry = fgetcsv( $csv ) ) !== false ) {
			$reservations[] = array( 'name' => trim( $entry[0] ), 'quantity' => isset( $entry[1] )? intval( $entry[1] ) : self::DEFAULT_QUANTITY );
		}
		foreach( $reservations as $reservation ) {
			WP_CLI::line( "Creating a reservation for '{$reservation['name']}' for {$reservation['quantity']} tickets." );
			$camptix->create_reservation( $post_id, $reservation['name'], $reservation['quantity'] );
		}
	}
}

WP_CLI::add_command( 'camptix', 'Camptix_Command' );