<?php

$labels = array();
$labels['button'] = 'SmsHosting';
$labels['page_title_compose'] = 'Compose new sms message';
$labels['page_title_configuration'] = 'Sms plugin Configuration';

$labels['leftmenu_caption'] = 'Configurations';
$labels['leftmenu_provider'] = 'Settings';
$labels['leftmenu_smshosting'] = 'Conf. SmsHosting';
$labels['leftmenu_etos'] = 'Conf. EmailToSms';
$labels['leftmenu_compose'] = 'Compose Msg.';
$labels['leftmenu_account'] = 'Account';

$labels['compose_caption'] = 'Compose message';
$labels['compose_from_label'] = 'From';
$labels['compose_to_label'] = 'To';
$labels['compose_msg_label'] = 'Message';
$labels['compose_send'] = 'Send';

$labels['param_account_caption'] = 'Account';

$labels['param_options_fileconfiguration_label'] = 'You current configuration is: ';
$labels['param_options_operation_not_available'] = 'You can\'t change this settings, contact your Administrator';
$labels['param_options_caption'] = 'Select provider';
$labels['param_options_label'] = 'Select provider to use';
$labels['param_options_smshosting'] = 'Sms Hosting';
$labels['param_options_emailtosms'] = 'Email-To-Sms provider';

$labels['smsh_caption'] = 'SmsHosting configuration';
$labels['smshusername_label'] = 'Smshosting username';
$labels['smshpassword_label'] = 'Smshosting password';
$labels['smshpassword_creditavailable'] = 'Your credit available';

$labels['etos_caption'] = 'Configure Email To Sms Gateway';
$labels['etos_domain_label'] = 'Insert email to sms gateway domain';
$labels['etos_senderemail_label'] = 'Insert email address autorized for sending';


$labels['error_compose_from_empty'] = 'From field required';
$labels['error_compose_from_number'] = 'From field max 11 numebers';
$labels['error_compose_from_chars'] = 'From field max 16 chars';
$labels['error_compose_to_empty'] = 'To field required';
$labels['error_compose_to_number'] = 'To field contains a not valid gsm number';
$labels['error_compose_message_empty'] = 'Message field required';

$labels['error_smshosting_account'] = 'Sms provider login data not found';
$labels['error_smsetos_domain'] = 'Email-To-Sms domain not found';
$labels['error_smsetos_senderemail'] = 'Email autorized for Email-To-Sms not found';

$labels['service_unavailable'] = 'Service Unavailable';
$labels['service_responce'] = 'Service response';
$labels['error_database'] = 'Error during update';
$labels['result_operationcompleted'] = 'Operation completed';
$labels['result_operationcompleted_with_errors'] = 'Operation completed with errors';

$labels['FAILED'] = 'There was an error calling web services';
$labels['GENERICERROR'] = 'Unknown error';
$labels['BADSENDERID'] = 'Bad sender parameter';
$labels['NOCREDIT'] = 'No credit available';
$labels['BADDATE'] = 'Bad date';
$labels['BADTRANSACTIONID'] = 'Bad transaction';
$labels['BADLOGIN'] = 'Bad login data';
$labels['MISSINGTEXT'] = 'Missing text message';

$labels['PENDING'] = 'Ok. Sms in send queue';
$labels['SENT'] = 'Ok. Sms sent to operator';
$labels['NOSENT'] = 'Ko. Sms non sent to operator';
$labels['DELIVERED'] = 'Ok. Sms sent to phone';
$labels['NOTDELIVERED'] = 'Ko. Sms not send to phone';
$labels['NOTINSERTED'] = 'Ko. Sms not inserted in gateway.';

$labels['DUPLICATESMS'] = 'Sms already sent in the last 24 hours';
$labels['BADNUMBERFORMAT'] = 'Gsm number format not correct';

$labels['buy_credit'] = 'Buy credit';

$labels['sms_char_count'] = 'Chars: ';
$labels['sms_msg_count'] = 'Messages: ';

$labels['_guide'] = 'Guide';
$labels['_guide_account'] = 'In this section you can access out SMS purchase service and view your available credit in euros on your account';
$labels['_guide_send'] = 'In this section yuo can send SMS messagge with the credit purchased on www.smshosting.it';
$labels['_guide_send1'] = 'In From field you can set sender name (max 11 chars) or gsm number (max 16 chars)';
$labels['_guide_send2'] = 'In To field you can set one or more GSM number (separated by ;) with international prefix (for example 39 in case of Italian number)'; 
$labels['_guide_send3'] = 'In Message field you can set SMS text';
$labels['_guide_send4'] = 'Max length of any sms is 160 chars ';
$labels['_guide_options'] = 'In this section you can set smshousting account user informations.';
                            
$messages = array();
?>
