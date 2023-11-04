<?php

return [
    
    //HEADER
    'head-version' => 'database version: 4.0',
    'head-sponsor' => '',//'<span style="background-color:rgba(255,255,0,1);">TEST DATA</span>',//'realized free of charge by an anonymous benefactor<br>maintenance fee provided by_________',//'sponsored by<br><a href="http://www.baxteritalia.it/" target="_blank"> Baxter Italia</a>',    
        
    //GENERAL
    'user' => 'user','users' => 'users','session'=>'session','session_id'=>'session id',
    'home'=>'home','patients'=>'patients','centers'=>'centers','queries'=>'queries','query'=>'query',
    'contacts'=>'contacts',
    'completion'=>'completion',
    'test'=>'test','name'=>'name','surname'=>'surname','description'=>'description',

    'record_count'=>'records count','show'=>'show','refresh'=>'refresh',
    'patients_encoding'=>'patients encoding translation table',//temp
    'statistics'=>'statistics',
    'year'=>'year','month'=>'month','day'=>'day',
    'type'=>'type',
    'data_check'=>'data check',
    'parameters'=>'parameters',
    
    'help'=>'help', 
    //OPS
    'open'=>'open','close'=>'close',
    'preview'=>'preview',    
    'login'=>'login','logout'=>'logout','language'=>'language',
    'send'=>'send','level'=>'level',
    'save'=>'save','saving'=>'saving...','saved'=>'saved',
    'delete'=>'delete','add'=>'add','deleting'=>'deleting...','deleted'=>'deleted',    
    'delete_confirm'=>'Are you sure you want the delete the record?',
    'dirty_form_exit'=>'the record has unsaved changes. Are you sure you want to exit?',
    'add'=>'add',
    'undo'=>'undo',   
    'export'=>'export',
    'update'=>'update',
    'operations'=>'operations',
    'download'=>'download','format'=>'format',
    'info'=>'info','table'=>'table','chart'=>'chart',    
    //AUTH
    'username'=>'username','password'=>'password',
    'remember me'=>'remember me', 'forgot your password'=>'forgot your password',
    'user registration'=>'user registration','confirm password'=>'confirm password','register'=>'register',
    'setting_password'=>'password setting','set_password'=>'set password', 
    'send_reset_to_mail'=>'send email with a link to reset password',
    'sent_reset_to_mail'=>'email sent on {time}<br><span style="font-size:80%">(Actual receptions depends onmail servers and could take some minute.)</span>',
    'wrong_password_reset_token'=>'token not associated with any user or already used',
    'disabled_user'=>'account disabled',
    'PasswordResetMail'=>[
        "intro"=>"A password reset for the account {username} has been requested.",
        "action"=>"Reset Password",
        "outro"=>"",
    ],        
    
    //QUERIES
    'query_select'=>'select data',
    'query_from'=>'data sources',
    'query_where'=>'data filters',
    'query_order'=>'order by',
    'query_submit'=>'submit',
    'query_preview'=>'preview',
    'query_result_not_valid_sql'=>'(not valid query)',
    'query_result_count'=>'results',
    'query_preview'=>'preview',
    'query_result_download'=>'download results',
    'new_query'=>'new query',
    'quedi_select_tooltip'=>'',
    'quedi_from_tooltip'=>'',
    'quedi_where_tooltip'=>'',
    'quedi_order_tooltip'=>'',
    
    //RDP 
    //general:
    'main_tables_count'=>'main tables records count',
    'last_log_entries'=>'last {n} accesses',
    'new_model_form'=>'new {modelName} form',
    'modified_records'=>'forms modified',
    //centers:
    'center'=>'center','center_management'=>'center management','all_centers'=>'all centers',    
    //patients:
    'patients'=>'patients','patient'=>'patient',
    'patients_code_generation_warning'=>'insert <b>surname, name and date of birth</b> paying special attention to typos: the patient code which will be created from these data will identify the patient within the db registry for all future purposes. Name and surname won\'t be stored.',
    'patients_code'=>'patient code',
    'patients_dob'=>'date of birth',    
    'patients_with_open_treatments_only'=>'only patients under treatment',
    'patients_center_only'=>'only patients from the current center',
    'active_patients_per_center'=>'patients under treatment',
    'patients_add'=>'add patient',
    'patients_code_check'=>'please check format code',
    'comorbidities_placeholder'=>'add comorbidities',
    'under_treatment'=>'under treatment','patients_not_updated'=>'not updated patients',
    //treatments/deaths:
    'treatments'=>'treatments','treatment'=>'treatment',
    'treatments_open'=>'open treatments','treatments_close'=>'close treatments',
    'treatments_add'=>'add treatment/tx',
    'death_form_not_present'=>'save treatment form to fill in the death form',
    'death_form'=>'death form',
    //follow ups
    'follow_up'=>'follow up','follow_ups'=>'follow ups',
    //follow up children:
    'clinical'=>'clinical data','biochemical'=>'biochemical data',
    'clinicals'=>'scheda clinica','biochemicals'=>'dati biochimici',
    'pd_prescriptions'=>'prescriptions','hd_prescriptions'=>'prescriptions',
    'therapies'=>'therapies','therapies_copy'=>'copy therapies from previous follow up',
    'every_x_days'=>'every days',
    //pd connections/hd accesses:
    'pd_connection'=>'connection','pd_connections'=>'connections',
    'connections'=>'connections',
    'hd_access'=>'hd accesso','accesses'=>'accesses',
    //peritoneal equilibration tests:
    'pets'=>'peritoneal eq tests',
    //catheters, complications/medications catheter:
    'catheter'=>'catheter','catheters'=>'catheters',
    'catheter_medications'=>'catheter medications',
    'catheter_complications'=>'catheter complications',
    //peritonites, per. therapies 
    'peritonitis'=>'peritonits','peritonites'=>'peritonites',
    'peritonitis_diagnoses_placeholder'=>'no diagnoses',
    'peritoneal_liquid_culture'=>'peritoneal liquid culture',
    'complications'=>'complications',  
    'period'=>'period',
    'drug'=>'drug',
    'peritonitis_therapies'=>'peritonitis therapies',
    //MEDICAL MISCELLANEA:
    'medications'=>'medications','complications'=>'complications',
    'exit_site_infection'=>'exit site infection','germ'=>'germ',
    'diagnostic_criteria'=>'diagnostic criteria','risk_factors'=>'risk factors','peritoneal_liquid_culture'=>'peritoneal liquid culture',
    'complications_warning'=>'peritonites should be signaled in their specific form and not as a generic complication',
    'select_symptoms'=>'select symptoms',
    'via'=>'via','administration_via'=>'administration via',
    'loading_dose'=>'loading dose',
    'intermittent'=>'intermittent', 
    //STATS RELATED:
    'stats'=>[        
        'treatments_per_year'=>'new treatments per year',
        'active_treatments'=>'current treatments',
        'forms_fill_in'=>'forms filling in',
        
        'treatments_n'=>'treatments number',
        'treatments_type'=>'type',
    ],    
    //MISCELLANEA:
    'days'=>'days','from'=>'from','to'=>'to',
    'city'=>'city',
    'specifics'=>'specifics','no_specifics'=>'no specifics','specify'=>'specify',
    'updated_to'=>'updated to',
    'results'=>'results','show_results'=>'show results','copied'=>'copied',
    
    //LINK & POPUP
    'popup_patient_search'=>'search by name + surname + date of birth',
    'popup_patient_insertion_privacy'=>'Why fill in name and family name doesn\'t violate patient privacy laws?',    
    //ERRORS
    'errors'=>[
        'not_specified'=>'unspecified error',
        'not_updatable'=>'the user is not authorized to update this record',
        'only_past_date'=>'past date required',
        'future_date'=>'non future date required', 
        'required_field'=>'required field',
        //patient-death-treatments:
        'treatments_present'=>'deleting a patient form with treatments is not allowed',
        'birth_death_order'=>'death predates birth',
        'birth_treatments'=>'the treatment/transplantation predates birth',        
        'death_treatments'=>'the treatment/transplantation follows death date',
        'oldcenters_required'=>'specify center',
        //treatments:
        'treat_overlapping_treatments'=>'overlapping treatments',
        'treat_non_overlapping_date'=>'date doesn\'t fall within the parent treatment',
        'treat_non_matching_type'=>'treatment type doesn\'t match with the record',
        'treat_missing_end_cause'=>'specify the cause of treatment end',
        'tx_failure_cause_required'=>'specify the cause of tx failure',
        //followups and children:
        'followup_datetimeconsistency'=>'The date is not consistent with the followup time',
        'pd_prescription_empty_bsa'=>'BSA can\'t be calculated, please compile clinical form first',
        'calc_dose_missing'=>'dose is required for this kind of therapy',
        //peritonites & peritherapies:
        'perit_therapy_date'=>'peritonitis and therapies dates doesndon\'tmatch',
        'perit_therapy_fromto'=>'therapy beginning day predates end',
        'perit_culture_execution'=>'please fill in at least one culture date+results or reason for no culture execution',
        'perit_culture_date'=>'the date should follow the peritonitis date',
        //catheters & catheter medications & complications:   
        'catheter_removal_dates'=>'removal date precede insertion date',
        'catheter_removal_reason_missing'=>'missing removal reason',
        'catheter_medicompli_date'=>'catheter insertion predates complication',
        'catheter_medications_date'=>'catheter insertion predates medication',         
    ],
    //WARNINGS
    'warnings'=>[
        'newly_added_patient'=>'remember to associate the patient id ({code}) to his/her identity',
        'catheter_insertion_date_to_treatment'=>'NB insertion date precede treatment start by more than {days} days',
    ],
    //STATIC
    'static'=>[
        'contacts'=>[
            'title'=>'contacts',
            'content'=>'<h3>Contacts</h3>'
            . '<table>'
            . '<tr><td colspan="2"><hr><b>Secreteriat:</b></td></tr>'
            . '<tr><td>Enrico Verrina </td><td><a href="mailto:enrico.verrina@gaslini.org">enrico.verrina@gaslini.org</a></td></tr>'
            . '<tr><td>Salvatore Luzio </td><td><a href="mailto:trapiantorene@gaslini.org">trapiantorene@gaslini.org</a></td></tr>'
            . '<tr><td colspan="2"><hr><b>Tech support:</b></td></tr>'
            . '<tr><td>Stefano Varriale </td><td><a href="mailto:assistenza@ridp.it">assistenza@ridp.it</a></td></tr>'
            . '<tr><td colspan="2"><hr></td></tr>'
            . '</table>'
        ],
        'help'=>[
            'queries'=>[
                "title"=>"interrogazioni",
                "content"=>[
                    "MAIN"=>
                        ["title"=>" ",
                        "content"=>
                        "Selezionata l'interrogazione dall'elenco, verranno compilate le sezioni opportune della pagina:<br>"
                    . "il pannello con gli eventuali <b>parametri</b> da specificare sulla destra - per maggiori informazioni sul singolo parametro, puntare il mouse sul nome parametro (senza cliccare);<br>"
                    . "il pannello <b>info</b> con una breve descrizione/note sull'interrogazione, ove necessario, e con il tasto di download dei risultati;<br>"
                    . "il pannello <b>tabella</b> con la preview dei risultati stessi;<br>"
                    . "il pannello <b>charts</b> con i grafici dell'interrogazione ove disponibili."
                            ]
                ]
            ],
            'privacy'=>[
                "title"=>"Informative sulla privacy",
                "content"=>[
                    "cookies"=>[
                        "title"=>"informativa sui cookie",
                        "content"=>
                            "La navigazione sul sito web www.ridp.it comporta l’invio di cookie e strumenti analoghi al terminale dell’utente.
                            Pertanto, con il presente documento, ai sensi degli artt. 13 e 122 del D. Lgs. 196/2003 (“codice privacy”), nonché in base a quanto previsto dal Provvedimento generale del Garante privacy dell’8 maggio 2014, Enrico Verrina, ℅ ospedale G. Gaslini, Largo Gaslini 5 16100 Genova, titolare del trattamento, fornisce agli utenti del sito alcune informazioni relative ai cookie o sistemi analoghi utilizzati o di cui si consente l’installazione.
                            I cookie sono stringhe di testo di piccole dimensioni che i siti visitati dall’utente inviano al suo terminale (solitamente al browser), dove vengono memorizzati per essere poi ritrasmessi agli stessi siti alla successiva visita del medesimo utente.
                            Si suddividono in cookie tecnici (utilizzati al solo fine di effettuare la trasmissione di una comunicazione su una rete di comunicazione elettronica o nella misura strettamente necessaria al fornitore di un servizio della società dell’informazione esplicitamente richiesto) o di profilazione (volti a creare profili relativi all’utente ed utilizzati al fine di inviare messaggi pubblicitari in linea con le preferenze manifestate dallo stesso nell’ambito della navigazione).
                            Questo sito utilizza cookie tecnici, rispetto ai quali, ai sensi dell’art. 122 del codice privacy e del Provvedimento del Garante dell’8 maggio 2014, non è richiesto alcun consenso.
                            Più precisamente il sito utilizza cookie tecnici strettamente necessari per consentire la navigazione da parte dell’utente ed, in assenza dei quali, il sito web non potrebbe funzionare correttamente, nonché cookie tecnici che agevolano la navigazione dell’utente anche mediante salvataggio delle preferenze di navigazione (ad esempio per impostare la lingua o la valuta) o per la gestione di statistiche da parte del titolare del sito."
                    ],
                    "policy"=>[
                        "title"=>"Privacy policy",
                        "content"=>
                            "Con questa informativa, resa anche ai sensi dell’art. 13 Reg. UE 2016/679, vengono descritte le modalità di gestione del sito web www.ridp.it con riferimento al trattamento dei dati degli utenti che lo consultano.
                            Questa informativa riguarda soltanto i dati trattati dal sito web riferito al nome a dominio www.ridp.it e non anche i dati trattati da altri siti web a cui si rimanda tramite link. 
                            <b>Titolare del trattamento</b>
                            Titolare del trattamento è il dr. Enrico Verrina, il quale può essere contattato ai seguenti recapiti: ℅ Istituto Gaslini, largo G. Gaslini 16100 Genova;  0105636642; e-mail enrico.verrina@gaslini.it; 
                            TODO"
                    ],
                    "GDPR"=>[
                        "title"=>"Nota sulla gestione dei dati dei pazienti ai sensi del GDPR",
                        "content"=>
                            "[Perché inserire nome e cognome non viola la privacy?]
                            Il Regolamento Europeo in materia di protezione dei dati personali (“GDPR”) definisce la pseudonimizzazione come «il trattamento dei dati personali in modo tale che i dati personali non possano più essere attribuiti a un interessato specifico senza l’utilizzo di informazioni aggiuntive, a condizione che tali informazioni aggiuntive siano conservate separatamente e soggette a misure tecniche e organizzative intese a garantire che tali dati personali non siano attribuiti a una persona fisica identificata o identificabile» (art. 4.5), GDPR). 

                            L’implementazione sul sito del Registro avviene con la pseudonimizzazione:
                            all’aggiunta di un paziente nuovo si immettono i dati nome, cognome, data di nascita. Questi dati sensibili (1) vengono tradotti da un algoritmo (cosiddetto di “hash”) in un codice di identificazione con queste caratteristiche:
                            i dati originari daranno sempre lo stesso codice, ma
                            dal codice non si può risalire ai dati originari - neanche conoscendo l’algoritmo, o avendo pieno accesso al database ecc.: 
                            Creato questo codice i dati sensibili vengono eliminati dal browser dell’utente senza averlo mai lasciato(2).
                            Questo metodo fornisce la dovuta anonimizzazione ma rende improbabile la creazione di un duplicato involontario di un paziente, poiché inserendo “Nome=Giulia, Cognome=Rossi , data nascita=1/1/2000” risulterà che il codice è già presente(3).
                            Ricerca di un paziente: la ricerca può avvenire sia per codice che per nome + cognome + data di nascita (dati che anche in questo caso vengono immediatamente codificati dal browser stesso ed eliminati). Questo può esimere, a rigore, dal “conservare separatamente (...) informazioni aggiuntive”, come recita il Regolamento.

                            (1) Insieme ad altri
                            (2) Il tipo id algoritmo e la non trasmissione dei dati sono potenzialmente verificabili da un tecnico, semplicemente accedendo alle pagine con un qualunque account. 
                            (3) la data di nascita ha ovvia valenza medica e viene conservata, ma altrettanto ovviamente non costituisce “dato sensibile” se disgiunta da nome e cognome
                            (4) In certa misura viene tenuto conto anche di errori di battitura.
                            "
                    ],                
                ],
            ],
        ],   
    ],
];
