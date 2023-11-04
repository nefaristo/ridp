<?php

return [
    'database' => [
        'schema'=>'rdp',
    ],
    'form'=>[//see SVLibs.js
        'label_class'=>'sv_label','input_class'=>'sv_input',//added to every label|input
        'input_placeholder_class'=>'sv_input_placeholder',//added in modelForm->input(...,$placeholder)
        'input_class_changed'=>'sv_input_changed',//not used at the moment        
        'required_class'=>'sv_required',//added to both label and input                     
    ],
    'QueryEditor'=>[
        'hidden_fields'=>['id','parent'],//those won't be shown in the column list 
        'content'=>[            
            'rename'=>['en'=>'rename','it'=>'rinomina'],
            'order_direction_label'=>['en'=>'','it'=>''],//label for asc/desc
            'new_line_label'=>['en'=>'','it'=>''],//all sections
            'add_group'=>['en'=>'group','it'=>'gruppo'],//add group "()" to where section
        ],
        'where_elements_groups'=>[//groups of elements possible in where
            'column'=>['en'=>'data','it'=>'dato'],
            'cfr_operators'=>['en'=>'comparison op.','it'=>'op. confronto'],
            'text'=>['en'=>'text','it'=>'testo'],
            'date'=>['en'=>'date','it'=>'data'],
            'booleans'=>['en'=>'conjunction','it'=>'congiunzione'],
            'group'=>['en'=>'group','it'=>'gruppo'],
            //'parentheses'=>['en'=>'parentheses','it'=>'parentesi'],
            //'math_operators'=>['en'=>'math','it'=>'op.matematici'],
            //'join_types'=>['en'=>'join type','it'=>'tipo collegamento'],
            //'direction'=>['en'=>'order','it'=>'ordine'],
        ],
    ],
    'sql_dictionary'=>[        
        'parentheses'=>[
            '('=>['en'=>'(','it'=>'('],
            ')'=>['en'=>')','it'=>')'],
        ],
        'join_types'=>[
            'JOIN'=>['en'=>'&harr;','it'=>'&harr;'],
            'LEFT JOIN'=>['en'=>'&larr;','it'=>'&larr;'],
            'RIGHT JOIN'=>['en'=>'&rarr;','it'=>'&rarr;'], 
        ],
        'math_operators'=>[
            '+'=>['en'=>'+','it'=>'+'],
            '-'=>['en'=>'-','it'=>'-'],
            '*'=>['en'=>'x','it'=>'x'],
            '/'=>['en'=>':','it'=>':'],
        ],
        'cfr_operators'=>[
            '<'=>['en'=>'&lt;','it'=>'&lt;'],
            '<='=>['en'=>'&le;','it'=>'&le;'],
            '='=>['en'=>'=','it'=>'='],
            '<>'=>['en'=>'&ne;','it'=>'&ne;'],
            '>='=>['en'=>'&ge;','it'=>'&ge;'],
            '>'=>['en'=>'&gt;','it'=>'&gt;'],
            'LIKE'=>['en'=>'&#8835;','it'=>'&#8835;'],
            'NOT NULL'=>['en'=>'&exist;','it'=>'&exist;'],
        ],
        'booleans'=>[            
            'AND'=>['en'=>'and','it'=>'e'],
            'OR'=>['en'=>'or','it'=>'o'], 
            'NOT'=>['en'=>'not','it'=>'non'],
        ],        
        'directions'=>[
            'ASC'=>['en'=>'&uarr;','it'=>'&darr;'],//['en'=>'&#9660;','it'=>'&#9660;'],
            'DESC'=>['en'=>'&darr;','it'=>'&uarr;'],//['en'=>'&#9650;','it'=>'&#9650;'],
        ],  
    ],    
    'sql_errors'=>[
        'en'=>[
            '23000.1048'=>'Some mandatory data have not been filled',
            '23000.1062'=>'Some unique fields are already present in the database (ie, patient with the same code).',
            '22003.1264'=>'One or more values are out of range',        
            '22001.1406'=>'Data too long',
            '23000.1451'=>'Can\'t delete the record. Children records should be deleted first (ie, single treatments of a patient).',
            '23000.1452'=>'Key constraint fail. Possibly the parent of this record has been already deleted',
            '42000.1065'=>'query is empty',
        ],
        'it'=>[
            '23000.1048'=>'Uno o più campi obbligatori non sono stati compilati',
            '23000.1062'=>'Dei campi unici sono già presenti nel database (ad es. un paziente con lo stesso codice).',
            '22003.1264'=>'Uno o più valori sono fuori dai limiti prefissati',
            '22001.1406'=>'Testo troppo lungo',
            '23000.1451'=>'Impossibile cancellare il record. Cancellare prima i record figli (ad es., i singoli cicli di un paziente)',                    
            '23000.1452'=>'Vincolo di chiave non rispettato - probabilmente il record padre di quello in aggiornamento è gia stato cancellato',
            '42000.1065'=>'interrogazione vuota',
        ],  
    ],
];
