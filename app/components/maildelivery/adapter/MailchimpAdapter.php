<?php
namespace app\components\maildelivery\adapter;

use \DrewM\MailChimp\MailChimp;

class MailchimpAdapter extends \app\components\base\AdapterAbstract
{
	const LANG_EN = 'en';
	const VISIBILITY_PUB = 'pub';
	const MEMBER_SUBSCRIBED = 'subscribed';

	protected $api;

	protected function init()
	{
		$apiKey = $this->getConfigParam('api_key');
		/*
		 * @FIXME: do not use hardcoded key
		 */

		$this->setApi(new MailChimp($apiKey));
	}

	protected function setApi($api)
	{
		$this->api = $api;

		return $this;
	}

	protected function getApi()
	{
		return $this->api;
	}

	public function getTemplatesList()
	{
		if (!$response = $this->getApi()->get('/templates')) {
			return;
		}

		return $response['templates'];
	}

	public function sendCampaign($campaignId)
	{
		if (!$response = $this->getApi()->post('/campaigns/' . $campaignId . '/send')) {
			return;
		}

		return $response;
	}

	public function createCampaign($templateId, $title, $recipients)
	{
		$list = $this->createRecipientsList($title, $recipients);
		
		$data = [
			'recipients' => [
				'list_id' => $list['id'],
				'list_name' => $title,
			],
			'settings' => [
				'subject_line' => '',
				'title' => '',
				'from_name' => '',
				'reply_to' => '',
				'use_conversation' => 'false',
				'to_name' => '',
				'folder_id' => '',
				'authenticate' => false,
				'auto_footer' => false,
				'inline_css' => false,
				'auto_tweet' => false,
				'auto_fb_post' => false,
				'fb_comments' => false,
				'template_id' => $templateId
			],
			'variate_settings' => [
				'winner_criteria' => '',
				'wait_time' => '',
				'test_size' => '',
				'subject_lines' => '',
				'send_times' => '',
				'from_names' => '',
				'reply_to_addresses' => ''
			],
			'tracking' => [
				'opens' => '',
				'html_clicks' => '',
				'text_clicks' => '',
				'goal_tracking' => '',
				'ecomm360' => '',
				'google_analytics' => '',
				'clicktale' => '',
				'salesforce' => [
					'campaign' => '',
					'notes' => ''
				]
			],
		];

		if (!$response = $this->getApi()->post('/campaigns', $data)) {
			return;
		}

		return $response;
	}

	public function createRecipientsList($title, $recipients)
	{
		if (!$list = $this->createList($title)) {
			return;
		}

		$members = [];

		foreach ($recipients as $recipient) {
			$email = $recipient['email'];

			$members[$email] = $this->addListMember(
				$list['id'],
				$email,
				$recipient['fields']
			);
		}

		return ['list' => $list, 'members' => $members];
	}

	public function addListMember($listId, $email, $fields = [])
	{
		$data = [
			'email_address' => $email,
			'merge_fields' => $fields,
			'status' => self::MEMBER_SUBSCRIBED,
		];

		$membersUrl = '/lists/' . $listId . '/members';

		if (!$response = $this->getApi()->post($membersUrl, $data)) {
			return;
		}

		return $response;
	}

	public function createList($title)
	{
		$data = [
			'name' => $title,
			'contact' => [
				'company' => $this->getConfigParam('company'),
				'address1' => $this->getConfigParam('company_address'),
				'address2' => '',
				'city' => $this->getConfigParam('company_city'),
				'state' => $this->getConfigParam('company_state'),
				'zip' => $this->getConfigParam('company_zip'),
				'country' => $this->getConfigParam('country'),
				'phone' => $this->getConfigParam('company_phone'),
			],
			'permission_reminder' => $title,
			'use_archive_bar' => true,
			'campaign_defaults' => [
				'from_name' => $this->getConfigParam('from_name'),
				'from_email' => $this->getConfigParam('from_email'),
				'subject' => $title,
				'language' => self::LANG_EN,
			],
			'notify_on_subscribe' => $this->getConfigParam('notifications_email'),
			'notify_on_unsubscribe' => $this->getConfigParam('notifications_email'),
			'email_type_option' => false,
			'visibility'  => self::VISIBILITY_PUB,
		];

		if (!$response = $this->getApi()->post('lists', $data)) {
			return;
		}

		return $response;
	}

	public function getReport($reportId)
	{
		if (!$response = $this->getApi()->get('reports/' . $reportId)) {
			return;
		}

		return $response;
	}
	
	public function getReports($count = 100, $offset = 0)
	{
		if (!$response = $this->getApi()->get(
			'reports',
			[
				'count' => $count,
				'offset' => $offset,
			]
		)) {
			return;
		}

		return isset($response['reports']) ? $response['reports'] : [];
	}

	public function getReportActivity($reportId, $count = 1000, $offset = 0)
	{
		if (!$response = $this->getApi()->get(
			'reports/' . $reportId . '/email-activity',
			[
				'count' => $count,
				'offset' => $offset,
			]
		)) {
			return;
		}

		return isset($response['emails']) ? $response['emails'] : [];
	}

	public function getReportUnsubscribes($reportId, $count = 100, $offset = 0)
	{
		if (!$response = $this->getApi()->get(
			'reports/' . $reportId . '/unsubscribed',
			[
				'count' => $count,
				'offset' => $offset,
			]
		)) {
			return;
		}

		return isset($response['unsubscribes']) ? $response['unsubscribes'] : [];
	}

	public function getReportSendTo($reportId, $count = 100, $offset = 0)
	{
		if (!$response = $this->getApi()->get(
			'reports/' . $reportId . '/sent-to',
			[
				'count' => $count,
				'offset' => $offset,
			]
		)) {
			return;
		}

		return isset($response['sent_to']) ? $response['sent_to'] : [];
	}
}