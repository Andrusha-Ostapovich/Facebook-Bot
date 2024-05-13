<?php

namespace Ostapovich;

class Facebook
{
    public string $token;
    /**
     * Constructs a new instance of the class.
     *
     * @param string $token The token to be assigned to the object.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
    /**
     * Sets the webhook with the given data.
     *
     * @param array $data The data containing the hub challenge.
     * @return string|null The JSON-encoded hub challenge, or null if it is not set.
     */
    public function setWebhook(array $data): string
    {
        $hub_challenge = isset($data['hub_challenge']) ? $data['hub_challenge'] : null;
        return json_encode($hub_challenge);
    }
    /**
     * Sets the security webhook with the given data.
     *
     * @param array $data The data containing the hub mode, hub verify token, and hub challenge.
     * @param string $verify_token The verify token used to verify the webhook.
     * @return string|null The JSON-encoded hub challenge, or null if it is not set or the verification fails.
     */
    public function setSecurityWebhook(array $data, string $verify_token): ?string
    {

        $hub_mode = isset($data['hub_mode']) ? $data['hub_mode'] : null;

        $hub_verify_token = isset($data['hub_verify_token']) ? $data['hub_verify_token'] : null;

        $hub_challenge = isset($data['hub_challenge']) ? $data['hub_challenge'] : null;

        if (!empty($hub_mode) && $hub_mode == 'subscribe' && $hub_verify_token == $verify_token) {

            return json_encode($hub_challenge);
        }
    }
    /**
     * Retrieves the message text from the given array of data.
     *
     * @param array $data The array of data containing the message.
     * @return string The message text.
     */
    public function getMessage(array $data): string
    {
        return $data['entry'][0]['messaging'][0]['message']['text'];
    }
    /**
     * Retrieves the referral data from the given array of data.(after setStartButton)
     *
     * @param array $data The array of data containing the referral.
     * @return string The referral data.
     */
    public function getRefData(array $data): string
    {
        return $data['entry'][0]['messaging'][0]['postback']['referral']['ref'];
    }
    /**
     * Generates a deep link URL by concatenating the bot URL and reference data.
     *
     * @param string $bot_url The base URL of the bot.
     * @param string $ref_data The reference data to be appended to the URL.
     * @return string The generated deep link URL.
     */
    public function getDeepLink(string $bot_url, string $ref_data): string
    {
        return "{$bot_url}?ref={$ref_data}";
    }
    /**
     * Sets the start button for the Facebook Messenger page.
     *
     * @param string $version The version of the Messenger platform. Defaults to 19.0.
     * @return void
     */
    public function setStartButton(string $version = '19.0'): void
    {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => [
                    'get_started' => ['payload' => 'Start']
                ],

            ]
        ];

        file_get_contents("https://graph.facebook.com/v{$version}/me/messenger_profile?access_token={$this->token}", false, stream_context_create($options));
    }
    /**
     * Retrieves the Facebook user ID from the given array of data.
     *
     * @param array $data The array of data containing the user ID.
     * @return string The Facebook user ID.
     */
    public function getFacebookUserId(array $data): string
    {
        return $data['entry'][0]['messaging'][0]['sender']['id'];
    }
    /**
     * Sends a Facebook message to a specified user.
     *
     * @param string $facebook_user_id The ID of the Facebook user to send the message to.
     * @param string $message The content of the message to be sent.
     * @param string $version The version of the Facebook API to use. Defaults to '19.0'.
     * @return array The response from the Facebook API as an associative array.
     */
    public function sendFacebookMessage(string $facebook_user_id, string $message, string $version = '19.0'): array
    {
        $url = "https://graph.facebook.com/v{$version}/me/messages?access_token={$this->token}";

        $data = [
            'recipient' => ['id' => $facebook_user_id],
            'message' => ['text' => $message],
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data)
            ]
        ];
        $responce = file_get_contents($url, false, stream_context_create($options));
        return json_decode($responce, true);
    }
}
