<?php

require_once 'Day.php';
Zend_Loader::loadClass('FM_Models_FM_Events');

class FM_Components_Calendar_Month
{

	public $days;
	public $month;
	public $firstDay;
	public $lastDay;
	public $today;
	public $currentDayIndex = 0;
	private $_orgId = 0;

	public function __construct($month = 0, $year = 0, $orgId = 0, $frontpage = false)
	{
		$this->_orgId = $orgId;

		$date = '';

		if ((isset($month) && !empty($month)) && (isset($year) && !empty($year))) {
			if (($month > 0 && $month < 13) && $year > 0) {
				$date = new FM_Components_Calendar_Day($month, 1, $year, $orgId);
			}
		}

		$date = ($date)? $date : FM_Components_Calendar_Day::getToday($orgId);

		$this->today = FM_Components_Calendar_Day::getToday($orgId);
		$this->firstDay = new FM_Components_Calendar_Day($date->mon, 01, $date->year, $orgId);
		$this->lastDay = new FM_Components_Calendar_Day($date->mon + 1, 0, $date->year, $orgId);

		$month = array();

		for ($i = $this->firstDay->wday, $day = 1; $day <= $this->lastDay->mday; $i++) {
			$month[$i] = new FM_Components_Calendar_Day($date->mon, $day, $date->year, $orgId);
			if (
			$month[$i]->mday == $this->today->mday &&
			$month[$i]->mon == $this->today->mon &&
			$month[$i]->year = $this->today->year
			) {
				$month[$i]->setAsToday();
				$this->currentDayIndex = $i;
			}

			++$day;
		}

		$this->days = $month;
	}
	
	public static function getAll($orgId) {
		$model = new FM_Models_FM_Events();
		$events = $model->findAllOrgEvents($orgId);
		$e = array();
		foreach ($events as $event) {
			$e = $event;
			$e['time'] = self::getFormattedTime($event);
			$re[] = $e;
		}
		return $re;
	}
	
	public static function getEventAsArray($id) {
		$model = new FM_Models_FM_Events();
		$e = $model->findEventById($id);
		$e['time'] = self::getFormattedTime($event);
		$e['formattedDate'] = date('m-d-Y', strtotime($e['datetag']));
		return $e;
	}
	
	public static function getFormattedTime($event) {
		$str = '';
		if(($event['starttime'] == '' || $event['starttime'] == '00:00') && ($event['endtime'] == '' || $event['endtime'] == '00:00')) {
			$str = "All Day Event";
		} elseif(($event['starttime'] != '' && $event['starttime'] != '00:00')&& ($event['endtime'] == '' || $event['endtime'] == '00:00')) {
			$str = date('g:i A', strtotime($event['starttime'])) . ' - TBD';
		}
		elseif(($event['endtime'] != '' && $event['endtime'] != '00:00')&& ($event['starttime'] == '' || $event['starttime'] == '00:00')) {
			$str = 'Whenever - ' . date('g:i A', strtotime($event['endtime']));
		} else {
			$str = date('g:i A', strtotime($event['starttime'])) . ' - ' . date('g:i A', strtotime($event['endtime']));
		}
		return $str;
	}

	public function getToday()
	{
		return $this->days[$this->currentDayIndex];
	}

	public function getDaysWithEvents()
	{
		$events = array();
		foreach ($this->days as $day) {
			if ($day->hasEvents()) {
				$events[$day->tag] = $day;
			}
		}

		return $events;
	}

	public function getActiveEvents($today, $all = false)
	{
		$events = array();
		foreach ($this->days as $day) {
			if($all || $day->mday >= $today) {
				if ($day->hasEvents()) {
					$events[$day->tag] = $day;
				}
			}
		}

		return $events;
	}

	public function __toString()
	{
		$m = array();

		for ($i = 0; $i < $this->firstDay->wday; ++$i) {
			$m[$i] = 'empty';

		}

		$m = array_merge($m, $this->days);
		//added for 0 wday issue caused
		$lastDay = ($this->lastDay->wday == 0) ? "1" : $this->lastDay->wday;

		for ($i = $lastDay; $i < (7 - $lastDay); ++$i) {
			$m[] = 'empty';
		}

		$weeks = array_chunk($m, 7);

		$html = '
        <h6> '.$this->firstDay->month . ' - ' . $this->firstDay->year .'</h6>
		<table border="1" cellpadding="0" cellspacing="0" id="calendarTabTable">		
			<tr>
				<th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>';

		foreach ($weeks as $w) {
			$html .= '<tr>';
			foreach ($w as $d) {
				if ($d !== 'empty') {
					$html .= '<td';
					$html .= ($d->hasEvents())  ? ' class="full"' : ' ';
					$html .= '><div class="day">'. $d->mday . '</div>';
					$html .= $this->eventHTML($d);
					$html .= '</td>';
				} else {
					$html .= '<td>&nbsp;</td>';
				}
			}
			$html .= '</tr>';
		}

		$html .= '</table>';
		return $html;
	}

	public function eventHTML($day) {
		$html .= '<div class="events">';
		if($day->hasEvents()) {
			foreach ($day->getEvents() as $event) {
				$html .= '<div class="event"><a href="#"  class="event_view">' . $event['name'] . '</a>
							<div id="event_' . $event['eventId'] . '" class="event_details">
								<div class="event_name">' . $event['name'] . '</div>
								<div class="event_time"><strong>Time: </strong>' . $event['time'] . '</div>
								<div class="event_location"><strong>Location: </strong>' . $event['location'] .'</div>
								<div class="event_description"><strong>Description:</strong>'. $event['description'] .'</div>
							</div>
						</div>';
			}
		}
		$html .= '<div>';
		return $html;
	}
}
