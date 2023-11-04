<?php

return [
    
    //HEADER
    'head-version' => 'v. database: 4.0', 
    'head-sponsor' => '',//'<span style="background-color:rgba(255,255,0,1);">DATI DI PROVA</span>',//'realizzato a titolo gratuito<br>canone offerto da ___________',//realizzato grazie a un grant di <br><a href="http://www.baxteritalia.it/" target="_blank"> Baxter Italia</a>',
     
    //GENERAL
    'user' => 'utente','users' => 'utenti','session'=>'sessione','session_id'=>'id sessione',
    'home'=>'home','patients'=>'pazienti','centers'=>'centri','queries'=>'interrogazioni','query'=>'interrogazione',
    'contacts'=>'contatti',
    'completion'=>'compilazione', 
    'test'=>'test','name'=>'nome','surname'=>'cognome','description'=>'descrizione',

    'record_count'=>'n°risultati','show'=>'visualizza','refresh'=>'aggiorna',
    'patients_encoding'=>'tabella di traduzione codifica pazienti',
    'statistics'=>'statistiche',
    'year'=>'anno','month'=>'mese','day'=>'giorno', 
    'type'=>'tipo',
    'data_check'=>'data check',
    'parameters'=>'parametri',
    
    'help'=>'help', 
    //OPS
    'open'=>'apri','close'=>'chiudi',
    'preview'=>'preview',
    'login'=>'login','logout'=>'logout','language'=>'lingua',
    'send'=>'invia','level'=>'livello',   
    'save'=>'save','saving'=>'saving...','saved'=>'salvataggio eseguito',
    'delete'=>'delete','add'=>'add','deleting'=>'cancellazione in corso...','deleted'=>'cancellazione eseguita',
    'delete_confirm'=>'Cancellare il record?',
    'dirty_form_exit'=>'alcuni dati sono stati modificati. Uscire senza salvare?',
    'add'=>'aggiungi',
    'undo'=>'indietro',    
    'export'=>'esporta',
    'update'=>'aggiorna',
    'operations'=>'operazioni',
    'download'=>'scarica','format'=>'formato',
    'info'=>'info','table'=>'tabella','chart'=>'chart',
    //AUTH
    'username'=>'username','password'=>'password', 
    'remember me'=>'ricorda su questo computer', 'forgot your password'=>'ho dimenticato la password',    
    'user registration'=>'registrazione utente','confirm password'=>'conferma password','register'=>'registrati',    
    'setting_password'=>'impostazione della password','set_password'=>'imposta password',
    'send_reset_to_mail'=>'manda un email con link per il rinnovo della password',
    'sent_reset_to_mail'=>'email inviata il {time}<br><span style="font-size:80%">(L\'effettiva ricezione dipende dai server di posta potrebbe impiegare alcuni minuti.)</span>',
    'wrong_password_reset_token'=>'codice non associato ad un utente oppure già utilizzato per il settaggio della password',
    'disabled_user'=>'account disabilitato',        
    'PasswordResetMail'=>[
        //"intro"=>"E' stato richiesto un reset della password per l'account associato a questa email su ridp.it",
        "intro"=>"Questa email permette di impostare la password per accedere al Registro Italiano Dialisi Pediatrica con il nome utente: '{username}'.<br>",
        "actionText"=>"Imposta Password",
        "outro"=>"L'email sarà valida fino al {expDateTime}. <br>Si prega di non rispondere a questa email, generata automaticamente. Per richiedere nuovamente l'invio di questa email, o l'associazione dell'account a un diverso indirizzo email, contattare la <a href='mailto:enricoverrina@gaslini.org'>segreteria</a>.",//temporaneo
        //"text2"=>"",
    ],    
    

    //QUERIES
    'query_description'=>"Selezionata l'<b>interrogazione</b> dall'elenco, verranno compilate le sezioni opportune della pagina:<br>"
    . "il pannello con gli eventuali <b>parametri</b> da specificare sulla destra - per maggiori informazioni sul singolo parametro, puntare il mouse sul nome parametro (senza cliccare);<br>"
    . "il pannello <b>info</b> con una breve descrizione/note sull'interrogazione, ove necessario, e con il tasto di download dei risultati;<br>"
    . "il pannello <b>tabella</b> con la preview dei risultati stessi;<br>"
    . "il pannello <b>charts</b> con i grafici dell'interrogazione ove disponibili.",
    'query_select'=>'selezione dati',
    'query_from'=>'fonti dati',
    'query_where'=>'filtri',
    'query_order'=>'ordinamento per',
    'query_submit'=>'invia',
    'query_preview'=>'anteprima',     
    'query_result_not_valid_sql'=>'(query non riconosciuta)',
    'query_result_count'=>'risultati',
    'query_preview'=>'preview',
    'query_result_download'=>'scarica risultati',
    'new_query'=>'nuova interrogazione',
    'quedi_select_tooltip'=>'le tabelle da cui provengono i dati Nel caso si selezionino tabelle non contigue le tabelle intermedie aranno inserite automaticamente Es. inserendo paziente e terapia peritonite, anche i dati del ciclo dialitico e della peritonite saranno selezionati.',
    'quedi_from_tooltip'=>'le tabelle da cui provengono i dati Nel caso si selezionino tabelle non contigue le tabelle intermedie aranno inserite automaticamente Es. inserendo paziente e terapia peritonite, anche i dati del ciclo dialitico e della peritonite saranno selezionati.',
    'quedi_where_tooltip'=>'',
    'quedi_order_tooltip'=>'',    
    
    //RDP 
    //general:
    'main_tables_count'=>'conteggio record tabelle principali',
    'last_log_entries'=>'ultimi {n} accessi',
    'new_model_form'=>'nuova scheda {modelName}',
    'modified_records'=>'schede modificate',
    //centers:
    'center'=>'centro','center_management'=>'gestione centro','all_centers'=>'tutti i centri', 
    //patients:     
    'patients'=>'pazienti','patient'=>'paziente',
    'patients_code_generation_warning'=>'inserire <b>cognome, nome e data di nascita</b> facendo particolare attenzione ad errori di battitura: il codice generato da questi dati sarà utilizzato nel database del registro per tutti gli usi futuri, compresa la ricerca del paziente.',
    'patients_code'=>'codice paziente',
    'patients_dob'=>'data di nascita',    
    'patients_with_open_treatments_only'=>'solo pazienti in trattamento',
    'patients_center_only'=>'solo pazienti afferenti al centro',    
    "treatments_per_center"=>"pazienti per centro",
    'patients_add'=>'aggiungi paziente',
    'patients_code_check'=>'controllare il codice del paziente',
    'comorbidities_placeholder'=>'aggiungi comorbidità',
    'patients_under_treatment'=>'in trattamento','patients_not_updated'=>'non aggiornati',
    //treatments/deaths:
    'treatments'=>'cicli dialisi','treatment'=>'ciclo dialisi',  
    'treatments_open'=>'trattamenti in corso','treatments_close'=>'trattamenti chiusi',
    'treatments_add'=>'aggiungi ciclo/tx',    
    'death_form_not_present'=>'salvare il trattamento per compilare la scheda decesso',
    'death_form'=>'scheda decesso',    
    //follow ups
    'follow_up'=>'osservazione','follow_ups'=>'osservazioni',
    //follow up children:
    'clinical'=>'scheda clinica','biochemical'=>'dati biochimici',
    'clinicals'=>'scheda clinica','biochemicals'=>'dati biochimici',
    'pd_prescriptions'=>'prescrizioni','hd_prescriptions'=>'prescrizioni',
    'therapies'=>'terapie','therapies_copy'=>'copia terapie da osservazione precedente',
    'every_x_days'=>'ogni gg.',
    //pd connections/hd accesses:
    'pd_connection'=>'connessione','pd_connections'=>'connessioni',
    'connections'=>'connessioni',
    'hd_access'=>'accesso ed','accesses'=>'accessi',
    //peritoneal equilibration tests:
    'pets'=>'test eq per','pet_add'=>'aggiungi test equilibrazione peritoneale',
    //catheters, complications/medications catheter:
    'catheter'=>'catetere','catheters'=>'cateteri',
    'catheter_medications'=>'medicazioni catetere',
    'catheter_complications'=>'complicazioni catetere',
    //peritonites, per. therapies     
    'peritonitis'=>'peritonite','peritonites'=>'peritoniti',
    'peritonitis_diagnoses_placeholder'=>'nessuna diagnosi',
    'peritoneal_liquid_culture'=>'coltura liquido peritoneale',
    'complications'=>'complicanze',
    'period'=>'periodo',
    'drug'=>'farmaco',
    'peritonitis_therapies'=>'terapie peritonite',
    //MEDICAL MISCELLANEA:
    'medications'=>'medicazioni','complications'=>'complicanze',
    'exit_site_infection'=>'infezione emergenza cutanea','germ'=>'germe',
    'diagnostic_criteria'=>'criteri diagnostici','risk_factors'=>'fattori di rischio','peritoneal_liquid_culture'=>'coltura liquido peritoneale',
    'complications_warning'=>'le peritoniti vanno segnalate nella loro specifica scheda e non come una complicanza generica',
    'select_symptoms'=>'seleziona sintomi',
    'via'=>'via','administration_via'=>'via somministrazione',
    'loading_dose'=>'dose carico',
    'intermittent'=>'intermittente',
    //STATS RELATED:
    'stats'=>[        
        'treatments_per_year'=>'nuovi trattamenti per anno',
        'active_treatments'=>'trattamenti in corso',
        'forms_fill_in'=>'compilazione schede',
        'treatments_n'=>'numero trattamenti',
        'treatments_type'=>'tipo',
    ],    
    //MISCELLANEA:
    'days'=>'giorni','from'=>'da','to'=>'a',
    'city'=>'città',
    'specifics'=>'specifica','no_specifics'=>'nessuna specifica', 'specify'=>'specifica',
    'updated_to'=>'aggiornato al',
    'results'=>'risultati','show_results'=>'mostra risultati','copied'=>'copiati',


    //LINK & POPUP
    'popup_patient_search'=>'ricerca per cognome + nome + data di nascita',
    'popup_patient_insertion_privacy'=>'Perché inserire cognome e nome non viola la privacy del paziente?',
    //ERRORS
    'errors'=>[
        'not_specified'=>'errore non specificato',
        'not_updatable'=>'l\'utente non ha il permesso per modificare questo record',
        'only_past_date'=>'immettere una data passata',
        'future_date'=>'impossibile immettere una data futura',
        'required_field'=>'campo obbligatorio',
        //patient-death-treatments:
        'treatments_present'=>'impossibile cancellare una scheda paziente con trattamenti',
        'birth_death_order'=>'la data di decesso precede quella di nascita',
        'birth_treatments'=>'il trattamento precede la data di nascita',
        'death_treatments'=>'trattamenti con date posteriori alla data di decesso',
        'oldcenters_required'=>'specificare centri precedenti',
        //treatments:
        'treat_overlapping_treatments'=>'i periodi dei trattamenti si sovrappongono',
        'treat_non_overlapping_date'=>'la data non rientra nel trattamento di appartenenza',
        'treat_non_matching_type'=>'il tipo di trattamento non corrisponde al record',
        'treat_missing_end_cause'=>'specificare la causa di fine',
        'tx_failure_cause_required'=>'specificare la causa di fallimento trapianto',
        //followups and children:
        'followup_datetimeconsistency'=>'La data non corrisponde al tempo di osservazione',                
        'pd_prescription_empty_bsa'=>'Impossibile calcolare la BSA, compilare prima la scheda clinica',
        'calc_dose_missing'=>'la dose è richiesta per questo tipo di terapia',
        //peritonites & peritherapies:
        'perit_therapy_date'=>'le date di peritonite e di terapie non corrispondono',
        'perit_therapy_fromto'=>'l\'inizio della terapia precede la fine', 
        'perit_culture_execution'=>'compilare i campi di almeno una cultura peritoneale o il motivo di non esecuzione cultura',
        'perit_culture_date'=>'la data deve seguire quella di peritonite',
        //catheters & catheter medications & complications: 
        'catheter_removal_dates'=>'la data di rimozione precede quella di inserzione',
        'catheter_removal_reason_missing'=>'motivo rimozione mancante',
        'catheter_complications_date'=>'la data inserzione catetere precede quella della complicanza',
        'catheter_medications_date'=>'la data inserzione catetere precede quella della medicazione', 
    ],
    //WARNINGS
    'warnings'=>[
        'newly_added_patient'=>'ricordarsi di associare il codice paziente ({code}) alla sua identità',
        'catheter_insertion_date_to_treatment'=>'NB: la data di inserzione precede quella di inizio trattamento per più di {days} giorni',
    ],
    //STATIC
    'static'=>[
        'contacts'=>[
            'title'=>'contatti',
            'content'=>'<h3>Contatti</h3>'
            . '<table>'
            . '<tr><td colspan="2"><hr><b>Segreteria:</b></td></tr>'
            . '<tr><td>Enrico Verrina </td><td><a href="mailto:enrico.verrina@gaslini.org">enrico.verrina@gaslini.org</a></td></tr>'
            . '<tr><td>Salvatore Luzio </td><td><a href="mailto:trapiantorene@gaslini.org">trapiantorene@gaslini.org</a></td></tr>'
            . '<tr><td colspan="2"><hr><b>Assistenza / supporto tecnico:</b></td></tr>'
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
                "title"=>"privacy e cookie",
                "content"=>[                
                    "GDPR"=>[
                        "title"=>"Nota sulla gestione dei dati dei pazienti ai sensi del GDPR",
                        "content"=>                        

                            "Il Regolamento Europeo in materia di protezione dei dati personali (“GDPR”) definisce la pseudonimizzazione come «il trattamento dei dati personali in modo tale che i dati personali <i>non possano più essere attribuiti a un interessato specifico senza l’utilizzo di informazioni aggiuntive</i>, a condizione che tali informazioni aggiuntive siano conservate separatamente e soggette a misure tecniche e organizzative intese a garantire che tali dati personali non siano attribuiti a una persona fisica identificata o identificabile» (art. 4.5), GDPR). 

                            <a name='patient_privacy'><b><i>Perché inserire cognome e nome non viola la privacy?</i></b></a>
                            L’implementazione sul sito del Registro avviene come segue:
                            all’aggiunta di un paziente nuovo si immettono i dati cognome, nome, data di nascita. Questi dati sensibili vengono tradotti da un algoritmo (cosiddetto di “hash”) in un codice di identificazione tale che <b>gli stessi dati sensibili diano sempre lo stesso codice</b>, ma <b>dal codice non si possa risalire ai dati sensibili </b> (neanche conoscendo l’algoritmo, o avendo pieno accesso al database ecc.) 
                            Il codice viene creato sul browser dell'utente e <b>i dati sensibili vengono eliminati subito dopo la creazione senza essere stati trasmessi</b> <small><i>(1) (2)</i></small>.
                            Questo metodo fornisce la dovuta anonimizzazione ma rende molto improbabile la creazione di un duplicato involontario di un paziente, poiché cercando di inserire come nuovo un paziente già immesso risulterà che il codice è già presente  <small><i>(3)</i></small>.
                            Questo significa che si può cercare un paziente conoscendo i tre dati di cui sopra, ma non si può risalire all'identità del paziente guardando le sue schede. Per farlo occorre prendere nota del codice (o parte di esso).

                            <a name='patient_search'><b><i>Ricerca di un paziente</i></b></a>: 
                            La ricerca per cognome + nome + data di nascita funzionerà invece inserendo i tre dati <i>completi</i> che a loro volta creano il codice paziente, in base al quale avviene la ricerca (anche in questo caso i dati sensibili non lasciano il browser). 
                            Se il codice paziente viene memorizzato/scritto altrove si può cercare direttamente per codice; in questo caso vale anche la classica ricerca parziale (solo l'inizio del codice).
                            <a name='patient_search'><b><i>Ricerca di un paziente (vecchia codifica)</i></b></a>: 
                            I pazienti inseriti prima del 1 ottobre 2018 prevedevano la codifica nel formato:
                            <i>prime due lettere del cognome + prime due lettere del nome + data nascita formato ggmmaa + sesso</i> 
                            Non essendo ovviamente possibile riottenere sistematicamente nome e cognome di ogni paziente da questo codice, per la ricodifica sono state usate le sole lettere disponibili.
                            Quindi Mario Rossi n. 11/11/11, che sarebbe presente nel database vecchia codifica come MARO111111M, non andrebbe cercato con cognome e nome interi ma con cognome=MA + nome=RO + data di nascita.</i>
                             <small><i>
                            (1) la non trasmissione è in linea di principio verificabile visualizzando il codice delle pagine di aggiornamento. 
                            (2) la data di nascita avendo valenza medica viene conservata anche in chiaro nel database, ma ovviamente non costituisce “dato sensibile” se disgiunta da nome e cognome.
                            (3) In certa misura viene tenuto conto anche di alcuni errori di battitura, alcune possibili italianizzazioni del nome (usando un 'phonetic hash', ovvero pronuncia uguale = codifica uguale), numero variabili di spazi, maisucole/minuscole.
                            Ad es. 'Derossi Yolanda', 'De Rossi Jolanda ' e 'De rossi Iolanda ' vengono codificati nello stesso modo (se con stessa data di nascita ovviamente), come pure 'Kamila'='Camilla', 'Jimmy'=Gimmi', 'Amoz'='Amotz' ecc. 
                            </i></small>
                            <hr>
                            "
                    ], 
                    "cookies"=>[
                        "title"=>"Informativa sui cookie",
                        "content"=>
                            "La navigazione sul sito web www.ridp.it comporta l’invio di cookie e strumenti analoghi al terminale dell’utente.
                            Pertanto, con il presente documento, ai sensi degli artt. 13 e 122 del D. Lgs. 196/2003 (“codice privacy”), nonché in base a quanto previsto dal Provvedimento generale del Garante privacy dell’8 maggio 2014, Enrico Verrina, ℅ ospedale G. Gaslini, Largo Gaslini 5 16100 Genova, titolare del trattamento, fornisce agli utenti del sito alcune informazioni relative ai cookie o sistemi analoghi utilizzati o di cui si consente l’installazione.
                            I cookie sono stringhe di testo di piccole dimensioni che i siti visitati dall’utente inviano al suo terminale (solitamente al browser), dove vengono memorizzati per essere poi ritrasmessi agli stessi siti alla successiva visita del medesimo utente.
                            Si suddividono in cookie tecnici (utilizzati al solo fine di effettuare la trasmissione di una comunicazione su una rete di comunicazione elettronica o nella misura strettamente necessaria al fornitore di un servizio della società dell’informazione esplicitamente richiesto) o di profilazione (volti a creare profili relativi all’utente ed utilizzati al fine di inviare messaggi pubblicitari in linea con le preferenze manifestate dallo stesso nell’ambito della navigazione).
                            Questo sito utilizza cookie tecnici, rispetto ai quali, ai sensi dell’art. 122 del codice privacy e del Provvedimento del Garante dell’8 maggio 2014, <u>non è richiesto alcun consenso</u>.
                            Più precisamente il sito utilizza cookie tecnici strettamente necessari per consentire la navigazione da parte dell’utente ed, in assenza dei quali, il sito web non potrebbe funzionare correttamente, nonché cookie tecnici che agevolano la navigazione dell’utente anche mediante salvataggio delle preferenze di navigazione (ad esempio per impostare la lingua o la valuta) o per la gestione di statistiche da parte del titolare del sito.
                            <hr>"
                    ],
                    "policy"=>[
                        "title"=>"Privacy policy",
                        "content"=>
                            "Con questa informativa, resa anche ai sensi dell’art. 13 Reg. UE 2016/679, vengono descritte le modalità di gestione del sito web www.ridp.it con riferimento al trattamento dei dati degli utenti che lo consultano.
                            Questa informativa riguarda soltanto i dati trattati dal sito web riferito al nome a dominio www.ridp.it e non anche i dati trattati da altri siti web a cui si rimanda tramite link. 
                            <b>Titolare del trattamento</b>
                            Titolare del trattamento è il dr. Enrico Verrina, il quale può essere contattato ai seguenti recapiti: ℅ Istituto Gaslini, largo G. Gaslini 16100 Genova;  0105636642; e-mail enrico.verrina@gaslini.it; 
                            <hr>"
                    ],
                ],
            ],
        ],
    ],
];
