<?php
/**
 * @file
 * Contains \Drupal\registration_form\Form\Multistep\ValidateEmail.
 */

namespace Drupal\registration_form\Controller;

use Drupal\registration_form;
use Drupal;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ValidateEmail {

 /**
  * {@inheritdoc}.
  */
	public function ValidateToken ($token) {
		$content = array();
		$confirm_email = db_select('registration_form','rf')
										->fields('rf',array('id' ,'random_string'))
										->condition('random_string', $token)
										->execute()
										->fetchObject();

		if($confirm_email) {
			db_query("UPDATE registration_form SET confirm_account = 1 WHERE random_string = '$token'")->execute();
			//drupal_set_message(t('Your Account is Succesfully validated . Please click on next button for further instructions.'));
		}
		return new RedirectResponse(Drupal::url('registration_form.multistep_two',array('id' => $confirm_email->id)));
	}
}
