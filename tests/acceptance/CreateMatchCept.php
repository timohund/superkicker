<?php
$I = new WebGuy($scenario);
$I->resetSystem();

// login
	$I->amOnPage("login");
	$I->fillField('#username',"admin");
	$I->fillField('#password',"password");
	$I->submitForm('form',array());

// create a club
	$I->wantTo('Club anlegen');
	$I->canSeeInTitle('Willkommen im Superkicker Tippspiel');
	$I->seeLink('Vereine','/admin/club/edit');
	$I->click('#clubs');

	$I->fillField('#club_new_name','BVB');
	$I->submitForm('#clubCreate',array());
	$I->fillField('#club_new_name','Fc Bayern');
	$I->submitForm('#clubCreate',array());

// create a match
	$I->wantTo('Match anlegen');
	$I->seeLink('Spielplan','/admin/match/edit');
	$I->click('#matches');
	$I->canSee('BVB');

	$I->fillField('#match_new_home',1);
	$I->fillField('#match_new_guest',2);
	$I->fillField('#match_new_day',1);
	$I->fillField('#match_new_date','13.05.2030 08:11');
	$I->submitForm('#matchCreate',array());

	$I->click('Home');


// create a tipp
	$I->wantTo('Tipp abgeben');
	$I->seeLink('Tipps','/user/tipp/edit');
	$I->click('#tipps');
	$I->canSeeInTitle('Tipp abgeben');
	$I->canSeeElement("form");
	$I->canSee("BVB");
	$I->fillField('#match_1_home',1);
	$I->fillField('#match_1_guest',2);
	$I->submitForm('#tippCreate',
			array(
					'match_1_home' => 1,
					'match_1_guest' => 2,
			)

	);
	$I->canSee('Deine Tipps wurden gespeichert');