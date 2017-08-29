<?php
/**
 * @file
 * Contains \Drupal\registration_form\Form\Multistep\MultistepOneForm.
 */

namespace Drupal\registration_form\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;

class MultistepOneForm extends MultistepFormBase {

	/**
	 * {@inheritdoc}.
	 */
	public function getFormId() {
		return 'multistep_form_one';
	}

	/**
	 * {@inheritdoc}.
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form = parent::buildForm($form, $form_state);

		$form['first_name'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('First Name'),
			'#required' => TRUE,
			'#default_value' => $this->store->get('first_name') ? $this->store->get('first_name') : '',
		);

		$form['middle_name'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Middle Name'),
			'#required' => TRUE,
			'#default_value' => $this->store->get('middle_name') ? $this->store->get('middle_name') : '',
		);

		$form['last_name'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Last Name'),
			'#required' => TRUE,
			'#default_value' => $this->store->get('last_name') ? $this->store->get('last_name') : '',
		);

		$form['email'] = array(
			'#type' => 'email',
			'#title' => $this->t('Email'),
			'#required' => TRUE,
			'#default_value' => $this->store->get('email') ? $this->store->get('email') : '',
		);

		$form['phone'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Phone Number'),
			'#required' => TRUE,
			'#default_value' => $this->store->get('phone') ? $this->store->get('phone') : '',
		);

		$form['street1'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Street 1'),
			'#default_value' => $this->store->get('street1') ? $this->store->get('street1') : '',
		);

		$form['street2'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Street 2'),
			'#default_value' => $this->store->get('street2') ? $this->store->get('street2') : '',
		);

		$form['city'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('City'),
			'#default_value' => $this->store->get('city') ? $this->store->get('city') : '',
		);

		$form['state'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('State'),
			'#default_value' => $this->store->get('state') ? $this->store->get('state') : '',
		);

		$form['zip'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Zip'),
			'#default_value' => $this->store->get('zip') ? $this->store->get('zip') : '',
		);

		$form['dob'] = array(
			'#type' => 'date',
			'#title' => $this->t('DOB'),
			'#default_value' => $this->store->get('dob') ? $this->store->get('dob') : '',
		);

		$form['actions']['submit']['#value'] = $this->t('Submit');
		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state) {
		if (strlen($form_state->getValue('phone')) < 10) {
			$form_state->setErrorByName('phone', $this->t('Mobile number is too short.'));
		}

		$email = $form_state->getValue('email');
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$form_state->setErrorByName('phone', $this->t('Invalid email format'));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->store->set('first_name', $form_state->getValue('first_name'));
		$this->store->set('middle_name', $form_state->getValue('middle_name'));
		$this->store->set('last_name', $form_state->getValue('last_name'));
		$this->store->set('email', $form_state->getValue('email'));
		$this->store->set('phone', $form_state->getValue('phone'));
		$this->store->set('street1', $form_state->getValue('street1'));
		$this->store->set('street2', $form_state->getValue('street2'));
		$this->store->set('city', $form_state->getValue('city'));
		$this->store->set('state', $form_state->getValue('state'));
		$this->store->set('zip', $form_state->getValue('zip'));
		$this->store->set('dob', $form_state->getValue('dob'));

		//Generating random string
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString     = '';
		$length           = 10;
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		//insert the variables into the table
		$values = array(
			'first_name'    => $this->store->get('first_name'),
			'middle_name'   => $this->store->get('middle_name'),
			'last_name'     => $this->store->get('last_name'),
			'email'         => $this->store->get('email'),
			'phone'         => $this->store->get('phone'),
			'street1'       => $this->store->get('street1'),
			'street2'       => $this->store->get('street2'),
			'city'          => $this->store->get('city'),
			'state'         => $this->store->get('state'),
			'zip'           => $this->store->get('zip'),
			'dob'           => $this->store->get('dob'),
			'random_string' => $randomString,
		);
		$insert = db_insert('registration_form')
			->fields(array(
				'first_name'    => $values['first_name'],
				'middle_name'   => $values['middle_name'],
				'last_name'     => $values['last_name'],
				'email'         => $values['email'],
				'phone'         => $values['phone'],
				'street1'       => $values['street1'],
				'street2'       => $values['street2'],
				'city'          => $values['city'],
				'state'         => $values['state'],
				'zip'           => $values['zip'],
				'dob'           => $values['dob'],
				'random_string' => $values['random_string'],
			))
			->execute();

		$mailManager            = \Drupal::service('plugin.manager.mail');
		$module                 = 'registration_form';
		$key                    = 'registerMail';
		$to                     = $this->store->get('email');
		$params['subject']      = $form_state->getValue('subject');
		$params['message']      = $form_state->getValue('message');
		$params['randomstring'] = $randomString;
		$langcode               = \Drupal::currentUser()->getPreferredLangcode();
		$send                   = TRUE;
		$mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

		drupal_set_message('A Mail has been sent. kindly click on the link to succesfully authenticate.', 'status');

		//$form_state->setRedirect('registration_form.validate',array('token' => $randomString));
	}
}
