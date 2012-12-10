<?php

class Camptix_Command extends WP_CLI_Command {
	const DEFAULT_QUANTITY = 1;

	/**
	 * @synposis ticket-post-id names-filename quantities-filename
	 * @subcommand add-reservations
	 */
	function add_reservations( $args, $assoc_args ) {
		global $camptix;
		list( $post_id, $names_file_name, $quantities_file_name ) = $args;
		if ( 'tix_ticket' != get_post_type( $post_id ) ) {
			WP_CLI::error( "There's no ticket with post_id '$post_id'." );
		}
		$names_text = file_get_contents( $names_file_name );
		if ( false === $names_text ) {
			WP_CLI::error( "Error reading names from '$names_file_name'." );
		}
		$quantities_text = file_get_contents( $quantities_file_name );
		if ( false === $quantities_text ) {
			WP_CLI::error( "Error reading quantities from '$quantities_file_name'." );
		}
		$names = split( "\n", $names_text );
		$quantities = split( "\n", $quantities_text );

		if ( count( $names ) != count( $quantities ) ) {
			WP_CLI::error( "Names and quantities have different counts." );
		}

		$reservations = array();
		for( $i = 0; $i < count( $names ); ++$i ) {
			if ( !$names[$i] && !$quantities[$i] )
				continue;
			$reservations[] = array( 'name' => trim( $names[$i] ), 'quantity' => intval( $quantities[$i] ) );
		}

		foreach( $reservations as $reservation ) {
			WP_CLI::line( "Creating a reservation for '{$reservation['name']}' for {$reservation['quantity']} tickets." );
			$camptix->create_reservation( $post_id, $reservation['name'], $reservation['quantity'] );
		}
	}
}

WP_CLI::add_command( 'camptix', 'Camptix_Command' );