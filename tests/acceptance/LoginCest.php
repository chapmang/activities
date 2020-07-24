<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function loginSuccessfully(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'geoff.chapman');
        $I->fillField('password', 'test');
        $I->click('Sign in');
        $I->see('activities');
    }

}
