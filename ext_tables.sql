CREATE TABLE tx_oelib_domain_model_germanzipcode (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,

    zip_code varchar(5) DEFAULT '' NOT NULL,
    city_name varchar(255) DEFAULT '' NOT NULL,
    longitude decimal(9,6) DEFAULT '0.000000' NOT NULL,
    latitude decimal(9,6) DEFAULT '0.000000' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY zip_code (zip_code)
);

#
# Table structure for table 'tx_oelib_test'
#
CREATE TABLE tx_oelib_test (
    uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    object_type int(11) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    friend int(11) unsigned DEFAULT '0' NOT NULL,
    owner int(11) unsigned DEFAULT '0' NOT NULL,
    children varchar(255) DEFAULT '' NOT NULL,
    related_records int(11) unsigned DEFAULT '0' NOT NULL,
    bidirectional int(11) unsigned DEFAULT '0' NOT NULL,
    composition int(11) unsigned DEFAULT '0' NOT NULL,
    composition2 int(11) unsigned DEFAULT '0' NOT NULL,
    float_data float(9,6) DEFAULT '0.000000' NOT NULL,
    decimal_data decimal(10,3) DEFAULT '0.000' NOT NULL,
    string_data varchar(255) DEFAULT '' NOT NULL,
    header varchar(255) DEFAULT '' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY dummy (is_dummy_record),
    KEY object_type (object_type)
);


#
# Table structure for table 'tx_oelib_testchild'
#
CREATE TABLE tx_oelib_testchild (
    uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    parent int(11) unsigned DEFAULT '0' NOT NULL,
    tx_oelib_parent2 int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY dummy (is_dummy_record)
);


#
# Table structure for table 'tx_oelib_test_article_mm'
#
CREATE TABLE tx_oelib_test_article_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign),
    KEY dummy (is_dummy_record)
);


#
# Table structure for table 'be_users'
#
CREATE TABLE be_users (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'be_groups'
#
CREATE TABLE be_groups (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'fe_groups'
#
CREATE TABLE fe_groups (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'sys_template'
#
CREATE TABLE sys_template (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);

#
# Table structure for table 'sys_file'
#
CREATE TABLE sys_file (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'sys_file_collection'
#
CREATE TABLE sys_file_collection (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'sys_file_reference'
#
CREATE TABLE sys_file_reference (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'sys_category'
#
CREATE TABLE sys_category (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);


#
# Table structure for table 'sys_category_record_mm'
#
CREATE TABLE sys_category_record_mm (
    tx_oelib_is_dummy_record tinyint(1) unsigned DEFAULT '0' NOT NULL,
    KEY dummy (tx_oelib_is_dummy_record)
);