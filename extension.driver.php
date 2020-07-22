<?php

class extension_fingerprints extends Extension {
	public function about() {
		return array(
			'name'			=> 'Fingerprints',
			'version'		=> '1.00',
			'release-date'	=> '2020-07-20',
			'author'		=> array(
				'name'			=> 'Stefan Wiegmann'
			),
			'description' => 'Adds fingerprint-hashes of individual selected files and provides the results as xml-parameters.'
		);
	}


	public function install() {
		return true;
	}


	public function uninstall() {
		// remove config settings
		Symphony::Configuration()->remove('fingerprints');
		Symphony::Configuration()->write();

		return true;
	}


	public function getSubscribedDelegates() {
		return array(
			array(
				'page' => '/system/preferences/',
				'delegate' => 'AddCustomPreferenceFieldsets',
				'callback' => 'systemPreferencesAppend'
			),

			array(
				'page' => '/system/preferences/',
				'delegate' => 'Save',
				'callback' => 'systemPreferencesSave'
			),

			array(
				'page' => '/frontend/',
				'delegate' => 'FrontendParamsResolve',
				'callback' => 'frontendParamsAdd'
			)
		);
	}


	public function systemPreferencesAppend(&$context) {
		$wrapper = $context['wrapper'];
		$fieldset = new XMLElement('fieldset', '', array('class' => 'settings'));


		// Headline
		$fieldset->appendChild(
			new XMLElement('legend', 'Fingerprints')
		);


		// Entries
		$label = Widget::Label('Entries:');

		$label->appendChild(
			Widget::Textarea(
				'fingerprints[entries]',
				5,
				50,
				Symphony::Configuration()->get('entries', 'fingerprints')
			)
		);

		$fieldset->appendChild($label);

		$fieldset->appendChild(
			new XMLElement('p', 'Syntax (one entry per row): <code>ds-node:path/filename</code><br>e.g.: <code>css:/workspace/assets/css/app.css</code>', array('class' => 'help'))
		);


		// Add fieldset
		$wrapper->appendChild($fieldset);
	}


	public function systemPreferencesSave(&$context) {
		// Entries
		if (isset($_POST['fingerprints']['entries'])) {
			Symphony::Configuration()->set('entries', General::sanitize($_POST['fingerprints']['entries']), 'fingerprints');
		} else {
			Symphony::Configuration()->remove('entries', 'fingerprints');
		}


		// Save
		Symphony::Configuration()->write();
	}


	public function frontendParamsAdd(&$context) {
		$s = Symphony::Configuration()->get('entries', 'fingerprints');

		if (!$s || !strlen($s)) return;

		$entries = preg_split("/\r\n|\n|\r/", $s);

		foreach($entries as $s) {
			$a = explode(":", $s);

			if (!$a || count($a) !== 2 || @!$mtime = filemtime($a[1])) continue;

			$context['params']['fingerprint-' . $a[0]] = md5($mtime);
		}
	}
}

?>
