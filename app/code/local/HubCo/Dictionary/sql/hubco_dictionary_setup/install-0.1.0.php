<?php
$this->startSetup();

/**
 * Note: there are many ways in Magento to achieve the same result below
 * of creating a database table. For this tutorial we have gone with the
 * Varien_Db_Ddl_Table method but feel free to explore what Magento do in
 * CE 1.8.0.0 and ealier versions if you are interested.
 */
$table = new Varien_Db_Ddl_Table();

/**
 * This is an alias to the real name of our database table, which is
 * configured in config.xml. By using an alias we can reference the same
 * table throughout our code if we wish and if the table name ever had to
 * change we could simply update a single location, config.xml
 * - smashingmagazine_branddirectory is the model alias
 * - brand is the table reference
 */
$table->setName($this->getTable('hubco_dictionary/abbreviation'));

/**
 * Add the columns we need for now. If you need more in the future you can
 * always create a new setup script as an upgrade, we will introduce that
 * later on in the tutorial.
 */
$table->addColumn(
    'entity_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    10,
    array(
        'auto_increment' => true,
        'unsigned' => true,
        'nullable'=> false,
        'primary' => true
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'updated_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'abbreviation',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'translation',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'suppliers',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'brands',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'categories',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);

/**
 * A couple of important lines that are often missed.
 */
$table->setOption('type', 'InnoDB');
$table->setOption('charset', 'utf8');

/**
 * Create the table!
 */
$this->getConnection()->createTable($table);

$table = new Varien_Db_Ddl_Table();

/**
 * This is an alias to the real name of our database table, which is
 * configured in config.xml. By using an alias we can reference the same
 * table throughout our code if we wish and if the table name ever had to
 * change we could simply update a single location, config.xml
 * - smashingmagazine_branddirectory is the model alias
 * - brand is the table reference
*/
$table->setName($this->getTable('hubco_dictionary/attribute'));

/**
 * Add the columns we need for now. If you need more in the future you can
 * always create a new setup script as an upgrade, we will introduce that
 * later on in the tutorial.
*/
$table->addColumn(
    'entity_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    10,
    array(
        'auto_increment' => true,
        'unsigned' => true,
        'nullable'=> false,
        'primary' => true
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'updated_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'attribute_name',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'attribute_code',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'value',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'translation',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'suppliers',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'brands',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'categories',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);

/**
 * A couple of important lines that are often missed.
*/
$table->setOption('type', 'InnoDB');
$table->setOption('charset', 'utf8');

/**
 * Create the table!
*/
$this->getConnection()->createTable($table);

/**
 * Tables for Make and Model for fitments
 */
$table->setName($this->getTable('hubco_dictionary/make'));

/**
 * Add the columns we need for now. If you need more in the future you can
 * always create a new setup script as an upgrade, we will introduce that
 * later on in the tutorial.
*/
$table->addColumn(
    'entity_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    10,
    array(
        'auto_increment' => true,
        'unsigned' => true,
        'nullable'=> false,
        'primary' => true
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'updated_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'supplier_make',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'translated_make',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'suppliers',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);

/**
 * A couple of important lines that are often missed.
*/
$table->setOption('type', 'InnoDB');
$table->setOption('charset', 'utf8');

/**
 * Create the table!
*/
$this->getConnection()->createTable($table);

$table->setName($this->getTable('hubco_dictionary/model'));

/**
 * Add the columns we need for now. If you need more in the future you can
 * always create a new setup script as an upgrade, we will introduce that
 * later on in the tutorial.
*/
$table->addColumn(
    'entity_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    10,
    array(
        'auto_increment' => true,
        'unsigned' => true,
        'nullable'=> false,
        'primary' => true
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'updated_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'make',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'supplier_model',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'translated_model',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'suppliers',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    null,
    array(
        'nullable' => false,
    )
);

/**
 * A couple of important lines that are often missed.
*/
$table->setOption('type', 'InnoDB');
$table->setOption('charset', 'utf8');

/**
 * Create the table!
*/
$this->getConnection()->createTable($table);

$this->endSetup();
