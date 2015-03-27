<?php
$I = new WebGuy($scenario);
$I->wantTo('Die Willkommensseite sehen und einen Tip anlegen');
$I->canSeeInTitle('Willkommen im Superkicker Tippspiel');
$I->seeLink('Tipp anlegen');
$I->click('Tipp anlegen');
$I->canSeeInTitle('Tipp abgeben');
$I->canSeeElement("form");
$I->canSee("Deine Tipps");
$I->canSee("BVB");
$I->fillField('#match_1_home',1);
$I->fillField('#match_1_guest',2);
$I->fillField('#match_2_home',3);
$I->fillField('#match_2_guest',4);
$I->submitForm('#tippCreate',
	array(
		'#foo' => 'bar',
		'match_1_home' => 1,
		'match_1_guest' => 2,
		'match_2_home' => 3,
		'match_2_guest' => 4
	)

);
$I->canSee('Deine Tipps wurden gespeichert');