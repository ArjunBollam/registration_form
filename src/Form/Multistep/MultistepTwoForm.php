<?php
/**
 * @file
 * Contains \Drupal\registration_form\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\registration_form\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MultistepTwoForm extends MultistepFormBase {

	/**
	 * {@inheritdoc}.
	 */
	public function getFormId() {
		return 'multistep_form_two';
	}

	/**
	 * {@inheritdoc}.
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form = parent::buildForm($form, $form_state);

		$form['actions']['previous'] = array(
			'#type' => 'link',
			'#title' => $this->t('Previous'),
			'#attributes' => array(
				'class' => array('button'),
			),
			'#weight' => 0,
			'#url' => Url::fromRoute('registration_form.multistep_one'),
		);

		$form['actions']['next'] = array(
			'#type' => 'link',
			'#title' => $this->t('Complete Questionnaire Form'),
			'#attributes' => array(
				'class' => array('button'),
			),
			'#weight' => 0,
			'#url' => Url::fromRoute('registration_form.multistep_three'),
		);

		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->store->set('age', $form_state->getValue('age'));
		$this->store->set('location', $form_state->getValue('location'));

		// Save the data
		parent::saveData();
		$form_state->setRedirect('some_route');
	}
}
