<?php

function deal_cards( $number_of_people = 2, $number_of_cards = 52 ){
	// all card types
	$card_types = array("A","2","3","4","5","6","7","8","9","X","J","Q","K");
	// all card colors
	$card_colors = array("S","H","D","C");

	// one card deck
	$cards = array();

	// loop types and colors and generate deck
	for( $i=0; $i<count($card_types); $i++ ):
		for( $j=0; $j < count( $card_colors ); $j++ ):
			$cards[] = sprintf(
				"%s-%s",
				$card_colors[$j],
				$card_types[$i]
			);
		endfor;
	endfor;

	// randomize it
	shuffle( $cards );

	// slice amount of cards that will be dealed
	$cards = array_slice($cards,0,$number_of_cards);
	// put dealed cards here
	$dealed_cards = array();

	// create row for each person
	for( $i=0;$i<$number_of_people;$i++):
		$dealed_cards[$i] = array();
	endfor;

	// current person index
	$people_index = 0;

	// loop while there are no more cards left
	while( count($cards) > 0 ) :

		// shift card and put it into current person's row
		$dealed_cards[$people_index][] = array_shift( $cards );

		// select next person
		$people_index++;

		// person index is out of bounds, start again from the first one
		if( $people_index === $number_of_people ) :
			$people_index = 0;
		endif;

	endwhile;

	// comma separate values in each person's row
	for( $i=0;$i<$number_of_people;$i++):
		$dealed_cards[$i] = join(",",$dealed_cards[$i]);
	endfor;

	// separate rows with newline char
	return join("\n",$dealed_cards);
}

function is_valid_number( $number ){
	return !!preg_match('/^\d+$/',$number);
}

if( isset($_GET["action"]) && $_GET["action"] == "calculate" ):

	$number_of_people = trim( $_GET["number_of_people"] );
	$number_of_cards = trim( $_GET["number_of_cards"] );

	if( !is_valid_number( $number_of_people ) || $number_of_people < 1 ) :

		die("Number of people has invalid value.");
		
	endif;

	if( !is_valid_number( $number_of_cards ) || $number_of_cards < 1 ) :

		die("Number of cards has invalid value.");
		
	endif;

	header("Content-type:text/plain");
	echo deal_cards( (int)$number_of_people, (int)$number_of_cards );

endif;