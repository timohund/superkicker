<?php
$I = new WebGuy($scenario);
$I->wantTo('Die Willkommensseite sehen und ein Match anlegen');
$I->canSeeInTitle('Willkommen im Superkicker Tippspiel');
$I->seeLink('Matches','/match/edit');
$I->click('#matches');
$I->canSee('Heim');
$I->canSee('Gast');
$I->canSee('BVB');
// i an see the match day
$I->canSee('34');
$I->click('Start');