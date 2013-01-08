<?php
class Camptix_Command extends WP_CLI_Command {
	var $texts = array(
		'free' => "Привет от TEDxBG!

С уважение искаме да ви поканим като специален гост на конференцията TEDxBG 2013. Надяваме се, че заедно с избраните от нас лектори ще успеем да ви вдъхновим с достатъчно качествени идеи за цялата предстояща година.

TEDxBG 2013 е празненство на идеите, които си заслужава да се споделят. Събитието ще се проведе на 12-и и 13-и януари в София. Конференцията ще е през първия ден, в Софийската опера и балет. Събрали сме най-вълнуващите ни запознанства през последната година и сме се опитали да извадим истинските истории и големите идеи от тях. Извън сцената сме създали идеалната обстановка за попиване на идеи – изненадваща кулинария, работилници, изложби и потенциал за нови контакти. А след събитието – безплатна постановка на “Мадам Бътерфлай” и афтърпарти. Вторият ден ще включва различни уъркшопи и възможности за доброволчество, за които ще получите информация по email по-късно. За повече информация посетете http://tedxbg.org/tedx2013/.

Вашите безплатни билети можете да намерите тук:

%s

Ще ви очакваме с нетърпение на 12-и януари, но ако не можете да присъствате, моля отговорете на този email.",
		'paid' => "Привет от TEDxBG!

С уважение искаме да ви поканим на конференцията TEDxBG 2013. Надяваме се, че заедно с избраните от нас лектори ще успеем да ви вдъхновим с достатъчно качествени идеи за цялата предстояща година.

TEDxBG 2013 е празненство на идеите, които си заслужава да се споделят. Събитието ще се проведе на 12-и и 13-и януари в София. Конференцията ще е през първия ден, в Софийската опера и балет. Събрали сме най-вълнуващите ни запознанства през последната година и сме се опитали да извадим истинските истории и големите идеи от тях. Извън сцената сме създали идеалната обстановка за попиване на идеи – изненадваща кулинария, работилници, изложби и потенциал за нови контакти. А след събитието – безплатна постановка на  „Мадам Бътерфлай“ и афтърпарти. Вторият ден ще включва различни уъркшопи и възможности за доброволчество, за които ще получите информация по email по-късно. За повече информация посетете http://tedxbg.org/tedx2013/.

В последните години билетите се разпродаваха за часове и не всички, които искаха да дойдат на конференцията, успяваха. Вашето присъствие е важно за нас, затова сме ви резервирали билет.

Вашите билети може да закупите от тук:

%s

Кодът ще е валиден три дни от датата на получаването на този мейл.

Ще ви очакваме с нетърпение на 12-и януари.",
		'friends' => "Здравейте приятели,

С уважение искаме да ви поканим на конференцията TEDxBG 2013. Надяваме се, че заедно с избраните от нас лектори ще успеем да ви вдъхновим с достатъчно качествени идеи за цялата предстояща година.

TEDxBG 2013 е празненство на идеите, които си заслужава да се споделят. Събитието ще се проведе на 12-и и 13-и януари в София. Конференцията ще е през първия ден, в Софийската опера и балет. Събрали сме най-вълнуващите ни запознанства през последната година и сме се опитали да извадим истинските истории и големите идеи от тях. Извън сцената сме създали идеалната обстановка за попиване на идеи – изненадваща кулинария, работилници, изложби и потенциал за нови контакти. А след събитието – безплатна постановка на  „Мадам Бътерфлай“ и афтърпарти. Вторият ден ще включва различни уъркшопи и възможности за доброволчество, за които ще получите информация по email по-късно. За повече информация посетете http://tedxbg.org/tedx2013/.

В последните години билетите се разпродаваха за часове и не всички, които искаха да дойдат на конференцията, успяваха. Вашето присъствие е важно за нас, затова сме ви резервирали билет.

Вашия билет можете да закупите от тук:

%s

Кодът ще е валиден три дни от датата на получаването на този мейл.

Ще ви очакваме с нетърпение на 12-и януари.",
		'ping' => "Здрасти,

Зоркият ни брояч на билети забеляза, че имате покана или резервация за билет за TEDxBG, но не сте ги използвали докрай. Много ще ни улесните, ако по-скоро изплзвате поканите си или закупите билетите си.

Не се притеснявайте, ако имате повече от един билет няма нужда да финализирате имената на посетителите сега. Ще имате достатъчно време да го направите докато дойде време за събитието.

Ако пък имате някакви технически проблеми, винаги може да ни пишете на hello@tedxbg.org и ние с радост ще ви помогнем.

За момента сте използвали %s от общо %s билета. Може да се регистрирате през ей-този линк:

%s

До скоро!",
		'sponsor' => "Здравей,

Като наш почетен спонсор (или приятел и партньор на наш спонсор) имаме удоволствието да те поканим да присъстваш на TEDxBG 2013.

За да се регистрираш, използвай линка по-долу:

%s

Ако имаш каквито е затруднения при регистрация или още въпроси, винаги може да ни намериш на hello@tedxbg.org.",
	);

	var $signature = "Поздрави,

Алек, Ники, Теди и Яна
организатори на TEDxBG";

	/**
	 * @synposis ticket-post-id csv-filename [--test]
	 * @subcommand add-reservations
	 */
	function add_reservations( $args, $assoc_args ) {
		list( $post_id, $file_name ) = $args;
		$test_mode = isset( $assoc_args['test'] );
		if ( 'tix_ticket' != get_post_type( $post_id ) ) {
			WP_CLI::error( "There's no ticket with post_id '$post_id'." );
		}
		$this->loop_reservations( $file_name, function( $reservation ) use ( $post_id, $test_mode ) {
			global $camptix;
			$test_text = $test_mode? 'test' : '';
			WP_CLI::line( "Creating a $test_text reservation for '{$reservation['name']}' for {$reservation['quantity']} tickets." );
			if ( !$test_mode ) {
				$camptix->create_reservation( $post_id, $reservation['name'], $reservation['quantity'] );
			}
		});
	}

	function loop_reservations( $file_name, $f ) {
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
			call_user_func( $f, $reservation );
		}
	}

	public function get_reservation_by_name( $name ) {
		global $camptix;
		$reservations = $camptix->get_all_reservations();
		foreach( $reservations as $token => $reservation ) {
			if ( $name == $reservation['name'] ) {
				return $reservation;
			}
		}
		return null;
	}

	public function get_reservation_link_by_name( $name ) {
		global $camptix;
		$reservation = $this->get_reservation_by_name( $name );
		if ( !$reservation ) {
			return null;
		}
		return $camptix->get_reservation_link( $reservation['id'], $reservation['token'] );
	}

	function send_free_invitations_email( $args, $assoc_args ) {
		list( $file_name, $type ) = $args;
		$test_mode = isset( $assoc_args['test'] );
		if ( !isset( $this->texts[$type] ) ) {
			WP_CLI::error( "Invalid e-mail type: $type");
		}
		$self = $this;
		$text = $this->texts[$type] . "\n\n" . $this->signature;
		$this->loop_reservations( $file_name, function( $reservation ) use ( $self, $test_mode, $text, $signature ){
			$link = $self->get_reservation_link_by_name( $reservation['name'] );
			if ( $test_mode ) {
				$reservation['email'] = 'nb@nikolay.bg';
			}
			if ( !$link ) {
				WP_CLI::line( "Skipping {$reservation['name']}, because name wasn't found." );
				return;
			}
			WP_CLI::line( "Sending an e-mail to '{$reservation['email']}' for {$reservation['quantity']} tickets." );
			wp_mail( $reservation['email'], 'Вашата покана за TEDxBG 2013', sprintf( $text, $link) );
			if ( $test_mode ) {
				die;
			}
		});
	}

	/**
	 * @subcommand clear-reservations
     */
	function clear_reservations() {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'tix_reservation%' " );
	}

	function ping_unused_reservations( $args, $assoc_args ) {
		list( $file_name ) = $args;
		$test_mode = isset( $assoc_args['test'] );
		$self = $this;
		$this->loop_reservations( $file_name, function( $reservation ) use ( $self, $test_mode ) {
			global $camptix;
			$real_reservation = $self->get_reservation_by_name( $reservation['name'] );
			if ( !$real_reservation ) {
				echo "Not found: {$reservation['name']}\n";
				return;
			}
			$quantity = $real_reservation['quantity'];
			$used = $camptix->get_purchased_tickets_count( $real_reservation['ticket_id'], $real_reservation['token'] );
			if ( $quantity == $used ) {
				return;
			}
			$text = $self->texts['ping'] . "\n\n" . $self->signature;
			$link = $camptix->get_reservation_link( $real_reservation['id'], $real_reservation['token'] );
			WP_CLI::line( "Sending an e-mail to '{$reservation['email']}' for {$reservation['quantity']} tickets, $used used." );
			$email = $test_mode? 'nb@nikolay.bg' : $reservation['email'];
			wp_mail( $email, 'Неизползвани билети/покани за TEDxBG', sprintf( $text, $used, $quantity, $link ) );
			if ( $test_mode ) {
				die;
			}
		} );
	}

	function get_unused_paid( $args, $assoc_args ) {
		list( $file_name ) = $args;
		$self = $this;
		$total_unused = 0;
		$this->loop_reservations( $file_name, function( $reservation ) use ( $self, $test_mode, &$total_unused ) {
			global $camptix;
			$real_reservation = $self->get_reservation_by_name( $reservation['name'] );
			if ( !$real_reservation ) {
				echo "Not found: {$reservation['name']}\n";
				return;
			}
			$quantity = $real_reservation['quantity'];
			$used = $camptix->get_purchased_tickets_count( $real_reservation['ticket_id'], $real_reservation['token'] );
			if ( $quantity == $used ) {
				return;
			}
			$total_unused += ($quantity - $used);
			$post_id = $real_reservation['ticket_id'];
			echo "$post_id {$real_reservation['name']} $used/$quantity {$real_reservation['token']}\n";
			$res = delete_post_meta( $post_id, 'tix_reservation', $real_reservation );
			var_dump( $res );
		} );
		$post_id = 1438;
		$ticket_quantity = intval( get_post_meta( $post_id, 'tix_quantity', true ) );
		echo "TQ: $ticket_quantity\n";
		update_post_meta( $post_id, 'tix_quantity', $ticket_quantity );
		echo "Total unused: $total_unused\n";
	}
}

WP_CLI::add_command( 'camptix', 'Camptix_Command' );

