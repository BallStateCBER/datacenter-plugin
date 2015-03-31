<?php
class BackupComponent extends Component {
	public $components = array('Response');
	public $Controller;

	public function initialize(Controller $controller) {
		$this->Controller = $controller;
		parent::initialize($controller);
	}

	public function backup_database($modelName, $tables = '*', $backupName = 'backup', $db_backup_key = null) {
		if (! Configure::check('db_backup_key')) {
			throw new ForbiddenException('This website is not set up for database backups yet.');
		}
		if (! $db_backup_key) {
			throw new ForbiddenException('No security key provided.');
		}
		if ($db_backup_key != Configure::read('db_backup_key')) {
			throw new ForbiddenException('Invalid security key provided.');
		}

		App::uses('Folder', 'Utility');
		$path = APP.DS.'db_backups';
		$folder = new Folder();
		if (! $folder->cd($path)) {
			throw new InternalErrorException('This website\'s database backup directory was not found.');
		}

		// The following was adapted from http://stackoverflow.com/a/20345956/52530
		$return = '';

		$this->{$modelName} = ClassRegistry::init($modelName);
		$dataSource = $this->{$modelName}->getDataSource();
		$databaseName = $dataSource->getSchemaName();


		// Do a short header
		$return .= '-- Database: `' . $databaseName . '`' . "\n";
		$return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";


		if ($tables == '*') {
			$tables = array();
			$result = $this->{$modelName}->query('SHOW TABLES');
			foreach($result as $resultKey => $resultValue){
				$tables[] = current($resultValue['TABLE_NAMES']);
			}
		} else {
			$tables = is_array($tables) ? $tables : explode(',', $tables);
		}

		// Run through all the tables
		foreach ($tables as $table) {
			$tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);

			$return .= 'DROP TABLE IF EXISTS ' . $table . ';';
			$createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
			$createTableEntry = current(current($createTableResult));
			$return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";

			// Output the table data
			foreach($tableData as $tableDataIndex => $tableDataDetails) {

				$return .= 'INSERT INTO ' . $table . ' VALUES(';

				foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

					if(is_null($dataValue)){
						$escapedDataValue = 'NULL';
					}
					else {
						// Convert the encoding
						$escapedDataValue = mb_convert_encoding( $dataValue, "UTF-8", "ISO-8859-1" );

						// Escape any apostrophes using the datasource of the model.
						$escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
					}

					$tableDataDetails[$table][$dataKey] = $escapedDataValue;
				}
				$return .= implode(',', $tableDataDetails[$table]);

				$return .= ");\n";
			}

			$return .= "\n\n\n";
		}

		// Set the default file name
		$fileName = "$databaseName-$backupName-".date('Y-m-d_H-i-s').'.sql';

		// Save the file
		App::uses('File', 'Utility');
		$file = new File($path.DS.$fileName, true);
		$file->write($return);

		// Trigger a download of the file
		$this->Controller->autoRender = false;
		$this->Controller->response->type('Content-Type: text/x-sql');
		$this->Controller->response->download($fileName);
		$this->Controller->response->body($return);
	}
}