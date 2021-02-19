<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class SmsService
{
    /**
     * @var \GuzzleHttp\Client
     */
	public $httpClient;

    /**
     * @var \GuzzleHttp\Psr7\Response
     */
	public $apiResponse;

    /**
     * SmsService constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @param int $to
     * @param string $message
     * @param null $sheduleTime
     * @return bool
     */
	public function sendMessage($to = null, $message = '', $sheduleTime = null)
	{
		$this->validateData($to, $message, $sheduleTime);

		$response = null;

		try {
			$response = $this->send($to, $message, $sheduleTime);

		} catch (Exception $e) {
			throw new Exception("Error Processing Request: {$e}");
		}

		if ($response->getStatusCode() != 200) {
			throw new Exception("Error Processing Request: {$response->getBody()}");
		}

		else {
			$this->apiResponse = $response;

			return true;
		}
	}

    /**
     * @param int $to
     * @param string $message
     * @param null $sheduleTime
     * @return \Psr\Http\Message\ResponseInterface
     */
	protected function send($to = null, $message = '', $sheduleTime = null)
	{
		return $this->httpClient->post(
			"https://mazinhost.com/smsv1/sms/api" .
			"?action=send-sms".
			"&api_key=" . config('services.mazinhost_sms.api_key') .
			"&to=" . $this->getFormattedPhoneNumber($to) .
			"&from=" . config('services.mazinhost_sms.sender_id') .
			"&sms=". $message .
            $sheduleTime != null ? "&schedule=". $sheduleTime : ''
		);
	}

    /**
     * @param int $number
     * @param string $message
     * @param null $date
     */
	protected function validateData($number = null, $message = '', $date = null)
	{
		if (
		    ! is_numeric($number) || strlen($number) < 10 || $message == ''
        ) throw new Exception("Error: Invalid data provided!");

		if ($date != null && strtotime($date) == false) throw new Exception("Error: Invalid date provided!");
	}

    /**
     * @param int $to
     * @return int
     */
    protected function getFormattedPhoneNumber(int $to)
    {
        return $to;
    }
}
