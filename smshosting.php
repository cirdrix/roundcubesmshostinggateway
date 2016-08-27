<?php
/**
 * SmsHosting Plugin
 *
 * @author Cirelli Alessandro 'Cirdrix'
 * @license GNU GPLv3+
 *
 * Configuration (see config.inc.php.dist)
 *
 * */
class smshosting extends rcube_plugin {
    const PLUGIN_NAME = 'smshosting';

    // Form params
    const PARAM_SENDER = '_from';
    const PARAM_RECIPIENT = '_to';
    const PARAM_MESSAGE = '_message';

    const PARAM_SMSH_USERNAME = '_smsh_username';
    const PARAM_SMSH_PASSWORD = '_smsh_password';

    private $rcmail;
    const TASK = 'smshosting';

    private $empty_form    = 0;

    function init() {
        
        $rcmail = rcmail::get_instance();
        $this->rcmail = $rcmail;

        if (file_exists('./plugins/' . self::PLUGIN_NAME . '/config/config.inc.php')) {
            $this->load_config('config/config.inc.php');
        } else {
            $this->load_config('config/config.inc.php.dist');
        }

        $this->add_texts('localization/', false);
        $this->register_action('plugin.' . self::PLUGIN_NAME, array($this, 'compose'));
        $this->register_action('plugin.' . self::PLUGIN_NAME . '_compose_send', array($this, 'compose_send'));

        $this->register_action('plugin.' . self::PLUGIN_NAME . '_account', array($this, 'account'));

        $this->register_action('plugin.' . self::PLUGIN_NAME . '_options', array($this, 'options'));
        $this->register_action('plugin.' . self::PLUGIN_NAME . '_options_save', array($this, 'options_save'));

        $this->register_action('plugin.' . self::PLUGIN_NAME . '_addressbook_handler', array($this, 'addressbook_handler'));
        
        $this->add_button(array(
            'name' => 'SmsHosting',
            'class' => 'smsh-button',
            'label' => self::PLUGIN_NAME . '.button',
            'href' => $rcmail->url(array('_action' => 'plugin.' . self::PLUGIN_NAME, '_task' => self::TASK))
                ), 'taskbar');

        $skin = $rcmail->config->get('skin');

        if (file_exists('./plugins/' . self::PLUGIN_NAME . '/ ' . $skin . '/smshosting.css')) {
            $this->include_stylesheet('skins/' . $skin . '/smshosting.css');
        } else {
            $this->include_stylesheet('skins/default/smshosting.css');
        }
    }

    /**
    * Initializes client-side autocompletion
    */
    
    function rcube_autocomplete_init2()
    {
		$RCMAIL = $this->rcmail;
        static $init;

        if ($init)
            return;

        $init = 1;

        if (($threads = (int)$RCMAIL->config->get('autocomplete_threads')) > 0) {
        	$book_types = (array) $RCMAIL->config->get('autocomplete_addressbooks', 'sql');
         	if (count($book_types) > 1) {
            	$RCMAIL->output->set_env('autocomplete_threads', $threads);
            	$RCMAIL->output->set_env('autocomplete_sources', $book_types);
            }
 		}

        $RCMAIL->output->set_env('autocomplete_max', (int)$RCMAIL->config->get('autocomplete_max', 15));
        $RCMAIL->output->set_env('autocomplete_min_length', $RCMAIL->config->get('autocomplete_min_length'));
        $RCMAIL->output->add_label('autocompletechars');
    }
    
    function compose() {
        $rcmail = $this->rcmail;

        $this->load_config();
        $this->register_handler('plugin.body', array($this, 'print_form_compose'));
        $this->register_handler('plugin.composemenu', array($this, 'print_configuration_menu'));
        $this->include_script('smshosting.js');

        // configure autocompletion
        $this->rcube_autocomplete_init2();

        $rcmail->output->set_pagetitle($this->gettext('page_title_compose'));
        $rcmail->output->send(self::PLUGIN_NAME . '.smscompose');
    }

    function compose_send() {

        $this->empty_form = 0;

        $rcmail = $this->rcmail;

        $sender = $this->getParameter(self::PARAM_SENDER);
        $recipient = $this->getParameter(self::PARAM_RECIPIENT);
        $message = $this->getParameter(self::PARAM_MESSAGE);

        $ar_errors = array();

        $default_identity = $this->get_default_identity();

        // Define variable
        $sms_smshosting_username = null;
        $sms_smshosting_password = null;

        if (!empty($sender)) {
            if (preg_match('/^[0-9]$/', $sender))
            {
                if (strlen($sender) > 11)
                    $ar_errors[] = $this->gettext('error_compose_from_number');
            }
            else
            {
                
                if (strlen($sender) > 16)
                	$ar_errors[] = $this->gettext('error_compose_from_chars');
                
            }
        }

        if (empty($recipient)) {
            $ar_errors[] = $this->gettext('error_compose_to_empty');
        } else {
            $msisdn_numbers_array = array();
            $ar_recipient = split(";", $recipient);
            for ($i = 0; $i < count($ar_recipient); $i++) {// each($ar_recipient as $gsm)
                if (trim($ar_recipient[$i]) != "")
                {
                    if ($this->is_valid_gsm(trim($ar_recipient[$i])))
                        $msisdn_numbers_array[$i] = array('customerId' => $i, number => trim($ar_recipient[$i]));
                    else
                        $ar_errors[] = $this->gettext('error_compose_to_number'). ": ".trim($ar_recipient[$i]);
                }
            }
        }

        if (empty($message)) {
            $ar_errors[] = $this->gettext('error_compose_message_empty');
        }


            
		$sms_smshosting_username = $rcmail->config->get('sms_smshosting_username');
        $sms_smshosting_password = $rcmail->config->get('sms_smshosting_password');

		if (!isset($sms_smshosting_username))
        	$sms_smshosting_username = $default_identity['sms_smsh_username'];

        if (!isset($sms_smshosting_password))
        	$sms_smshosting_password = $default_identity['sms_smsh_password'];

        if (!isset($sms_smshosting_username) || empty($sms_smshosting_password))
        	$ar_errors[] = $this->gettext('error_smshosting_account');
            

        if (count($ar_errors) == 0) {
            try {
                if (!$rcmail->config->get('sms_debugmode')) {

					$client = new SoapClient($rcmail->config->get('sms_smshosting_wsapi_url'));
					//$sms_send_datetime = date("YmdHisO");

					$response = $client->send(array('username' => $sms_smshosting_username,
								'password' => $sms_smshosting_password,
								'numbers' => $msisdn_numbers_array,
								'text' => $message,
								'from' => $sender));

					$sending_errors = array();


					if ($response != null) {

						$this->write_mylog('operationResult: ' . $response->operationResult);

						if ($response->operationResult == "SUCCESS") {
							if (!is_array($response->results))
								$results = array($response->results);
							else
								$results = $response->results;

							foreach ($results as $result) {
								if ($result->status == "NOTINSERTED")
								{
									$sending_errors[] = $result->msisdn->number . " -> " .$this->gettext($result->status) . " -> " . $this->gettext($result->statusDetail);
								}
								$this->write_mylog('internalId: ' . $result->internalId);
								$this->write_mylog('msisdn:[customerId]:' . $result->msisdn->customerId);
								$this->write_mylog('msisdn:[number]:' . $result->msisdn->number);
								$this->write_mylog('status: ' . $result->status);
								$this->write_mylog('statusDetail: ' . $result->statusDetail);
								$this->write_mylog('transactionId: ' . $result->transactionId);
							}
						}
						else {
							$sending_errors[] = $this->gettext($response->operationDetail);
							$this->write_mylog('operationDetail: ' . $response->operationDetail);
						}
					}

					if (count($sending_errors) == 0){
						$rcmail->output->show_message($this->gettext('result_operationcompleted'));
						$this->empty_form = 1;
					}
					else
						$rcmail->output->show_message($this->gettext('result_operationcompleted_with_errors').'<br />'.implode('<br />', $sending_errors), 'error');

                } else {
                    $rcmail->output->show_message('debug mode activated - change config to disable', 'error');
                }
            } catch (Exception $e) {
                $rcmail->output->show_message('Error: ' . $e, 'error');
            }
        } else {
            $rcmail->output->show_message(implode('<br />', $ar_errors), 'error');
        }
        $this->compose();
    }

    function account() {
        $rcmail = $this->rcmail;
        $this->load_config();
        $this->register_handler('plugin.body', array($this, 'print_form_account'));
        $this->register_handler('plugin.composemenu', array($this, 'print_configuration_menu'));
        $this->include_script('smshosting.js');
        $rcmail->output->set_pagetitle($this->gettext('page_title_configuration'));
        $rcmail->output->send(self::PLUGIN_NAME . '.smsaccount');
    }

    function options_save() {
        $rcmail = $this->rcmail;
        $success = true;
        $error = "";

       
		$username = $this->getParameter(self::PARAM_SMSH_USERNAME);
		$password = $this->getParameter(self::PARAM_SMSH_PASSWORD);

		try {
			$default_identity = $this->get_default_identity();

			$plugin = $rcmail->plugins->exec_hook('identity_update', array('id' => $default_identity['identity_id'], 'record' => array('sms_smsh_username' => $username, 'sms_smsh_password' => $password)));
			$save_data = $plugin['record'];
			if (!$plugin['abort'])
				$updated = $rcmail->user->update_identity($default_identity['identity_id'], $save_data);
			else
			{
				$success = false;
				write_log('error', 'Database error during update_identity for sms_username and password');
			}

		} catch (Exception $e) {
			$success = false;
			write_log('error', 'Exception: ' . $e);
		}
        

        if ($success)
            $rcmail->output->show_message($this->gettext('result_operationcompleted'));
        else
            $rcmail->output->show_message($this->gettext('error_database'), 'error');

        $this->options();
    }

    function options() {
        $rcmail = $this->rcmail;
        $this->load_config();
        $this->register_handler('plugin.body', array($this, 'print_form_options'));
        $this->register_handler('plugin.composemenu', array($this, 'print_configuration_menu'));
        $this->include_script('smshosting.js');
        $rcmail->output->set_pagetitle($this->gettext('_page_title_configuration'));
        $rcmail->output->send(self::PLUGIN_NAME . '.smsoptions');
    }

    function print_form_options() {
        $rcmail = $this->rcmail;

        $table_smshosting = $this->configure_smshosting_create_html($rcmail);

        $br = html::br();
        $button_send = html::tag('input', array('type' => 'submit', 'class' => 'button', 'value' => $this->gettext('save'), 'style' => 'width:100px'));

        $form_options = html::tag('form', array(
                        'action' => $rcmail->url('plugin.' . self::PLUGIN_NAME . '_options_save'),
                        'method' => 'post'), $table_smshosting . $br . $button_send);

        return $form_options;
    }

    function configure_smshosting_create_html($rcmail) {

        $sms_smshosting_username = $rcmail->config->get('sms_smshosting_username');
        $sms_smshosting_password = $rcmail->config->get('sms_smshosting_password');

        if (!isset($sms_smshosting_username)) {
            $default_identity = $this->get_default_identity();

            $sms_smshosting_username = $default_identity['sms_smsh_username'];
            $sms_smshosting_password = $default_identity['sms_smsh_password'];

            $table = new html_table(array('id' => 'sms_config'));
            $table->add_row();
            $table->add('title', Q($this->gettext('smshusername_label')));
            $username = new html_inputfield(array('name' => self::PARAM_SMSH_USERNAME, 'style' => 'width:150px'));
            $table->add('editfield', $username->show($sms_smshosting_username));

            $table->add_row();
            $table->add('title', Q($this->gettext('smshpassword_label')));
            $password = new html_inputfield(array('name' => self::PARAM_SMSH_PASSWORD, 'style' => 'width:150px', 'type'=> 'password'));
            $table->add('editfield', $password->show($sms_smshosting_password));

            return $table->show();
        }

        return html::tag('p', array('class' => 'sms-p'), Q($this->gettext('param_options_operation_not_available')));
    }

    function print_configuration_menu() {
        $rcmail = $this->rcmail;
        $br = html::br();
        $option_link = html::p('', html::a(array('class' => '', 'href' => $rcmail->url('plugin.' . self::PLUGIN_NAME . '_options')), Q($this->gettext('leftmenu_provider'))));
        $compose_link = html::p('', html::a(array('class' => '', 'href' => $rcmail->url('plugin.' . self::PLUGIN_NAME)), Q($this->gettext('leftmenu_compose'))));
        $account_link = html::p('', html::a(array('class' => '', 'href' => $rcmail->url('plugin.' . self::PLUGIN_NAME . '_account')), Q($this->gettext('leftmenu_account'))));

        $options_div = html::div(array('class' => ''), $compose_link . $option_link . $br . $account_link );

        return $options_div;
    }

    function print_form_account() {
        $rcmail = $this->rcmail;

        $sms_smshosting_username = $rcmail->config->get('sms_smshosting_username');
        $sms_smshosting_password = $rcmail->config->get('sms_smshosting_password');

        $default_identity = $this->get_default_identity();

        if (!isset($sms_smshosting_username)) {
            $sms_smshosting_username = $default_identity['sms_smsh_username'];
            $sms_smshosting_password = $default_identity['sms_smsh_password'];
        }

		$client = new SoapClient($rcmail->config->get('sms_smshosting_wsapi_user_url'));
		$response = $client->getUserInfo(array('username' => $sms_smshosting_username, 'password' => $sms_smshosting_password));

		if ($response != null) {
			if ($response->operationResult == "SUCCESS")
				$credit_available = html::p('', Q($this->gettext('smshpassword_creditavailable')) .': '.$response->userInfo->credit .' euro');
			else if ($response->operationResult == "FAILED")
				$credit_available = html::p('', Q($this->gettext('smshpassword_creditavailable')) .': '.Q($this->gettext('service_responce')). ' ' . $this->gettext($response->operationDetail));
		}
		else
		{
			$credit_available = html::p('', Q($this->gettext('smshpassword_creditavailable')) .': '. Q($this->gettext('service_unavailable')));
		}

		$buy_credit = html::p('', html::a(array('href' => Q($rcmail->config->get('sms_smshosting_buy_credit_url')), 'target' => '_blank'), $this->gettext('buy_credit')));

		return $credit_available. $buy_credit;
    }

    function print_form_compose() {
        $rcmail = $this->rcmail;
        
        $sender = "";
        
        
        $sms_smshosting_username = $rcmail->config->get('sms_smshosting_username');
        $sms_smshosting_password = $rcmail->config->get('sms_smshosting_password');
        
        $default_identity = $this->get_default_identity();
        
        
        $sms_smshosting_username = $default_identity['sms_smsh_username'];
        $sms_smshosting_password = $default_identity['sms_smsh_password'];
        
		$client = new SoapClient($rcmail->config->get('sms_smshosting_wsapi_user_url'));
		$response = $client->getUserInfo(array('username' => $sms_smshosting_username, 'password' => $sms_smshosting_password));
		
		if ($response != null) {
			if ($response->operationResult == "SUCCESS")
				$sender = $response->userInfo->sender;
			else if ($response->operationResult == "FAILED")
			{
				$sender = "";
				$rcmail->output->show_message($this->gettext($response->operationDetail),'error');
			}
				
		}
        
        $table = new html_table(array('id' => 'compose-headers'));
        $table->add_row();
        $table->add('title', $this->gettext('compose_from_label'));
        $sender = new html_inputfield(array('name' => self::PARAM_SENDER, 'value' => $sender));
        $table->add('editfield', $sender->show( ( !$this->empty_form ? $this->getParameter(self::PARAM_SENDER) : '' )));

        $table->add_row();
        $table->add('title', $this->gettext('compose_to_label'));
        $recipient = new html_textarea(array('cols' => 80, 'rows' => 3, 'name' => self::PARAM_RECIPIENT, 'autocomplete' => 'off', 'id' => self::PARAM_RECIPIENT, 'spellcheck' => 'false'));
        $table->add('editfield', $recipient->show( ( !$this->empty_form ? $this->getParameter(self::PARAM_RECIPIENT) : '' )));

        $table->add_row();
        $table->add('title', $this->gettext('compose_msg_label'));
        $textmessage = new html_textarea(array('cols' => 80, 'rows' => 10, 'name' => self::PARAM_MESSAGE, 'id' => self::PARAM_MESSAGE, 'style' => 'height:100px'));
        $table->add('editfield', $textmessage->show( ( !$this->empty_form ? $this->getParameter(self::PARAM_MESSAGE) : '' )));

        $table->add_row();
        $table->add('title', '');
        $button_send = html::tag('input', array('type' => 'submit', 'class' => 'button', 'value' => $this->gettext('compose_send'), 'style' => 'width:100px'));
        $button_cancel = html::tag('input', array('type' => 'reset', 'class' => 'button', 'value' => $this->gettext('cancel'), 'style' => 'width:100px'));

        $table->add('editfield', $button_send. '&nbsp;' .$button_cancel. ' <span id="sms_counter_container">'. $this->gettext('sms_char_count') .'<b id="sms_char_count">0/160</b>. '. $this->gettext('sms_msg_count'). ' <b id="sms_msg_count">1/3</b>.</span>');

        $form = html::tag('form', array(
                    'action' => $rcmail->url('plugin.' . self::PLUGIN_NAME . '_compose_send'),
                    'method' => 'post'), $table->show());

        return $form;
    }

    function addressbook_handler()
    {
    	$rcmail = $this->rcmail;
    	$search_query = $this->getParameter("_search");

    	// Get the list of address book we take first
    	$rcube_contacts = $rcmail->get_address_book(0,false);

    	// Contact fields array to search to, "*" to search in all fields
    	//$rcube_result_set = $rcube_contacts->search(array('email','name','surname','mobile'), $search_query);
		$rcube_result_set = $rcube_contacts->search('*', $search_query);

    	// Populate return array with name and mobile property
    	while ($row = $rcube_result_set->next()) {
            $ar_address[] = array( 'name' => $row['name']." <".$row['phone:mobile'][0].">", 'mobile' => $row['phone:mobile']);
            //Old versions
			//$ar_address[] = array( 'name' => $row['name']." <".$row['mobile'].">", 'mobile' => $row['mobile']);
    	}

    	// Call callback function with contact array filtered
        if( @$ar_address > 0 )
            $rcmail->output->command('plugin.smshosting_addressbook_callback', $ar_address);
    }

    // Utility function that return vad_dump values in string
    function vardump_to_string($var)
    {
    	ob_start();
    	var_dump(json_encode($var));
    	$result_vd = ob_get_clean();

    	return $result_vd;
    }

    function get_default_identity() {
        $rcmail = $this->rcmail;
        $identities = $this->rcmail->user->list_identities();

        foreach ($identities as $idx => $ident) {
            if ($ident['standard']) {
                return $ident;
            }
        }
    }

    function is_valid_gsm($number)
    {
        return preg_match('/^[+]?[0-9]{5,14}$/', $number);
    }

    static function getParameter($paramName, $default = null) {
        $pa = get_input_value($paramName, RCUBE_INPUT_POST, true, "UTF8");
        if (empty($pa)) {
            return $default;
        } else {
            return $pa;
        }
    }

    function write_mylog($message)
    {
        $rcmail = $this->rcmail;
        $trace_enabled = $rcmail->config->get('sms_traceenabled');
        if ($trace_enabled)
            write_log('info', '['. self::PLUGIN_NAME. '] '.$message);
    }

}
