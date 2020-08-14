<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class HomePageCest
{
    public function homePageContent(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('welcome to the home page');
    }
}
