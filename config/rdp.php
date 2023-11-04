<?php
//Mainly DB related
return [
    'hierarchy'=>[
        "center"=>[//key=lowercased model name
            "name"=>"center","names"=>"centers",//singular & plural keys of lang
            "parent"=>NULL,//key (lowercase), not model name
            "children"=>[//list
                "patient"=>[],//optional: ["if"=>"code to evaluate","multiple"=>default false | true if not a single edit form]
            ],
            "orderBy"=>"code",//default order by
            //"editLink"=>"/edit/follow_up/{parent}",//(Only here to complete list) the default is /edit/key/{id}
        ],
            "patient"=>[
                "name"=>"patient","names"=>"patients",
                "parent"=>"center",
                "children"=>[
                    "treatment"=>[],
                ],
                "orderBy"=>"code",
            ],
            "death"=>[
                "name"=>"death","names"=>"deaths",
                "parent"=>"patient",
                "editIn"=>"/edit/patient/{parent}",
            ],        
                "treatment"=>[
                    "name"=>"treatment","names"=>"treatments",
                    "parent"=>"patient",            
                    "children"=>[
                        "follow_up"=>["if"=>"\$this->type=='PD' || \$this->type=='HD'"],
                        "pd_connection"=>["if"=>"\$this->type=='PD'"],//condition on this model attributes
                        "hd_access"=>["if"=>"\$this->type=='HD'"],
                        "catheter"=>["if"=>"\$this->type=='PD'"],
                        "complication"=>[],
                        "peritonitis"=>["if"=>"\$this->type=='PD'"],                        
                        "peritoneal_equilibration_test"=>["if"=>"\$this->type=='PD'"],              
                    ],
                    "orderBy"=>"start_date",
                ],    
                    "follow_up"=>[
                        "name"=>"follow_up","names"=>"follow_ups",
                        "parent"=>"treatment",            
                        "children"=>[
                            "clinical"=>["hideInBreadcrumbs"=>true],
                            "biochemical"=>["hideInBreadcrumbs"=>true],
                            "pd_prescription"=>["if"=>"\$this->parentType()=='PD'","hideInBreadcrumbs"=>true],
                            "hd_prescription"=>["if"=>"\$this->parentType()=='HD'","hideInBreadcrumbs"=>true],
                            "therapy"=>["hideInBreadcrumbs"=>true],
                        ],
                        "orderBy"=>"date",
                    ],
                        "clinical"=>[
                            "name"=>"clinical","names"=>"clinicals",
                            "parent"=>"follow_up",            
                            "children"=>[],
                            "orderBy"=>"id",//dummy
                            "editIn"=>"/edit/follow_up/{parent}",
                        ],
                        "biochemical"=>[
                            "name"=>"biochemical","names"=>"biochemicals",
                            "parent"=>"follow_up",            
                            "children"=>[],
                            "orderBy"=>"id",//dummy
                            "editIn"=>"/edit/follow_up/{parent}",
                        ],
                        "pd_prescription"=>[
                            "name"=>"pd_prescription","names"=>"pd_prescriptions",
                            "parent"=>"follow_up",            
                            "children"=>[],
                            "orderBy"=>"id",//dummy
                            "editIn"=>"/edit/follow_up/{parent}",
                        ],
                        "hd_prescription"=>[
                            "name"=>"hd_prescription","names"=>"hd_prescriptions",
                            "parent"=>"follow_up",            
                            "children"=>[],
                            "editIn"=>"/edit/follow_up/{parent}",
                        ],
                        "therapy"=>[
                            "name"=>"therapy","names"=>"therapies",
                            "parent"=>"follow_up",            
                            "children"=>[],
                            "editIn"=>"/edit/follow_up/{parent}",
                        ],
                    "pd_connection"=>[
                        "name"=>"pd_connections","names"=>"pd_connections",
                        "parent"=>"treatment",            
                        "children"=>[],
                        "orderBy"=>"date",
                    ],
                    "hd_access"=>[
                        "name"=>"hd_access","names"=>"hd_accesses",
                        "parent"=>"treatment",            
                        "children"=>[],
                        "orderBy"=>"date",
                    ],
                    "catheter"=>[
                        "name"=>"catheter","names"=>"catheters",
                        "parent"=>"treatment",            
                        "children"=>[],//["catheter_medication","catheter_complication"],
                        "orderBy"=>"date",
                    ],
                        "catheter_medication"=>[
                            "name"=>"catheter_medication","names"=>"catheter_medications",
                            "parent"=>"catheter",            
                            "children"=>[],
                            "orderBy"=>"date",
                            "editIn"=>"/edit/catheter/{parent}",
                        ],
                        "catheter_complication"=>[
                            "name"=>"catheter_complication","names"=>"catheter_complications",
                            "parent"=>"catheter",            
                            "children"=>[],
                            "orderBy"=>"date",
                            "editIn"=>"/edit/catheter/{parent}",
                        ],        
                    "complication"=>[
                        "name"=>"complication","names"=>"complications",
                        "parent"=>"treatment",            
                        "children"=>[],
                        "orderBy"=>"date",
                    ],
                    "peritonitis"=>[
                        "name"=>"peritonitis","names"=>"peritonites",
                        "parent"=>"treatment",            
                        "children"=>["peritonitis_therapy"=>[],],
                        "orderBy"=>"date",
                    ],
                        "peritonitis_therapy"=>[
                            "name"=>"peritonitis_therapy","names"=>"peritonitis_therapies",
                            "parent"=>"peritonitis",            
                            "children"=>[],
                            "orderBy"=>"start_date",
                            "editIn"=>"/edit/peritonitis/{parent}",
                        ],
                    "peritoneal_equilibration_test"=>[
                        "name"=>"pet","names"=>"pets",
                        "parent"=>"treatment",            
                        "children"=>[],
                        "orderBy"=>"date",
                    ], 
    ],
    'tree' => [
        #MAIN PATIENTS TREE, covering all:
        #dependencies besides basic tables: only patients_treatments_
        "centers"=>"select id,code, concat(city, '-' , COALESCE(institute,COALESCE(unit,'?'))) as short_desc, concat(unit, ' ', institute,' ' ,city) as long_desc from centers ",
        "users"=>"select id, center, username, priority from users",
        "patients"=>"select id,parent,code,treats_open from patients_treatments_ ",
        "treatments"=>"select id, parent, type, concat(treatments.type,' ',concat_ws('-',date_format(treatments.start_date,'%d/%m/%y'))) AS title from treatments ",
        //"treatments"=>"select id, parent, type, concat(treatments.type,' ',concat_ws('-',date_format(treatments.start_date,'%d/%m/%y'),date_format(treatments.end_date,'%d/%m/%y'))) AS title from treatments ",
        "follow_ups"=>"select id, parent, date, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it, IF(time IS NULL,DATE_FORMAT(date,'%d/%m/%y'),IF(time=-1,'fine',CONCAT('mese ', time))) AS desc_it, IF(time IS NULL,DATE_FORMAT(date,'%m-%d-%y'),IF(time=-1,'end',CONCAT(time,' month'))) AS desc_en from follow_ups",
        //"follow_ups"=>"select id, parent, date, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it, IF(time IS NULL,DATE_FORMAT(date,'%d/%m/%y'),CONCAT('(',IF(time=-1,'fine',CONCAT('mese ', time)),')')) AS desc_it, IF(time IS NULL,DATE_FORMAT(date,'%m-%d-%y'),CONCAT('(',IF(time=-1,'end',CONCAT(time,' month')),')')) AS desc_en from follow_ups",
        "pd_connections"=>"select id, parent, date,DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from pd_connections",
        "hd_accesses"=>"select id, parent, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from hd_accesses",
        "catheters"=>"select id, parent, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from catheters",
        "peritonites"=>"select id, parent, date, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from peritonites",
        "peritoneal_equilibration_tests"=>"select id, parent, date, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from peritoneal_equilibration_tests",
        "complications"=>"select id, parent, date, DATE_FORMAT(date,'%m-%d-%y') as date_en, DATE_FORMAT(date,'%d/%m/%y') as date_it from complications",
"check_former_treatments"=>"select treatments_.*, concat(treatment_code) AS title FROM treatments_ LEFT JOIN treatments ON treatments_.id=treatments.id "
    . "WHERE treatments.type=former_treatment ORDER BY center_code, treatments.start_date ", 
    ],     
    'datacheck'=>[//sql potentially filtered by "centro", mandatory           
        "follow_ups_duplicates"=>[
            "name_it"=>"duplicati osservazioni","desc_it"=>"osservazioni dello stesso trattamento con stessa data","notes_it"=>"",
            "sql"=>
                '# same date, parent; with checksum for its children to see if they are completely redundant=>one can be erased
                SELECT * FROM(
                SELECT 
                centers.code as `centro`,
                CONCAT(view_follow_ups_it_1.date," (",view_follow_ups_it_1.trattamento,")") AS `dati duplicati`,
                CONCAT("<a href=\'/edit/follow_up/",first_,"\'>",view_follow_ups_it_1.`mese ciclo`,"</a>") AS `1° copia`,
                CONCAT("<a href=\'/edit/follow_up/",last_,"\'>",view_follow_ups_it_2.`mese ciclo`,"</a>") AS `2° copia`,
                IF(biochemicals_1.checksum<=>biochemicals_2.checksum,"ok","DIVERSI") as "dati biochimici",
                IF(clinicals_1.checksum<=>clinicals_2.checksum,"ok","DIVERSI") as "dati clinici",    
                IF(pd_prescriptions_1.checksum <=> pd_prescriptions_2.checksum 
                                AND hd_prescriptions_1.checksum <=> hd_prescriptions_2.checksum, "ok", "DIVERSE")
                                 as "prescrizioni",
                        IF(therapies_1.checksum<=>therapies_2.checksum,"ok","DIVERSE") as "terapie"
                FROM 
                        (SELECT follow_ups.parent , min(id) as "first_" ,max(id) as "last_", date, count(id) as N
                                FROM follow_ups GROUP BY parent,date  HAVING N>1
                                ORDER BY parent,date) duplicates    
                LEFT JOIN view_follow_ups_it AS view_follow_ups_it_1 ON view_follow_ups_it_1.id=first_
                LEFT JOIN view_follow_ups_it AS view_follow_ups_it_2 ON view_follow_ups_it_2.id=last_
                LEFT JOIN treatments ON treatments.id = duplicates.parent
                LEFT JOIN patients ON patients.id = treatments.parent
                LEFT JOIN centers ON centers.id = patients.parent                                
                LEFT JOIN _checksum_biochemicals AS biochemicals_1 ON biochemicals_1.parent=first_
                LEFT JOIN _checksum_biochemicals AS biochemicals_2 ON biochemicals_2.parent=last_
                LEFT JOIN _checksum_clinicals AS clinicals_1 ON clinicals_1.parent=first_
                LEFT JOIN _checksum_clinicals AS clinicals_2 ON clinicals_2.parent=last_
                LEFT JOIN _checksum_pd_prescriptions AS pd_prescriptions_1 ON pd_prescriptions_1.parent=first_
                LEFT JOIN _checksum_pd_prescriptions AS pd_prescriptions_2 ON pd_prescriptions_2.parent=last_
                LEFT JOIN _checksum_hd_prescriptions AS hd_prescriptions_1 ON hd_prescriptions_1.parent=first_
                LEFT JOIN _checksum_hd_prescriptions AS hd_prescriptions_2 ON hd_prescriptions_2.parent=last_
                LEFT JOIN _checksum_follow_ups_therapies AS therapies_1 ON therapies_1.id=first_
                LEFT JOIN _checksum_follow_ups_therapies AS therapies_2 ON therapies_2.id=last_
                ORDER BY centro,view_follow_ups_it_1.date
                ) AS all_duplicates
                WHERE `dati biochimici`!="ok" OR `dati clinici`!="ok" OR `prescrizioni`!="ok" OR `terapie`!="ok" 
                ', 
            ],
            "former_treatments"=>[
                "name_it"=>"trattamenti precedenti","desc_it"=>"trattamenti con ciclo precedente non corrispondente non corrispondente al campo 'trattamento precedente'","notes_it"=>"",            
                "sql"=>
                    'SELECT
                    centers.code AS centro, CONCAT(LEFT(patients.code,5),"...") AS paziente,                
                    CONCAT ("<a href=\'/edit/treatment/",treatments.id,"\'>",treatments_count.N,"°: ",treatments.type,"</a>") AS trattamento,
                    treatments.former_treatment AS "trattamento precedente<br>segnalato",
                    CONCAT ("<a href=\'/edit/treatment/",previous.id,"\'>",previous.N,"°: ",previous.type,"</a>") AS "trattamento precedente<br>effettivo"
                    #DATEDIFF(treatments.end_date,previous.start_date) AS "intervallo gg."
                    FROM treatments_count 
                    LEFT JOIN treatments ON treatments.id=treatments_count.id 
                    LEFT JOIN patients ON patients.id=treatments.parent
                    LEFT JOIN centers ON centers.id=patients.parent
                    LEFT JOIN treatments_count AS previous ON previous.parent=treatments_count.parent AND previous.N=treatments_count.N-1
                    WHERE former_treatment!=previous.type AND treatments_count.N>1 AND (former_treatment="PD" OR former_treatment="HD")
                    ORDER BY paziente,trattamento,treatments_count.N
                    ', 
            ], 
        ],    
    'queries_lists'=>[
        'interrogazioni base'=>"CUSTOM",
        'info generali database'=>'INFO',
        'tabelle base'=>"TABLE",
        'liste riferimento'=>"LIST",        
    ],    
    'statistics'=>[//queries with grouping possibilities:
         "active_treatments"=>[
            //"sql"=>"SELECT COUNT(*) AS treatments_n, SUM(treatment_open) AS treatments_open, FLOOR(AVG(treatment_duration)/30.41) AS avg_duration FROM treatments_",
            "sql"=>"SELECT center_code, SUM(IF(treatment_type='PD',1,0)) AS 'PD',SUM(IF(treatment_type='HD',1,0)) AS 'HD' FROM treatments_ WHERE treatment_open = 1 GROUP BY center_code ORDER BY COUNT(*) DESC, center_code DESC",
            "chart"=>[ 
                "type"=>"BarChart",
                "columns"=>["center_code"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],],
            ],
        ],
        "treatments_per_year"=>[
            "sql"=>"SELECT DATE_FORMAT(treatment_start_date,'%Y') AS year, SUM(IF(treatment_type='PD',1,0)) AS 'PD',SUM(IF(treatment_type='HD',1,0)) AS 'HD',SUM(IF(treatment_type='TX',1,0)) AS 'TX' FROM treatments_ GROUP BY YEAR(treatment_start_date) ORDER BY YEAR(treatment_start_date);",
            "chart"=>[
                "type"=>"BarChart",
                "columns"=>["year"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],"TX"=>["number","TX"],],
            ] 
        ],
        "forms_fill_in"=>[
            "sql"=>"",
            "chart"=>[
                "type"=>"BarChart",
                "columns"=>["year"=>["string"],"PD"=>["number","PD"],"HD"=>["number","HD"],"TX"=>["number","TX"],],
            ] 
        ],
       
    ],
//other options:
    'misc'=>[
        "treatment_end_cause"=>["death"=>3,"technique_change"=>4,"center_change"=>6,"tx_failure"=>7,"other_causes"=>10],
        "follow_ups"=>["step_months"=>6,"tolerance_days"=>30],//tolerance must be < step/2!! 
        "catheter_date_tolerance_days_warning"=>30,//insertion date precedes treatment by less then these days=>warnings, if more, error
        "update_datacheck_after"=>24,//hours
    ],
    
    //EXPORT FILES FORMAT
    'export_formats'=>[
        "dropdown"=>["xlsx"=>"Excel","ods"=>"OpenOffice","csv"=>"csv","html"=>"html","json"=>"json","xls"=>"Excel 2003"],
        "file_extension"=>["xlsx"=>"xlsx","csv"=>"csv","ods"=>"ods","html"=>"html","json"=>"json","xls"=>"xls",],
        "mime_type"=>[
            "xlsx"=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "ods"=>"application/vnd.oasis.opendocument.spreadsheet",
            "csv"=>"type=text/csv",
            "html"=>"type=text/html",
            "json"=>"type=text/json",
            "xls"=>"application/vnd.ms-excel",
        ],
        "php_office_writer"=>[//custom extensions
            "xlsx"=>"Xlsx","ods"=>"Ods","csv"=>"Csv","html"=>"Html","json"=>NULL,"xls"=>"Xls",
            'pdf'=>'Tcpdf',// |'Dompdf'|'Mpdf' :?
        ]
    ]
];
