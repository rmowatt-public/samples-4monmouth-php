<?php
Zend_Loader::loadClass('FM_Components_Util_TextAd');
Zend_Loader::loadClass('FM_Components_Organization');
class FM_Components_EmailFormatter {

	public static function forumComment($post, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$controller = (!$sport->getSlug()) ? FM_Components_Organization::getTypeController($sport->getType()) . '/' . $id : '';
		$str = '<table>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Email : ' . $post['email']. '</td></tr>';
		$str .= '<tr><td>Message : ' . $post['message']. '</td></tr>';
		$str .= '<tr><td>Date : ' . $post['date'] . '</td></tr>';
		$str .= '<tr><td>View This Miniweb @ <a href="http://4monmouth.com/'. $controller . '">http://4monmouth.com/'. $controller .'</a></td></tr>' ;
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}

	public static function contactUs($post) {

		$str = '<table>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Email : ' . $post['email']. '</td></tr>';
		$str .= '<tr><td>Message : ' . $post['message']. '</td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		//mail('colonius_sol@hotmail.com', 'contact us', $str);
		return $str;

	}

	public static function feedback($post) {
		$str = '<table>';
		$str .= '<tr><td>Your feedback has been Submitted. Thank you.</td></tr>';
		$str .= '<tr><td>Email : ' . $post['email']. '</td></tr>';
		$str .= '<tr><td>Feedback : ' . $post['feedback']. '</td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		//mail('colonius_sol@hotmail.com', 'contact us', $str);
		return $str;

	}

	public static function formatSiteEmail($post) {
		$str = '<table>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Email : ' . $post['email']. '</td></tr>';
		$str .= '<tr><td>Message : ' . $post['message']. '</td></tr>';
		//$str .= '<tr><td></td></tr>' ;
		$str .= '</table>';
		return $str;
	}

	public static function sendSportsEvent($post, $time, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$str = '<table>';
		$str .= '<tr><td><h2>A New Event Has Been Posted By ' . $sport->getName()  . '</h2></td></tr>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Review : ' . $time . '</td></tr>';
		$str .= '<tr><td>Location : ' . $post['location']. '</td></tr>';
		$str .= '<tr><td>Description : ' . $post['description']. '</td></tr>';
		$str .= '<tr><td><h4>View All ' . $sport->getName() . '\'s Events at <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></h4></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';

		return $str;
	}

	public static function reviewEmail($post) {
		$org = new FM_Components_Organization(array('id'=>$post['orgId']));
		$id = ($org->getSlug()) ? '/client/' . $org->getSlug() : $org->getLink();
		$str = '<table>';
		$str .= '<tr><td><h2>Your review has been submitted to ' . $org->getName()  . '</h2></td></tr>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Review : ' . $post['testimonial']. '</td></tr>';
		$str .= '<tr><td><h4>View All ' . $org->getName() . '\'s Reviews at <a href="http://4monmouth.com'. $id .'">http://4monmouth.com'. $id .'</a></h4></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';

		return $str;
	}

	public static function reviewEmailAdmin($post) {
		$org = new FM_Components_Organization(array('id'=>$post['orgId']));
		$id = ($org->getSlug()) ? '/client/' . $org->getSlug() : $org->getLink();
		$str = '<table>';
		$str .= '<tr><td><h2>A review has been submitted to ' . $org->getName()  . '</h2></td></tr>';
		$str .= '<tr><td>Name : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Review : ' . $post['testimonial']. '</td></tr>';
		$str .= '<tr><td><h4>View and Administrate All ' . $org->getName() . '\'s Reviews at <a href="http://4monmouth.com'. $id .'">http://4monmouth.com'. $id .'</a></h4></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}


	public static function updatePassword($post, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$str = '<table>';
		$str .= '<tr><td><h2>Your Password Has Been Changed</h2></td></tr>';
		$str .= '<tr><td>Old Password : ' . $post['old'] . '</td></tr>';
		$str .= '<tr><td>New Password : ' . $post['newPwd']. '</td></tr>';
		$str .= '<tr><td>View This Miniweb @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}
	
	
	public static function updatePasswordNoOrg($post) {
		$str = '<table>';
		$str .= '<tr><td><h2>Your Password Has Been Changed</h2></td></tr>';
		$str .= '<tr><td>Old Password : ' . $post['old'] . '</td></tr>';
		$str .= '<tr><td>New Password : ' . $post['newPwd']. '</td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}

	public static function createAccountRequestLetter($post, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$str = '<table>';
		$str .= '<tr><td><h2>Account Request</h2></td></tr>';
		$str .= '<tr><td>Organization Name : ' . $sport->getName() . '</td></tr>';
		$str .= '<tr><td>Your Name : ' . $post['name']. '</td></tr>';
		if(array_key_exists('note', $post) && $post['note'] != '') {
			$str .= '<tr><td>Your Note : ' . $post['note']. '</td></tr>';
		}
		$str .= '<tr><td><h4>Your request has been submitted. You will be contacted shortly.</h4></td></tr>';
		$str .= '<tr><td>View This Miniweb @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}

	public static function createAdminAccountRequestLetter($post, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$str = '<table>';
		$str .= '<tr><td><h2>Account Has Been Requested</h2></td></tr>';
		$str .= '<tr><td>Name of Requestee : ' . $post['name'] . '</td></tr>';
		$str .= '<tr><td>Email of Requestee : ' . $post['email']. '</td></tr>';
		if($post['note'] != '') {
			$str .= '<tr><td>Note : ' . $post['note']. '</td></tr>';
		}
		$str .= '<tr><td><h4>You do not have to approve this request. To add user please add them using the email provided at your admin panel @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></h4></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';
		return $str;
	}

	public static function createPasswordLetter($user, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		return '
				<h2>This is an automated message. Please do not respond.</h2>
				<p>user name : ' . $user->getUserName() . '<br />
				password : ' . $user->getPassword() . 
				'<br />View This Miniweb @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></p>' .
				'<p><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></p>' .
				FM_Components_Util_TextAd::getRandom(4);
	}

	public static function sendSportEmail($comment, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		$str = '<table>';
		$str .= '<tr><td><h2>An Email From ' . $sport->getName() . '</h2></td></tr>';
		$str .= '<tr><td>' . $comment . '</td></tr>';
		$str .= '<tr><td>Visit Us At @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></td></tr>';
		$str .= '<tr><td><div style="color:green;padding-top:1em;">The 4Monmouth Team</div></td></tr>' ;
		$str .= '</table><p>';
		$str .= FM_Components_Util_TextAd::getRandom(4);
		$str .= '</p>';

		return $str;
	}


	public static function createConfirmLetter($args, $sport) {
		$id = ($sport->getSlug()) ? 'client/' . $sport->getSlug() : '' . $sport->getId();
		return '
				<h2>This is an automated message. Please do not respond.</h2>
				<p>user name : ' . $args['uname'] . '<br/>
				password : ' . $args['pwd'] . 
				'<br />View This Miniweb @ <a href="http://4monmouth.com/'. $id .'">http://4monmouth.com/'. $id .'</a></p>' .
				FM_Components_Util_TextAd::getRandom(4);
	}

}