<?php
/**
 * @file
 * Contains \Drupal\registration_form\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\registration_form\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MultistepThreeForm extends MultistepFormBase {

	/**
	 * {@inheritdoc}.
	 */
	public function getFormId() {
		return 'multistep_form_three';
	}

	/**
	 * {@inheritdoc}.
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form = parent::buildForm($form, $form_state);

		$form['#attributes']['enctype'] = 'multipart/form-data';

		$form['photo'] = array(
			'#type' => 'managed_file',
			'#title' => $this->t('Please Upload Your photo'),
			'#required' => true,
		);

		$form['q1'] = array(
			'#type' => 'radios',
			'#title' => $this->t('What is your favourite sports'),
			'#required' => TRUE,
			'#options' => array(
				t('Cricket'),
				t('Football'),
				t('Hockey'),
				t('Golf'),)
		);

		$form['q2'] = array(
			'#type' => 'radios',
			'#title' => $this->t('What is your Place of birth'),
			'#required' => TRUE,
			'#options' => array(
				t('Boston'),
				t('Washington'),
				t('Los angeles'),
				t('Philadelphia'),)
		);

		$form['actions']['submit']['#value'] = $this->t('Submit');
		return $form;
	}

	function _photos_form_validate(array &$form, FormStateInterface $form_state) {
		$file = file_save_upload('photo');
		if (!$file) {
			$form_state->setErrorByName('photo', 'File upload error');
		} else {
			$file->status = FILE_STATUS_PERMANENT;
			file_save($file);
			$form_state['storage']['fid'] = $file->fid;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->store->set('photo', $form_state->getValue('photo'));
		$this->store->set('q1', $form_state->getValue('q1'));
		$this->store->set('q2', $form_state->getValue('q2'));

		// Save the data
		parent::saveData();
		//$form_state->setRedirect('some_route');
	}
}
