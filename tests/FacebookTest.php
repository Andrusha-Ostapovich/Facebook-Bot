<?php

use PHPUnit\Framework\TestCase;
use Ostapovich\Facebook;

class FacebookTest extends TestCase
{
    protected Facebook $facebook;

    protected function setUp(): void
    {
        $this->facebook = new Facebook('YOUR_FACEBOOK_ACCESS_TOKEN');
    }

    public function testSetWebhook()
    {
        $data = ['hub_challenge' => 'CHALLENGE_STRING'];
        $result = $this->facebook->setWebhook($data);
        $this->assertEquals('CHALLENGE_STRING', $result);
    }

    public function testSetSecurityWebhook()
    {
        // Test with valid verification token and hub mode
        $data = [
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'VERIFY_TOKEN',
            'hub_challenge' => 'CHALLENGE_STRING'
        ];
        $result = $this->facebook->setSecurityWebhook($data, 'VERIFY_TOKEN');
        $this->assertEquals('CHALLENGE_STRING', $result);

        // Test with invalid verification token
        $result = $this->facebook->setSecurityWebhook($data, 'INVALID_TOKEN');
        $this->assertNull($result);

        // Test with invalid hub mode
        $data['hub_mode'] = 'unsubscribe';
        $result = $this->facebook->setSecurityWebhook($data, 'VERIFY_TOKEN');
        $this->assertNull($result);
    }

    public function testGetMessage()
    {
        $data = [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'message' => [
                                'text' => 'Hello, world!'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $result = $this->facebook->getMessage($data);
        $this->assertEquals('Hello, world!', $result);
    }

    public function testGetRefData()
    {
        $data = [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'postback' => [
                                'referral' => [
                                    'ref' => 'REFERRAL_DATA'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $result = $this->facebook->getRefData($data);
        $this->assertEquals('REFERRAL_DATA', $result);
    }

    public function testSetStartButton()
    {
        // Test without exceptions
        $this->expectNotToPerformAssertions();
        $this->facebook->setStartButton();
    }

    public function testGetFacebookUserId()
    {
        $data = [
            'entry' => [
                [
                    'messaging' => [
                        [
                            'sender' => [
                                'id' => '123456789'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $result = $this->facebook->getFacebookUserId($data);
        $this->assertEquals('123456789', $result);
    }

    public function testSendFacebookMessage()
    {
        // Test sending message to a Facebook user
        $response = $this->facebook->sendFacebookMessage('USER_ID', 'Hello, Facebook user!');
        $this->assertArrayHasKey('message_id', $response);
    }
}
