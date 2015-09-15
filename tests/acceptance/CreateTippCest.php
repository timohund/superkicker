<?php

class CreateTippCest
{
    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function reset(SuperkickerWebGuy $I) {
        $I->resetSystem();
        $_SESSION = array();
        $I->resetCookie('PHPSESSID');
    }

    protected function loginAs(SuperkickerWebGuy $I, $username, $password) {
        $I->amOnPage("login");
        $I->fillField('#username', $username);
        $I->fillField('#password', $password);
        $I->submitForm('form',array());
    }

    /**
     * @param SuperkickerWebGuy $I
     */
    protected function logout(SuperkickerWebGuy $I) {
        $I->click("#logout");
        $I->see('Login');
        $I->seeElement('div#login');
    }

    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function loginAsAdmin(SuperkickerWebGuy $I) {
        $this->loginAs($I, 'admin', 'password');
    }

    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function loginAsTest(SuperkickerWebGuy $I) {
        $this->loginAs($I, 'test', 'test');
    }

    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function createClub(SuperkickerWebGuy $I) {
        // create a club
        $I->wantTo('Club anlegen');
        $I->canSeeInTitle('Willkommen im Superkicker Tippspiel');
        $I->seeLink('Vereine','/admin/club/edit');
        $I->click('#clubs');

        $I->fillField('#club_new_name','BVB');
        $I->attachFile('#logo_new','smile.png');
        $I->click('#clubCreate input[type=submit]');

        $I->fillField('#club_new_name','Fc Bayern');
        $I->attachFile('#logo_new','smile2.png');
        $I->click('#clubCreate input[type=submit]');
    }

    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function createTournament(SuperkickerWebGuy $I) {
        $I->wantTo('Turnier anlegen');
        $I->seeLink('Turniere','/admin/tournament/edit');
        $I->click('#tournaments');
        $I->fillField('#tournaments_new_name','1. Bundesliga');
        $I->fillField('#tournaments_new_matchdays', 32);
        $I->submitForm('#tournamentCreate',array());
    }

    /**
     * @param \SuperkickerWebGuy $I
     */
    protected function createMatch(SuperkickerWebGuy $I) {
        $I->wantTo('Match anlegen');
        $I->seeLink('Spielplan','/admin/match/edit/1');
        $I->click('#matches');
        $I->canSee('BVB');

        $I->selectOption('#match_new_home',1);
        $I->selectOption('#match_new_guest',2);
        $I->fillField('#match_new_day',1);
        $I->fillField('#match_new_date','13.05.2030 08:11');
        $I->submitForm('#matchCreate',array());
    }

    /**
     * @param SuperkickerWebGuy $I
     */
    protected function registerTestUser(SuperkickerWebGuy $I) {
        $I->wantTo('Testuser registrieren');
        $I->amOnPage("register");
        $I->see("Registrieren");
        $I->fillField("#fos_user_registration_form_username","test");
        $I->fillField("#fos_user_registration_form_plainPassword_first","test");
        $I->fillField("#fos_user_registration_form_plainPassword_second","test");
        $I->fillField("#fos_user_registration_form_plainPassword_second","test");
        $I->fillField("#fos_user_registration_form_email","test@testdomain.de");
        $I->click("#_submit");
        $I->see("Glückwunsch test, Ihr Benutzerkonto ist jetzt bestätigt");
        $I->click("#logout");
    }

    /**
     * @before reset
     * @before loginAsAdmin
     * @before createClub
     * @before createTournament
     * @before createMatch
     * @param \SuperkickerWebGuy $I
     */
    public function createTip(SuperkickerWebGuy $I) {
        $I->seeLink('Tipps','/user/tipp/edit/1');
        $I->click('#tipps');
        $I->canSeeInTitle('Tipp abgeben');
        $I->canSeeElement("form");
        $I->canSee("BVB");
        $I->fillField('#match_1_home',1);
        $I->fillField('#match_1_guest',2);
        $I->submitForm('#tippCreate', array());
        $I->canSee('Deine Tipps wurden gespeichert');
    }

    /**
     * @before reset
     * @before registerTestUser
     * @before loginAsAdmin
     * @param \SuperkickerWebGuy $I
     */
    public function createTipGroupAndInviteTestUser(SuperkickerWebGuy $I) {
        $I->wantTo('Tippgemeindschaft anlegen');
        $I->seeLink('Gruppen', '/user/tipgroup/edit');
        $I->click('#tipgroups');
        $I->canSeeInTitle("Tippgemeindschaften verwalten");
        $I->canSeeElement("form");
        $I->fillField('#tipgroup_new_name', 'Toptipper');
        $I->submitForm('#tippGroupCreate', array());
        $I->canSee('Deine Tippgemeindschaft wurde gespeichert');
        $I->click('#tipgroups');
        $I->seeLink('Verwalten', '/user/tipgroup/manage_single/1');
        $I->click('#manage_single_group_1');
        $I->see("Einladen");
        $I->see("test");
        $I->selectOption("#userId","test");
        $I->click("#invite");
    }

    /**
    * @before reset
    * @before registerTestUser
    * @before loginAsAdmin
    * @param \SuperkickerWebGuy $I
    */
    public function canNotInviteUsersIntoGroupsOfOthers(SuperkickerWebGuy $I) {
        $I->wantTo('Einladen von Benutzer in Gruppen anderer verhindern');
        $I->seeLink('Gruppen', '/user/tipgroup/edit');
        $I->click('#tipgroups');
        $I->canSeeInTitle("Tippgemeindschaften verwalten");
        $I->canSeeElement("form");
        $I->fillField('#tipgroup_new_name', 'Toptipper');
        $I->submitForm('#tippGroupCreate', array());
        $I->canSee('Deine Tippgemeindschaft wurde gespeichert');
        $I->click('#tipgroups');
        $I->seeLink('Verwalten', '/user/tipgroup/manage_single/1');
        //logout admin
        $this->logout($I);
        $this->loginAsTest($I);

        //fake invitation request
        //@todo how to user without dev context here
        $I->sendAjaxPostRequest('/app_dev.php/user/tipgroup/invite_user',
                array('tipGroupId' => 1,'userId' => 2)
        );
        $I->see("You are not the admin of this group");
    }

    /**
     * @before reset
     * @before loginAsAdmin
     * @before createTipGroupAndInviteTestUser
     * @before logout
     * @before loginAsAdmin
     * @param \SuperkickerWebGuy $I
     */
    public function canNotAcceptInvitationsOfOtherUsers(SuperkickerWebGuy $I) {
        $I->wantTo("Verhindern das Einladungen anderer Benutzer akzeptiert werden");
        // try to accept the invitation of user test with user admin

        //@todo how to user without dev context here
        $I->sendAjaxGetRequest('/app_dev.php/user/tipgroup/accept_invidation/2');
        $I->see("You are not the invited used");
    }
}