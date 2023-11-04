<?php
return [
    "categories" => [
        "solo admin" => [
            "privileges" => [100],
            "list" => [
                "sessions_by_center_it"
                //SUB CAT1" => ["privileges" => [10, 100], "list" => ["active_treatments_it", "treatments_start_per_year_it"]]
            ]
        ],
        "temporaneo" => [
            "privileges" => [10, 100],
            "list" => ["patients_open_treatments_it","peritonites_as_complications_it"],
        ],
        "interrogazioni custom" => [
            "privileges" => [10, 100],
            "list" => ["treatments_info_1_it","transplantations_it","preposttx_complications_it","patients_by_therapies_it","complications_union_peritonites_it","complications_union_peritonites_union_nocp_it"],
        ],
        "statistiche" => [
            "privileges" => [10, 100],
            "list" => [
                "active_treatments_it", "treatments_per_year_it", "treatment_days_per_year_it", "treatments_start_per_year_it", "treatments_end_cause_it", "treatment_change_causes_it","treatment_durations_it",
                "peritonites_prob_it",  
                "comorbidities_n_it",
                "complications_per_year_it",
                "peritonites_per_year_it",
                "hospitalizations_per_year_it",
                "data_distributions_it",
                "peritonites_date_it",
                "completion_by_year_it"
            ],
        ],
        "tabelle base"=>[
            "privileges"=>[1,10,100],
            "list"=>["centers_it", "patients_it","deaths_it","treatments_it", 
                "follow_ups_it","clinicals_it","biochemicals_it","pd_prescriptions_it","hd_prescriptions_it",
                "pd_connections_it","hd_accesses_it","catheters_it","peritonites_it","peritoneal_equilibration_tests_it","complications_it",],
        ],
        "liste riferimento"=>[
            "privileges"=>[1,10,100],
            "list"=>["_l_primary_renal_diseases_it","_l_comorbidities_it","_l_death_causes_it","_l_treatment_change_causes_it","_l_treatment_end_causes_it",
                "_l_therapies_it","_l_pd_connections_it","_l_hd_accesses_it",
                "_l_catheter_types_it","_l_catheter_disinfectants_it","_l_catheter_removal_reasons_it","_l_catheter_complications_it","_l_catheter_complication_symptoms_it",
                "_l_peritonitis_germs_it","_l_peritonitis_diagnoses_it","_l_peritonitis_therapies_it",], 
        ],        
    ], 
    "details"=>[ 
        //CUSTOM:
        "dummy_it"=>[//basic template with parameters, copy and edit
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"titolo it","desc_it"=>"sottotitolo it","notes_it"=>"",
            "sql"=>"SELECT * FROM treatments 
                    WHERE 
                    YEAR(start_date) >= :min_start_year 
                    AND 
                    `età a inizio trattamento`>= :min_age 
                    ",
            "parameters"=>[
                "min_start_year"=>["name_it"=>"dal","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>0],
                "min_age"=>["name_it"=>"età iniziale min","default"=>0],
            ],
        ],
        "patients_open_treatments_it"=>[//TEMPORARY
            "name_it"=>"verifica aggiornamenti",
            "desc_it"=>"log degli ultimi accessi con aggiornamento pazienti",
            "sql"=>"SELECT center_id AS center, id as patient, code as patient_code,
                CONCAT('aggiornamento al ', DATE_FORMAT(last_complete_update,'%d/%m/%Y')) AS text_it,'' AS note_it 
                FROM patients_treatments_ 
                WHERE treats_open>0 
                ORDER BY last_complete_update ASC"
        ],"peritonites_as_complications_it"=>[//TEMPORARY
            "name_it"=>"peritonite come complicanze",
            "desc_it"=>"Trova complicanze con descrizione '*peri*' e peritoniti dello stesso ciclo, e con differenza di data minore del parametro specificato). Ordinate per differenza assoluta crescente di date",
            "sql"=>"SELECT 
                treats.paziente AS 'paziente',
                CONCAT('<a href=\'https://ridp.it/edit/treatment/',treats.id,'\' target=\'_blank\'>',treats.tipo,' ',treats.`inizio trattamento`,'>') AS 'ciclo' ,
                DATE_FORMAT(compli.date,'%d/%m/%Y') AS 'data complic.',
                DATE_FORMAT(peri.date,'%d/%m/%Y') AS 'data perit.',
                DATEDIFF(compli.date,peri.date) AS 'diff.',
                compli.description AS 'descrizione complic.',
                compli.dialysis_related AS 'rel.dial.' 
                FROM view_complications_it compli
                LEFT JOIN view_peritonites_it peri
                ON compli.parent=peri.parent AND ABS(DATEDIFF(compli.date,peri.date))<= :maxdiff
                LEFT JOIN view_treatments_it treats
                ON treats.id=compli.parent
                WHERE compli.description LIKE '%peri%' AND peri.id IS NOT NULL 
                ORDER BY ABS(DATEDIFF(compli.date,peri.date)) ASC",
            "parameters"=>[
                "maxdiff"=>["name_it"=>"differenza di date<u>+</u>","list_source"=>["1"=>"1","3"=>"3","5"=>"5","10"=>"10","30"=>"30","60"=>"60","365"=>"365"],"default"=>5],
            ]
        ],
        "treatments_info_1_it"=>[
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"info generali trattamenti","desc_it"=>"dati più comuni sui trattamenti dialitici","notes_it"=>"NB: 'n°' è il numero trattamento all'interno del Registro",
            "sql"=>"SELECT view_patients_it.codice, `inizio trattamento`,`fine trattamento`, tipo, N as `n°`, `trattamento precedente`, `causa fine`, `data di nascita`, `età a inizio trattamento`, `durata trattamento`, `causa cambio tecnica`, `Causa decesso`, prd AS `malattia di base`, `prd (codice edta)` AS `codice malattia di base`, centro AS `codice centro` 
                    FROM view_treatments_it 
                    LEFT JOIN treatments_count ON treatments_count.id=view_treatments_it.id
                    LEFT JOIN view_patients_it ON view_treatments_it.parent=view_patients_it.id
                    LEFT JOIN view_deaths_it ON view_deaths_it.parent=view_patients_it.id
                    WHERE 
                    YEAR(view_treatments_it.start_date) >= :min_start_year AND YEAR(view_treatments_it.start_date) <= :max_start_year 
                    AND 
                    `età a inizio trattamento`>= :min_age AND `età a inizio trattamento`<= :max_age 
                    AND IF(':former_treatment'='',1,`trattamento precedente`=':former_treatment')
                    AND N<=:N 
                    ORDER BY view_treatments_it.start_date
                    ",
            "parameters"=>[
                "min_start_year"=>["name_it"=>"dal","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>0],
                "max_start_year"=>["name_it"=>"al","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>2017],
                "min_age"=>["name_it"=>"età iniziale min","default"=>0],
                "max_age"=>["name_it"=>"età iniziale max","default"=>100],
                "former_treatment"=>["name_it"=>"trattamento precedente","list_source"=>[NULL=>"","CT"=>"CT","TX"=>"TX","PD"=>"PD","HD"=>"HD"],"default"=>""],                
                "N"=>["name_it"=>"n°trattamento","list_source"=>["1"=>"1°","100"=>"tutti"],"default"=>100],
            ],
        ],       
        "transplantations_it"=>[
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"trapianti noti al Registro","desc_it"=>"preemptive tx + trapianti basati su fine dialisi=trapianto","notes_it"=>"",
            "sql"=>"
                SELECT 
                    DATE_FORMAT(treats.date,'%d/%m/%Y') as data,
                    patients.code as code,
                    treats.type, N as  `preceded by treatments`, 
                    CONCAT('https://ridp.it/edit/patient/',patients.id) as link                                        
                FROM
                    (SELECT id, parent, start_date AS date, 'preemptive' AS type FROM treatments 
                    WHERE treatments.type='TX'
                    UNION
                    SELECT id, parent, end_date AS date,  
                    CONCAT(IF(end_cause=1,'cadaveric','living donor'),' transplant after ',treatments.type) AS type 
                    FROM treatments 
                    WHERE treatments.end_cause=1 OR treatments.end_cause=2
                    ORDER BY date
                    ) treats
                LEFT JOIN treatments_count ON treats.id=treatments_count.id
                LEFT JOIN patients ON treats.parent=patients.id
                WHERE 
                    YEAR(date) >= :min_start_year AND YEAR(date) <= :max_start_year 
                ",
            "parameters"=>[
                "min_start_year"=>["name_it"=>"dal","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>2000],
                "max_start_year"=>["name_it"=>"al","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>2017],
            ], 
        ], 
        "patients_by_therapies_it"=>[//search for patients with a certain therapy
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"ricerca pazienti per terapia","desc_it"=>"pazienti con terapie specificate",
            "notes_it"=>"I parametri che riguardano la terapia sono in 'or', quindi ad es. inserire:<br>farmaco='cinacalcet' + specifica 1='cinacalcet' + specifica 2='mimpara'<br>amplia al massimo la ricerca del cinacalcet, trovando sia le voci inserite correttamente che quelle in cui il farmaco è inserito sotto 'altro' con principio attivo <i>o</i> nome commerciale.",
            "sql"=>"
                SELECT                 
                CONCAT('<a href=\'https://ridp.it/edit/patient/',patient_id,'\' target=\'blank\'>','•','</a>') AS ' ',
                center_code as centro, 
                patient_code as paziente,                 
                DATE_FORMAT(min(follow_ups.date),'%d/%m/%Y') as `prima terapia`,
                DATE_FORMAT(max(follow_ups.date),'%d/%m/%Y') as `ultima terapia`,                
                count(therapies.id) AS 'n° voci',
                GROUP_CONCAT(DISTINCT _l_therapies.name_it) as farmaco, 
                GROUP_CONCAT(therapies.specification SEPARATOR '; ') as specifiche
                FROM therapies
                JOIN _l_therapies ON _l_therapies.id=therapies.type
                JOIN follow_ups ON follow_ups.id=therapies.parent
                JOIN treatments_ ON treatments_.id=follow_ups.parent 
                WHERE 
                IF(:sel_center_id=0,true,center_id =:sel_center_id)
                AND
                year(follow_ups.date)>=:drug_start_year
                AND
                IF(:drug_id=-1 AND ':drug_specifics_1'='' AND ':drug_specifics_2'='',false,
                    therapies.type= :drug_id 
                    OR
                    IF(':drug_specifics_1'='',false,therapies.specification LIKE '%:drug_specifics_1%')
                    OR
                    IF(':drug_specifics_2'='',false,therapies.specification LIKE '%:drug_specifics_2%')
                )
                GROUP BY paziente, _l_therapies.id
                ORDER BY  centro,`prima terapia`
                ",
            "parameters"=>[ 
                "sel_center_id"=>["name_it"=>"centro","list_source"=>"SELECT 0,'tutti' FROM centers UNION (SELECT id,CONCAT(code,' - ',city) FROM centers ORDER BY code)","default"=>""],
                "drug_start_year"=>["name_it"=>"anno di partenza (terapia)","list_source"=>"SELECT 1000,'dall\'inizio' UNION (SELECT year(date),year(date) FROM follow_ups GROUP BY year(date))","default"=>""],
                "drug_id"=>["name_it"=>"farmaco","list_source"=>"SELECT -1,'[selezionare farmaco]' FROM _l_therapies UNION (SELECT id,name_it FROM _l_therapies ORDER BY name_it)","default"=>"","desc_it"=>"test"],
                "drug_specifics_1"=>["name_it"=>"ricerca testuale nella specifica (1)","default"=>""],
                "drug_specifics_2"=>["name_it"=>"ricerca testuale nella specifica (2)","default"=>""], 
            ],
        ],
        "preposttx_complications_it"=>[
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"complicanze pre-post tx","desc_it"=>"complicanze pre e post trapianto basati su tabella di input - WORK IN PROGRESS","notes_it"=>"",
            "sql"=>"
                SELECT __q_20200315_input .*,results.*  FROM (
                SELECT
                SUM(IF(
                complipatients.date>=COALESCE(range1_start,'2100-01-01') 
                AND 
                complipatients.date<range1_end,
                1,0)) AS 'complicazioni pre trapianto',

                SUM(IF(
                complipatients.date>=COALESCE(range1_start,'2100-01-01') 
                AND 
                complipatients.date<range1_end
                AND 
                dialysis_related=1,
                1,0)) AS 'complicazioni pre trapianto legate alla dialisi',

                SUM(IF(
                complipatients.date>=COALESCE(range1_start,'2100-01-01') 
                AND 
                complipatients.date<range1_end
                AND 
                dialysis_related IS NULL,
                1,0)) AS 'complicazioni pre trapianto sconosciute',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end,
                1,0)) AS 'complicazioni post trapianto',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end
                AND 
                dialysis_related=1,
                1,0)) AS 'complicazioni post trapianto legate alla dialisi',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end
                AND 
                dialysis_related IS NULL,
                1,0)) AS 'complicazioni post trapianto sconosciute',

                mixedcode

                FROM 
                        (#corrected input: univocal patient code and standard date formats
                        SELECT    	
                __q_20200315_input.codicepaziente_1 as mixedcode,
                        patients.id AS patient,patients.code AS code,
                STR_TO_DATE(datainiziodialisipretx,'%d/%m/%Y') as range1_start,
                STR_TO_DATE(data1trapianto,'%d/%m/%Y') as range1_end,
                STR_TO_DATE(iniziociclodialisiposttx,'%d/%m/%Y') as range2_start,
                STR_TO_DATE(fineciclodialisiposttx,'%d/%m/%Y') as range2_end
                FROM 
                 `__q_20200315_input`
                LEFT JOIN 
                patients
                ON patients.code=codicepaziente_1 OR patients.code=codicepaziente_2
                ) input_table
                LEFT JOIN 
                (# patient’s complications regardless of treatment
                SELECT treatments.parent AS patient, date, dialysis_related
                FROM complications JOIN treatments ON complications.parent = treatments.id
                ) complipatients
                ON complipatients.patient=input_table.patient
                GROUP BY input_table.patient
                ) results

                JOIN __q_20200315_input ON __q_20200315_input.codicepaziente_1=results.mixedcode

                    ",
            "parameters"=>[
                
            ],
        ], 

        "complications_union_peritonites_it"=>[
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"complicanze e peritoniti","desc_it"=>"","notes_it"=>"campi complicanze union peritoniti separati su richiesta<br>complicanza: causa=&gt;complicanza: descrizione",
            "sql"=>"
                SELECT 
                pats.codice AS 'codice paziente',
                pats.centro AS 'codice centro',
                pats.`data di nascita`,
                pats.prd AS 'malattia di base',
                pats.`prd (codice edta)` AS 'codice malattia di base',
                COALESCE(comorbidities.N,0) AS 'n° comorbidità',
                comorbidities.desc_it_1 AS 'com.1',
                comorbidities.desc_it_2 AS 'com.2',
                comorbidities.desc_it_3 AS 'com.3',
                treats.`età a inizio trattamento`,
                treats.`inizio trattamento`,
                treats.`fine trattamento`,
                treats.tipo as 'tipo trattamento',
                treatments_count.N AS 'n° ciclo',
                IF(treatments.end_cause=4,1,0) AS 'cambio tecnica', 
                treats.`trattamento precedente` AS 'trattamento precedente',
                treats.`causa fine` AS 'causa fine ciclo' ,
                ROUND(treatments_.treatment_duration/30.44,1) AS 'durata trattamento (mesi)',
                COALESCE(treats.`causa cambio tecnica`) AS `causa cambio tecnica`,
                IF(treatments.end_cause=3,1,0) AS 'decesso', 
                IF(treatments.end_cause=3,deaths_.cause_it,'') AS 'causa decesso' ,
                `complicanza`,
                `complicanza: descrizione`,
                `complicanza: associata alla dialisi`,
                `complicanza: data`,
                `complicanza: gg.ricovero`,
                `peritonite`,
                `peritonite: data`,
                `peritonite: gg.ricovero`,
                `peritonite: diagnosi`,
                `peritonite: 1° coltura liquido peritoneale`


                FROM
                (
                SELECT 
                id,parent,
                1 AS 'complicanza',
                compli.description AS 'complicanza: descrizione',
                COALESCE (compli.dialysis_related,'') AS 'complicanza: associata alla dialisi',
                DATE_FORMAT(compli.date,'%d/%m/%Y') AS 'complicanza: data',
                compli.hospitalization_days AS 'complicanza: gg.ricovero',
                0 AS 'peritonite',
                '' AS 'peritonite: data',
                '' AS 'peritonite: gg.ricovero',
                '' AS 'peritonite: diagnosi',
                '' AS 'peritonite: 1° coltura liquido peritoneale'
                FROM view_complications_it compli
                UNION
                SELECT 
                id,parent,
                0 AS 'complicanza',
                '' AS 'complicanza: descrizione',
                '' AS 'complicanza: associata alla dialisi',
                '' AS 'complicanza: data',
                '' AS 'complicanza: gg.ricovero',
                1 AS 'peritonite',
                peri.data AS 'peritonite: data',
                peri.`giorni ricovero` AS 'peritonite: gg.ricovero',
                peri.diagnosi AS 'peritonite: diagnosi',
                peri.`coltura liquido peritoneale 1` AS 'peritonite: 1° coltura liquido peritoneale'
                FROM view_peritonites_it peri
                )compliperi
                LEFT JOIN 
                view_treatments_it treats ON compliperi.parent=treats.id
                LEFT JOIN 
                view_patients_comorbidities comorbidities ON comorbidities.patient_id = treats.parent
                LEFT JOIN
                treatments_count ON treatments_count.id=treats.id 
                LEFT JOIN
                treatments_ ON treatments_.id=treats.id 
                LEFT JOIN 
                treatments ON treatments.id=treats.id 
                LEFT JOIN
                view_patients_it pats ON pats.id=treats.parent
                LEFT JOIN 
                deaths_ ON deaths_.parent=pats.id 
                ORDER BY pats.codice
                    ",
            "parameters"=>[
                
            ],
        ],        
        
        "complications_union_peritonites_union_nocp_it"=>[
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"complicanze e peritoniti + altri cicli","desc_it"=>"","notes_it"=>"campi complicanze union peritoniti separati su richiesta<br>complicanza: causa=&gt;complicanza: descrizione",
            "sql"=>"

SELECT 
pats.codice AS 'codice paziente',
pats.centro AS 'codice centro',
pats.`genere` AS `sesso`,
pats.`data di nascita`,
pats.prd AS 'malattia di base',
pats.`prd (codice edta)` AS 'codice malattia di base',
COALESCE(comorbidities.N,0) AS 'n° comorbidità',
comorbidities.desc_it_1 AS 'com.1',
comorbidities.desc_it_2 AS 'com.2',
comorbidities.desc_it_3 AS 'com.3',
treats.`età a inizio trattamento`,
treats.`inizio trattamento`,
treats.`fine trattamento`,
treats.tipo as 'tipo trattamento',
treatments_count.N AS 'n° ciclo',
IF(treatments.end_cause=4,1,0) AS 'cambio tecnica', 
treats.`trattamento precedente` AS 'trattamento precedente',
treats.`causa fine` AS 'causa fine ciclo' ,
ROUND(treatments_.treatment_duration/30.44,1) AS 'durata trattamento (mesi)',
COALESCE(treats.`causa cambio tecnica`) AS `causa cambio tecnica`,
IF(treatments.end_cause=3,1,0) AS 'decesso', 
IF(treatments.end_cause=3,deaths_.cause_it,'') AS 'causa decesso' ,
COALESCE(`complicanza`,0) AS complicanza,
`complicanza: descrizione`,
`complicanza: associata alla dialisi`,
`complicanza: data`,
`complicanza: gg.ricovero`,
COALESCE(`peritonite`,0) AS peritonite,
`peritonite: data`,
`peritonite: gg.ricovero`,
`peritonite: diagnosi`,
`peritonite: 1° coltura liquido peritoneale`

FROM
(
SELECT 
id,parent,
1 AS 'complicanza',
compli.description AS 'complicanza: descrizione',
COALESCE (compli.dialysis_related,'') AS 'complicanza: associata alla dialisi',
DATE_FORMAT(compli.date,'%d/%m/%Y') AS 'complicanza: data',
compli.hospitalization_days AS 'complicanza: gg.ricovero',
0 AS 'peritonite',
'' AS 'peritonite: data',
'' AS 'peritonite: gg.ricovero',
'' AS 'peritonite: diagnosi',
'' AS 'peritonite: 1° coltura liquido peritoneale'
FROM view_complications_it compli
UNION
SELECT 
id,parent,
0 AS 'complicanza',
'' AS 'complicanza: descrizione',
'' AS 'complicanza: associata alla dialisi',
'' AS 'complicanza: data',
'' AS 'complicanza: gg.ricovero',
1 AS 'peritonite',
peri.data AS 'peritonite: data',
peri.`giorni ricovero` AS 'peritonite: gg.ricovero',
peri.diagnosi AS 'peritonite: diagnosi',
peri.`coltura liquido peritoneale 1` AS 'peritonite: 1° coltura liquido peritoneale'
FROM view_peritonites_it peri
)compliperi
RIGHT JOIN 
view_treatments_it treats ON compliperi.parent=treats.id
LEFT JOIN
treatments_count ON treatments_count.id=treats.id 
LEFT JOIN
treatments_ ON treatments_.id=treats.id 
LEFT JOIN 
treatments ON treatments.id=treats.id 
LEFT JOIN
view_patients_it pats ON pats.id=treats.parent
LEFT JOIN 
view_patients_comorbidities comorbidities ON comorbidities.patient_id = pats.id
LEFT JOIN 
deaths_ ON deaths_.parent=pats.id 
ORDER BY CAST(complicanza AS DECIMAL)+CAST(peritonite AS DECIMAL) DESC,pats.codice

                    ",
            "parameters"=>[
                
            ],
        ],

        "patients_by_therapies_it"=>[//search for patients with a certain therapy
            "type"=>"custom","privilege"=>"10",
            "name_it"=>"ricerca pazienti per terapia","desc_it"=>"pazienti con terapie specificate",
            "notes_it"=>"I parametri che riguardano la terapia sono in 'or', quindi ad es. inserire:<br>farmaco='cinacalcet' + specifica 1='cinacalcet' + specifica 2='mimpara'<br>amplia al massimo la ricerca del cinacalcet, trovando sia le voci inserite correttamente che quelle in cui il farmaco è inserito sotto 'altro' con principio attivo <i>o</i> nome commerciale.",
            "sql"=>"
                SELECT                 
                CONCAT('<a href=\'https://ridp.it/edit/patient/',patient_id,'\' target=\'blank\'>','•','</a>') AS ' ',
                center_code as centro, 
                patient_code as paziente,                 
                DATE_FORMAT(min(follow_ups.date),'%d/%m/%Y') as `prima terapia`,
                DATE_FORMAT(max(follow_ups.date),'%d/%m/%Y') as `ultima terapia`,                
                count(therapies.id) AS 'n° voci',
                GROUP_CONCAT(DISTINCT _l_therapies.name_it) as farmaco, 
                GROUP_CONCAT(therapies.specification SEPARATOR '; ') as specifiche
                FROM therapies
                JOIN _l_therapies ON _l_therapies.id=therapies.type
                JOIN follow_ups ON follow_ups.id=therapies.parent
                JOIN treatments_ ON treatments_.id=follow_ups.parent 
                WHERE 
                IF(:sel_center_id=0,true,center_id =:sel_center_id)
                AND
                year(follow_ups.date)>=:drug_start_year
                AND
                IF(:drug_id=-1 AND ':drug_specifics_1'='' AND ':drug_specifics_2'='',false,
                    therapies.type= :drug_id 
                    OR
                    IF(':drug_specifics_1'='',false,therapies.specification LIKE '%:drug_specifics_1%')
                    OR
                    IF(':drug_specifics_2'='',false,therapies.specification LIKE '%:drug_specifics_2%')
                )
                GROUP BY paziente, _l_therapies.id
                ORDER BY  centro,`prima terapia`
                ",
            "parameters"=>[ 
                "sel_center_id"=>["name_it"=>"centro","list_source"=>"SELECT 0,'tutti' FROM centers UNION (SELECT id,CONCAT(code,' - ',city) FROM centers ORDER BY code)","default"=>""],
                "drug_start_year"=>["name_it"=>"anno di partenza (terapia)","list_source"=>"SELECT 1000,'dall\'inizio' UNION (SELECT year(date),year(date) FROM follow_ups GROUP BY year(date))","default"=>""],
                "drug_id"=>["name_it"=>"farmaco","list_source"=>"SELECT -1,'[selezionare farmaco]' FROM _l_therapies UNION (SELECT id,name_it FROM _l_therapies ORDER BY name_it)","default"=>"","desc_it"=>"test"],
                "drug_specifics_1"=>["name_it"=>"ricerca testuale nella specifica (1)","default"=>""],
                "drug_specifics_2"=>["name_it"=>"ricerca testuale nella specifica (2)","default"=>""], 
            ],
        ],
        
        //STATISTICS:NOT USED ANYMORE? CHECK, SEE CONFIG/QUERIES.PHP     
         "active_treatments_it"=>[
            "name_it"=>"cicli dialisi in corso",
            "desc_it"=>"trattamenti attualmente in corso, divisi per centro e tipo dialisi",
            //"sql"=>"SELECT COUNT(*) AS treatments_n, SUM(treatment_open) AS treatments_open, FLOOR(AVG(treatment_duration)/30.41) AS avg_duration FROM treatments_",
            "sql"=>"SELECT center_code AS centro, SUM(IF(treatment_type='PD',1,0)) AS 'PD',SUM(IF(treatment_type='HD',1,0)) AS 'HD' FROM treatments_ WHERE treatment_open = 1 GROUP BY center_code ORDER BY COUNT(*) DESC, center_code DESC",            
            "chart"=>[ 
                "type"=>"BarChart",
                "columns"=>["centro"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],],
                "options"=>[
                    "chartArea"=>["height"=>"80%","top"=>"20","width"=>"80%","left"=>"10%"],
                    "legend"=>["position"=>"right","alignment"=>"end","maxLines"=>"4"],
                    "isStacked"=>true,"is3D"=>true,
                ],                
            ],
        ],
        "treatments_per_year_it"=>[  
            "name_it"=>"trattamenti per anno",
            "desc_it"=>"trattamenti in corso durante ogni anno, divisi per tipo",
            "sql"=>"SELECT year_ AS anno,PD,HD,TX FROM treatments_by_year ORDER BY year_",
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>["anno"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],"TX"=>["number","TX"],],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],
       "treatment_days_per_year_it"=>[  
            "name_it"=>"durata totale trattamenti per anno",
            "desc_it"=>"giorni totali di trattamento per ogni anno, divisi per tipo",
            "sql"=>"SELECT year_ AS anno,PD_days,HD_days,TX_days FROM treatments_by_year ORDER BY year_",
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>["anno"=>["string"],"PD_days"=>["number","PD_days"],"HD_days"=>["number","HD_days"],"TX_days"=>["number","TX_days"],],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],        
        "treatments_start_per_year_it"=>[
            "name_it"=>"nuovi trattamenti per anno",
            "desc_it"=>"incidenza trattamenti per anno, divisi per tipo",
            "notes_it"=>"NB: con trattamento precedente=terapia conservativa si hanno i <i>pazienti</i> incidenti, a prescindere dal Registro",
            "sql"=>"SELECT "
            . "DATE_FORMAT(treatments.start_date,'%Y') AS anno, SUM(IF(treatments.type='PD',1,0)) AS 'PD',SUM(IF(treatments.type='HD',1,0)) AS 'HD',SUM(IF(treatments.type='TX',1,0)) AS 'TX' "
            . "FROM "
            . "treatments_count LEFT JOIN treatments ON treatments.id=treatments_count.id "
            . "WHERE "
            . " N<=:N "
            //. "AND YEAR(treatments.start_date)>=:min_start_year AND YEAR(treatments.start_date)<= :max_start_year  "
            . "AND IF(':former_treatment'='',1,former_treatment=':former_treatment')"
            . "GROUP BY YEAR(treatments.start_date) "
            . "ORDER BY YEAR(treatments.start_date);",          
            "parameters"=>[
                "N"=>["name_it"=>"n°trattamento","list_source"=>["1"=>"1°","100"=>"tutti"],"default"=>100],
                "former_treatment"=>["name_it"=>"trattamento precedente","list_source"=>[NULL=>"","CT"=>"CT","TX"=>"TX","PD"=>"PD","HD"=>"HD"],"default"=>""],        
                //"min_start_year"=>["name_it"=>"dal","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>1981],
                //"max_start_year"=>["name_it"=>"al","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>2017],
            ],
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>["anno"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],"TX"=>["number","TX"],],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],
        "treatments_end_cause_it"=>[
            "name_it"=>"cause fine trattamenti",
            "desc_it"=>"termini cicli dialitici per anno, divisi per cause fine",
            "sql"=>"SELECT 
            COALESCE(YEAR(end_date),NULL) as anno, 
            SUM(IF(_l_treatment_end_causes.id=1,1,0)) AS 'tx da cadavere',
            SUM(IF(_l_treatment_end_causes.id=1,2,0)) AS 'tx da vivente',
            SUM(IF(_l_treatment_end_causes.id=3,1,0)) AS 'decesso',            
            SUM(IF(_l_treatment_end_causes.id=4,1,0)) AS 'altra tecnica',
            SUM(IF(_l_treatment_end_causes.id=5,1,0)) AS 'ripresa f.r.',
            SUM(IF(_l_treatment_end_causes.id=6,1,0)) AS 'uscita da RIDP'
            FROM treatments 
            LEFT JOIN _l_treatment_end_causes ON _l_treatment_end_causes.id=treatments.end_cause
            LEFT JOIN _l_treatment_change_causes ON _l_treatment_change_causes.id=treatments.technique_change_cause
            WHERE treatments.end_date IS NOT NULL  
            GROUP BY anno
            HAVING anno>= :min_year
            ORDER BY anno",
            "parameters"=>[
                "min_year"=>["name_it"=>"dal","list_source"=>"SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>1989],
            ],            
            "chart"=>[
                "type"=>"AreaChart",
                "columns"=>["anno"=>["string"],
                    "tx da cadavere"=>["number","tx da cadavere"],"tx da vivente"=>["number","tx da vivente"],
                    "ripresa f.r."=>["number","ripresa f.r."],"uscita da RIDP"=>["number","uscita da RIDP"],
                    "altra tecnica"=>["number","altra tecnica"],"decesso"=>["number","decesso"],                    
                ],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],   
        "treatment_change_causes_it"=>[
            "name_it"=>"cause cambio tecnica",
            "desc_it"=>"cause cambio tecnica su tutti i trattamenti",
            "sql"=>"SELECT 
            _l_treatment_change_causes.name_it AS causa,
            count(start_date) AS N
            FROM _l_treatment_change_causes 
            LEFT JOIN treatments  ON _l_treatment_change_causes.id=treatments.technique_change_cause           
            WHERE treatments.end_date IS NOT NULL   AND technique_change_cause IS NOT NULL AND technique_change_cause !=0
            GROUP BY _l_treatment_change_causes.name_it
            ORDER BY N DESC",
            "chart"=>[
                "type"=>"PieChart",
                "columns"=>["causa"=>["string"],"N"=>["number","numero"]],
                "options"=>["legend"=>["position"=>"left"], "chartArea"=>["width"=>"70%","height"=>"70%",],"is3D"=>"true"],
            ],
        ],
        
        "treatment_durations_it"=>[
            "name_it"=>"distribuzione durata trattamenti",            
            "desc_it"=>"distribuzione durate trattamenti, per tipo.<br><span style='font-size:80%'>L'intervallo di tempo sulle ascisse è quello selezionato come parametro.</span>", 
            "notes_it"=>"NB: TX preemptive non registrati fino al 2013",
            "sql"=>"SELECT 
                    TRUNCATE (treatment_duration/:interval_days,0) AS intervallo,
                    COUNT(*) AS N,
                    SUM(IF(treatment_type='PD',1,0)) AS PD,
                    SUM(IF(treatment_type='HD',1,0)) AS HD,
                    SUM(IF(treatment_type='TX',1,0)) AS TX
                    FROM treatments_
                    WHERE IF(:outliers=NULL,true, treatment_duration<=:outliers*365)
                    GROUP BY intervallo
                    ORDER BY intervallo", 
            "parameters"=>[
                //"treatment_year"=>["name_it"=>"anno inizio trattamento","list_source"=>"SELECT NULL as year_ FROM treatments UNION SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_","default"=>NULL],            
                "interval_days"=>[
                    "name_it"=>"ampiezza intervalli","default"=>"91",
                    "list_source"=>["7"=>"settimane","30"=>"mesi","91"=>"trimestri","365"=>"anni"],
                    "desc_it"=>"ampiezza intervalli in cui sono raggruppati i dati",
                ],
                "outliers"=>[
                    "name_it"=>"periodo considerato","default"=>"5",
                    "list_source"=>["1"=>"primo anno","5"=>"primi 5 anni","10"=>"primi 10 anni","NULL"=>"tutto"],
                    "desc_it"=>"esclude gli outliers oltre una certa durata",
                ],
            ],
            "chart"=>[
                "type"=>"ComboChart", 
                "columns"=>["intervallo"=>["number","-"],"N"=>["number","totali"],"PD"=>["number","PD"],"HD"=>["number","HD"],"TX"=>["number","TX"]],                
                "options"=>[ 
                    //"curveType"=>"function",//smooth
                    "isStacked"=>false, //not in scatter/line
                    "pointSize"=>"1",
                    "hAxis"=>["title"=>"intervallo"],
                    "vAxes"=>[
                        "0"=>[
                            "viewWindowMode"=>'explicit',
                            //"viewWindow"=>["max"=>"510","min"=>"82"],
                            "gridlines"=>["color"=>'grey'],
                            "title"=>"numero trattamenti",   
                        ],                     
                    ],
                    
                    "series"=>[
                    ],
                    "colors"=>["#000000", "#cc0066", "#3333ff","#006600"],
                    //"chartArea"=>["left"=>"30%","top"=>"5%","width"=>"50%","height"=>"70%"], 
                ]
            ]             
        ], 
        
        "data_distributions_it"=>[
            "name_it"=>"distribuzione dati bio/clinici",            
            "desc_it"=>"distribuzione delle medie dei dati dati biochimici/clinici numerici, divisi per M/F.", 
            "sql"=>"SELECT TRUNCATE(data_approx,2) AS dato, COUNT(IF(gender!='F',1,NULL)) AS F, COUNT(IF(gender!='M',1,NULL)) AS M, TRUNCATE(avg_,2) AS media, TRUNCATE(std_,2) AS 'SD'
                    FROM
                        (SELECT gender, data AS data_raw, avg_, std_, IF(:interval_width=0,data,(std_* :interval_width)*ROUND(data/(std_* :interval_width))) AS data_approx 
                        FROM
                            (SELECT patients.id, gender, AVG(:col_name) AS data
                             FROM
                             biochemicals LEFT JOIN clinicals ON clinicals.parent=biochemicals.parent
                             LEFT JOIN follow_ups ON follow_ups.id=biochemicals.parent
                             LEFT JOIN treatments ON treatments.id=follow_ups.parent
                             LEFT JOIN patients ON patients.id=treatments.parent
                             GROUP BY patients.id
                             HAVING data IS NOT NULL) personal
                        LEFT JOIN
                            (SELECT AVG(:col_name) AS avg_, STD(:col_name) AS std_ FROM biochemicals LEFT JOIN clinicals ON clinicals.parent=biochemicals.parent) stats ON true
                        WHERE ABS(data-avg_)<std_ * :outliers )  outlied
                    GROUP BY dato
                    ORDER BY dato",
            "parameters"=>[
                "col_name"=>["name_it"=>"dato","default"=>"Ca",
                    "list_source"=>["Ca"=>"Calcio","P"=>"Fosforo","serum_protein"=>"proteinemia totale","albuminemia"=>"albuminemia","haemoglobin"=>"emoglobina","urea"=>"urea"],
                    "desc_it"=>"il dato scelto. A seconda della distribuzione può essere necessario aggiustare l'ampiezza degli intervalli e l'esclusione degli outliers.",
                ],                 
                "interval_width"=>[
                    "name_it"=>"ampiezza intervalli","default"=>"0.005",
                    "list_source"=>["0.005"=>"5‰SD","0.01"=>"1%SD","0.05"=>"5%SD","0"=>"0"],
                    "desc_it"=>"ampiezza intervalli in cui sono raggruppati i dati, espressa in percentuali di deviazioni standard",
                ],
                "outliers"=>[
                    "name_it"=>"includi solo dati nell'intervallo","default"=>"1",
                    "list_source"=>["0.01"=>"1%SD","0.05"=>"5%SD","0.1"=>"10%SD","0.25"=>"25%SD","0.5"=>"50%SD","0.75"=>"75%SD","1"=>"1SD","2"=>"2SD","100"=>"includi tutti"],
                    "desc_it"=>"esclude gli outliers al di fuori di un intervallo espresso in deviazioni standard o frazioni",
                ],
            ],
            "chart"=>[
                "type"=>"LineChart", 
                "columns"=>["dato"=>["number"," "],"F"=>["number","F"],"M"=>["number","M"]],                
                "options"=>[
                    //"curveType"=>"function",//smooth
                    "isStacked"=>true, //not in scatter/line
                    "pointSize"=>"2",
                    "hAxis"=>["title"=>"intervallo"],
                    "vAxes"=>[
                        "0"=>[
                            "viewWindowMode"=>'explicit',
                            //"viewWindow"=>["max"=>"510","min"=>"82"],
                            "gridlines"=>["color"=>'grey'],
                            "title"=>"N", 
                        ],
                        "1"=>[
                            "gridlines"=>["color"=>'transparent'],
                            "viewWindowMode"=>'explicit',
                            //"viewWindow"=>["max"=>"1300","min"=>"0"],
                            //"format"=>"#%",
                            "title"=>"M",
                        ],                        
                    ],
                    
                    "series"=>[
                        "0"=>["targetAxisIndex"=>"0"],
                        "1"=>["targetAxisIndex"=>"0"],
                    ],
                    "colors"=>["#cc0066", "#3333ff"],
                    //"chartArea"=>["left"=>"30%","top"=>"5%","width"=>"50%","height"=>"70%"], 
                ]
            ]             
        ],        
        
        "peritonites_date_it"=>[
            "name_it"=>"casi di peritonite/tempo peritoneale",
            "desc_it"=>"n°peritoniti/gg da inizio dialisi",
            "sql"=>"SELECT :days_interval*ROUND(DATEDIFF(date,start_date)/ :days_interval) AS tempo, COUNT(peritonites.id) AS N  
                FROM peritonites LEFT JOIN treatments ON treatments.id=peritonites.parent
                WHERE type='PD'  
                GROUP BY tempo                  
                HAVING tempo>=0 AND ((:limit=0) OR tempo<:limit)
                ORDER by tempo",
            "parameters"=>[               
                "days_interval"=>["name_it"=>"raggruppamento per","desc_it"=>" di raggruppamento della distribuzione",
                    "list_source"=>["1"=>"giorno","7"=>"settimana","30.4"=>"mese","60.8"=>"bimestre","182.6"=>"semestre","365.3"=>"anno"],
                    "default"=>"60.8"],
                "limit"=>["name_it"=>"limite",
                    "list_source"=>["0"=>"tutti","366"=>"primo anno","832"=>"primi 2 anni","1825"=>"primi 5 anni"],
                    "default"=>"1825"],  
            ],
            "chart"=>[
                "type"=>"ScatterChart",
                "columns"=>["tempo"=>["number","tempo"],"N"=>["number","N"],],
                "options"=>[
                    "title"=>"distribuzione tempo peritoniti da inizio dialisi",
                    "legend"=>["position"=>"in"],
                    "chartArea"=>["top"=>"5%","left"=>"5%","height"=>"70%","width"=>"70%"],                    
                    "hAxis"=>["gridlines"=>["color"=>'#555555']],
                    "vAxis"=>["slantedText"=>"true","textPosition"=>"none","title"=>"n. episodi","textStyle"=>["fontName"=>"arial"]], 
                ],
            ],            
        ],  
        "peritonites_per_year_it"=>[
            "name_it"=>"andamento peritoniti",
            "desc_it"=>"NB: le peritoniti <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le peritoniti incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno/paziente</b> che rappresenta il valore medio aspettato di peritoniti per anno di dialisi.",
            "sql"=>"
                SELECT 
	abs_compli.year_ AS anno,
    COALESCE(N_peri*IF(:mode=0,1,:mode/tby.PD_days),0) AS 'n°peritoniti'
FROM
	(SELECT 
	YEAR(peri.date) AS year_, 
	COUNT(peri.id) AS 'N_peri'
	FROM peritonites peri
	LEFT JOIN treatments_ ON treatments_.id=peri.parent
	GROUP BY year_
) abs_compli
     LEFT JOIN treatments_by_year AS tby ON tby.year_=abs_compli.year_
     WHERE tby.year_>= :min_year
     ORDER BY tby.year_
            ",
            "parameters"=>[
                "min_year"=>["name_it"=>"anno inizio",
                    "list_source"=>["1980"=>"tutti","1990"=>"1990","2000"=>"2000",],
                    "default"=>"1980"], 
                "mode"=>["name_it"=>"modalità",
                    "list_source"=>["0"=>"numeri assoluti","365"=>"relativi ad 1 anno dialisi/paziente"],
                    "default"=>"0"],  
            ],
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>["anno"=>["string"],'n°peritoniti'=>["number",'n°peritoniti']],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],
        "complications_per_year_it"=>[  
            "name_it"=>"andamento complicanze",
            "desc_it"=>"NB: le complicanze <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le complicanze incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno/paziente</b> che rappresenta il valore medio aspettato di complicanze per anno di dialisi.",
            "sql"=>"
                SELECT 
	abs_compli.year_ AS anno,
    HD_compli*IF(:mode=0,1,:mode/tby.HD_days) AS 'relative a HD',
    PD_compli*IF(:mode=0,1,:mode/tby.PD_days) AS 'relative a PD',
    ND_compli*IF(:mode=0,1,:mode/tby.ALL_days) AS 'non dialitiche'
FROM
	(SELECT 
	YEAR(complications_.date) AS year_, 
	#COUNT(complications_.id) AS total_compli,
	SUM(complications_.dr*
	(treatments_.treatment_type='HD')) AS 'HD_compli',
	SUM(complications_.dr*
	(treatments_.treatment_type='PD')) AS 'PD_compli',
	SUM(ABS(complications_.dr-1)) AS 'ND_compli'
	FROM complications_
	LEFT JOIN treatments_ ON treatments_.id=complications_.parent
	GROUP BY year_
) abs_compli
     LEFT JOIN treatments_by_year AS tby ON tby.year_=abs_compli.year_
     ORDER BY anno
            ",   
            "parameters"=>[
                "mode"=>["name_it"=>"modalità",
                    "list_source"=>["0"=>"numeri assoluti","365"=>"relativi ad 1 anno dialisi/paziente"],
                    "default"=>"0"],  
            ],
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>["anno"=>["string"],'relative a HD'=>["number",'relative a HD'],'relative a PD'=>["number",'relative a PD'],'non dialitiche'=>["number",'non dialitiche']],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],
"hospitalizations_per_year_it"=>[  
            "name_it"=>"andamento ospedalizzazioni",
            "desc_it"=>"Andamento anno per anno di numero o durata delle ospedalizzazioni (scelta del dato da parametri)<br>NB: le ospedalizzazioni <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le ospedalizzazioni incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno dialisi/paziente</b> che rappresenta il valore medio asprettato del dato per un anno di dialisi.<br>
<br>
<span style='font-size:90%'> <b>Es.</b>:cercando i giorni di ospedalizzazione in numero assoluto, si vede ad es. che quelli correlati all'emodialisi nel 2001 sono stati 209; selezionando invece 'relativo ad anno/paziente' per HD 2001 si ha 5.3 gg: ovvero i pazienti sono stati ospedalizzati in media 5.3 giorni per anno di ciclo a causa di complicanze legate all'emodialisi.</span>",
            "sql"=>"SELECT 
	hosp.year_ AS anno,
	IF(':what'='days',HD_hospdays,HD_hospnumb)*IF(:mode=0,1,:mode/tby.HD_days) AS 'rel.HD', 
	IF(':what'='days',PD_hospdays,PD_hospnumb)*IF(:mode=0,1,:mode/tby.PD_days) AS 'rel.PD', 
	(IF(':what'='days',ALL_hospdays,ALL_hospnumb)-IF(':what'='days',HD_hospdays,HD_hospnumb)-IF(':what'='days',PD_hospdays,PD_hospnumb))*IF(:mode=0,1,:mode/tby.ALL_days) AS 'rel.ND',
        IF(':what'='days',ALL_hospdays,ALL_hospnumb)*IF(:mode=0,1,:mode/tby.ALL_days) AS 'tot'        
FROM
	(SELECT 
	YEAR(complications_.date) AS year_, 
	SUM(complications_.dr*(complications_.hd>0)*
	(treatments_.treatment_type='HD')) AS 'HD_hospnumb',
	SUM(complications_.dr*complications_.hd*
	(treatments_.treatment_type='HD')) AS 'HD_hospdays', 
	SUM(complications_.dr*(complications_.hd>0)*
	(treatments_.treatment_type='PD')) AS 'PD_hospnumb',
	SUM(complications_.dr*complications_.hd*
	(treatments_.treatment_type='PD')) AS 'PD_hospdays', 
	SUM(complications_.hd>0) AS 'ALL_hospnumb',
	SUM(complications_.hd) AS 'ALL_hospdays' 
	FROM complications_
	LEFT JOIN treatments_ ON treatments_.id=complications_.parent
	GROUP BY year_
) hosp
     LEFT JOIN treatments_by_year AS tby ON tby.year_=hosp.year_
     ORDER BY anno

            ",   
            "parameters"=>[
                "what"=>["name_it"=>"dato",
                    "list_source"=>["numb"=>"n°ospedalizzazioni","days"=>"giorni ospedalizzazione"],
                    "default"=>"numb"],
                "mode"=>["name_it"=>"modalità",
                    "list_source"=>["0"=>"numero assoluto","365"=>"relativi ad 1 anno dialisi/paziente"],
                    "default"=>"0"], 
                 
            ],
            "chart"=>[
                "type"=>"ColumnChart",
                "columns"=>[
                    "anno"=>["string"],'rel.HD'=>["number",'osp.relative a HD'],'rel.PD'=>["number",'osp.relative a PD'],'rel.ND'=>["number",'osp.non relative a dialisi'],
                    ],
                "options"=>["isStacked"=>"true"], 
            ] ,
        ],  
        "comorbidities_n_it"=>[
            "name_it"=>"numero comorbidità",
            "desc_it"=>"distribuzione del numero di comorbidità per paziente",
            "sql"=>"SELECT 
                COUNT(patient) AS 'n° pazienti',
                IF(comorbidities=0,'nessuna',CONCAT(comorbidities, ' comorb.')) AS 'n° comorbidità' FROM
                (	
                    SELECT
                        patients.id AS patient,
                        COUNT(pc.comorbidity_id) AS comorbidities
                        FROM patients
                    LEFT JOIN patients_comorbidities pc ON pc.patient_id=patients.id
                        GROUP BY patients.id  
                )comorb_numbers
                GROUP BY comorbidities
                ORDER BY comorbidities ASC", 
            "chart"=>[
                "type"=>"PieChart",
                "columns"=>["n° comorbidità"=>["string"],"n° pazienti"=>["number","numero"]],
                "options"=>["legend"=>["position"=>"left"], "chartArea"=>["width"=>"70%","height"=>"70%",],"is3D"=>"true"],
            ],
        ],
        "peritonites_prob_it"=>[
            "name_it"=>"probabilità peritoniti",
            "desc_it"=>"probabilità non cumulativa in funzione del tempo dall' inizio della peritoneale",
            "sql"=>"SELECT ROUND(DATEDIFF(date,start_date)/ :days_interval) AS intervallo, COUNT(peritonites.id)/n_tot AS p
                    FROM peritonites LEFT JOIN treatments ON treatments.id=peritonites.parent
                    LEFT JOIN (SELECT COUNT(id) AS n_tot FROM peritonites) nTotTab ON true
                    WHERE type='PD'  
                    GROUP BY intervallo ASC WITH ROLLUP                 
                    HAVING intervallo>=0 AND ((:limit=0) OR :days_interval*intervallo<:limit)",
            "parameters"=>[               
                "days_interval"=>["name_it"=>"intervallo","desc_it"=>"intervallo di campionamento della distribuzione",
                    "list_source"=>["1"=>"giorno","7"=>"settimana","30.4"=>"mese","60.8"=>"bimestre","182.6"=>"semestre","365.3"=>"anno"],
                    "default"=>"30.4"],
                "limit"=>["name_it"=>"periodo","desc_it"=>"limite al tempo da inizio dialisi",
                    "list_source"=>["0"=>"-","366"=>"primo anno","1096"=>"primi 3 anni","1825"=>"primi 5 anni"],
                    "default"=>"1825"],  
            ],
            "chart"=>[
                "type"=>"ScatterChart",
                "columns"=>["intervallo"=>["number","intervallo"],"p"=>["number","prob"],],
                "options"=>[
                    "title"=>"distribuzione di probabilità peritoniti",
                    "trendlines"=>["0"=>["type"=>"linear","showR2"=>"true","visibleInLegend"=>"true"],],
                    "legend"=>["position"=>"in","alignment"=>"start"],
                    "chartArea"=>["top"=>"15","height"=>"70%","width"=>"80%"],                    
                    "axisTitlesPosition"=>"out",
                    "hAxis"=>["title"=>"intervallo (v. parametri)","gridlines"=>["units"=>"25","color"=>'#555555']],
                    "vAxis"=>["title"=>"prob.% non cumulativa","format"=>"percent","textStyle"=>["fontName"=>"arial"]],                    
                    "series"=>["0"=>["visibleInLegend"=>"false"]],
                    "colors"=>['#435988'],
                    "trendlines"=>["0"=>["type"=>"exponential","degree"=>"3","showR2"=>"true","visibleInLegend"=>"true"],],
                ],
            ],            
        ],  
        
        //CENTERS:
        "completion_by_year_it"=>[
            "sql"=>"SELECT 
                clinicals_.year_ AS anno,
                clinici,biochimici,prescrizioni_pd,prescrizioni_hd
                FROM
                (SELECT 
                YEAR(start_time) as year_, AVG(filled) as clinici
                FROM _completion_clinicals
                GROUP BY year_
                )clinicals_
                LEFT JOIN
                (SELECT 
                YEAR(start_time) as year_, AVG(filled) as biochimici
                FROM _completion_biochemicals
                GROUP BY year_ 
                )biochemicals_
                ON biochemicals_.year_=clinicals_.year_
                LEFT JOIN
                (SELECT 
                YEAR(start_time) as year_, AVG(filled) as prescrizioni_pd
                FROM _completion_pd_prescriptions
                GROUP BY year_ 
                )pd_prescriptions_
                ON pd_prescriptions_.year_=clinicals_.year_                
                LEFT JOIN
                (SELECT 
                YEAR(start_time) as year_, AVG(filled) as prescrizioni_hd
                FROM _completion_hd_prescriptions
                GROUP BY year_ 
                )hd_prescriptions_
                ON hd_prescriptions_.year_=clinicals_.year_
                HAVING year_<YEAR(NOW())-1
                ORDER BY clinicals_.year_",
            "name_it"=>"compilazione osservazioni",
            "notes_it"=>"La query non tiene traccia di aggiunta campi, cambio versioni DB e metodologie di raccolta dati.<br>versioni database:<br>v.1.0 fino al 2002<br>v.2.0: 2003-2004<br>v.3.0 al 2004-2018<br>v.4.0: corrente",
            "chart"=>[
                "type"=>"LineChart", 
                "columns"=>["anno"=>["string"," "],"clinici"=>["number","clinici"],"biochimici"=>["number","biochimici"],"prescrizioni_pd"=>["number","prescrizioni PD"],"prescrizioni_hd"=>["number","prescrizioni HD"]],                
                "options"=>[
                    //"curveType"=>"function",//smooth
                    "isStacked"=>false, //not in scatter/line
                    "pointSize"=>"2",
                    "hAxis"=>["title"=>"anno","slantedText"=>false,"slantedTextAngle"=>"90","textStyle"=>["fontSize"=>"10px"]],
                    "vAxes"=>[
                        "0"=>[
                            "format"=>"percent",                        
                            "viewWindowMode"=>'explicit',
                            //"viewWindow"=>["max"=>"510","min"=>"82"],
                            "gridlines"=>["color"=>'grey'],
                            "title"=>"% scheda compilata", 
                        ],                       
                    ],
                    
                    "series"=>[
                        "0"=>["targetAxisIndex"=>"0"],
                    ],
                    "colors"=>["#cc0066", "#3333ff", "#22ffff" , "#22ff00"],
                    //"chartArea"=>["left"=>"30%","top"=>"5%","width"=>"50%","height"=>"70%"], 
                ]
            ]             
        ],
        "sessions_by_center_it"=>[
            "sql"=>"SELECT YEAR(start_time) AS Y, WEEKOFYEAR(start_time) AS W, centers.code AS center_code, count(_s_session.id) AS N
                    FROM 
                    users
                    LEFT JOIN _s_session ON users.id=_s_session.user
                    LEFT JOIN centers ON centers.id=users.center
                    GROUP BY W,center_code, Y
                    ORDER BY Y,W
                ",
            "name_it"=>"collegamenti centro",
            "notes_it"=>"",
            "chart_"=>[
                "type"=>"LineChart", 
                "columns"=>["anno"=>["string"," "],"clinici"=>["number","clinici"],"biochimici"=>["number","biochimici"],"prescrizioni_pd"=>["number","prescrizioni PD"],"prescrizioni_hd"=>["number","prescrizioni HD"]],                
                "options"=>[
                    //"curveType"=>"function",//smooth
                    "isStacked"=>false, //not in scatter/line
                    "pointSize"=>"2",
                    "hAxis"=>["title"=>"anno","slantedText"=>false,"slantedTextAngle"=>"90","textStyle"=>["fontSize"=>"10px"]],
                    "vAxes"=>[
                        "0"=>[
                            "format"=>"percent",                        
                            "viewWindowMode"=>'explicit',
                            //"viewWindow"=>["max"=>"510","min"=>"82"],
                            "gridlines"=>["color"=>'grey'],
                            "title"=>"% scheda compilata", 
                        ],                       
                    ],
                    
                    "series"=>[
                        "0"=>["targetAxisIndex"=>"0"],
                    ],
                    "colors"=>["#cc0066", "#3333ff", "#22ffff" , "#22ff00"],
                    //"chartArea"=>["left"=>"30%","top"=>"5%","width"=>"50%","height"=>"70%"], 
                ]
            ]             
        ],        
        
        //TABLES:
        "centers_it"=>[
            "sql"=>"select `centers`.`code` AS `codice`,`db689269632`.`centers`.`unit` AS `unità`,`db689269632`.`centers`.`institute` AS `istituto`,`db689269632`.`centers`.`address` AS `indirizzo`,`db689269632`.`centers`.`city` AS `città` from `db689269632`.`centers` order by `db689269632`.`centers`.`code`",
            "name_it"=>"centri",
        ],  
        "patients_it"=>[
            "sql"=>"SELECT codice,centro,genere,`data di nascita`,`luogo di nascita`, `provincia di nascita`, `luogo di residenza`,`provincia di residenza`,`prd (codice edta)`,prd, `comorbidità`,`specifica comorbidità`,`ultimo aggiornamento completo` FROM view_patients_it",
            "name_it"=>"pazienti",
        ],
        "deaths_it"=>[
            "sql"=>"SELECT `patients`.`code` AS `paziente`,DATE_FORMAT(`deaths`.`date`,'%d/%m/%Y') AS `data decesso`,`_l_death_causes`.`name_it` AS `causa decesso`,`_l_death_causes_groups`.`desc_it` AS `tipo causa decesso`,`deaths`.`cause_description` AS `causa decesso descrizione`,round(((year(`deaths`.`date`) - year(`patients`.`birth_date`)) + ((month(`deaths`.`date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età al decesso`,`deaths`.`renal_risk_factors` AS `fattori di rischio renali`,`deaths`.`other_risk_factors` AS `altri fattori di rischio`,`deaths`.`subsequent_risk_factors` AS `fattori di rischio successivi`,`_l_death_causes`.`name_en` AS `causa decesso (en)`,`_l_death_causes_groups`.`desc_en` AS `desc_en`,`_l_death_causes`.`code_1995` AS `causa decesso (codice pre-1995)` from (((`deaths` left join `_l_death_causes` on((`deaths`.`cause` = `_l_death_causes`.`id`))) left join `_l_death_causes_groups` on((`_l_death_causes`.`group` = `_l_death_causes_groups`.`id`))) left join `patients` on((`deaths`.`parent` = `patients`.`id`)));",
            "name_it"=>"decessi",
        ],        
        "treatments_it"=>[
            "sql"=>"select `patients`.`code` AS `paziente`,`treatments`.`type` AS `tipo`,date_format(`treatments`.`start_date`,'%d/%m/%Y') AS `inizio trattamento`,date_format(`treatments`.`end_date`,'%d/%m/%Y') AS `fine trattamento`,round(((year(`treatments`.`start_date`) - year(`patients`.`birth_date`)) + ((month(`treatments`.`start_date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età a inizio trattamento`,timestampdiff(MONTH,`treatments`.`start_date`,coalesce(`treatments`.`end_date`,curdate())) AS `durata trattamento`,`treatments`.`creatinine_clearance` AS `clearance creatinina`,`treatments`.`residual_diuresis` AS `diuresi residua`,`treatments`.`urea_clearance` AS `clearance urea`,`treatments`.`former_treatment` AS `trattamento precedente`,`_l_treatment_end_causes`.`name_it` AS `causa fine`,if((`treatments`.`end_cause` = 4),if((`treatments`.`type` = 'PD'),'1','2'),`_l_treatment_end_causes`.`espn_code`) AS `causa fine (codice espn)`,`treatments`.`new_center` AS `nuovo centro`,`_l_treatment_change_causes`.`name_it` AS `causa cambio tecnica`,`treatments`.`technique_change_cause_specification` AS `specifica causa cambio tecnica`,if((`treatments`.`type` = 'PD'),`treatments`.`pd_training_days`,'N/A') AS `giorni di training peritoneale`,`_l_treatment_end_causes`.`name_en` AS `causa fine (en)`,`_l_treatment_change_causes`.`name_en` AS `causa cambio tecnica (en)` from (((`treatments` left join `patients` on((`treatments`.`parent` = `patients`.`id`))) left join `_l_treatment_end_causes` on((`treatments`.`end_cause` = `_l_treatment_end_causes`.`id`))) left join `_l_treatment_change_causes` on((`treatments`.`technique_change_cause` = `_l_treatment_change_causes`.`id`))) ORDER BY `treatments`.`start_date` ",
            "name_it"=>"trattamenti",
        ],  
        "follow_ups_it"=>[
            "sql"=>"SELECT
                follow_ups.id AS id,
                patient_code AS 'paziente', 
                CONCAT(DATE_FORMAT(treatment_start_date,'%d/%m/%Y'), ' ',treatment_type) as trattamento,
                ifnull(if((`follow_ups`.`time` = -(1)),'fine dialisi',concat('mese ',`follow_ups`.`time`)),'N/A') AS `mese ciclo`,
                `_l_transplantation_list`.`name_it` AS `lista trapianti`,
                round(((year(`follow_ups`.`date`) - year(`patients`.`birth_date`)) + ((month(`follow_ups`.`date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età all'osservazione`,
                COALESCE(theracount.N,0) AS  `n.terapie`
                FROM
                follow_ups
                LEFT JOIN treatments_ ON follow_ups.parent = treatments_.id
                LEFT JOIN patients ON treatments_.patient_id = patients.id
                LEFT JOIN _l_transplantation_list ON follow_ups.transplantation_list = _l_transplantation_list.code
                LEFT JOIN 
                (SELECT parent, COUNT(id) AS N FROM therapies GROUP BY parent) theracount ON theracount.parent=follow_ups.id

                ORDER BY paziente, follow_ups.date
            ",
            "name_it"=>"osservazioni",
        ],  
        "clinicals_it"=>[
            "sql"=>"SELECT * FROM view_clinicals_it",  
            "name_it"=>"schede cliniche",
        ],
        "biochemicals_it"=>[
            "sql"=>"SELECT * FROM view_biochemicals_it",  
            "name_it"=>"dati biochimici",
        ],
        "pd_prescriptions_it"=>[
            "sql"=>"SELECT * FROM view_pd_prescriptions_it",  
            "name_it"=>"prescrizioni pd",
        ],
        "hd_prescriptions_it"=>[
            "sql"=>"SELECT * FROM view_hd_prescriptions_it",  
            "name_it"=>"prescrizioni hd",
        ],
        "pd_connections_it"=>[
            "sql"=>"SELECT * FROM view_pd_connections_it",
            "name_it"=>"connessioni",
        ],  
        "hd_accesses_it"=>[
            "sql"=>"SELECT * FROM view_hd_accesses_it",
            "name_it"=>"accessi",
        ],   
        "catheters_it"=>[
            "sql"=>"SELECT * FROM catheters",
            "name_it"=>"cateteri",
        ],  
        "peritonites_it"=>[
            "sql"=>"SELECT * FROM peritonites",
            "name_it"=>"peritoniti",
        ],  
        "peritoneal_equilibration_tests_it"=>[
            "sql"=>"SELECT * FROM peritoneal_equilibration_tests",
            "name_it"=>"test eq.per.",
        ],   
        "complications_it"=>[
            "sql"=>"SELECT * FROM complications_ ORDER BY date",
            "name_it"=>"complicanze",
        ],  
        //LIST:
        "_l_primary_renal_diseases_it"=>[
            "sql"=>"SELECT id AS codice,name_it AS nome ,name_en AS `nome(en)`,edta_code as `codice EDTA` FROM _l_primary_renal_diseases ORDER BY id",
            "name_it"=>"malattie di base",
        ],
        "_l_comorbidities_it"=>[
            "sql"=>"SELECT id as codice, name_it AS nome , name_en AS `nome(en)` FROM _l_comorbidities WHERE active=1 ORDER BY id",
            "name_it"=>"comorbidità",
        ],        
        "_l_death_causes_it"=>[
            "sql"=>"SELECT _l_death_causes.id AS codice, name_it as nome, desc_it AS categoria FROM _l_death_causes LEFT JOIN _l_death_causes_groups ON _l_death_causes.`group`= _l_death_causes_groups.id ORDER BY _l_death_causes.id ASC",
            "name_it"=>"cause decesso",
        ],        
        "_l_treatment_change_causes_it"=>[
            "sql"=>"SELECT id as codice, name_it as nome, name_en as name FROM _l_treatment_change_causes ORDER BY id;",
            "name_it"=>"cause cambio trattamento",
        ],        
        "_l_treatment_end_causes_it"=>[
            "sql"=>"SELECT id as codice, name_it as nome, CONCAT(COALESCE(espn_code,''),COALESCE(espn_note,'')) as 'codice ESPN', treatment_type AS 'tipo trattamento' FROM _l_treatment_end_causes;",
            "name_it"=>"cause cambio trattamento",
        ],        
        "_l_therapies_it"=>[
            "sql"=>"SELECT id as codice, name_it, name_en,everyday,`order`,old_name, uom_it as `u.m.`, calc_uom_it as `u.m. calc.` FROM view_l_therapies_it ORDER BY `order`;",
            "name_it"=>"terapie",
        ],        
        "_l_pd_connections_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_pd_connections ORDER BY id;",
            "name_it"=>"tipi connessione pd",
        ],        
        "_l_hd_accesses_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_hd_accesses ORDER BY id;",
            "name_it"=>"tipi accesso hd",
        ],        
        "_l_catheter_types_it"=>[
            "sql"=>"SELECT id AS codice, name_it as nome FROM _l_catheter_types ORDER BY id;",
            "name_it"=>"tipi catetere",
        ],        
        "_l_catheter_disinfectants_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_catheter_disinfectants ORDER BY id;",
            "name_it"=>"disinfettanti catetere",
        ],        
        "_l_catheter_removal_reasons_it"=>[
            "sql"=>"SELECT id AS codice, name_it as nome FROM _l_catheter_removal_reasons ORDER BY id;",
            "name_it"=>"motivi rimozione catetere",
        ],        
        "_l_catheter_complications_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_catheter_complications ORDER BY `order`;",
            "name_it"=>"complicanze catetere",
        ],        
        "_l_catheter_complication_symptoms_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome, type_it as 'tipo complicanza' FROM _l_catheter_complication_symptoms ORDER BY type_it,id;",
            "name_it"=>"sintomi complicanze catetere",
        ],        
        "_l_peritonitis_germs_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome, type_it as tipo FROM _l_peritonitis_germs ORDER BY `order`, id;",
            "name_it"=>" germi peritonite",
        ],        
        "_l_peritonitis_diagnoses_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_peritonitis_diagnoses ORDER BY `order`;",
            "name_it"=>"diagnosi peritoniti",
        ],        
        "_l_peritonitis_therapies_it"=>[
            "sql"=>"SELECT id AS codice, name_it AS nome FROM _l_peritonitis_therapies WHERE id!=0 ORDER BY `order`, id;",
            "name_it"=>"terapie peritoniti",
        ],       
    ],
];
