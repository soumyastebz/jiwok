<?php
// function to generate the discount code for geonaute



function get_discode($seed_length=8) {

	$seed_length=8;

    $seed = "GIFTJIWOKREUBROPROGRAMMERSP8J12V2K9REUBRO1357JIWOK";

    $str = 'DC';

    srand((double)microtime()*1000000);

    for ($i=0;$i<$seed_length;$i++) {

        $str .= substr ($seed, rand() % 48, 1);

    }

	return $str;

}



?>