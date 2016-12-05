<?php
namespace app\components\mailchimp;

use app\components\base\Application;

class StatisticManager
{
	public function getUnsubscribedEmails()
	{
		$activities = $this->findActivityEmailsByEvent('unsubscribe');

		return array_map(
			function($activity) {
				return $activity->email;
			},
			$activities
		);
	}

	public function getActivityStatisticByEmail($email)
	{
		$activities = $this->findActivitiesByEmail($email);
		$reportsIds = [];
		$activityMap = [];

		foreach ($activities as $activity) {
			$reportsIds[] = $activity->reportId;
			$activityMap[$activity->report_id][] = $activity;
		}

		$reports = $this->findReportsByIds(array_unique($reportsIds));

		return [
			'reports' => $reports,
			'activity' => $activityMap
		];
	}

	protected function findActivitiesByEmail($email)
	{
		$dql = 'SELECT a FROM Entity\MailchimpActivity a WHERE '
            .'a.email=:email '
            . 'ORDER BY a.timestamp ASC';

        $query = $this
        	->getApp()
            ->getEm()
            ->createQuery($dql);

        $query->setParameter('email', $email);

        return $query->getResult();
	}

	protected function findActivityEmailsByEvent($event)
	{
		$dql = 'SELECT a FROM Entity\MailchimpActivity a WHERE '
            .'a.event=:event '
            . 'GROUP BY a.email';

        $query = $this
        	->getApp()
            ->getEm()
            ->createQuery($dql);

        $query->setParameter('event', $event);

        return $query->getResult();
	}

	protected function findReportsByIds($reportsIds)
	{
		if (count($reportsIds) == 0) {
			return [];
		}

		$dql = 'SELECT r FROM Entity\MailchimpReport r WHERE '
            .'r.id IN (' . implode(',', $reportsIds) . ') '
            . 'ORDER BY r.send_timestamp DESC';

        $query = $this
        	->getApp()
            ->getEm()
            ->createQuery($dql);

        return $query->getResult();
	}

	protected function getApp()
	{
		return Application::getInstance();
	}
}