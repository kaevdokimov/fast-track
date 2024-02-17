<?php

namespace App\Tests\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback!');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(3, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Санкт-Петербург');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Санкт-Петербург 2023');
        $this->assertSelectorExists('div:contains("There are 4 comments.")');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/conference/sankt-peterburg-2023');
        $client->submitForm('Submit', [
            'comment[author]' => 'Козьма Прутков',
            'comment[text]' => 'Что скажут о тебе другие, коли ты сам о себе ничего сказать не можешь?',
            'comment[email]' => 'kozma.prutkov@ya.ru',
            'comment[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 5 comments")');
    }
}
