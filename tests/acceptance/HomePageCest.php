<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class HomePageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function validateHomePageContent(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to Futuristic House Design');
    }

    public function validateHomePageAboutLink(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('About', 'a');
    }

    public function validateHomePageLoginLink(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Login', 'a');
    }
}