<?php

$lang['estimate_add_edit_admin_note']       = 'Nota interna';
$lang['my_timesheets']                      = 'Attività';
$lang['items']          = 'Vetture';
$lang['estimate_subtotal']               = 'Subtotale';
$lang['tax_1']                                    = 'ND';
$lang['tax_2']                                    = 'ND';
$lang['invoice_table_tax_heading']             = 'ND';
$lang['estimate_table_tax_heading']      = 'ND';

# Version 3.0.0
$lang['contracts_view_marked_as_signed'] = 'Marked As Signed';
$lang['contracts_view_signed'] = 'firmato';
$lang['contracts_view_not_expired'] = 'Non scaduto';
$lang['contract_information'] = 'Informazioni sul contratto';
$lang['receipt'] = 'Receipt';
$lang['search_proposals'] = 'Cerca proposte';
$lang['search_estimates'] = 'Ricerca preventivi';
$lang['quick_create'] = 'Creazione rapida';

$lang['staff_related_ticket_notification_to_assignee_only'] = 'Invia le notifiche dei ticket relativi al personale solo all\'assegnatario del ticket';
$lang['staff_related_ticket_notification_to_assignee_only_help'] = 'Se questa opzione è impostata su Sì e il ticket non ha un assegnatario, la notifica sarà inviata a tutto il personale che appartiene al reparto ticket';
$lang['import_expenses'] = 'Importa spese';
$lang['show_pdf_signature_proposal'] = 'Mostra la firma PDF sulla proposta';
$lang['enable_honeypot_spam_validation'] = 'Abilita la convalida dello spam di Honeypot';

$lang['open_google_map'] = 'Apri in Google Map';
$lang['milestone_start_date'] = 'Data di inizio';
$lang['send_reminder_for_completed_but_not_billed_tasks'] = 'Invia un promemoria via email delle attività Ordinebili completate ma non Ordinete';
$lang['staff_to_notify_completed_but_not_billed_tasks'] = 'Seleziona i membri dello staff che vuoi ricevere il promemoria';
$lang['reminder_for_completed_but_not_billed_tasks_days'] = 'Selezionare i giorni della settimana in cui inviare il promemoria';
$lang['notifications'] = 'Notifiche';
$lang['merged'] = 'Fuso';
$lang['ticket_merged_notice'] = 'Questo ticket è stato unito al ticket con ID';
$lang['view_primary_ticket'] = 'Visualizza il ticket primario';
$lang['merge_tickets'] = 'Unisci i biglietti';
$lang['primary_ticket'] = 'Biglietto primario';
$lang['primary_ticket_status'] = 'Stato del biglietto primario';
$lang['tickets_merged'] = 'Biglietti uniti con successo';
$lang['cannot_merge_into_merged_ticket'] = 'Il biglietto unito ad un altro biglietto non può essere usato come biglietto primario';
$lang['merge_ticket_ids_field_label'] = 'Merge Ticket #';
$lang['merge_ticket_ids_field_placeholder'] = 'esempio: 5 o 5,6';
$lang['cannot_merge_tickets_with_ids'] = 'Il biglietto %s è già unito in un altro biglietto';
$lang['ticket_merged_tickets_header'] = 'Questo biglietto contiene %s biglietti uniti';
$lang['batch_payments_table_invoice_number_heading'] = 'Numero di Ordine';
$lang['batch_payments_table_payment_date_heading'] = 'Data di pagamento';
$lang['batch_payments_table_payment_mode_heading'] = 'Modalità di pagamento';
$lang['batch_payments_table_transaction_heading'] = 'Transaction Id';
$lang['batch_payments_table_amount_received_heading'] = 'Importo ricevuto';
$lang['batch_payments_table_invoice_balance_due'] = 'Saldo della Ordine dovuto';
$lang['add_batch_payments'] = 'Aggiungi pagamenti';
$lang['batch_payment_filter_by_customer'] = 'Filtra le Ordini per cliente';
$lang['batch_payments'] = 'Pagamenti batch';
$lang['batch_payment_added_successfully'] = 'Hai aggiunto con successo %s pagamenti';
$lang['batch_payments_send_invoice_payment_recorded'] = 'Non inviare email di pagamento della Ordine registrata ai contatti del cliente';
$lang['invoice_batch_payments'] = 'Pagamento batch';
$lang['staff_is_currently_replying'] = '%s sta rispondendo al ticket.';

$lang['permission_view_timesheet_report'] = 'Visualizza rapporto attività';
$lang['timesheets_overview_all_members_notice_permission'] = 'La panoramica dei timesheet per tutti i membri del personale è disponibile solo per il personale con il permesso di visualizzare i timesheet report e per gli amministratori.';
$lang['show_project_on_proposal'] = 'Mostra il nome del progetto sulla proposta';
$lang['ticket_reports_staff'] = 'Membro dello staff';
$lang['ticket_reports_total_assigned'] = 'Totale biglietti assegnati';
$lang['ticket_reports_open_tickets'] = 'Ticket aperti';
$lang['ticket_reports_closed_tickets'] = 'Ticket chiusi';
$lang['ticket_reports_replies_to_tickets'] = 'Risposte ai Ticket';
$lang['ticket_reports_average_reply_time'] = 'Tempo medio di risposta';
$lang['home_tickets_report'] = 'Rapporto sui ticket dello staff';
$lang['ticket_reports_average_reply_time_help'] = 'Tempo medio di risposta dai ticket assegnati';
$lang['created_by'] = 'Creato da';

$lang['timesheets_overview']                                = 'Panoramica attività registrate';



$lang['contract_content_permission_edit_warning'] = 'Le autorizzazioni attuali non consentono di modificare il contenuto del contratto. Consultare un
                              amministratore per ottenere il permesso di modificare i contratti.';
$lang['mark_as_signed'] = 'Segna come firmato';
$lang['unmark_as_signed'] = 'Segna come firmato';
$lang['marked_as_signed'] = 'Contrassegnato come firmato';
$lang['contract_marked_as_signed_info'] = 'Questo contratto è contrassegnato manualmente come firmato';
$lang['save_and_send_later'] = 'Salva e invia più tardi';
$lang['schedule'] = 'Pianifica';
$lang['schedule_email_for'] = 'Pianifica e-mail per %s';
$lang['schedule_date'] = 'Quando vuoi inviare l\'email?';
$lang['email_scheduled_successfully'] = 'Email pianificata con successo';
$lang['invoice_will_be_sent_at'] = 'La Ordine sarà inviata a %s';

# Version 2.5.0
$lang['recaptcha_ignore_ips'] = 'Indirizzi IP ignorati';
$lang['recaptcha_ignore_ips_info'] = 'Inserisci gli indirizzi IP separati da coma che vuoi che il reCaptcha salti la convalida.';
$lang['show_task_reminders_on_calendar'] = 'Promemoria attività';
$lang['contracts_about_to_expire'] = 'Contratti in scadenza a breve';
$lang['no_contracts_about_to_expire'] = 'Non ci sono contratti in scadenza nei prossimi %s giorni.';
$lang['lead_value_tooltip'] = 'Verrà utilizzata la valuta base.';
$lang['lead_add_edit_lead_value'] = 'Lead Value';

# Version 2.6.0
$lang['gantt_view_day'] = 'Vista giornaliera';
$lang['gantt_view_week'] = 'Vista settimanale';
$lang['gantt_view_month'] = 'Vista Mensile';
$lang['gantt_view_year'] = 'Vista annuale';

# Version 2.7.0
$lang['hour_of_day_perform_tasks_reminder_notification_help'] = 'Formato 24 ore eq. 9 per le 9 del mattino o 15 per le 15 del pomeriggio. È usato per le attività ricorrenti, i promemoria delle attività, ecc';
$lang['clients_nav_contacts'] = 'Contatti';
$lang['clients_my_contacts'] = 'Contatti';
$lang['clients_my_contact'] = 'Contatti';
$lang['new_contact'] = 'Nuovo contatto';
$lang['customer_contact'] = 'I miei contatti';
$lang['clients_contact_added'] = 'Contatto aggiunto con successo';
$lang['clients_contact_updated'] = 'Contatto aggiornato con successo';
$lang['allow_primary_contact_to_manage_other_contacts'] = 'Consenti al contatto primario di gestire i contatti di altri clienti';
$lang['contact_form_validation_is_unique'] = 'Il contatto con questo {campo} esiste già nel nostro sistema';
$lang['invoice_number_not_applied_on_draft'] = 'Se la Ordine è salvata come bozza, il numero non sarà applicato, invece, il numero di Ordine successivo sarà dato quando la Ordine sarà inviata al cliente o sarà contrassegnata come inviata';

$lang['two_factor_authentication_disabed'] = 'Disabilitato';
$lang['enable_google_two_factor_authentication'] = 'Abilita Google Authenticator';
$lang['set_google_two_factor_authentication_failed'] = 'Salvataggio dell\'autenticazione non riuscito, riprovare';
$lang['enter_two_factor_auth_code_from_mobile'] = 'Inserire il codice di autenticazione dall\'app Authenticator';
$lang['staff_two_factor_authentication'] = 'Autenticazione a due fattori';
$lang['google_authentication_code'] = 'Inserisci il codice dall\'app Authenticator';
$lang['set_two_factor_authentication_successful'] = 'Aggiornate con successo le impostazioni di autenticazione a due fattori';
$lang['set_two_factor_authentication_failed'] = 'Impossibile aggiornare le impostazioni di autenticazione a due fattori';
$lang['google_2fa_code_valid'] = 'L\'autenticazione è stata verificata con successo';
$lang['google_2fa_code_invalid'] = 'Codice di autenticazione non valido inserito, riprovare';
$lang['google_2fa_scan_qr_guide'] = 'Scansionate il QR qui sotto con l\'app Google Authenticator sul vostro dispositivo mobile, dopodiché compilate il campo sottostante con il codice generato nell\'app';
$lang['google_2fa_manul_input_secret'] = 'Chiave segreta per l\'inserimento manuale';

# Version 2.7.1
$lang['templates'] = 'Templates';
$lang['add_template'] = 'Aggiungi modello';
$lang['edit_template'] = 'Modifica template';
$lang['template_added'] = 'Template aggiunto con successo';
$lang['template_updated'] = 'Template aggiornato con successo';
$lang['template_name'] = 'Titolo del template';
$lang['template_content'] = 'Contenuto del template';
$lang['insert_template'] = 'Inserisci';
$lang['items_table_amounts_exclude_currency_symbol'] = 'Escludi il simbolo della valuta dalla tabella degli articoli Importo';

$lang['multiplies_of'] = 'Multipli di';
$lang['round_off_task_timer_option'] = 'Arrotonda il timer delle attività';
$lang['task_timer_dont_round_off'] = 'Non arrotondare';
$lang['task_timer_round_up'] = 'Arrotonda per eccesso';
$lang['task_timer_round_down'] = 'Arrotonda per difetto';
$lang['task_timer_round_nearest'] = 'Arrotonda al più vicino';
$lang['calendar_task_reminder'] = 'Promemoria attività';
$lang['projects_chart'] = 'Grafico progetti';
$lang['overdue_by_days'] = 'in ritardo di %s giorni';

# Fatture > Ordini
$lang['invoices']                       = 'Ordini';
$lang['invoice']                        = 'Ordine';
$lang['invoice_lowercase']              = 'Ordine';
$lang['create_new_invoice']             = 'Crea nuovo Ordine';
$lang['view_invoice']                   = 'Mostra Ordine';
$lang['invoice_payment_recorded']       = 'Pagamento Ordine Registrato';
$lang['invoice_payment_record_failed']  = 'Fallita Registrazione Pagamento Ordine';
$lang['invoice_sent_to_client_success'] = 'L\'Ordine è stato inviato con successo al cliente';
$lang['invoice_sent_to_client_fail']    = 'Problema durante l\'invio della Ordine';
$lang['invoice_reminder_send_problem']  = 'Problema nell\invio del promemoria della Ordine scaduta';
$lang['invoice_overdue_reminder_sent']  = 'Promemoria Ordine Scaduta Inviato con Successo';

$lang['invoice_details']              = 'Dettagli Ordine';
$lang['invoice_view']                 = 'Mostra Ordine';
$lang['invoice_select_customer']      = 'Cliente';
$lang['invoice_add_edit_number']      = 'Numero Ordine';
$lang['invoice_add_edit_date']        = 'Data Ordine';
$lang['invoice_add_edit_duedate']     = 'Scadenza';
$lang['invoice_add_edit_currency']    = 'Valuta';
$lang['invoice_add_edit_client_note'] = 'Nota Cliente';
$lang['invoice_add_edit_admin_note']  = 'Nota Admin';

$lang['invoices_toggle_table_tooltip'] = 'Mostra Tabella Completa';

$lang['edit_invoice_tooltip']                   = 'Modifica Ordine';
$lang['delete_invoice_tooltip']                 = 'Cancella Ordine. Nota: Tutti i pagamenti riguardanti questa Ordine saranno cancellati (se presenti).';
$lang['invoice_sent_to_email_tooltip']          = 'Invia per Email';
$lang['invoice_already_send_to_client_tooltip'] = 'Questa Ordine è già stata inviata al cliente %s';
$lang['send_overdue_notice_tooltip']            = 'Invia Sollecito';
$lang['invoice_view_activity_tooltip']          = 'Attività Log';
$lang['invoice_record_payment']                 = 'Registra Pagamento';


$lang['invoice_send_to_client_modal_heading']    = 'Invia questa Ordine al cliente';
$lang['invoice_send_to_client_attach_pdf']       = 'Allega PDF Ordine';
$lang['invoice_send_to_client_preview_template'] = 'Anteprima Modello Email';

$lang['invoice_dt_table_heading_number']  = 'Ordine n°';
$lang['invoice_dt_table_heading_date']    = 'Data';
$lang['invoice_dt_table_heading_client']  = 'Cliente';
$lang['invoice_dt_table_heading_duedate'] = 'Scadenza';
$lang['invoice_dt_table_heading_amount']  = 'Importo';
$lang['invoice_dt_table_heading_status']  = 'Stato';

$lang['record_payment_for_invoice']              = 'Pagamento Registrato per';
$lang['record_payment_amount_received']          = 'Importo Ricevuto';
$lang['record_payment_date']                     = 'Data Pagamento';
$lang['record_payment_leave_note']               = 'Lascia una nota';
$lang['invoice_payments_received']               = 'Pagamenti Ricevuti';
$lang['invoice_record_payment_note_placeholder'] = 'Nota Admin';
$lang['no_payments_found']                       = 'Nessun pagamento trovato per questa Ordine';

# Payments
$lang['payments']                             = 'Pagamenti';
$lang['payment']                              = 'Pagamento';
$lang['payment_lowercase']                    = 'pagamento';
$lang['payments_table_number_heading']        = 'Pagamento n°';
$lang['payments_table_invoicenumber_heading'] = 'Ordine n°';
$lang['payments_table_mode_heading']          = 'Metodo Pagamento';
$lang['payments_table_date_heading']          = 'Data';
$lang['payments_table_amount_heading']        = 'Importo';
$lang['payments_table_client_heading']        = 'Cliente';
$lang['payment_not_exists']                   = 'Il pagamento non esiste';

$lang['payment_edit_for_invoice']     = 'Pagamento per Ordine';
$lang['payment_edit_amount_received'] = 'Importo Ricevuto';
$lang['payment_edit_date']            = 'Data Pagamento';
# Knowledge Base
$lang['kb_article_add_edit_subject'] = 'Oggetto';
$lang['kb_article_add_edit_group']   = 'Gruppo';
$lang['kb_string']                   = 'FAQ';
$lang['kb_article']                  = 'Articolo';
$lang['kb_article_lowercase']        = 'articolo';
$lang['kb_article_new_article']      = 'Nuovo Articolo';
$lang['kb_article_disabled']         = 'Disabilitato';
$lang['kb_article_description']      = 'Descrizione articolo';

$lang['kb_no_articles_found']          = 'Nessu articolo d\'informazione di base trovato';
$lang['kb_dt_article_name']            = 'Nome Articolo';
$lang['kb_dt_group_name']              = 'Gruppo';
$lang['new_group']                     = 'Nuovo Gruppo';
$lang['kb_group_add_edit_name']        = 'Nome Gruppo';
$lang['kb_group_add_edit_description'] = 'Descrizione breve';
$lang['kb_group_add_edit_disabled']    = 'Disabilitato';
$lang['kb_group_add_edit_note']        = 'Nota: Tutti gli articoli in questo gruppo saranno nascosti se viene selezionato disabilita';
$lang['group_table_name_heading']      = 'Nome';
$lang['group_table_isactive_heading']  = 'Attivo';
$lang['kb_no_groups_found']            = 'Nessun gruppo Informazione di base trovato';

$lang['estimate_convert_to_invoice'] = 'Converti in Ordine';
$lang['estimate_pdf_heading']            = 'PRE-ORDINE';
$lang['invoice_bill_to']                       = 'Ordinato A';
$lang['estimate_invoiced_date']                  = 'Preventivo inserito in ORDINI il %s';
$lang['expenses_list_invoiced']                       = 'Ordine creato';
$lang['is_invoiced_estimate_delete_error'] = 'Questo preventivo è in ORDINI. Non puoi eliminarlo.';
$lang['expense_list_invoice']  = 'Ordine creato';
$lang['estimate_invoiced']                                  = 'Ordine creato';
$lang['estimates_not_invoiced']                   = 'Non in ORDINI';

// FATTURA -> ORDINI
$lang['billable_amount']                                             = 'Importo ordinabile';
$lang['last_child_invoice_date']                                     = 'Data ultimo ordine relativo';
$lang['description_in_invoice_item']                                 = 'Includere la descrizione articolo in ordine';
$lang['credit_invoice_date']                                            = 'Data Ordine';

//

$lang['invoice_pdf_heading'] = 'ORDINE';
$lang['invoice_adjustment']                    = 'Bolli in Ordine (iva x15)';
$lang['invoice_bill_to']                       = 'Ordinato A';
$lang['invoice_data_date']                     = 'Data Ordine:';
$lang['client_invoices_tab']                  = 'Ordini';
$lang['email_template_invoices_fields_heading'] = 'Ordini';
# Fattura Items
$lang['invoice_items']                     = 'Prodotti nell\'Ordine';
$lang['invoice_item']                      = 'Prodotto Ordine';
$lang['new_invoice_item']                  = 'Nuovo Prodotto';
$lang['invoice_item_lowercase']            = 'prodotto ordine';
# Fatture
$lang['invoices']                       = 'Ordini';
$lang['invoice']                        = 'Ordine';
$lang['invoice_lowercase']              = 'ordine';
$lang['create_new_invoice']             = 'Crea Nuovo Ordine';
$lang['view_invoice']                   = 'Mostra Ordine';
$lang['invoice_payment_recorded']       = 'Pagamento Ordine Registrato';
$lang['invoice_payment_record_failed']  = 'Fallita Registrazione Pagamento Ordine';
$lang['invoice_sent_to_client_success'] = 'L\'ordine è stato inviato con successo al cliente';
$lang['invoice_sent_to_client_fail']    = 'Problema durante l\'invio dell\'ordine';
$lang['invoice_reminder_send_problem']  = 'Problema nell\'invio del promemoria dell\'ordine scaduto';
$lang['invoice_overdue_reminder_sent']  = 'Promemoria Ordine Scaduto Inviato con Successo';
$lang['invoice_details']              = 'Dettagli Ordine';
$lang['invoice_view']                 = 'Mostra Ordine';
$lang['invoice_add_edit_number']      = 'Numero Ordine';
$lang['invoice_add_edit_date']        = 'Data Ordine';
$lang['edit_invoice_tooltip']                   = 'Modifica Ordine';
$lang['delete_invoice_tooltip']                 = 'Cancella Ordine. Nota: Tutti i pagamenti riguardanti questo ordine saranno cancellati (se presenti).';
$lang['invoice_already_send_to_client_tooltip'] = 'Questo ordine è già stato inviato al cliente %s';
$lang['invoice_send_to_client_modal_heading']    = 'Invia questo ordine al cliente';
$lang['invoice_send_to_client_attach_pdf']       = 'Allega PDF ordine';
$lang['invoice_dt_table_heading_number']  = 'Ordine n°';
$lang['no_payments_found']                       = 'Nessun pagamento trovato per questo ordine';
$lang['payments_table_invoicenumber_heading'] = 'Ordine n°';
$lang['payment_edit_for_invoice']     = 'Pagamento per ordine';
$lang['report_sales_base_currency_select_explanation']    = 'È necessario selezionare la valuta perché avete ordini con valuta diversa';
$lang['reports_sales_dt_customers_total_invoices']        = 'Totale Ordini';
$lang['settings_cron_send_overdue_reminder']                 = 'Invia promemoria ordine scaduto';
$lang['settings_cron_send_overdue_reminder_tooltip']         = 'Invia al cliente un\'email di ordine scaduto quando lo stato dell\'ordine viene aggiornato a Scaduto da Cron Job';
$lang['settings_sales_invoice_prefix']                             = 'Prefisso Numero Ordine';
$lang['settings_sales_require_client_logged_in_to_view_invoice']   = 'Richiede che il cliente abbia effettuato l\'accesso per vedere l\'ordine';
$lang['settings_sales_next_invoice_number']                        = 'Prossimo Numero Ordine';
$lang['settings_sales_decrement_invoice_number_on_delete']         = 'Decremento del numero di Ordine su Elimina';
$lang['settings_sales_decrement_invoice_number_on_delete_tooltip'] = 'Vuoi diminuire il numero di ordine quando l\'ultimo ordine è cancellato? Es. Se è impostata questa opzione a SÌ e prima che l\'ordine sia cancellato il numero di ordine successivo sarà 15, il numero di ordine successivo sarà diminuito a 14 per il prossimo ordine. Se è impostata su No il numero rimarrà a 15';
$lang['settings_sales_invoice_number_format']                      = 'Formato Numero Ordine';
$lang['settings_sales_company_info_note'] = 'Queste informazioni verranno visualizzate su ordini/preventivi/pagamenti e altri documenti PDF in cui è richiesta informazione aziendale';
# Fattura Activity Log
$lang['user_sent_overdue_reminder'] = '%s invia promemoria ordini scaduti';
# Home
$lang['clients_quick_invoice_info']           = 'Info Ordine Rapido';
$lang['clients_home_currency_select_tooltip'] = 'Devi selezionare la valuta perchè hai degli ordini con valute differenti';
# Fatture
$lang['clients_my_invoices']        = 'I Miei Ordini';
$lang['clients_invoice_dt_number']  = 'Ordine n°';
$lang['clients_nav_invoices']  = 'Ordini';
$lang['payment_table_invoice_number']                  = 'Numero Ordine';
$lang['payment_table_invoice_date']                    = 'Data Ordine';
$lang['payment_table_invoice_amount_total']            = 'Importo Ordine';
# Fatture
$lang['view_invoice_as_customer_tooltip']                                     = 'Vedi Ordine come Cliente';
$lang['invoice_add_edit_recurring']                                           = 'Ordine Ricorrente?';
$lang['invoices_list_not_have_payment']                                       = 'Ordini senza cronologia pagamenti';
$lang['invoices_list_recurring']                                              = 'Ordini Ricorrenti';
$lang['invoices_create_invoice_from_recurring_only_on_paid_invoices']         = 'Crea un nuovo ordine dal principale ordine ricorrente solo se è con lo stato Pagato';
$lang['invoices_create_invoice_from_recurring_only_on_paid_invoices_tooltip'] = 'Creare un nuovo ordine dal principale ordine ricorrente solo se l\'ordine principale è stato pagato? Se questo campo viene contrassegnato come No e l\'ordine ricorrente non presenta lo stato Pagato, l\'ordine non sarà creato.';
$lang['view_invoice_pdf_link_pay']                                            = 'Paga Ordine';
$lang['payment_mode_add_edit_description_tooltip'] = 'Qui puoi impostare le informazioni del tuo conto bancario. Saranno mostrate come HTML nell\'ordine.';
# Payments
// Uses on stripe page
$lang['payment_for_invoice'] = 'Pagamento per Ordine';
# Fattura
$lang['invoice_add_edit_allowed_payment_modes']           = 'Metodi di pagamenti abilitati per questo ordine';
$lang['invoice_add_edit_recurring_invoices_from_invoice'] = 'Ordine ricorrente da questo ordine';
$lang['client_zip_invoices']      = 'ZIP Ordini';
$lang['settings_delete_only_on_last_invoice']                       = 'Eliminazione ordine consentito solo su ultimo ordine';
$lang['settings_delete_only_on_last_estimate']                      = 'Eliminare preventivo consentito solo su ultimo ordine';
$lang['settings_sales_heading_invoice']                             = 'Ordini';
$lang['settings_sales_cron_invoice_heading']                        = 'Ordine';
$lang['settings_estimate_auto_convert_to_invoice_on_client_accept']   = 'Auto convertire il preventivo a ordine dopo accettazione del cliente';


$lang['estimate_invoiced_date']                  = 'Preventivo Ordinato il %s';

$lang['estimate_convert_to_invoice'] = 'Converti a Ordine';


$lang['estimate_adjustment']             = 'Bolli in Ordine';
$lang['clients_estimate_invoiced_successfully'] = 'Preventivo accettato. Ecco l\'ordine per questo preventivo.';


$lang['calendar_invoice']           = 'Ordine';
$lang['settings_show_sale_agent_on_invoices']       = 'Mostra Agente di Vendita su Ordine';

$lang['invoice_marked_as_sent']        = 'Contrassegna l\'ordine come inviato con successo';
$lang['invoice_marked_as_sent_failed'] = 'Contrassegno l\'ordine come invio fallito';

$lang['expense_add_edit_billable']                    = 'Ordinabile';
$lang['expense_converted_to_invoice']                 = 'Spesa convertita con successo in ordine';
$lang['expense_converted_to_invoice_fail']            = 'Impossibile convertire questa spesa a ordine. Controllare la registrazione degli errori.';
$lang['expenses_list_billable']                       = 'Ordinabile';
$lang['expenses_list_non_billable']                   = 'Non Ordinabile';
$lang['expenses_list_invoiced']                       = 'Ordinato';
$lang['expenses_list_unbilled']                       = 'Non Ordinabile';
$lang['expense_invoice_delete_not_allowed']           = 'Non puoi eliminare questa spesa. La spesa è già stata ordinata.';
$lang['expense_convert_to_invoice']                   = 'Converti In Ordine';
$lang['expense_invoice_not_created']                  = 'Ordine Non Creato';
$lang['expense_billed']                               = 'Ordinato';
$lang['expense_not_billed']                           = 'Non Ordinato';
$lang['expense_already_invoiced']                     = 'Questa spesa è già ordinata';
$lang['expense_recurring_auto_create_invoice']        = 'Auto Creare Ordine';
$lang['expense_recurring_send_custom_on_renew']       = 'Inviare l\'ordine via email al cliente quando si ripete la spesa';
$lang['expense_recurring_autocreate_invoice_tooltip'] = 'Se questa opzione è selezionata l\'ordine per il cliente verrà automaticamente creata quando la spesa sarà rinnovata.';
$lang['custom_field_invoice']     = 'Ordini';
$lang['estimate_convert_to_invoice_successfully'] = 'Preventivo convertito a ordine con successo';

# Version 1.0.6
# Fatture
# Currencies
$lang['invoice_copy']              = 'Copia Ordine';
$lang['invoice_copy_success']      = 'Ordine copiato con successo';
$lang['invoice_copy_fail']         = 'Copia dell\'ordine fallita';
$lang['show_shipping_on_invoice'] = 'Mostra i dettagli di spedizione nell\'ordine';

# Stime
$lang['is_invoiced_estimate_delete_error'] = 'Questo preventivo è ordinato. Non puoi eliminarlo.';

# Customers & Fatture / Stime
$lang['billing_shipping']         = 'Ordine & Spedizione';
$lang['billing_address']          = 'Indirizzo Ordine';

# Customer
$lang['customer_update_address_info_on_invoices']              = 'Aggiornare le informazioni di ordine/spedizione su tutte i precedenti ordini/preventivi';
$lang['customer_update_address_info_on_invoices_help']         = 'Se si seleziona questo campo spedizione e ordine sarà aggiornato su tutte gli ordini e preventivi. Nota: gli ordini con lo stato Pagata non saranno modificate.';

$lang['customer_billing_copy']                                 = 'Copia Indirizzo Ordine';
# Expenses
$lang['expense_list_invoice']  = 'Ordinato';

$lang['settings_sales_invoice_due_after']                                    = 'Ordine dovuto dopo (giorni)';

$lang['show_invoices_on_calendar']           = 'Ordini';
# Leads

$lang['bulk_export_pdf_invoices']      = 'Ordini';
# Customers
$lang['customer_permission_invoice']  = 'Ordine';



# Fatture
$lang['delete_invoice'] = 'Elimina Ordine';
$lang['show_invoice_estimate_status_on_pdf']                      = 'Vedi stato ordine/preventivo su PDF';
$lang['proposal_convert_invoice']               = 'Ordine';
$lang['proposal_convert_to_invoice']            = 'Converti in Ordine';
$lang['proposal_converted_to_invoice_success']  = 'Proposta convertita in ordine con successo';
$lang['proposal_converted_to_invoice_fail']     = 'Impossibile convertire la proposta in ordine';
$lang['customer_have_invoices_by']       = 'Contiene ordine di stato %s';
$lang['not_recurring_invoices_cron_activity_heading']             = 'Attività Cron Job Ordini Ricorrenti';
$lang['not_invoice_created']                                      = 'Ordine Creato:';
$lang['not_invoice_renewed']                                      = 'Ordine Rinnovato:';
$lang['not_invoice_sent_to_customer']                             = 'Ordine Inviato al Cliente: %s';
$lang['not_action_taken_from_recurring_invoice']                  = 'Ordine intrapreso da ordine ricorrente:';
$lang['estimate_activity_converted']                              = 'ha convertito questo preventivo in ordine.<br /> %s';
$lang['invoice_activity_number_changed']                          = 'Numero ordine cambiato da %s a %s';
$lang['estimate_activity_client_accepted_and_converted']          = 'Cliente ha accettato questo preventivo. Preventivo convertito in ordine con numero %s';
$lang['invoice_activity_status_updated']                          = 'Stato ordine aggiornato da %s a %s';
$lang['invoice_activity_created']                                 = 'ha creato l\'ordine';
$lang['invoice_activity_from_expense']                            = 'convertito in ordine da spesa';
$lang['invoice_activity_recurring_created']                       = 'Ordine [Recurring] creato da CRON';
$lang['invoice_activity_recurring_from_expense_created']          = '[Ordine From Expense] Ordine creato da CRON';
$lang['invoice_activity_sent_to_client_cron']                     = 'Ordine inviato al cliente da CRON';
$lang['invoice_activity_sent_to_client']                          = 'ha inviato l\'ordine al cliente';
$lang['invoice_activity_marked_as_sent']                          = 'ha contrassegnato l\'ordine come inviata';
$lang['invoice_activity_payment_deleted']                         = 'ha eliminato il pagamento per l\'ordine. Pagamento #%s, cifra totale %s';
$lang['invoice_activity_payment_made_by_client']                  = 'Cliente ha effettuato il pagamento per l\'ordine dal totale <b>%s</b> - %s';
$lang['settings_amount_to_words_desc']     = 'Importo totale output a parole nell\'ordine/stima';
$lang['settings_show_tax_per_item']        = 'Mostra IVA per oggetto (Ordini/Stime)';
$lang['report_invoice_number']            = 'Ordine #';
$lang['home_invoice_overview']        = 'Panoramica Ordine';
$lang['zip_invoices']         = 'Zip Ordini';
$lang['task_billable']          = 'Ordinabile';
$lang['task_billable_yes']      = 'Ordinabile';
$lang['task_billable_no']       = 'Non ordinabile';
$lang['task_billed']            = 'Ordinato';
$lang['task_billed_yes']        = 'Ordinato';
$lang['task_billed_no']         = 'Non ordinato';
$lang['task_is_billed']         = 'Questo compito è addebitato sull\'ordine con numero %s';

# Progetti
$lang['cant_change_billing_type_billed_tasks_found']   = ' Impossibile modificare il tipo di ordine. Compiti addebitati già trovato per questo progetto.';

$lang['project_invoiced_successfully']                       = 'Progetto ordinato con successo';
$lang['project_billing_type']                                = 'Tipo di Ordine';
$lang['project_invoices']                                 = 'Ordine';
$lang['invoice_project']                                  = 'Ordine Progetto';
$lang['invoice_project_info']                             = 'Informazini Ordine progetto';
$lang['project_activity_invoiced_project']             = 'Ordine Progetto';

/////
$lang['sales_report_cancelled_invoices_not_included']          = 'Ordini annullati escluse dal rapporto';
$lang['invoices_merge_cancel_merged_invoices']                 = 'Contrassegna gli ordini uniti come cancellati invece di eliminarli';
$lang['invoice_marked_as_cancelled_successfully']              = 'Ordine contrassegnato come cancellato con successo';
$lang['invoice_unmarked_as_cancelled']                         = 'Ordine non contrassegnato come cancellato con successo';
$lang['invoice_project_see_billed_tasks']                      = 'Vedi compiti che saranno addebitati su questo ordine';
$lang['invoice_project_nothing_to_bill']                       = 'Nessun compito da addebitare. Non esitare ad aggiungere quello che vuoi negli oggetti ordine.';
$lang['invoice_project_stop_billable_timers_only']             = 'Interrompi solo timer ordinabili';
$lang['project_invoice_timers_started']                        = 'Timer compito trovati in esecuzione su compiti addebitabili, impossibile creare l\'ordine. Si prega di interrompere i timer compito per creare l\'ordine.';
$lang['invoices_available_for_merging']                        = 'Ordini disponibili per l\'unione';
$lang['invoices_merge_discount']                               = 'Dovrai applicare lo sconto al totale di %s manualmente per questo ordine';
$lang['invoice_merge_number_warning']                          = 'Unire gli ordini creerà degli spazi vuoti nei numeri di ordini. Si prega di non unire gli ordini se non si vogliono spazi vuoti nella cronologia di ordinazione. Avrete inoltre l\'opzione di regolare manualmente i numeri di ordini se vorrete riempire gli spazi vuoti.';

$lang['invoice_set_reminder_title']              = 'Imposta Promemoria Ordine';
$lang['invoice_project_see_billed_expenses']              = 'Guarda spese che saranno addebitate su questo ordine';
$lang['notification_when_customer_pay_invoice'] = 'Ricevi notifica quando il cliente dell\'ordine (built-in)';
$lang['not_invoice_payment_recorded']           = 'Nuovo pagamento ordine- %s';
$lang['changing_items_affect_warning']          = 'Cambiare le info oggetto non influenzerà l\'ordine/stime create.';
$lang['calendar_invoice_reminder']              = 'Promemoria Ordine';
$lang['show_invoice_reminders_on_calendar']     = 'Promemoria Ordine';
$lang['not_estimate_invoice_deleted']           = 'ha eliminato l\'ordine creato';
# Version 1.1.8
$lang['invoice_number_exists']                  = 'Questo numero di ordine esiste per l\'anno in corso.';
$lang['invoice_activity_marked_as_cancelled']   = 'ha contrassegnato l\'ordine come cancellato';
$lang['invoice_activity_unmarked_as_cancelled'] = 'non ha contrassegnato l\'ordine come cancellato';
$lang['invoice_report']                         = 'Rapporto Ordine';
$lang['remove_tax_name_from_item_table'] = 'Rimuovi il nome tassa dalla fila tabella oggetto (Ordine/Stime)';
$lang['invoice_files']                            = 'Files Ordine';
$lang['invoices_awaiting_payment']                = 'Ordini in Attesa di Pagamento';
$lang['outstanding_invoices']                     = 'Ordini Arretrati';
$lang['past_due_invoices']                        = 'Ordini in Scadenza Passati';
$lang['paid_invoices']                            = 'Ordini Pagati';
$lang['projects_total_invoices_created']                    = 'Totale ordini creati';
$lang['do_not_send_invoice_payment_email_template_contact'] = 'Non inviare e-mail di registrazione pagamento ordine ai contatti cliente ';
$lang['cancel_overdue_reminders_invoice']                   = 'Impedisci l\'invio di promemoria di scadenza per questo ordine ';
$lang['customer_shipping_address_notice']                   = 'Non compilare le informazioni sull\'indirizzo di spedizione se non utilizzerai l\'indirizzo di spedizione sull\'ordine cliente ';
$lang['exclude_invoices_draft_from_client_area']            = 'Escludi gli ordini con stato bozza dall\'area clienti';
$lang['invoice_draft_status_info']                          = 'Quest\'ordine ha lo stato di Bozza, lo stato cambierà automaticamente quando invierà la fattura al cliente o sarà contrassegnata come inviata.';
$lang['payment_mode_invoices_only']                = 'Solo Ordini';
# Version 1.2.8
$lang['show_transactions_on_invoice_pdf']            = 'Mostra pagamenti ordine (transazioni) su PDF';
$lang['show_on_invoice_on_pdf']                      = 'Mostra %s su PDF Ordine';
$lang['show_pay_link_to_invoice_pdf']                = 'Mostra Link Paga Ordine nel PDF (Non applicato sullo stato ordine è Cancellato)';
$lang['invoice_is_overdue']                   = 'Questo ordine è in ritardo di %s giorni';
$lang['next_invoice_date']                                  = 'Data successiva dell\'ordine: %s';
$lang['invoice_recurring_from']                             = 'Questa fattura viene creata dall\'ordine ricorrente con numero: %s';
$lang['child_invoices']                                     = 'Ordini relativi';
$lang['child_expenses']                                     = 'Spese per ordini relativi';
$lang['permission_payments_based_on_invoices']              = 'Sulla base delle autorizzazioni VIEW (proprie) ordini';
$lang['settings_paymentmethod_default_selected_on_invoice'] = 'Seleziona l\'impostazione predefinita sull\'ordine';
$lang['estimate_invoiced']                                  = 'ordinato';
$lang['invoice_not_found']                                  = 'Ordine non trovato';
$lang['invoice_activity_auto_converted_from_estimate']      = 'Auto ordine creato da preventivo con numero %s';
$lang['inv_hour_of_day_perform_auto_operations_help']       = 'Utilizzato per ordini ricorrenti, avvisi scaduti ecc..';
$lang['allow_primary_contact_to_view_edit_billing_and_shipping'] = 'Consenti il ​​contatto principale per visualizzare/modificare i dettagli di ordine e spedizione';
$lang['recurring_invoice_draft_notice']           = 'Questo ordine è con la bozza di stato, è necessario contrassegnare questo ordine come inviato. Gli ordini ricorrenti con il progetto di stato non saranno ricreate dal lavoro cron.';
# Version 1.8.0
$lang['not_customer_viewed_invoice']                         = 'È stata visualizzata un ordine con il numero %s';
$lang['invoiced_amount']                                     = 'Importo ordinato';
$lang['customer_statement_info']                             = 'Visualizzazione di tutte gli ordini e pagamenti tra %s e %s';
$lang['statement_invoice_details']                           = 'Ordine %s - dovuto per %s';
$lang['statement_payment_details']                           = 'Pagamento (%s) All\'ordine %s';
$lang['estimates_not_invoiced']                   = 'Non ordinato';
$lang['item_report_paid_invoices_notice']        = 'Il report dei prodotti è generato unicamente su ordini pagati al netto di sconti e tasse.';
$lang['show_pdf_signature_invoice']              = 'Mostra Firma PDF in Ordine';
$lang['invoice_cancelled_email_disabled']                  = 'L\'ordine è cancellato. Deseleziona come cancellata per abilitare la mail al cliente';
$lang['reccuring_invoice_option_gen_and_send']             = 'Genera e Autoinvia l\'ordine rinnovato al cliente';
$lang['reccuring_invoice_option_gen_unpaid']               = 'Genera una ordine Non Pagato';
$lang['reccuring_invoice_option_gen_draft']                = 'Genera una ordine Bozza';
$lang['expense_field_billable_help']           = 'Se ordinabile, %s può essere aggiunto in ordine come descrizione lunga.';
$lang['task_biillable_checked_on_creation']    = 'Vuoi che l\'opzione ordinabile sia attiva di default quando un\'attività viene creata?';
$lang['invoices_credited']                                       = 'Ordini Accreditati';
$lang['invoice_credits_applied']                                 = 'Crediti applicati correttamente all\'ordine';
$lang['credit_amount_bigger_then_invoice_balance']               = 'Ammontare del credito totale maggiore del totale ordine';
$lang['credited_invoices_not_found']                             = 'Ordine accreditato non trovato';
$lang['credit_invoice_number']                                   = 'Ordine Numero';
$lang['confirm_invoice_credits_from_credit_note']                = 'Quando crei una nota di credito partendo da un ordine non pagato, l\'ammontare del credito sarà applicato all\'ordine. Sei sicuro di voler procedere alla creazione della nota di credito?';
$lang['credit_invoice_date']                                     = 'Data Ordine';
$lang['apply_to_invoice']                                        = 'Applica all\'Ordine';
$lang['credits_successfully_applied_to_invoices']                = 'Credito Ordine correttamente applicato';
$lang['credit_note_no_invoices_available']                       = 'Non ci sono ordini disponibili per questo cliente';
$lang['show_total_paid_on_invoice']                              = 'Mostra totale pagato sugli ordini';
$lang['show_credits_applied_on_invoice']                         = 'Mostra credito applicato sull\'ordine';
$lang['show_amount_due_on_invoice']                              = 'Mostra Totale dovuto sulla\'ordine';
$lang['customer_profile_update_credit_notes']                    = 'Aggiorna le informazioni di ordinazione/spedizione di tutte le precedenti note di credito (Note di credito chiuse escluse)';
$lang['show_project_on_invoice']                  = 'Mostra il nome del progetto sull\'ordine';
$lang['showing_billable_tasks_from_project'] = 'Visualizza compiti ordinabili del progetto';
$lang['no_billable_tasks_found']             = 'Compiti ordinabili non trovati';
$lang['first_billing_date']                                     = 'Prima data di ordine';
$lang['next_billing_cycle']                                     = 'Prossimo ciclo di ordine';
$lang['cancel_at_end_of_billing_period']                        = 'Annulla alla fine del periodo di ordine';
$lang['billing_plan']                                           = 'Piano di ordine';
$lang['upcoming_invoice']                                       = 'Ordine in arrivo';
$lang['subscription_will_be_canceled_at_end_of_billing_period'] = 'Questo abbonamento verrà annullato alla fine del periodo di ordine.';
$lang['allow_staff_view_invoices_assigned']          = 'Consenti ai membri dello staff di visualizzare gli ordini a cui sono assegnati';
$lang['subscription_option_send_invoice']                       = 'Inviare l\'ordine';
$lang['subscription_option_send_payment_receipt_and_invoice']   = 'Invia ordine e ricevuta di pagamento';
$lang['last_invoice_date']      = 'Data ultima ordine';
$lang['next_invoice_date_list'] = 'Prossima ordine';
$lang['billable_amount']                                             = 'Importo ordinabile';
$lang['last_child_invoice_date']                                     = 'Data ultimo ordine relativo';
$lang['description_in_invoice_item']                                 = 'Includere la descrizione articolo in ordine';
$lang['description_in_invoice_item_help']                            = 'Utile se si desidera includere ulteriori informazioni sull\'ordine di abbonamento, cosa include questo abbonamento.';


// 26/03/24

$lang['invoice_dt_table_heading_amount']  = 'Importo mensile';
$lang['clients_invoice_dt_amount']  = 'Importo mensile';
$lang['estimate_dt_table_heading_amount']     = 'Importo mensile';
$lang['estimate_table_amount_heading']   = 'Importo mensile';
$lang['clients_estimate_dt_amount']             = 'Importo mensile';