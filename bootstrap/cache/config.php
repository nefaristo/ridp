<?php return array (
  'SVLibs' => 
  array (
    'database' => 
    array (
      'schema' => 'rdp',
    ),
    'form' => 
    array (
      'label_class' => 'sv_label',
      'input_class' => 'sv_input',
      'input_placeholder_class' => 'sv_input_placeholder',
      'input_class_changed' => 'sv_input_changed',
      'required_class' => 'sv_required',
    ),
    'QueryEditor' => 
    array (
      'hidden_fields' => 
      array (
        0 => 'id',
        1 => 'parent',
      ),
      'content' => 
      array (
        'rename' => 
        array (
          'en' => 'rename',
          'it' => 'rinomina',
        ),
        'order_direction_label' => 
        array (
          'en' => '',
          'it' => '',
        ),
        'new_line_label' => 
        array (
          'en' => '',
          'it' => '',
        ),
        'add_group' => 
        array (
          'en' => 'group',
          'it' => 'gruppo',
        ),
      ),
      'where_elements_groups' => 
      array (
        'column' => 
        array (
          'en' => 'data',
          'it' => 'dato',
        ),
        'cfr_operators' => 
        array (
          'en' => 'comparison op.',
          'it' => 'op. confronto',
        ),
        'text' => 
        array (
          'en' => 'text',
          'it' => 'testo',
        ),
        'date' => 
        array (
          'en' => 'date',
          'it' => 'data',
        ),
        'booleans' => 
        array (
          'en' => 'conjunction',
          'it' => 'congiunzione',
        ),
        'group' => 
        array (
          'en' => 'group',
          'it' => 'gruppo',
        ),
      ),
    ),
    'sql_dictionary' => 
    array (
      'parentheses' => 
      array (
        '(' => 
        array (
          'en' => '(',
          'it' => '(',
        ),
        ')' => 
        array (
          'en' => ')',
          'it' => ')',
        ),
      ),
      'join_types' => 
      array (
        'JOIN' => 
        array (
          'en' => '&harr;',
          'it' => '&harr;',
        ),
        'LEFT JOIN' => 
        array (
          'en' => '&larr;',
          'it' => '&larr;',
        ),
        'RIGHT JOIN' => 
        array (
          'en' => '&rarr;',
          'it' => '&rarr;',
        ),
      ),
      'math_operators' => 
      array (
        '+' => 
        array (
          'en' => '+',
          'it' => '+',
        ),
        '-' => 
        array (
          'en' => '-',
          'it' => '-',
        ),
        '*' => 
        array (
          'en' => 'x',
          'it' => 'x',
        ),
        '/' => 
        array (
          'en' => ':',
          'it' => ':',
        ),
      ),
      'cfr_operators' => 
      array (
        '<' => 
        array (
          'en' => '&lt;',
          'it' => '&lt;',
        ),
        '<=' => 
        array (
          'en' => '&le;',
          'it' => '&le;',
        ),
        '=' => 
        array (
          'en' => '=',
          'it' => '=',
        ),
        '<>' => 
        array (
          'en' => '&ne;',
          'it' => '&ne;',
        ),
        '>=' => 
        array (
          'en' => '&ge;',
          'it' => '&ge;',
        ),
        '>' => 
        array (
          'en' => '&gt;',
          'it' => '&gt;',
        ),
        'LIKE' => 
        array (
          'en' => '&#8835;',
          'it' => '&#8835;',
        ),
        'NOT NULL' => 
        array (
          'en' => '&exist;',
          'it' => '&exist;',
        ),
      ),
      'booleans' => 
      array (
        'AND' => 
        array (
          'en' => 'and',
          'it' => 'e',
        ),
        'OR' => 
        array (
          'en' => 'or',
          'it' => 'o',
        ),
        'NOT' => 
        array (
          'en' => 'not',
          'it' => 'non',
        ),
      ),
      'directions' => 
      array (
        'ASC' => 
        array (
          'en' => '&uarr;',
          'it' => '&darr;',
        ),
        'DESC' => 
        array (
          'en' => '&darr;',
          'it' => '&uarr;',
        ),
      ),
    ),
    'sql_errors' => 
    array (
      'en' => 
      array (
        '23000.1048' => 'Some mandatory data have not been filled',
        '23000.1062' => 'Some unique fields are already present in the database (ie, patient with the same code).',
        '22003.1264' => 'One or more values are out of range',
        '22001.1406' => 'Data too long',
        '23000.1451' => 'Can\'t delete the record. Children records should be deleted first (ie, single treatments of a patient).',
        '23000.1452' => 'Key constraint fail. Possibly the parent of this record has been already deleted',
        '42000.1065' => 'query is empty',
      ),
      'it' => 
      array (
        '23000.1048' => 'Uno o più campi obbligatori non sono stati compilati',
        '23000.1062' => 'Dei campi unici sono già presenti nel database (ad es. un paziente con lo stesso codice).',
        '22003.1264' => 'Uno o più valori sono fuori dai limiti prefissati',
        '22001.1406' => 'Testo troppo lungo',
        '23000.1451' => 'Impossibile cancellare il record. Cancellare prima i record figli (ad es., i singoli cicli di un paziente)',
        '23000.1452' => 'Vincolo di chiave non rispettato - probabilmente il record padre di quello in aggiornamento è gia stato cancellato',
        '42000.1065' => 'interrogazione vuota',
      ),
    ),
  ),
  'app' => 
  array (
    'name' => 'RIDP',
    'description' => 'Registro Italiano di Dialisi Pediatrica',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://www.ridp.it',
    'timezone' => 'Europe/Rome',
    'locale' => 'it',
    'fallback_locale' => 'it',
    'key' => 'base64:1RdFzKZ0xlCJgYrcrjJGW7AQAES26cQbgNfvXj9UXIw=',
    'cipher' => 'AES-256-CBC',
    'log' => 'daily',
    'log_level' => 'error',
    'log_max_files' => 30,
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'Collective\\Html\\HtmlServiceProvider',
      24 => 'Yajra\\Datatables\\DatatablesServiceProvider',
      25 => 'Rap2hpoutre\\LaravelLogViewer\\LaravelLogViewerServiceProvider',
      26 => 'hisorange\\BrowserDetect\\ServiceProvider',
      27 => 'Cyberduck\\LaravelExcel\\ExcelServiceProvider',
      28 => 'Khill\\Lavacharts\\Laravel\\LavachartsServiceProvider',
      29 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      30 => 'App\\Providers\\AppServiceProvider',
      31 => 'App\\Providers\\AuthServiceProvider',
      32 => 'App\\Providers\\EventServiceProvider',
      33 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Browser' => 'hisorange\\BrowserDetect\\Facade',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Form' => 'Collective\\Html\\FormFacade',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Lava' => 'Khill\\Lavacharts\\Laravel\\LavachartsFacade',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => '_s_password_resets',
        'expire' => 4320,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'null',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/homepages/45/d689264821/htdocs/RIDP/storage/framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'compile' => 
  array (
    'files' => 
    array (
    ),
    'providers' => 
    array (
    ),
  ),
  'database' => 
  array (
    'fetch' => 5,
    'default' => 'ridp',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => '/homepages/45/d689264821/htdocs/RIDP/database/database.sqlite',
        'prefix' => '',
      ),
      'ridp' => 
      array (
        'driver' => 'mysql',
        'host' => 'db689269632.db.1and1.com',
        'port' => '3306',
        'database' => 'db689269632',
        'username' => 'dbo689269632',
        'password' => 'ridp@419',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'RDP',
        'username' => 'rdpadmin',
        'password' => 'rdpadmin419132',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'port' => '5432',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => 'localhost',
        'password' => NULL,
        'port' => 6379,
        'database' => 0,
      ),
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
    ),
    'fractal' => 
    array (
      'includes' => 'include',
      'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
    ),
    'script_template' => 'datatables::script',
    'index_column' => 'DT_Row_Index',
    'namespace' => 
    array (
      'base' => 'DataTables',
      'model' => '',
    ),
    'pdf_generator' => 'excel',
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => '/homepages/45/d689264821/htdocs/RIDP/storage/cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => '/homepages/45/d689264821/htdocs/RIDP/storage/exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => '/homepages/45/d689264821/htdocs/RIDP/vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => '/homepages/45/d689264821/htdocs/RIDP/vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => '/homepages/45/d689264821/htdocs/RIDP/vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/homepages/45/d689264821/htdocs/RIDP/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/homepages/45/d689264821/htdocs/RIDP/storage/app/public',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
    ),
  ),
  'languages' => 
  array (
    'it' => 'italiano',
    'en' => 'english',
  ),
  'mail' => 
  array (
    'driver' => 'mail',
    'host' => 'smtp.1and1.it',
    'port' => 465,
    'from' => 
    array (
      'address' => 'autenticazione@ridp.it',
      'name' => 'autenticazione RIDP',
    ),
    'encryption' => 'ssl',
    'username' => 'autenticazione@ridp.it',
    'password' => 'Oneandone2212_',
  ),
  'queries' => 
  array (
    'categories' => 
    array (
      'solo admin' => 
      array (
        'privileges' => 
        array (
          0 => 100,
        ),
        'list' => 
        array (
          0 => 'sessions_by_center_it',
        ),
      ),
      'temporaneo' => 
      array (
        'privileges' => 
        array (
          0 => 10,
          1 => 100,
        ),
        'list' => 
        array (
          0 => 'patients_open_treatments_it',
          1 => 'peritonites_as_complications_it',
        ),
      ),
      'interrogazioni custom' => 
      array (
        'privileges' => 
        array (
          0 => 10,
          1 => 100,
        ),
        'list' => 
        array (
          0 => 'treatments_info_1_it',
          1 => 'transplantations_it',
          2 => 'preposttx_complications_it',
          3 => 'patients_by_therapies_it',
          4 => 'complications_union_peritonites_it',
        ),
      ),
      'statistiche' => 
      array (
        'privileges' => 
        array (
          0 => 10,
          1 => 100,
        ),
        'list' => 
        array (
          0 => 'active_treatments_it',
          1 => 'treatments_per_year_it',
          2 => 'treatment_days_per_year_it',
          3 => 'treatments_start_per_year_it',
          4 => 'treatments_end_cause_it',
          5 => 'treatment_change_causes_it',
          6 => 'treatment_durations_it',
          7 => 'peritonites_prob_it',
          8 => 'comorbidities_n_it',
          9 => 'complications_per_year_it',
          10 => 'peritonites_per_year_it',
          11 => 'hospitalizations_per_year_it',
          12 => 'data_distributions_it',
          13 => 'peritonites_date_it',
          14 => 'completion_by_year_it',
        ),
      ),
      'tabelle base' => 
      array (
        'privileges' => 
        array (
          0 => 1,
          1 => 10,
          2 => 100,
        ),
        'list' => 
        array (
          0 => 'centers_it',
          1 => 'patients_it',
          2 => 'deaths_it',
          3 => 'treatments_it',
          4 => 'follow_ups_it',
          5 => 'clinicals_it',
          6 => 'biochemicals_it',
          7 => 'pd_prescriptions_it',
          8 => 'hd_prescriptions_it',
          9 => 'pd_connections_it',
          10 => 'hd_accesses_it',
          11 => 'catheters_it',
          12 => 'peritonites_it',
          13 => 'peritoneal_equilibration_tests_it',
          14 => 'complications_it',
        ),
      ),
      'liste riferimento' => 
      array (
        'privileges' => 
        array (
          0 => 1,
          1 => 10,
          2 => 100,
        ),
        'list' => 
        array (
          0 => '_l_primary_renal_diseases_it',
          1 => '_l_comorbidities_it',
          2 => '_l_death_causes_it',
          3 => '_l_treatment_change_causes_it',
          4 => '_l_treatment_end_causes_it',
          5 => '_l_therapies_it',
          6 => '_l_pd_connections_it',
          7 => '_l_hd_accesses_it',
          8 => '_l_catheter_types_it',
          9 => '_l_catheter_disinfectants_it',
          10 => '_l_catheter_removal_reasons_it',
          11 => '_l_catheter_complications_it',
          12 => '_l_catheter_complication_symptoms_it',
          13 => '_l_peritonitis_germs_it',
          14 => '_l_peritonitis_diagnoses_it',
          15 => '_l_peritonitis_therapies_it',
        ),
      ),
    ),
    'details' => 
    array (
      'dummy_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'titolo it',
        'desc_it' => 'sottotitolo it',
        'notes_it' => '',
        'sql' => 'SELECT * FROM treatments 
                    WHERE 
                    YEAR(start_date) >= :min_start_year 
                    AND 
                    `età a inizio trattamento`>= :min_age 
                    ',
        'parameters' => 
        array (
          'min_start_year' => 
          array (
            'name_it' => 'dal',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 0,
          ),
          'min_age' => 
          array (
            'name_it' => 'età iniziale min',
            'default' => 0,
          ),
        ),
      ),
      'patients_open_treatments_it' => 
      array (
        'name_it' => 'verifica aggiornamenti',
        'desc_it' => 'log degli ultimi accessi con aggiornamento pazienti',
        'sql' => 'SELECT center_id AS center, id as patient, code as patient_code,
                CONCAT(\'aggiornamento al \', DATE_FORMAT(last_complete_update,\'%d/%m/%Y\')) AS text_it,\'\' AS note_it 
                FROM patients_treatments_ 
                WHERE treats_open>0 
                ORDER BY last_complete_update ASC',
      ),
      'peritonites_as_complications_it' => 
      array (
        'name_it' => 'peritonite come complicanze',
        'desc_it' => 'Trova complicanze con descrizione \'*peri*\' e peritoniti dello stesso ciclo, e con differenza di data minore del parametro specificato). Ordinate per differenza assoluta crescente di date',
        'sql' => 'SELECT 
                treats.paziente AS \'paziente\',
                CONCAT(\'<a href=\\\'https://ridp.it/edit/treatment/\',treats.id,\'\\\' target=\\\'_blank\\\'>\',treats.tipo,\' \',treats.`inizio trattamento`,\'>\') AS \'ciclo\' ,
                DATE_FORMAT(compli.date,\'%d/%m/%Y\') AS \'data complic.\',
                DATE_FORMAT(peri.date,\'%d/%m/%Y\') AS \'data perit.\',
                DATEDIFF(compli.date,peri.date) AS \'diff.\',
                compli.description AS \'descrizione complic.\',
                compli.dialysis_related AS \'rel.dial.\' 
                FROM view_complications_it compli
                LEFT JOIN view_peritonites_it peri
                ON compli.parent=peri.parent AND ABS(DATEDIFF(compli.date,peri.date))<= :maxdiff
                LEFT JOIN view_treatments_it treats
                ON treats.id=compli.parent
                WHERE compli.description LIKE \'%peri%\' AND peri.id IS NOT NULL 
                ORDER BY ABS(DATEDIFF(compli.date,peri.date)) ASC',
        'parameters' => 
        array (
          'maxdiff' => 
          array (
            'name_it' => 'differenza di date<u>+</u>',
            'list_source' => 
            array (
              1 => '1',
              3 => '3',
              5 => '5',
              10 => '10',
              30 => '30',
              60 => '60',
              365 => '365',
            ),
            'default' => 5,
          ),
        ),
      ),
      'treatments_info_1_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'info generali trattamenti',
        'desc_it' => 'dati più comuni sui trattamenti dialitici',
        'notes_it' => 'NB: \'n°\' è il numero trattamento all\'interno del Registro',
        'sql' => 'SELECT view_patients_it.codice, `inizio trattamento`,`fine trattamento`, tipo, N as `n°`, `trattamento precedente`, `causa fine`, `data di nascita`, `età a inizio trattamento`, `durata trattamento`, `causa cambio tecnica`, `Causa decesso`, prd AS `malattia di base`, `prd (codice edta)` AS `codice malattia di base`, centro AS `codice centro` 
                    FROM view_treatments_it 
                    LEFT JOIN treatments_count ON treatments_count.id=view_treatments_it.id
                    LEFT JOIN view_patients_it ON view_treatments_it.parent=view_patients_it.id
                    LEFT JOIN view_deaths_it ON view_deaths_it.parent=view_patients_it.id
                    WHERE 
                    YEAR(view_treatments_it.start_date) >= :min_start_year AND YEAR(view_treatments_it.start_date) <= :max_start_year 
                    AND 
                    `età a inizio trattamento`>= :min_age AND `età a inizio trattamento`<= :max_age 
                    AND IF(\':former_treatment\'=\'\',1,`trattamento precedente`=\':former_treatment\')
                    AND N<=:N 
                    ORDER BY view_treatments_it.start_date
                    ',
        'parameters' => 
        array (
          'min_start_year' => 
          array (
            'name_it' => 'dal',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 0,
          ),
          'max_start_year' => 
          array (
            'name_it' => 'al',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 2017,
          ),
          'min_age' => 
          array (
            'name_it' => 'età iniziale min',
            'default' => 0,
          ),
          'max_age' => 
          array (
            'name_it' => 'età iniziale max',
            'default' => 100,
          ),
          'former_treatment' => 
          array (
            'name_it' => 'trattamento precedente',
            'list_source' => 
            array (
              '' => '',
              'CT' => 'CT',
              'TX' => 'TX',
              'PD' => 'PD',
              'HD' => 'HD',
            ),
            'default' => '',
          ),
          'N' => 
          array (
            'name_it' => 'n°trattamento',
            'list_source' => 
            array (
              1 => '1°',
              100 => 'tutti',
            ),
            'default' => 100,
          ),
        ),
      ),
      'transplantations_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'trapianti noti al Registro',
        'desc_it' => 'preemptive tx + trapianti basati su fine dialisi=trapianto',
        'notes_it' => '',
        'sql' => '
                SELECT 
                    DATE_FORMAT(treats.date,\'%d/%m/%Y\') as data,
                    patients.code as code,
                    treats.type, N as  `preceded by treatments`, 
                    CONCAT(\'https://ridp.it/edit/patient/\',patients.id) as link                                        
                FROM
                    (SELECT id, parent, start_date AS date, \'preemptive\' AS type FROM treatments 
                    WHERE treatments.type=\'TX\'
                    UNION
                    SELECT id, parent, end_date AS date,  
                    CONCAT(IF(end_cause=1,\'cadaveric\',\'living donor\'),\' transplant after \',treatments.type) AS type 
                    FROM treatments 
                    WHERE treatments.end_cause=1 OR treatments.end_cause=2
                    ORDER BY date
                    ) treats
                LEFT JOIN treatments_count ON treats.id=treatments_count.id
                LEFT JOIN patients ON treats.parent=patients.id
                WHERE 
                    YEAR(date) >= :min_start_year AND YEAR(date) <= :max_start_year 
                ',
        'parameters' => 
        array (
          'min_start_year' => 
          array (
            'name_it' => 'dal',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 2000,
          ),
          'max_start_year' => 
          array (
            'name_it' => 'al',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 2017,
          ),
        ),
      ),
      'patients_by_therapies_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'ricerca pazienti per terapia',
        'desc_it' => 'pazienti con terapie specificate',
        'notes_it' => 'I parametri che riguardano la terapia sono in \'or\', quindi ad es. inserire:<br>farmaco=\'cinacalcet\' + specifica 1=\'cinacalcet\' + specifica 2=\'mimpara\'<br>amplia al massimo la ricerca del cinacalcet, trovando sia le voci inserite correttamente che quelle in cui il farmaco è inserito sotto \'altro\' con principio attivo <i>o</i> nome commerciale.',
        'sql' => '
                SELECT                 
                CONCAT(\'<a href=\\\'https://ridp.it/edit/patient/\',patient_id,\'\\\' target=\\\'blank\\\'>\',\'•\',\'</a>\') AS \' \',
                center_code as centro, 
                patient_code as paziente,                 
                DATE_FORMAT(min(follow_ups.date),\'%d/%m/%Y\') as `prima terapia`,
                DATE_FORMAT(max(follow_ups.date),\'%d/%m/%Y\') as `ultima terapia`,                
                count(therapies.id) AS \'n° voci\',
                GROUP_CONCAT(DISTINCT _l_therapies.name_it) as farmaco, 
                GROUP_CONCAT(therapies.specification SEPARATOR \'; \') as specifiche
                FROM therapies
                JOIN _l_therapies ON _l_therapies.id=therapies.type
                JOIN follow_ups ON follow_ups.id=therapies.parent
                JOIN treatments_ ON treatments_.id=follow_ups.parent 
                WHERE 
                IF(:sel_center_id=0,true,center_id =:sel_center_id)
                AND
                year(follow_ups.date)>=:drug_start_year
                AND
                IF(:drug_id=-1 AND \':drug_specifics_1\'=\'\' AND \':drug_specifics_2\'=\'\',false,
                    therapies.type= :drug_id 
                    OR
                    IF(\':drug_specifics_1\'=\'\',false,therapies.specification LIKE \'%:drug_specifics_1%\')
                    OR
                    IF(\':drug_specifics_2\'=\'\',false,therapies.specification LIKE \'%:drug_specifics_2%\')
                )
                GROUP BY paziente, _l_therapies.id
                ORDER BY  centro,`prima terapia`
                ',
        'parameters' => 
        array (
          'sel_center_id' => 
          array (
            'name_it' => 'centro',
            'list_source' => 'SELECT 0,\'tutti\' FROM centers UNION (SELECT id,CONCAT(code,\' - \',city) FROM centers ORDER BY code)',
            'default' => '',
          ),
          'drug_start_year' => 
          array (
            'name_it' => 'anno di partenza (terapia)',
            'list_source' => 'SELECT 1000,\'dall\\\'inizio\' UNION (SELECT year(date),year(date) FROM follow_ups GROUP BY year(date))',
            'default' => '',
          ),
          'drug_id' => 
          array (
            'name_it' => 'farmaco',
            'list_source' => 'SELECT -1,\'[selezionare farmaco]\' FROM _l_therapies UNION (SELECT id,name_it FROM _l_therapies ORDER BY name_it)',
            'default' => '',
            'desc_it' => 'test',
          ),
          'drug_specifics_1' => 
          array (
            'name_it' => 'ricerca testuale nella specifica (1)',
            'default' => '',
          ),
          'drug_specifics_2' => 
          array (
            'name_it' => 'ricerca testuale nella specifica (2)',
            'default' => '',
          ),
        ),
      ),
      'preposttx_complications_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'complicanze pre-post tx',
        'desc_it' => 'complicanze pre e post trapianto basati su tabella di input - WORK IN PROGRESS',
        'notes_it' => '',
        'sql' => '
                SELECT __q_20200315_input .*,results.*  FROM (
                SELECT
                SUM(IF(
                complipatients.date>=COALESCE(range1_start,\'2100-01-01\') 
                AND 
                complipatients.date<range1_end,
                1,0)) AS \'complicazioni pre trapianto\',

                SUM(IF(
                complipatients.date>=COALESCE(range1_start,\'2100-01-01\') 
                AND 
                complipatients.date<range1_end
                AND 
                dialysis_related=1,
                1,0)) AS \'complicazioni pre trapianto legate alla dialisi\',

                SUM(IF(
                complipatients.date>=COALESCE(range1_start,\'2100-01-01\') 
                AND 
                complipatients.date<range1_end
                AND 
                dialysis_related IS NULL,
                1,0)) AS \'complicazioni pre trapianto sconosciute\',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end,
                1,0)) AS \'complicazioni post trapianto\',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end
                AND 
                dialysis_related=1,
                1,0)) AS \'complicazioni post trapianto legate alla dialisi\',

                SUM(IF(
                complipatients.date>=range2_start
                AND 
                complipatients.date<range2_end
                AND 
                dialysis_related IS NULL,
                1,0)) AS \'complicazioni post trapianto sconosciute\',

                mixedcode

                FROM 
                        (#corrected input: univocal patient code and standard date formats
                        SELECT    	
                __q_20200315_input.codicepaziente_1 as mixedcode,
                        patients.id AS patient,patients.code AS code,
                STR_TO_DATE(datainiziodialisipretx,\'%d/%m/%Y\') as range1_start,
                STR_TO_DATE(data1trapianto,\'%d/%m/%Y\') as range1_end,
                STR_TO_DATE(iniziociclodialisiposttx,\'%d/%m/%Y\') as range2_start,
                STR_TO_DATE(fineciclodialisiposttx,\'%d/%m/%Y\') as range2_end
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

                    ',
        'parameters' => 
        array (
        ),
      ),
      'complications_union_peritonites_it' => 
      array (
        'type' => 'custom',
        'privilege' => '10',
        'name_it' => 'complicanze e peritoniti',
        'desc_it' => '',
        'notes_it' => 'campi complicanze union peritoniti separati su richiesta<br>complicanza: causa=&gt;complicanza: descrizione',
        'sql' => '
SELECT 
pats.codice AS \'codice paziente\',
pats.centro AS \'codice centro\',
pats.`data di nascita`,
pats.prd AS \'malattia di base\',
pats.`prd (codice edta)` AS \'codice malattia di base\',
COALESCE(comorbidities.N,0) AS \'n° comorbidità\',
comorbidities.desc_it_1 AS \'com.1\',
comorbidities.desc_it_2 AS \'com.2\',
comorbidities.desc_it_3 AS \'com.3\',
treats.`età a inizio trattamento`,
treats.`inizio trattamento`,
treats.`fine trattamento`,
treats.tipo as \'tipo trattamento\',
treatments_count.N AS \'n° ciclo\',
IF(treatments.end_cause=4,1,0) AS \'cambio tecnica\', 
treats.`trattamento precedente` AS \'trattamento precedente\',
treats.`causa fine` AS \'causa fine ciclo\' ,
ROUND(treatments_.treatment_duration/30.44,1) AS \'durata trattamento (mesi)\',
COALESCE(treats.`causa cambio tecnica`) AS `causa cambio tecnica`,
IF(treatments.end_cause=3,1,0) AS \'decesso\', 
IF(treatments.end_cause=3,deaths_.cause_it,\'\') AS \'causa decesso\' ,
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
1 AS \'complicanza\',
compli.description AS \'complicanza: descrizione\',
COALESCE (compli.dialysis_related,\'\') AS \'complicanza: associata alla dialisi\',
DATE_FORMAT(compli.date,\'%d/%m/%Y\') AS \'complicanza: data\',
compli.hospitalization_days AS \'complicanza: gg.ricovero\',
0 AS \'peritonite\',
\'\' AS \'peritonite: data\',
\'\' AS \'peritonite: gg.ricovero\',
\'\' AS \'peritonite: diagnosi\',
\'\' AS \'peritonite: 1° coltura liquido peritoneale\'
FROM view_complications_it compli
UNION
SELECT 
id,parent,
0 AS \'complicanza\',
\'\' AS \'complicanza: descrizione\',
\'\' AS \'complicanza: associata alla dialisi\',
\'\' AS \'complicanza: data\',
\'\' AS \'complicanza: gg.ricovero\',
1 AS \'peritonite\',
peri.data AS \'peritonite: data\',
peri.`giorni ricovero` AS \'peritonite: gg.ricovero\',
peri.diagnosi AS \'peritonite: diagnosi\',
peri.`coltura liquido peritoneale 1` AS \'peritonite: 1° coltura liquido peritoneale\'
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
                    ',
        'parameters' => 
        array (
        ),
      ),
      'active_treatments_it' => 
      array (
        'name_it' => 'cicli dialisi in corso',
        'desc_it' => 'trattamenti attualmente in corso, divisi per centro e tipo dialisi',
        'sql' => 'SELECT center_code AS centro, SUM(IF(treatment_type=\'PD\',1,0)) AS \'PD\',SUM(IF(treatment_type=\'HD\',1,0)) AS \'HD\' FROM treatments_ WHERE treatment_open = 1 GROUP BY center_code ORDER BY COUNT(*) DESC, center_code DESC',
        'chart' => 
        array (
          'type' => 'BarChart',
          'columns' => 
          array (
            'centro' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
          ),
          'options' => 
          array (
            'chartArea' => 
            array (
              'height' => '80%',
              'top' => '20',
              'width' => '80%',
              'left' => '10%',
            ),
            'legend' => 
            array (
              'position' => 'right',
              'alignment' => 'end',
              'maxLines' => '4',
            ),
            'isStacked' => true,
            'is3D' => true,
          ),
        ),
      ),
      'treatments_per_year_it' => 
      array (
        'name_it' => 'trattamenti per anno',
        'desc_it' => 'trattamenti in corso durante ogni anno, divisi per tipo',
        'sql' => 'SELECT year_ AS anno,PD,HD,TX FROM treatments_by_year ORDER BY year_',
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
            'TX' => 
            array (
              0 => 'number',
              1 => 'TX',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'treatment_days_per_year_it' => 
      array (
        'name_it' => 'durata totale trattamenti per anno',
        'desc_it' => 'giorni totali di trattamento per ogni anno, divisi per tipo',
        'sql' => 'SELECT year_ AS anno,PD_days,HD_days,TX_days FROM treatments_by_year ORDER BY year_',
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'PD_days' => 
            array (
              0 => 'number',
              1 => 'PD_days',
            ),
            'HD_days' => 
            array (
              0 => 'number',
              1 => 'HD_days',
            ),
            'TX_days' => 
            array (
              0 => 'number',
              1 => 'TX_days',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'treatments_start_per_year_it' => 
      array (
        'name_it' => 'nuovi trattamenti per anno',
        'desc_it' => 'incidenza trattamenti per anno, divisi per tipo',
        'notes_it' => 'NB: con trattamento precedente=terapia conservativa si hanno i <i>pazienti</i> incidenti, a prescindere dal Registro',
        'sql' => 'SELECT DATE_FORMAT(treatments.start_date,\'%Y\') AS anno, SUM(IF(treatments.type=\'PD\',1,0)) AS \'PD\',SUM(IF(treatments.type=\'HD\',1,0)) AS \'HD\',SUM(IF(treatments.type=\'TX\',1,0)) AS \'TX\' FROM treatments_count LEFT JOIN treatments ON treatments.id=treatments_count.id WHERE  N<=:N AND IF(\':former_treatment\'=\'\',1,former_treatment=\':former_treatment\')GROUP BY YEAR(treatments.start_date) ORDER BY YEAR(treatments.start_date);',
        'parameters' => 
        array (
          'N' => 
          array (
            'name_it' => 'n°trattamento',
            'list_source' => 
            array (
              1 => '1°',
              100 => 'tutti',
            ),
            'default' => 100,
          ),
          'former_treatment' => 
          array (
            'name_it' => 'trattamento precedente',
            'list_source' => 
            array (
              '' => '',
              'CT' => 'CT',
              'TX' => 'TX',
              'PD' => 'PD',
              'HD' => 'HD',
            ),
            'default' => '',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
            'TX' => 
            array (
              0 => 'number',
              1 => 'TX',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'treatments_end_cause_it' => 
      array (
        'name_it' => 'cause fine trattamenti',
        'desc_it' => 'termini cicli dialitici per anno, divisi per cause fine',
        'sql' => 'SELECT 
            COALESCE(YEAR(end_date),NULL) as anno, 
            SUM(IF(_l_treatment_end_causes.id=1,1,0)) AS \'tx da cadavere\',
            SUM(IF(_l_treatment_end_causes.id=1,2,0)) AS \'tx da vivente\',
            SUM(IF(_l_treatment_end_causes.id=3,1,0)) AS \'decesso\',            
            SUM(IF(_l_treatment_end_causes.id=4,1,0)) AS \'altra tecnica\',
            SUM(IF(_l_treatment_end_causes.id=5,1,0)) AS \'ripresa f.r.\',
            SUM(IF(_l_treatment_end_causes.id=6,1,0)) AS \'uscita da RIDP\'
            FROM treatments 
            LEFT JOIN _l_treatment_end_causes ON _l_treatment_end_causes.id=treatments.end_cause
            LEFT JOIN _l_treatment_change_causes ON _l_treatment_change_causes.id=treatments.technique_change_cause
            WHERE treatments.end_date IS NOT NULL  
            GROUP BY anno
            HAVING anno>= :min_year
            ORDER BY anno',
        'parameters' => 
        array (
          'min_year' => 
          array (
            'name_it' => 'dal',
            'list_source' => 'SELECT YEAR(start_date) AS year_ FROM treatments GROUP BY YEAR(start_date) ORDER BY year_',
            'default' => 1989,
          ),
        ),
        'chart' => 
        array (
          'type' => 'AreaChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'tx da cadavere' => 
            array (
              0 => 'number',
              1 => 'tx da cadavere',
            ),
            'tx da vivente' => 
            array (
              0 => 'number',
              1 => 'tx da vivente',
            ),
            'ripresa f.r.' => 
            array (
              0 => 'number',
              1 => 'ripresa f.r.',
            ),
            'uscita da RIDP' => 
            array (
              0 => 'number',
              1 => 'uscita da RIDP',
            ),
            'altra tecnica' => 
            array (
              0 => 'number',
              1 => 'altra tecnica',
            ),
            'decesso' => 
            array (
              0 => 'number',
              1 => 'decesso',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'treatment_change_causes_it' => 
      array (
        'name_it' => 'cause cambio tecnica',
        'desc_it' => 'cause cambio tecnica su tutti i trattamenti',
        'sql' => 'SELECT 
            _l_treatment_change_causes.name_it AS causa,
            count(start_date) AS N
            FROM _l_treatment_change_causes 
            LEFT JOIN treatments  ON _l_treatment_change_causes.id=treatments.technique_change_cause           
            WHERE treatments.end_date IS NOT NULL   AND technique_change_cause IS NOT NULL AND technique_change_cause !=0
            GROUP BY _l_treatment_change_causes.name_it
            ORDER BY N DESC',
        'chart' => 
        array (
          'type' => 'PieChart',
          'columns' => 
          array (
            'causa' => 
            array (
              0 => 'string',
            ),
            'N' => 
            array (
              0 => 'number',
              1 => 'numero',
            ),
          ),
          'options' => 
          array (
            'legend' => 
            array (
              'position' => 'left',
            ),
            'chartArea' => 
            array (
              'width' => '70%',
              'height' => '70%',
            ),
            'is3D' => 'true',
          ),
        ),
      ),
      'treatment_durations_it' => 
      array (
        'name_it' => 'distribuzione durata trattamenti',
        'desc_it' => 'distribuzione durate trattamenti, per tipo.<br><span style=\'font-size:80%\'>L\'intervallo di tempo sulle ascisse è quello selezionato come parametro.</span>',
        'notes_it' => 'NB: TX preemptive non registrati fino al 2013',
        'sql' => 'SELECT 
                    TRUNCATE (treatment_duration/:interval_days,0) AS intervallo,
                    COUNT(*) AS N,
                    SUM(IF(treatment_type=\'PD\',1,0)) AS PD,
                    SUM(IF(treatment_type=\'HD\',1,0)) AS HD,
                    SUM(IF(treatment_type=\'TX\',1,0)) AS TX
                    FROM treatments_
                    WHERE IF(:outliers=NULL,true, treatment_duration<=:outliers*365)
                    GROUP BY intervallo
                    ORDER BY intervallo',
        'parameters' => 
        array (
          'interval_days' => 
          array (
            'name_it' => 'ampiezza intervalli',
            'default' => '91',
            'list_source' => 
            array (
              7 => 'settimane',
              30 => 'mesi',
              91 => 'trimestri',
              365 => 'anni',
            ),
            'desc_it' => 'ampiezza intervalli in cui sono raggruppati i dati',
          ),
          'outliers' => 
          array (
            'name_it' => 'periodo considerato',
            'default' => '5',
            'list_source' => 
            array (
              1 => 'primo anno',
              5 => 'primi 5 anni',
              10 => 'primi 10 anni',
              'NULL' => 'tutto',
            ),
            'desc_it' => 'esclude gli outliers oltre una certa durata',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ComboChart',
          'columns' => 
          array (
            'intervallo' => 
            array (
              0 => 'number',
              1 => '-',
            ),
            'N' => 
            array (
              0 => 'number',
              1 => 'totali',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
            'TX' => 
            array (
              0 => 'number',
              1 => 'TX',
            ),
          ),
          'options' => 
          array (
            'isStacked' => false,
            'pointSize' => '1',
            'hAxis' => 
            array (
              'title' => 'intervallo',
            ),
            'vAxes' => 
            array (
              0 => 
              array (
                'viewWindowMode' => 'explicit',
                'gridlines' => 
                array (
                  'color' => 'grey',
                ),
                'title' => 'numero trattamenti',
              ),
            ),
            'series' => 
            array (
            ),
            'colors' => 
            array (
              0 => '#000000',
              1 => '#cc0066',
              2 => '#3333ff',
              3 => '#006600',
            ),
          ),
        ),
      ),
      'data_distributions_it' => 
      array (
        'name_it' => 'distribuzione dati bio/clinici',
        'desc_it' => 'distribuzione delle medie dei dati dati biochimici/clinici numerici, divisi per M/F.',
        'sql' => 'SELECT TRUNCATE(data_approx,2) AS dato, COUNT(IF(gender!=\'F\',1,NULL)) AS F, COUNT(IF(gender!=\'M\',1,NULL)) AS M, TRUNCATE(avg_,2) AS media, TRUNCATE(std_,2) AS \'SD\'
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
                    ORDER BY dato',
        'parameters' => 
        array (
          'col_name' => 
          array (
            'name_it' => 'dato',
            'default' => 'Ca',
            'list_source' => 
            array (
              'Ca' => 'Calcio',
              'P' => 'Fosforo',
              'serum_protein' => 'proteinemia totale',
              'albuminemia' => 'albuminemia',
              'haemoglobin' => 'emoglobina',
              'urea' => 'urea',
            ),
            'desc_it' => 'il dato scelto. A seconda della distribuzione può essere necessario aggiustare l\'ampiezza degli intervalli e l\'esclusione degli outliers.',
          ),
          'interval_width' => 
          array (
            'name_it' => 'ampiezza intervalli',
            'default' => '0.005',
            'list_source' => 
            array (
              '0.005' => '5‰SD',
              '0.01' => '1%SD',
              '0.05' => '5%SD',
              0 => '0',
            ),
            'desc_it' => 'ampiezza intervalli in cui sono raggruppati i dati, espressa in percentuali di deviazioni standard',
          ),
          'outliers' => 
          array (
            'name_it' => 'includi solo dati nell\'intervallo',
            'default' => '1',
            'list_source' => 
            array (
              '0.01' => '1%SD',
              '0.05' => '5%SD',
              '0.1' => '10%SD',
              '0.25' => '25%SD',
              '0.5' => '50%SD',
              '0.75' => '75%SD',
              1 => '1SD',
              2 => '2SD',
              100 => 'includi tutti',
            ),
            'desc_it' => 'esclude gli outliers al di fuori di un intervallo espresso in deviazioni standard o frazioni',
          ),
        ),
        'chart' => 
        array (
          'type' => 'LineChart',
          'columns' => 
          array (
            'dato' => 
            array (
              0 => 'number',
              1 => ' ',
            ),
            'F' => 
            array (
              0 => 'number',
              1 => 'F',
            ),
            'M' => 
            array (
              0 => 'number',
              1 => 'M',
            ),
          ),
          'options' => 
          array (
            'isStacked' => true,
            'pointSize' => '2',
            'hAxis' => 
            array (
              'title' => 'intervallo',
            ),
            'vAxes' => 
            array (
              0 => 
              array (
                'viewWindowMode' => 'explicit',
                'gridlines' => 
                array (
                  'color' => 'grey',
                ),
                'title' => 'N',
              ),
              1 => 
              array (
                'gridlines' => 
                array (
                  'color' => 'transparent',
                ),
                'viewWindowMode' => 'explicit',
                'title' => 'M',
              ),
            ),
            'series' => 
            array (
              0 => 
              array (
                'targetAxisIndex' => '0',
              ),
              1 => 
              array (
                'targetAxisIndex' => '0',
              ),
            ),
            'colors' => 
            array (
              0 => '#cc0066',
              1 => '#3333ff',
            ),
          ),
        ),
      ),
      'peritonites_date_it' => 
      array (
        'name_it' => 'casi di peritonite/tempo peritoneale',
        'desc_it' => 'n°peritoniti/gg da inizio dialisi',
        'sql' => 'SELECT :days_interval*ROUND(DATEDIFF(date,start_date)/ :days_interval) AS tempo, COUNT(peritonites.id) AS N  
                FROM peritonites LEFT JOIN treatments ON treatments.id=peritonites.parent
                WHERE type=\'PD\'  
                GROUP BY tempo                  
                HAVING tempo>=0 AND ((:limit=0) OR tempo<:limit)
                ORDER by tempo',
        'parameters' => 
        array (
          'days_interval' => 
          array (
            'name_it' => 'raggruppamento per',
            'desc_it' => ' di raggruppamento della distribuzione',
            'list_source' => 
            array (
              1 => 'giorno',
              7 => 'settimana',
              '30.4' => 'mese',
              '60.8' => 'bimestre',
              '182.6' => 'semestre',
              '365.3' => 'anno',
            ),
            'default' => '60.8',
          ),
          'limit' => 
          array (
            'name_it' => 'limite',
            'list_source' => 
            array (
              0 => 'tutti',
              366 => 'primo anno',
              832 => 'primi 2 anni',
              1825 => 'primi 5 anni',
            ),
            'default' => '1825',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ScatterChart',
          'columns' => 
          array (
            'tempo' => 
            array (
              0 => 'number',
              1 => 'tempo',
            ),
            'N' => 
            array (
              0 => 'number',
              1 => 'N',
            ),
          ),
          'options' => 
          array (
            'title' => 'distribuzione tempo peritoniti da inizio dialisi',
            'legend' => 
            array (
              'position' => 'in',
            ),
            'chartArea' => 
            array (
              'top' => '5%',
              'left' => '5%',
              'height' => '70%',
              'width' => '70%',
            ),
            'hAxis' => 
            array (
              'gridlines' => 
              array (
                'color' => '#555555',
              ),
            ),
            'vAxis' => 
            array (
              'slantedText' => 'true',
              'textPosition' => 'none',
              'title' => 'n. episodi',
              'textStyle' => 
              array (
                'fontName' => 'arial',
              ),
            ),
          ),
        ),
      ),
      'peritonites_per_year_it' => 
      array (
        'name_it' => 'andamento peritoniti',
        'desc_it' => 'NB: le peritoniti <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le peritoniti incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno/paziente</b> che rappresenta il valore medio aspettato di peritoniti per anno di dialisi.',
        'sql' => '
                SELECT 
	abs_compli.year_ AS anno,
    COALESCE(N_peri*IF(:mode=0,1,:mode/tby.PD_days),0) AS \'n°peritoniti\'
FROM
	(SELECT 
	YEAR(peri.date) AS year_, 
	COUNT(peri.id) AS \'N_peri\'
	FROM peritonites peri
	LEFT JOIN treatments_ ON treatments_.id=peri.parent
	GROUP BY year_
) abs_compli
     LEFT JOIN treatments_by_year AS tby ON tby.year_=abs_compli.year_
     WHERE tby.year_>= :min_year
     ORDER BY tby.year_
            ',
        'parameters' => 
        array (
          'min_year' => 
          array (
            'name_it' => 'anno inizio',
            'list_source' => 
            array (
              1980 => 'tutti',
              1990 => '1990',
              2000 => '2000',
            ),
            'default' => '1980',
          ),
          'mode' => 
          array (
            'name_it' => 'modalità',
            'list_source' => 
            array (
              0 => 'numeri assoluti',
              365 => 'relativi ad 1 anno dialisi/paziente',
            ),
            'default' => '0',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'n°peritoniti' => 
            array (
              0 => 'number',
              1 => 'n°peritoniti',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'complications_per_year_it' => 
      array (
        'name_it' => 'andamento complicanze',
        'desc_it' => 'NB: le complicanze <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le complicanze incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno/paziente</b> che rappresenta il valore medio aspettato di complicanze per anno di dialisi.',
        'sql' => '
                SELECT 
	abs_compli.year_ AS anno,
    HD_compli*IF(:mode=0,1,:mode/tby.HD_days) AS \'relative a HD\',
    PD_compli*IF(:mode=0,1,:mode/tby.PD_days) AS \'relative a PD\',
    ND_compli*IF(:mode=0,1,:mode/tby.ALL_days) AS \'non dialitiche\'
FROM
	(SELECT 
	YEAR(complications_.date) AS year_, 
	#COUNT(complications_.id) AS total_compli,
	SUM(complications_.dr*
	(treatments_.treatment_type=\'HD\')) AS \'HD_compli\',
	SUM(complications_.dr*
	(treatments_.treatment_type=\'PD\')) AS \'PD_compli\',
	SUM(ABS(complications_.dr-1)) AS \'ND_compli\'
	FROM complications_
	LEFT JOIN treatments_ ON treatments_.id=complications_.parent
	GROUP BY year_
) abs_compli
     LEFT JOIN treatments_by_year AS tby ON tby.year_=abs_compli.year_
     ORDER BY anno
            ',
        'parameters' => 
        array (
          'mode' => 
          array (
            'name_it' => 'modalità',
            'list_source' => 
            array (
              0 => 'numeri assoluti',
              365 => 'relativi ad 1 anno dialisi/paziente',
            ),
            'default' => '0',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'relative a HD' => 
            array (
              0 => 'number',
              1 => 'relative a HD',
            ),
            'relative a PD' => 
            array (
              0 => 'number',
              1 => 'relative a PD',
            ),
            'non dialitiche' => 
            array (
              0 => 'number',
              1 => 'non dialitiche',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'hospitalizations_per_year_it' => 
      array (
        'name_it' => 'andamento ospedalizzazioni',
        'desc_it' => 'Andamento anno per anno di numero o durata delle ospedalizzazioni (scelta del dato da parametri)<br>NB: le ospedalizzazioni <b>assolute</b> anno per anno non danno un trend affidabile, perché non si tiene conto di durata e numero di dialisi su cui le ospedalizzazioni incidono. <br>
Per questo si può scegliere di rappresentare il dato <b>relativo ad 1 anno dialisi/paziente</b> che rappresenta il valore medio asprettato del dato per un anno di dialisi.<br>
<br>
<span style=\'font-size:90%\'> <b>Es.</b>:cercando i giorni di ospedalizzazione in numero assoluto, si vede ad es. che quelli correlati all\'emodialisi nel 2001 sono stati 209; selezionando invece \'relativo ad anno/paziente\' per HD 2001 si ha 5.3 gg: ovvero i pazienti sono stati ospedalizzati in media 5.3 giorni per anno di ciclo a causa di complicanze legate all\'emodialisi.</span>',
        'sql' => 'SELECT 
	hosp.year_ AS anno,
	IF(\':what\'=\'days\',HD_hospdays,HD_hospnumb)*IF(:mode=0,1,:mode/tby.HD_days) AS \'rel.HD\', 
	IF(\':what\'=\'days\',PD_hospdays,PD_hospnumb)*IF(:mode=0,1,:mode/tby.PD_days) AS \'rel.PD\', 
	(IF(\':what\'=\'days\',ALL_hospdays,ALL_hospnumb)-IF(\':what\'=\'days\',HD_hospdays,HD_hospnumb)-IF(\':what\'=\'days\',PD_hospdays,PD_hospnumb))*IF(:mode=0,1,:mode/tby.ALL_days) AS \'rel.ND\',
        IF(\':what\'=\'days\',ALL_hospdays,ALL_hospnumb)*IF(:mode=0,1,:mode/tby.ALL_days) AS \'tot\'        
FROM
	(SELECT 
	YEAR(complications_.date) AS year_, 
	SUM(complications_.dr*(complications_.hd>0)*
	(treatments_.treatment_type=\'HD\')) AS \'HD_hospnumb\',
	SUM(complications_.dr*complications_.hd*
	(treatments_.treatment_type=\'HD\')) AS \'HD_hospdays\', 
	SUM(complications_.dr*(complications_.hd>0)*
	(treatments_.treatment_type=\'PD\')) AS \'PD_hospnumb\',
	SUM(complications_.dr*complications_.hd*
	(treatments_.treatment_type=\'PD\')) AS \'PD_hospdays\', 
	SUM(complications_.hd>0) AS \'ALL_hospnumb\',
	SUM(complications_.hd) AS \'ALL_hospdays\' 
	FROM complications_
	LEFT JOIN treatments_ ON treatments_.id=complications_.parent
	GROUP BY year_
) hosp
     LEFT JOIN treatments_by_year AS tby ON tby.year_=hosp.year_
     ORDER BY anno

            ',
        'parameters' => 
        array (
          'what' => 
          array (
            'name_it' => 'dato',
            'list_source' => 
            array (
              'numb' => 'n°ospedalizzazioni',
              'days' => 'giorni ospedalizzazione',
            ),
            'default' => 'numb',
          ),
          'mode' => 
          array (
            'name_it' => 'modalità',
            'list_source' => 
            array (
              0 => 'numero assoluto',
              365 => 'relativi ad 1 anno dialisi/paziente',
            ),
            'default' => '0',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ColumnChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
            ),
            'rel.HD' => 
            array (
              0 => 'number',
              1 => 'osp.relative a HD',
            ),
            'rel.PD' => 
            array (
              0 => 'number',
              1 => 'osp.relative a PD',
            ),
            'rel.ND' => 
            array (
              0 => 'number',
              1 => 'osp.non relative a dialisi',
            ),
          ),
          'options' => 
          array (
            'isStacked' => 'true',
          ),
        ),
      ),
      'comorbidities_n_it' => 
      array (
        'name_it' => 'numero comorbidità',
        'desc_it' => 'distribuzione del numero di comorbidità per paziente',
        'sql' => 'SELECT 
                COUNT(patient) AS \'n° pazienti\',
                IF(comorbidities=0,\'nessuna\',CONCAT(comorbidities, \' comorb.\')) AS \'n° comorbidità\' FROM
                (	
                    SELECT
                        patients.id AS patient,
                        COUNT(pc.comorbidity_id) AS comorbidities
                        FROM patients
                    LEFT JOIN patients_comorbidities pc ON pc.patient_id=patients.id
                        GROUP BY patients.id  
                )comorb_numbers
                GROUP BY comorbidities
                ORDER BY comorbidities ASC',
        'chart' => 
        array (
          'type' => 'PieChart',
          'columns' => 
          array (
            'n° comorbidità' => 
            array (
              0 => 'string',
            ),
            'n° pazienti' => 
            array (
              0 => 'number',
              1 => 'numero',
            ),
          ),
          'options' => 
          array (
            'legend' => 
            array (
              'position' => 'left',
            ),
            'chartArea' => 
            array (
              'width' => '70%',
              'height' => '70%',
            ),
            'is3D' => 'true',
          ),
        ),
      ),
      'peritonites_prob_it' => 
      array (
        'name_it' => 'probabilità peritoniti',
        'desc_it' => 'probabilità non cumulativa in funzione del tempo dall\' inizio della peritoneale',
        'sql' => 'SELECT ROUND(DATEDIFF(date,start_date)/ :days_interval) AS intervallo, COUNT(peritonites.id)/n_tot AS p
                    FROM peritonites LEFT JOIN treatments ON treatments.id=peritonites.parent
                    LEFT JOIN (SELECT COUNT(id) AS n_tot FROM peritonites) nTotTab ON true
                    WHERE type=\'PD\'  
                    GROUP BY intervallo ASC WITH ROLLUP                 
                    HAVING intervallo>=0 AND ((:limit=0) OR :days_interval*intervallo<:limit)',
        'parameters' => 
        array (
          'days_interval' => 
          array (
            'name_it' => 'intervallo',
            'desc_it' => 'intervallo di campionamento della distribuzione',
            'list_source' => 
            array (
              1 => 'giorno',
              7 => 'settimana',
              '30.4' => 'mese',
              '60.8' => 'bimestre',
              '182.6' => 'semestre',
              '365.3' => 'anno',
            ),
            'default' => '30.4',
          ),
          'limit' => 
          array (
            'name_it' => 'periodo',
            'desc_it' => 'limite al tempo da inizio dialisi',
            'list_source' => 
            array (
              0 => '-',
              366 => 'primo anno',
              1096 => 'primi 3 anni',
              1825 => 'primi 5 anni',
            ),
            'default' => '1825',
          ),
        ),
        'chart' => 
        array (
          'type' => 'ScatterChart',
          'columns' => 
          array (
            'intervallo' => 
            array (
              0 => 'number',
              1 => 'intervallo',
            ),
            'p' => 
            array (
              0 => 'number',
              1 => 'prob',
            ),
          ),
          'options' => 
          array (
            'title' => 'distribuzione di probabilità peritoniti',
            'trendlines' => 
            array (
              0 => 
              array (
                'type' => 'exponential',
                'degree' => '3',
                'showR2' => 'true',
                'visibleInLegend' => 'true',
              ),
            ),
            'legend' => 
            array (
              'position' => 'in',
              'alignment' => 'start',
            ),
            'chartArea' => 
            array (
              'top' => '15',
              'height' => '70%',
              'width' => '80%',
            ),
            'axisTitlesPosition' => 'out',
            'hAxis' => 
            array (
              'title' => 'intervallo (v. parametri)',
              'gridlines' => 
              array (
                'units' => '25',
                'color' => '#555555',
              ),
            ),
            'vAxis' => 
            array (
              'title' => 'prob.% non cumulativa',
              'format' => 'percent',
              'textStyle' => 
              array (
                'fontName' => 'arial',
              ),
            ),
            'series' => 
            array (
              0 => 
              array (
                'visibleInLegend' => 'false',
              ),
            ),
            'colors' => 
            array (
              0 => '#435988',
            ),
          ),
        ),
      ),
      'completion_by_year_it' => 
      array (
        'sql' => 'SELECT 
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
                ORDER BY clinicals_.year_',
        'name_it' => 'compilazione osservazioni',
        'notes_it' => 'La query non tiene traccia di aggiunta campi, cambio versioni DB e metodologie di raccolta dati.<br>versioni database:<br>v.1.0 fino al 2002<br>v.2.0: 2003-2004<br>v.3.0 al 2004-2018<br>v.4.0: corrente',
        'chart' => 
        array (
          'type' => 'LineChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
              1 => ' ',
            ),
            'clinici' => 
            array (
              0 => 'number',
              1 => 'clinici',
            ),
            'biochimici' => 
            array (
              0 => 'number',
              1 => 'biochimici',
            ),
            'prescrizioni_pd' => 
            array (
              0 => 'number',
              1 => 'prescrizioni PD',
            ),
            'prescrizioni_hd' => 
            array (
              0 => 'number',
              1 => 'prescrizioni HD',
            ),
          ),
          'options' => 
          array (
            'isStacked' => false,
            'pointSize' => '2',
            'hAxis' => 
            array (
              'title' => 'anno',
              'slantedText' => false,
              'slantedTextAngle' => '90',
              'textStyle' => 
              array (
                'fontSize' => '10px',
              ),
            ),
            'vAxes' => 
            array (
              0 => 
              array (
                'format' => 'percent',
                'viewWindowMode' => 'explicit',
                'gridlines' => 
                array (
                  'color' => 'grey',
                ),
                'title' => '% scheda compilata',
              ),
            ),
            'series' => 
            array (
              0 => 
              array (
                'targetAxisIndex' => '0',
              ),
            ),
            'colors' => 
            array (
              0 => '#cc0066',
              1 => '#3333ff',
              2 => '#22ffff',
              3 => '#22ff00',
            ),
          ),
        ),
      ),
      'sessions_by_center_it' => 
      array (
        'sql' => 'SELECT YEAR(start_time) AS Y, WEEKOFYEAR(start_time) AS W, centers.code AS center_code, count(_s_session.id) AS N
                    FROM 
                    users
                    LEFT JOIN _s_session ON users.id=_s_session.user
                    LEFT JOIN centers ON centers.id=users.center
                    GROUP BY W,center_code, Y
                    ORDER BY Y,W
                ',
        'name_it' => 'collegamenti centro',
        'notes_it' => '',
        'chart_' => 
        array (
          'type' => 'LineChart',
          'columns' => 
          array (
            'anno' => 
            array (
              0 => 'string',
              1 => ' ',
            ),
            'clinici' => 
            array (
              0 => 'number',
              1 => 'clinici',
            ),
            'biochimici' => 
            array (
              0 => 'number',
              1 => 'biochimici',
            ),
            'prescrizioni_pd' => 
            array (
              0 => 'number',
              1 => 'prescrizioni PD',
            ),
            'prescrizioni_hd' => 
            array (
              0 => 'number',
              1 => 'prescrizioni HD',
            ),
          ),
          'options' => 
          array (
            'isStacked' => false,
            'pointSize' => '2',
            'hAxis' => 
            array (
              'title' => 'anno',
              'slantedText' => false,
              'slantedTextAngle' => '90',
              'textStyle' => 
              array (
                'fontSize' => '10px',
              ),
            ),
            'vAxes' => 
            array (
              0 => 
              array (
                'format' => 'percent',
                'viewWindowMode' => 'explicit',
                'gridlines' => 
                array (
                  'color' => 'grey',
                ),
                'title' => '% scheda compilata',
              ),
            ),
            'series' => 
            array (
              0 => 
              array (
                'targetAxisIndex' => '0',
              ),
            ),
            'colors' => 
            array (
              0 => '#cc0066',
              1 => '#3333ff',
              2 => '#22ffff',
              3 => '#22ff00',
            ),
          ),
        ),
      ),
      'centers_it' => 
      array (
        'sql' => 'select `centers`.`code` AS `codice`,`db689269632`.`centers`.`unit` AS `unità`,`db689269632`.`centers`.`institute` AS `istituto`,`db689269632`.`centers`.`address` AS `indirizzo`,`db689269632`.`centers`.`city` AS `città` from `db689269632`.`centers` order by `db689269632`.`centers`.`code`',
        'name_it' => 'centri',
      ),
      'patients_it' => 
      array (
        'sql' => 'SELECT codice,centro,genere,`data di nascita`,`luogo di nascita`, `provincia di nascita`, `luogo di residenza`,`provincia di residenza`,`prd (codice edta)`,prd, `comorbidità`,`specifica comorbidità`,`ultimo aggiornamento completo` FROM view_patients_it',
        'name_it' => 'pazienti',
      ),
      'deaths_it' => 
      array (
        'sql' => 'SELECT `patients`.`code` AS `paziente`,DATE_FORMAT(`deaths`.`date`,\'%d/%m/%Y\') AS `data decesso`,`_l_death_causes`.`name_it` AS `causa decesso`,`_l_death_causes_groups`.`desc_it` AS `tipo causa decesso`,`deaths`.`cause_description` AS `causa decesso descrizione`,round(((year(`deaths`.`date`) - year(`patients`.`birth_date`)) + ((month(`deaths`.`date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età al decesso`,`deaths`.`renal_risk_factors` AS `fattori di rischio renali`,`deaths`.`other_risk_factors` AS `altri fattori di rischio`,`deaths`.`subsequent_risk_factors` AS `fattori di rischio successivi`,`_l_death_causes`.`name_en` AS `causa decesso (en)`,`_l_death_causes_groups`.`desc_en` AS `desc_en`,`_l_death_causes`.`code_1995` AS `causa decesso (codice pre-1995)` from (((`deaths` left join `_l_death_causes` on((`deaths`.`cause` = `_l_death_causes`.`id`))) left join `_l_death_causes_groups` on((`_l_death_causes`.`group` = `_l_death_causes_groups`.`id`))) left join `patients` on((`deaths`.`parent` = `patients`.`id`)));',
        'name_it' => 'decessi',
      ),
      'treatments_it' => 
      array (
        'sql' => 'select `patients`.`code` AS `paziente`,`treatments`.`type` AS `tipo`,date_format(`treatments`.`start_date`,\'%d/%m/%Y\') AS `inizio trattamento`,date_format(`treatments`.`end_date`,\'%d/%m/%Y\') AS `fine trattamento`,round(((year(`treatments`.`start_date`) - year(`patients`.`birth_date`)) + ((month(`treatments`.`start_date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età a inizio trattamento`,timestampdiff(MONTH,`treatments`.`start_date`,coalesce(`treatments`.`end_date`,curdate())) AS `durata trattamento`,`treatments`.`creatinine_clearance` AS `clearance creatinina`,`treatments`.`residual_diuresis` AS `diuresi residua`,`treatments`.`urea_clearance` AS `clearance urea`,`treatments`.`former_treatment` AS `trattamento precedente`,`_l_treatment_end_causes`.`name_it` AS `causa fine`,if((`treatments`.`end_cause` = 4),if((`treatments`.`type` = \'PD\'),\'1\',\'2\'),`_l_treatment_end_causes`.`espn_code`) AS `causa fine (codice espn)`,`treatments`.`new_center` AS `nuovo centro`,`_l_treatment_change_causes`.`name_it` AS `causa cambio tecnica`,`treatments`.`technique_change_cause_specification` AS `specifica causa cambio tecnica`,if((`treatments`.`type` = \'PD\'),`treatments`.`pd_training_days`,\'N/A\') AS `giorni di training peritoneale`,`_l_treatment_end_causes`.`name_en` AS `causa fine (en)`,`_l_treatment_change_causes`.`name_en` AS `causa cambio tecnica (en)` from (((`treatments` left join `patients` on((`treatments`.`parent` = `patients`.`id`))) left join `_l_treatment_end_causes` on((`treatments`.`end_cause` = `_l_treatment_end_causes`.`id`))) left join `_l_treatment_change_causes` on((`treatments`.`technique_change_cause` = `_l_treatment_change_causes`.`id`))) ORDER BY `treatments`.`start_date` ',
        'name_it' => 'trattamenti',
      ),
      'follow_ups_it' => 
      array (
        'sql' => 'SELECT
                follow_ups.id AS id,
                patient_code AS \'paziente\', 
                CONCAT(DATE_FORMAT(treatment_start_date,\'%d/%m/%Y\'), \' \',treatment_type) as trattamento,
                ifnull(if((`follow_ups`.`time` = -(1)),\'fine dialisi\',concat(\'mese \',`follow_ups`.`time`)),\'N/A\') AS `mese ciclo`,
                `_l_transplantation_list`.`name_it` AS `lista trapianti`,
                round(((year(`follow_ups`.`date`) - year(`patients`.`birth_date`)) + ((month(`follow_ups`.`date`) - month(`patients`.`birth_date`)) / 12)),1) AS `età all\'osservazione`,
                COALESCE(theracount.N,0) AS  `n.terapie`
                FROM
                follow_ups
                LEFT JOIN treatments_ ON follow_ups.parent = treatments_.id
                LEFT JOIN patients ON treatments_.patient_id = patients.id
                LEFT JOIN _l_transplantation_list ON follow_ups.transplantation_list = _l_transplantation_list.code
                LEFT JOIN 
                (SELECT parent, COUNT(id) AS N FROM therapies GROUP BY parent) theracount ON theracount.parent=follow_ups.id

                ORDER BY paziente, follow_ups.date
            ',
        'name_it' => 'osservazioni',
      ),
      'clinicals_it' => 
      array (
        'sql' => 'SELECT * FROM view_clinicals_it',
        'name_it' => 'schede cliniche',
      ),
      'biochemicals_it' => 
      array (
        'sql' => 'SELECT * FROM view_biochemicals_it',
        'name_it' => 'dati biochimici',
      ),
      'pd_prescriptions_it' => 
      array (
        'sql' => 'SELECT * FROM view_pd_prescriptions_it',
        'name_it' => 'prescrizioni pd',
      ),
      'hd_prescriptions_it' => 
      array (
        'sql' => 'SELECT * FROM view_hd_prescriptions_it',
        'name_it' => 'prescrizioni hd',
      ),
      'pd_connections_it' => 
      array (
        'sql' => 'SELECT * FROM view_pd_connections_it',
        'name_it' => 'connessioni',
      ),
      'hd_accesses_it' => 
      array (
        'sql' => 'SELECT * FROM view_hd_accesses_it',
        'name_it' => 'accessi',
      ),
      'catheters_it' => 
      array (
        'sql' => 'SELECT * FROM catheters',
        'name_it' => 'cateteri',
      ),
      'peritonites_it' => 
      array (
        'sql' => 'SELECT * FROM peritonites',
        'name_it' => 'peritoniti',
      ),
      'peritoneal_equilibration_tests_it' => 
      array (
        'sql' => 'SELECT * FROM peritoneal_equilibration_tests',
        'name_it' => 'test eq.per.',
      ),
      'complications_it' => 
      array (
        'sql' => 'SELECT * FROM complications_ ORDER BY date',
        'name_it' => 'complicanze',
      ),
      '_l_primary_renal_diseases_it' => 
      array (
        'sql' => 'SELECT id AS codice,name_it AS nome ,name_en AS `nome(en)`,edta_code as `codice EDTA` FROM _l_primary_renal_diseases ORDER BY id',
        'name_it' => 'malattie di base',
      ),
      '_l_comorbidities_it' => 
      array (
        'sql' => 'SELECT id as codice, name_it AS nome , name_en AS `nome(en)` FROM _l_comorbidities WHERE active=1 ORDER BY id',
        'name_it' => 'comorbidità',
      ),
      '_l_death_causes_it' => 
      array (
        'sql' => 'SELECT _l_death_causes.id AS codice, name_it as nome, desc_it AS categoria FROM _l_death_causes LEFT JOIN _l_death_causes_groups ON _l_death_causes.`group`= _l_death_causes_groups.id ORDER BY _l_death_causes.id ASC',
        'name_it' => 'cause decesso',
      ),
      '_l_treatment_change_causes_it' => 
      array (
        'sql' => 'SELECT id as codice, name_it as nome, name_en as name FROM _l_treatment_change_causes ORDER BY id;',
        'name_it' => 'cause cambio trattamento',
      ),
      '_l_treatment_end_causes_it' => 
      array (
        'sql' => 'SELECT id as codice, name_it as nome, CONCAT(COALESCE(espn_code,\'\'),COALESCE(espn_note,\'\')) as \'codice ESPN\', treatment_type AS \'tipo trattamento\' FROM _l_treatment_end_causes;',
        'name_it' => 'cause cambio trattamento',
      ),
      '_l_therapies_it' => 
      array (
        'sql' => 'SELECT id as codice, name_it, name_en,everyday,`order`,old_name, uom_it as `u.m.`, calc_uom_it as `u.m. calc.` FROM view_l_therapies_it ORDER BY `order`;',
        'name_it' => 'terapie',
      ),
      '_l_pd_connections_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_pd_connections ORDER BY id;',
        'name_it' => 'tipi connessione pd',
      ),
      '_l_hd_accesses_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_hd_accesses ORDER BY id;',
        'name_it' => 'tipi accesso hd',
      ),
      '_l_catheter_types_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it as nome FROM _l_catheter_types ORDER BY id;',
        'name_it' => 'tipi catetere',
      ),
      '_l_catheter_disinfectants_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_catheter_disinfectants ORDER BY id;',
        'name_it' => 'disinfettanti catetere',
      ),
      '_l_catheter_removal_reasons_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it as nome FROM _l_catheter_removal_reasons ORDER BY id;',
        'name_it' => 'motivi rimozione catetere',
      ),
      '_l_catheter_complications_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_catheter_complications ORDER BY `order`;',
        'name_it' => 'complicanze catetere',
      ),
      '_l_catheter_complication_symptoms_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome, type_it as \'tipo complicanza\' FROM _l_catheter_complication_symptoms ORDER BY type_it,id;',
        'name_it' => 'sintomi complicanze catetere',
      ),
      '_l_peritonitis_germs_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome, type_it as tipo FROM _l_peritonitis_germs ORDER BY `order`, id;',
        'name_it' => ' germi peritonite',
      ),
      '_l_peritonitis_diagnoses_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_peritonitis_diagnoses ORDER BY `order`;',
        'name_it' => 'diagnosi peritoniti',
      ),
      '_l_peritonitis_therapies_it' => 
      array (
        'sql' => 'SELECT id AS codice, name_it AS nome FROM _l_peritonitis_therapies WHERE id!=0 ORDER BY `order`, id;',
        'name_it' => 'terapie peritoniti',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'rdp' => 
  array (
    'hierarchy' => 
    array (
      'center' => 
      array (
        'name' => 'center',
        'names' => 'centers',
        'parent' => NULL,
        'children' => 
        array (
          'patient' => 
          array (
          ),
        ),
        'orderBy' => 'code',
      ),
      'patient' => 
      array (
        'name' => 'patient',
        'names' => 'patients',
        'parent' => 'center',
        'children' => 
        array (
          'treatment' => 
          array (
          ),
        ),
        'orderBy' => 'code',
      ),
      'death' => 
      array (
        'name' => 'death',
        'names' => 'deaths',
        'parent' => 'patient',
        'editIn' => '/edit/patient/{parent}',
      ),
      'treatment' => 
      array (
        'name' => 'treatment',
        'names' => 'treatments',
        'parent' => 'patient',
        'children' => 
        array (
          'follow_up' => 
          array (
            'if' => '$this->type==\'PD\' || $this->type==\'HD\'',
          ),
          'pd_connection' => 
          array (
            'if' => '$this->type==\'PD\'',
          ),
          'hd_access' => 
          array (
            'if' => '$this->type==\'HD\'',
          ),
          'catheter' => 
          array (
            'if' => '$this->type==\'PD\'',
          ),
          'complication' => 
          array (
          ),
          'peritonitis' => 
          array (
            'if' => '$this->type==\'PD\'',
          ),
          'peritoneal_equilibration_test' => 
          array (
            'if' => '$this->type==\'PD\'',
          ),
        ),
        'orderBy' => 'start_date',
      ),
      'follow_up' => 
      array (
        'name' => 'follow_up',
        'names' => 'follow_ups',
        'parent' => 'treatment',
        'children' => 
        array (
          'clinical' => 
          array (
            'hideInBreadcrumbs' => true,
          ),
          'biochemical' => 
          array (
            'hideInBreadcrumbs' => true,
          ),
          'pd_prescription' => 
          array (
            'if' => '$this->parentType()==\'PD\'',
            'hideInBreadcrumbs' => true,
          ),
          'hd_prescription' => 
          array (
            'if' => '$this->parentType()==\'HD\'',
            'hideInBreadcrumbs' => true,
          ),
          'therapy' => 
          array (
            'hideInBreadcrumbs' => true,
          ),
        ),
        'orderBy' => 'date',
      ),
      'clinical' => 
      array (
        'name' => 'clinical',
        'names' => 'clinicals',
        'parent' => 'follow_up',
        'children' => 
        array (
        ),
        'orderBy' => 'id',
        'editIn' => '/edit/follow_up/{parent}',
      ),
      'biochemical' => 
      array (
        'name' => 'biochemical',
        'names' => 'biochemicals',
        'parent' => 'follow_up',
        'children' => 
        array (
        ),
        'orderBy' => 'id',
        'editIn' => '/edit/follow_up/{parent}',
      ),
      'pd_prescription' => 
      array (
        'name' => 'pd_prescription',
        'names' => 'pd_prescriptions',
        'parent' => 'follow_up',
        'children' => 
        array (
        ),
        'orderBy' => 'id',
        'editIn' => '/edit/follow_up/{parent}',
      ),
      'hd_prescription' => 
      array (
        'name' => 'hd_prescription',
        'names' => 'hd_prescriptions',
        'parent' => 'follow_up',
        'children' => 
        array (
        ),
        'editIn' => '/edit/follow_up/{parent}',
      ),
      'therapy' => 
      array (
        'name' => 'therapy',
        'names' => 'therapies',
        'parent' => 'follow_up',
        'children' => 
        array (
        ),
        'editIn' => '/edit/follow_up/{parent}',
      ),
      'pd_connection' => 
      array (
        'name' => 'pd_connections',
        'names' => 'pd_connections',
        'parent' => 'treatment',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
      ),
      'hd_access' => 
      array (
        'name' => 'hd_access',
        'names' => 'hd_accesses',
        'parent' => 'treatment',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
      ),
      'catheter' => 
      array (
        'name' => 'catheter',
        'names' => 'catheters',
        'parent' => 'treatment',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
      ),
      'catheter_medication' => 
      array (
        'name' => 'catheter_medication',
        'names' => 'catheter_medications',
        'parent' => 'catheter',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
        'editIn' => '/edit/catheter/{parent}',
      ),
      'catheter_complication' => 
      array (
        'name' => 'catheter_complication',
        'names' => 'catheter_complications',
        'parent' => 'catheter',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
        'editIn' => '/edit/catheter/{parent}',
      ),
      'complication' => 
      array (
        'name' => 'complication',
        'names' => 'complications',
        'parent' => 'treatment',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
      ),
      'peritonitis' => 
      array (
        'name' => 'peritonitis',
        'names' => 'peritonites',
        'parent' => 'treatment',
        'children' => 
        array (
          'peritonitis_therapy' => 
          array (
          ),
        ),
        'orderBy' => 'date',
      ),
      'peritonitis_therapy' => 
      array (
        'name' => 'peritonitis_therapy',
        'names' => 'peritonitis_therapies',
        'parent' => 'peritonitis',
        'children' => 
        array (
        ),
        'orderBy' => 'start_date',
        'editIn' => '/edit/peritonitis/{parent}',
      ),
      'peritoneal_equilibration_test' => 
      array (
        'name' => 'pet',
        'names' => 'pets',
        'parent' => 'treatment',
        'children' => 
        array (
        ),
        'orderBy' => 'date',
      ),
    ),
    'tree' => 
    array (
      'centers' => 'select id,code, concat(city, \'-\' , COALESCE(institute,COALESCE(unit,\'?\'))) as short_desc, concat(unit, \' \', institute,\' \' ,city) as long_desc from centers ',
      'users' => 'select id, center, username, priority from users',
      'patients' => 'select id,parent,code,treats_open from patients_treatments_ ',
      'treatments' => 'select id, parent, type, concat(treatments.type,\' \',concat_ws(\'-\',date_format(treatments.start_date,\'%d/%m/%y\'))) AS title from treatments ',
      'follow_ups' => 'select id, parent, date, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it, IF(time IS NULL,DATE_FORMAT(date,\'%d/%m/%y\'),IF(time=-1,\'fine\',CONCAT(\'mese \', time))) AS desc_it, IF(time IS NULL,DATE_FORMAT(date,\'%m-%d-%y\'),IF(time=-1,\'end\',CONCAT(time,\' month\'))) AS desc_en from follow_ups',
      'pd_connections' => 'select id, parent, date,DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from pd_connections',
      'hd_accesses' => 'select id, parent, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from hd_accesses',
      'catheters' => 'select id, parent, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from catheters',
      'peritonites' => 'select id, parent, date, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from peritonites',
      'peritoneal_equilibration_tests' => 'select id, parent, date, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from peritoneal_equilibration_tests',
      'complications' => 'select id, parent, date, DATE_FORMAT(date,\'%m-%d-%y\') as date_en, DATE_FORMAT(date,\'%d/%m/%y\') as date_it from complications',
      'check_former_treatments' => 'select treatments_.*, concat(treatment_code) AS title FROM treatments_ LEFT JOIN treatments ON treatments_.id=treatments.id WHERE treatments.type=former_treatment ORDER BY center_code, treatments.start_date ',
    ),
    'datacheck' => 
    array (
      'follow_ups_duplicates' => 
      array (
        'name_it' => 'duplicati osservazioni',
        'desc_it' => 'osservazioni dello stesso trattamento con stessa data',
        'notes_it' => '',
        'sql' => '# same date, parent; with checksum for its children to see if they are completely redundant=>one can be erased
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
      ),
      'former_treatments' => 
      array (
        'name_it' => 'trattamenti precedenti',
        'desc_it' => 'trattamenti con ciclo precedente non corrispondente non corrispondente al campo \'trattamento precedente\'',
        'notes_it' => '',
        'sql' => 'SELECT
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
      ),
    ),
    'queries_lists' => 
    array (
      'interrogazioni base' => 'CUSTOM',
      'info generali database' => 'INFO',
      'tabelle base' => 'TABLE',
      'liste riferimento' => 'LIST',
    ),
    'statistics' => 
    array (
      'active_treatments' => 
      array (
        'sql' => 'SELECT center_code, SUM(IF(treatment_type=\'PD\',1,0)) AS \'PD\',SUM(IF(treatment_type=\'HD\',1,0)) AS \'HD\' FROM treatments_ WHERE treatment_open = 1 GROUP BY center_code ORDER BY COUNT(*) DESC, center_code DESC',
        'chart' => 
        array (
          'type' => 'BarChart',
          'columns' => 
          array (
            'center_code' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
          ),
        ),
      ),
      'treatments_per_year' => 
      array (
        'sql' => 'SELECT DATE_FORMAT(treatment_start_date,\'%Y\') AS year, SUM(IF(treatment_type=\'PD\',1,0)) AS \'PD\',SUM(IF(treatment_type=\'HD\',1,0)) AS \'HD\',SUM(IF(treatment_type=\'TX\',1,0)) AS \'TX\' FROM treatments_ GROUP BY YEAR(treatment_start_date) ORDER BY YEAR(treatment_start_date);',
        'chart' => 
        array (
          'type' => 'BarChart',
          'columns' => 
          array (
            'year' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
            'TX' => 
            array (
              0 => 'number',
              1 => 'TX',
            ),
          ),
        ),
      ),
      'forms_fill_in' => 
      array (
        'sql' => '',
        'chart' => 
        array (
          'type' => 'BarChart',
          'columns' => 
          array (
            'year' => 
            array (
              0 => 'string',
            ),
            'PD' => 
            array (
              0 => 'number',
              1 => 'PD',
            ),
            'HD' => 
            array (
              0 => 'number',
              1 => 'HD',
            ),
            'TX' => 
            array (
              0 => 'number',
              1 => 'TX',
            ),
          ),
        ),
      ),
    ),
    'misc' => 
    array (
      'treatment_end_cause' => 
      array (
        'death' => 3,
        'technique_change' => 4,
        'center_change' => 6,
        'tx_failure' => 7,
        'other_causes' => 10,
      ),
      'follow_ups' => 
      array (
        'step_months' => 6,
        'tolerance_days' => 30,
      ),
      'catheter_date_tolerance_days_warning' => 30,
      'update_datacheck_after' => 24,
    ),
    'export_formats' => 
    array (
      'dropdown' => 
      array (
        'xlsx' => 'Excel',
        'ods' => 'OpenOffice',
        'csv' => 'csv',
        'html' => 'html',
        'json' => 'json',
        'xls' => 'Excel 2003',
      ),
      'file_extension' => 
      array (
        'xlsx' => 'xlsx',
        'csv' => 'csv',
        'ods' => 'ods',
        'html' => 'html',
        'json' => 'json',
        'xls' => 'xls',
      ),
      'mime_type' => 
      array (
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'csv' => 'type=text/csv',
        'html' => 'type=text/html',
        'json' => 'type=text/json',
        'xls' => 'application/vnd.ms-excel',
      ),
      'php_office_writer' => 
      array (
        'xlsx' => 'Xlsx',
        'ods' => 'Ods',
        'csv' => 'Csv',
        'html' => 'Html',
        'json' => NULL,
        'xls' => 'Xls',
        'pdf' => 'Tcpdf',
      ),
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/homepages/45/d689264821/htdocs/RIDP/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/homepages/45/d689264821/htdocs/RIDP/resources/views',
    ),
    'compiled' => '/homepages/45/d689264821/htdocs/RIDP/storage/framework/views',
  ),
);
