<?php

$labels = array();
$labels['button'] = 'SmsHosting';
$labels['page_title_compose'] = 'Crea un nuovo messaggio sms';
$labels['page_title_configuration'] = 'Configurazione Sms plugin';

$labels['leftmenu_caption'] = 'Configurazioni';
$labels['leftmenu_provider'] = 'Impostazioni';
$labels['leftmenu_smshosting'] = 'Account provider';
$labels['leftmenu_etos'] = 'Email-to-Sms provider';
$labels['leftmenu_compose'] = 'Componi sms';
$labels['leftmenu_account'] = 'Account';

$labels['compose_caption'] = 'Componi sms';
$labels['compose_from_label'] = 'Mittente: ';
$labels['compose_to_label'] = 'Destinatari: ';
$labels['compose_msg_label'] = 'Messaggio: ';
$labels['compose_send'] = 'Invia';

$labels['param_account_caption'] = 'Account';

$labels['param_options_fileconfiguration_label'] = 'La tua attuale configurazione è: ';
$labels['param_options_operation_not_available'] = 'Non puoi modificare la configurazione, contatta l\'Amministratore.';
$labels['param_options_caption'] = 'Seleziona il provider';
$labels['param_options_label'] = 'Seleziona il provider da usare';
$labels['param_options_smshosting'] = 'Sms provider';
$labels['param_options_emailtosms'] = 'Email-To-Sms provider';

$labels['smsh_caption'] = 'Sms provider configuration';
$labels['smshusername_label'] = 'Sms provider username';
$labels['smshpassword_label'] = 'Sms provider password';
$labels['smshpassword_creditavailable'] = 'Il tuo credito disponibile è';


$labels['etos_caption'] = 'Configura Email To Sms';
$labels['etos_domain_label'] = 'Inserisci il dominio da utilizzare';
$labels['etos_senderemail_label'] = 'Inserisci l\'indirizzo email autorizzato all\'invio';



$labels['error_compose_from_empty'] = 'Campo Da: obbligatorio';
$labels['error_compose_from_number'] = 'Campo Da: Lunghezza massima 11 numeri';
$labels['error_compose_from_chars'] = 'Campo Da: Lunghezza massima 16 caratteri';
$labels['error_compose_to_empty'] = 'Campo A: obbligatorio';
$labels['error_compose_to_number'] = 'Campo A: contiene un numero non valido';
$labels['error_compose_message_empty'] = 'Campo testo: obbligatorio';

$labels['error_smshosting_account'] = 'Dati di accesso Sms provider non trovati';
$labels['error_smsetos_domain'] = 'Dominio Email-To-Sms non trovato';
$labels['error_smsetos_senderemail'] = 'Email autorizzata a Email-To-Sms non trovata';


$labels['service_unavailable'] = 'Servizio non disponibile';
$labels['service_responce'] = 'Risposta dal servizio';
$labels['error_database'] = 'Errore durante l\'aggiornamento';
$labels['result_operationcompleted'] = 'Operazione completata';
$labels['result_operationcompleted_with_errors'] = 'Operazione completata con errori:';

$labels['FAILED'] = 'Si è verificato un problema nella chiamata';
$labels['GENERICERROR'] = 'Errore sconosciuto';
$labels['BADSENDERID'] = 'Mittente non corretto';
$labels['NOCREDIT'] = 'Credito non sufficiente';
$labels['BADDATE'] = 'Formato della data di invio non valido';
$labels['BADTRANSACTIONID'] = 'Formato del transaction id non corretto';
$labels['BADLOGIN'] = 'Username o password non corretti';
$labels['MISSINGTEXT'] = 'Testo del messaggio errato';

$labels['PENDING'] = 'Ok. Sms in coda per l\'invio.';
$labels['SENT'] = 'Ok. Sms inviato all\'operatore.';
$labels['NOSENT'] = 'Ko. Sms non inviato all\'operatore.';
$labels['DELIVERED'] = 'Ok. Sms consegnato al cellulare.';
$labels['NOTDELIVERED'] = 'Ko. Sms non consegnato al cellulare.';
$labels['NOTINSERTED'] = 'Ko. Sms non inserito nel gateway.';

$labels['DUPLICATESMS'] = 'Sms già inserito nelle ultime 24 ore.';
$labels['BADNUMBERFORMAT'] = 'Formatto del numero di cellulare non corretto';

$labels['buy_credit'] = 'Acquista credito';

$labels['sms_char_count'] = 'Caratteri: ';
$labels['sms_msg_count'] = 'Messaggi: ';

$labels['_guide'] = 'Guide';
$labels['_guide_account'] = 'Da questa sezione puoi accedere all\'acquisto del credito dal provider SMS e visualizzare il credito in euro residuo sull\'account.';
$labels['_guide_send'] = 'Questa e\' la sezione che consente l\'invio degli SMS, utilizzando il credito acquistato presso il nostro sito www.smshosting.it';
$labels['_guide_send1'] = 'nel campo mitente puoi indicare il testo fino a 11 caratteri oppure il numero fino a 16 cifre che compare sul cellulare del destinatario quale mittente dell\'smshosting.';
$labels['_guide_send2'] = 'nel campo destinatari puoi indicare uno o pi\'u numeri GSM (separati da ; ) completi di prefisso internazionale (39 nel caso di numeri italiani) ai quali inviare il messaggio. Se parte di un numero destinatario e\' presente nella rubrica online del tuo account attivo presso il provider, il sistema ne propone l\'autocompletamento.';
$labels['_guide_send3'] = 'nel campo messaggio puoi indicare il testo dell\'SMS da inviare ai destinatari.';
$labels['_guide_send4'] = 'la lunghezza massima di ciascun SMS e\' di 160 caratteri. Nel caso di messaggi piu\' lunghi di 160 caratteri, alcuni caratteri sono utilizzati per la loro numerazione progressiva riducendo, dunque, il numero complessivo di caratteri disponibili. ';
$labels['_guide_options'] = 'Questa e\' la sezione dove configurare i parametri di accesso al provider smshosting.';

$messages = array();
?>
