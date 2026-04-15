-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2026 at 02:36 PM
-- Server version: 5.7.42-0ubuntu0.18.04.1
-- PHP Version: 5.6.40-63+ubuntu18.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staging_pvers`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindMissingNumbersInRange` ()  BEGIN
  DECLARE min_number INT;
  DECLARE max_number INT;
  DECLARE current_number INT;
  
    SET min_number = 1;    SET max_number = 1000;    
    CREATE TEMPORARY TABLE IF NOT EXISTS ExistingNumbers (number INT);
  
    INSERT INTO ExistingNumbers (number)
  SELECT DISTINCT CAST(SUBSTRING_INDEX(order_column, '/', -1) AS SIGNED)
  FROM your_table_name
  WHERE order_column LIKE 'SADR/2022/%';

    CREATE TEMPORARY TABLE IF NOT EXISTS MissingNumbers (number INT);
  
    SET current_number = min_number;
  
    WHILE current_number <= max_number DO
    IF NOT EXISTS (SELECT * FROM ExistingNumbers WHERE number = current_number) THEN
      INSERT INTO MissingNumbers (number) VALUES (current_number);
    END IF;
    
    SET current_number = current_number + 1;
  END WHILE;
  
    SELECT * FROM MissingNumbers;
  
    DROP TEMPORARY TABLE IF EXISTS ExistingNumbers;
  DROP TEMPORARY TABLE IF EXISTS MissingNumbers;
  
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE `acos` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aefis`
--

CREATE TABLE `aefis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `aefi_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_id` int(11) DEFAULT NULL,
  `vigiflow_ref` varchar(55) DEFAULT NULL,
  `vigiflow_date` datetime DEFAULT NULL,
  `vigiflow_message` longtext,
  `webradr_ref` varchar(255) DEFAULT NULL,
  `webradr_date` datetime DEFAULT NULL,
  `webradr_message` varchar(255) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `report_type` varchar(20) DEFAULT NULL,
  `name_of_institution` varchar(100) DEFAULT NULL,
  `institution_code` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `patient_address` varchar(255) DEFAULT NULL,
  `patient_phone` varchar(255) DEFAULT NULL,
  `patient_village` varchar(255) DEFAULT NULL,
  `ip_no` varchar(100) DEFAULT NULL,
  `date_of_birth` varchar(20) DEFAULT NULL,
  `age_months` int(11) DEFAULT NULL,
  `age_years` int(11) DEFAULT NULL,
  `age_weeks` int(11) DEFAULT NULL,
  `age_days` int(11) DEFAULT NULL,
  `age_group` varchar(255) DEFAULT NULL,
  `patient_ward` varchar(100) DEFAULT NULL,
  `patient_county` varchar(255) DEFAULT NULL,
  `patient_sub_county` varchar(255) DEFAULT NULL,
  `vaccination_center` varchar(255) DEFAULT NULL,
  `vaccination_county` varchar(255) DEFAULT NULL,
  `vaccination_type` varchar(255) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `pregnancy_status` varchar(20) DEFAULT NULL,
  `bcg` tinyint(1) DEFAULT NULL,
  `convulsion` tinyint(1) DEFAULT NULL,
  `urticaria` tinyint(1) DEFAULT NULL,
  `high_fever` tinyint(1) DEFAULT NULL,
  `abscess` tinyint(1) DEFAULT NULL,
  `local_reaction` tinyint(1) DEFAULT NULL,
  `anaphylaxis` tinyint(1) DEFAULT NULL,
  `meningitis` tinyint(1) DEFAULT NULL,
  `paralysis` tinyint(1) DEFAULT NULL,
  `toxic_shock` tinyint(1) DEFAULT NULL,
  `complaint_other` tinyint(1) DEFAULT NULL,
  `complaint_other_specify` text,
  `description_of_reaction` text,
  `date_aefi_started` date DEFAULT NULL,
  `time_aefi_started` varchar(10) DEFAULT NULL,
  `aefi_symptoms` text,
  `serious` varchar(10) DEFAULT NULL,
  `serious_yes` varchar(250) DEFAULT NULL,
  `serious_other` text,
  `medical_history` text,
  `treatment_given` text,
  `specimen_collected` varchar(255) DEFAULT NULL,
  `outcome` varchar(100) DEFAULT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `device` tinyint(2) DEFAULT '0',
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `aefi_descriptions`
--

CREATE TABLE `aefi_descriptions` (
  `id` int(11) NOT NULL,
  `aefi_id` int(11) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aefi_list_of_vaccines`
--

CREATE TABLE `aefi_list_of_vaccines` (
  `id` int(11) NOT NULL,
  `aefi_id` int(11) DEFAULT NULL,
  `saefi_id` int(11) DEFAULT NULL,
  `vaccine_id` int(11) DEFAULT NULL,
  `vaccine_name` varchar(200) DEFAULT NULL,
  `vaccination_date` datetime DEFAULT NULL,
  `vaccination_time` varchar(10) DEFAULT NULL,
  `vaccine_manufacturer` varchar(255) DEFAULT NULL,
  `vaccination_route` varchar(255) DEFAULT NULL,
  `vaccination_site` varchar(55) DEFAULT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `icsr_code` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `diluent_batch_number` varchar(55) DEFAULT NULL,
  `diluent_manufacturer` varchar(255) DEFAULT NULL,
  `diluent_expiry_date` date DEFAULT NULL,
  `suspected_drug` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aefi_reactions`
--

CREATE TABLE `aefi_reactions` (
  `id` int(11) NOT NULL,
  `aefi_id` int(11) DEFAULT NULL,
  `reaction_name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aggregates`
--

CREATE TABLE `aggregates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aggregate_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `summary_available` varchar(11) DEFAULT 'No',
  `sub_county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `messageid` varchar(255) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `comment` mediumtext,
  `reporter_email` varchar(255) DEFAULT NULL,
  `e2b_content` longtext,
  `e2b_type` varchar(255) DEFAULT NULL,
  `e2b_file` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `signature` tinyint(1) DEFAULT NULL,
  `submitted` int(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_designation` int(11) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(50) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `resubmit` varchar(15) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Submitted',
  `active` tinyint(2) DEFAULT '1',
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `action_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `inn_name` varchar(255) DEFAULT NULL,
  `mah` varchar(255) DEFAULT NULL,
  `local_technical` varchar(255) DEFAULT NULL,
  `therapeutic_group` varchar(255) DEFAULT NULL,
  `authorised_indications` varchar(255) DEFAULT NULL,
  `form_strength` varchar(255) DEFAULT NULL,
  `interval_code` int(11) DEFAULT NULL,
  `submission_frequency` varchar(255) DEFAULT NULL,
  `reminder_date` datetime DEFAULT NULL,
  `introduction` longtext,
  `worldwide_marketing` longtext,
  `action_taken` longtext,
  `reference_changes` longtext,
  `estimated_exposure` longtext,
  `clinical_findings` longtext,
  `efficacy` longtext,
  `late_breaking` longtext,
  `safety_concerns` longtext,
  `risks_evaluation` longtext,
  `risks_characterisation` longtext,
  `benefit_evaluation` longtext,
  `risk_balance` longtext,
  `recommendation` longtext,
  `conclusion` longtext,
  `report_type` varchar(255) DEFAULT NULL,
  `manager_initiated` tinyint(1) DEFAULT '0',
  `manager_submitted` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `aggregate_list_of_signals`
--

CREATE TABLE `aggregate_list_of_signals` (
  `id` int(11) NOT NULL,
  `aggregate_id` int(11) DEFAULT NULL,
  `aggregate_followup_id` int(11) DEFAULT NULL,
  `signal_term` mediumtext,
  `source_trigger` mediumtext,
  `status` mediumtext,
  `outcome` mediumtext,
  `date_detected` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `reason_summary` mediumtext,
  `evaluation_method` mediumtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

CREATE TABLE `aros` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) NOT NULL,
  `dirname` varchar(255) DEFAULT NULL,
  `basename` varchar(255) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `alternative` varchar(50) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `description` text,
  `year` char(50) DEFAULT NULL,
  `file_date` date DEFAULT NULL,
  `pocket_name` varchar(255) DEFAULT NULL,
  `version_no` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attachments_old`
--

CREATE TABLE `attachments_old` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `sadr_followup_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `aefi_id` int(11) DEFAULT NULL,
  `device_id` int(11) DEFAULT NULL,
  `transfusion_id` int(11) DEFAULT NULL,
  `medication_id` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `description` text,
  `mimetype` varchar(255) DEFAULT NULL,
  `filesize` int(11) UNSIGNED DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `basename` varchar(255) DEFAULT NULL,
  `dirname` varchar(255) DEFAULT NULL,
  `checksum` varchar(255) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alternative` varchar(50) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `authorities`
--

CREATE TABLE `authorities` (
  `id` int(5) NOT NULL,
  `mah_name` varchar(137) DEFAULT NULL,
  `mah_company_name` varchar(153) DEFAULT NULL,
  `mah_company_address` varchar(261) DEFAULT NULL,
  `mah_company_telephone` varchar(168) DEFAULT NULL,
  `mah_company_email` varchar(109) DEFAULT NULL,
  `product` int(5) DEFAULT NULL,
  `master_mah` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auto_drugs`
--

CREATE TABLE `auto_drugs` (
  `id` int(11) NOT NULL,
  `drugName` varchar(255) NOT NULL,
  `drugCode` varchar(255) NOT NULL,
  `isGeneric` tinyint(1) DEFAULT '1',
  `isPreferred` tinyint(1) DEFAULT '0',
  `countryOfSales` longtext,
  `activeIngredients` longtext,
  `atcs` longtext,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ce2bs`
--

CREATE TABLE `ce2bs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ce2b_id` int(11) DEFAULT NULL,
  `vigiflow_ref` varchar(255) DEFAULT NULL,
  `vigiflow_message` varchar(255) DEFAULT NULL,
  `vigiflow_date` varchar(255) DEFAULT NULL,
  `report_type` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `messageid` varchar(255) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `comment` mediumtext,
  `reporter_email` varchar(255) DEFAULT NULL,
  `e2b_content` longtext,
  `e2b_type` varchar(255) DEFAULT NULL,
  `e2b_file` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `signature` tinyint(1) DEFAULT NULL,
  `submitted` int(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_designation` int(11) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(50) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `resubmit` varchar(15) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Submitted',
  `active` tinyint(2) DEFAULT '1',
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `action_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `report_reference` varchar(255) DEFAULT NULL,
  `creation_time` varchar(255) DEFAULT NULL,
  `sender_reference` varchar(255) DEFAULT NULL,
  `receiver_id` varchar(255) DEFAULT NULL,
  `sender_id` varchar(255) DEFAULT NULL,
  `sender_unique_identifier` varchar(255) DEFAULT NULL,
  `worldwide_identifier` varchar(255) DEFAULT NULL,
  `case_narrative` longtext,
  `date_first_received` varchar(255) DEFAULT NULL,
  `date_most_recent_info` varchar(255) DEFAULT NULL,
  `serious` tinyint(1) DEFAULT '0',
  `patient_name` varchar(255) DEFAULT NULL,
  `patient_sex` varchar(255) DEFAULT NULL,
  `patient_dob` varchar(25) DEFAULT NULL,
  `patient_number` varchar(255) DEFAULT NULL,
  `past_medical` longtext,
  `sender_address` varchar(255) DEFAULT NULL,
  `sender_city` varchar(255) DEFAULT NULL,
  `sender_state` varchar(255) DEFAULT NULL,
  `sender_department` varchar(255) DEFAULT NULL,
  `sender_organization` varchar(255) DEFAULT NULL,
  `results_in_death` tinyint(1) DEFAULT '0',
  `life_threatening` tinyint(1) DEFAULT '0',
  `prolonged_hospitalization` tinyint(1) DEFAULT '0',
  `incapacitating` tinyint(1) DEFAULT '0',
  `seriousnesscongenitalanomali` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ce2b_list_of_drugs`
--

CREATE TABLE `ce2b_list_of_drugs` (
  `id` int(11) NOT NULL,
  `ce2b_id` int(11) DEFAULT NULL,
  `ce2b_followup_id` int(11) DEFAULT NULL,
  `dose_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `frequency_id` int(11) DEFAULT NULL,
  `frequency_id_other` varchar(255) DEFAULT NULL,
  `drug_name` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `stop_date` date DEFAULT NULL,
  `indication` varchar(100) DEFAULT NULL,
  `suspected_drug` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ce2b_reaction`
--

CREATE TABLE `ce2b_reaction` (
  `id` int(11) NOT NULL,
  `ce2b_id` int(11) DEFAULT NULL,
  `reaction_name` text,
  `meddra_code` text,
  `start_date` text,
  `source_country` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `criteria_death_null` varchar(255) DEFAULT NULL,
  `criteria_death_value` varchar(255) DEFAULT NULL,
  `life_threatening_null` varchar(255) DEFAULT NULL,
  `life_threatening_value` varchar(255) DEFAULT NULL,
  `prolonged_hospitalisation_null` varchar(255) DEFAULT NULL,
  `prolonged_hospitalisation_value` varchar(255) DEFAULT NULL,
  `incapacitating_null` varchar(255) DEFAULT NULL,
  `incapacitating_value` varchar(255) DEFAULT NULL,
  `birth_defect_null` varchar(255) DEFAULT NULL,
  `birth_defect_value` varchar(255) DEFAULT NULL,
  `other_medical_null` varchar(255) DEFAULT NULL,
  `other_medical_value` varchar(255) DEFAULT NULL,
  `reaction_outcome_null` varchar(255) DEFAULT NULL,
  `reaction_outcome_value` varchar(255) DEFAULT NULL,
  `medical_confirmation_null` varchar(255) DEFAULT NULL,
  `medical_confirmation_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` longtext,
  `review` text,
  `deleted` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `concomittant_drugs`
--

CREATE TABLE `concomittant_drugs` (
  `id` int(11) NOT NULL,
  `sae_id` int(11) DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `indication` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE `counties` (
  `id` int(11) NOT NULL,
  `county_name` varchar(50) DEFAULT NULL,
  `org_unit` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `code` char(2) CHARACTER SET latin1 DEFAULT '',
  `name` tinytext CHARACTER SET latin1,
  `name_fr` tinytext CHARACTER SET latin1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cron_tasks`
--

CREATE TABLE `cron_tasks` (
  `id` int(10) NOT NULL,
  `jobtype` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'task / method',
  `created` datetime NOT NULL,
  `notbefore` datetime DEFAULT NULL,
  `fetched` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `failed` int(3) NOT NULL DEFAULT '0',
  `failure_message` text COLLATE utf8_unicode_ci,
  `workerkey` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interval` int(10) NOT NULL DEFAULT '0' COMMENT 'in minutes',
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `device_id` int(11) DEFAULT NULL,
  `report_type` varchar(20) DEFAULT NULL,
  `report_title` varchar(255) DEFAULT NULL,
  `name_of_institution` varchar(100) DEFAULT NULL,
  `institution_code` varchar(100) DEFAULT NULL,
  `institution_address` varchar(100) DEFAULT NULL,
  `institution_contact` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `patient_address` varchar(255) DEFAULT NULL,
  `patient_phone` varchar(255) DEFAULT NULL,
  `patient_weight` float DEFAULT NULL,
  `patient_height` float DEFAULT NULL,
  `pregnancy_status` varchar(55) DEFAULT NULL,
  `trimester` varchar(75) DEFAULT NULL,
  `ip_no` varchar(100) DEFAULT NULL,
  `date_of_birth` varchar(20) DEFAULT NULL,
  `age_years` varchar(55) DEFAULT NULL,
  `allergy` varchar(100) DEFAULT NULL,
  `allergy_specify` varchar(255) DEFAULT NULL,
  `problem_noted` varchar(25) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `device_model` varchar(255) DEFAULT NULL,
  `catalogue` varchar(255) DEFAULT NULL,
  `manufacturer_name` varchar(255) DEFAULT NULL,
  `manufacturer_address` varchar(255) DEFAULT NULL,
  `manufacture_date` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `operator` varchar(100) DEFAULT NULL,
  `operator_specify` varchar(255) DEFAULT NULL,
  `device_usage` varchar(100) DEFAULT NULL,
  `device_duration_type` varchar(255) DEFAULT NULL,
  `device_duration` int(11) DEFAULT NULL,
  `device_availability` varchar(25) DEFAULT NULL,
  `device_unavailability` varchar(255) DEFAULT NULL,
  `implant_date` date DEFAULT NULL,
  `explant_date` date DEFAULT NULL,
  `implant_duration_type` varchar(255) DEFAULT NULL,
  `implant_duration` int(11) DEFAULT NULL,
  `specimen_type` varchar(25) DEFAULT NULL,
  `patients_involved` int(11) DEFAULT NULL,
  `tests_done` int(11) DEFAULT NULL,
  `false_positives` int(11) DEFAULT NULL,
  `false_negatives` int(11) DEFAULT NULL,
  `true_positives` int(11) DEFAULT NULL,
  `true_negatives` int(11) DEFAULT NULL,
  `date_onset_incident` date DEFAULT NULL,
  `serious` varchar(10) DEFAULT NULL,
  `serious_yes` varchar(250) DEFAULT NULL,
  `death_date` date DEFAULT NULL,
  `description_of_reaction` text,
  `remedial_action` text,
  `outcome` varchar(100) DEFAULT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `deleted` tinyint(4) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `disproportionalities`
--

CREATE TABLE `disproportionalities` (
  `id` int(11) NOT NULL,
  `drug_name` varchar(255) DEFAULT NULL,
  `reaction_name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `doses`
--

CREATE TABLE `doses` (
  `id` int(11) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `icsr_code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE `drugs` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `inn_name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `manufacturer` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `local_trade_rep` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `registration_status` varchar(255) DEFAULT NULL,
  `retention_status` varchar(255) DEFAULT NULL,
  `donation` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug_dictionaries`
--

CREATE TABLE `drug_dictionaries` (
  `id` int(10) NOT NULL,
  `MedId` varchar(35) DEFAULT NULL,
  `drug_record_number` varchar(6) DEFAULT NULL,
  `sequence_no_1` varchar(2) DEFAULT NULL,
  `sequence_no_2` varchar(3) DEFAULT NULL,
  `sequence_no_3` varchar(10) DEFAULT NULL,
  `sequence_no_4` varchar(10) DEFAULT NULL,
  `trade_name` varchar(255) DEFAULT NULL,
  `generic` char(1) DEFAULT NULL,
  `drug_name` varchar(455) DEFAULT NULL,
  `health_program` varchar(255) DEFAULT NULL,
  `name_specifier` varchar(30) DEFAULT NULL,
  `market_authorization_number` varchar(30) DEFAULT NULL,
  `market_authorization_date` varchar(8) DEFAULT NULL,
  `market_authorization_withdrawal_date` varchar(8) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `company` varchar(10) DEFAULT NULL,
  `marketing_authorization_holder` varchar(10) DEFAULT NULL,
  `source_code` varchar(10) DEFAULT NULL,
  `source_country` varchar(10) DEFAULT NULL,
  `source_year` varchar(3) DEFAULT NULL,
  `product_type` varchar(10) DEFAULT NULL,
  `product_group` varchar(10) DEFAULT NULL,
  `create_date` varchar(8) DEFAULT NULL,
  `date_changed` varchar(8) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drug_dictionaries_old`
--

CREATE TABLE `drug_dictionaries_old` (
  `id` mediumint(10) NOT NULL,
  `MedId` varchar(35) CHARACTER SET latin1 DEFAULT NULL,
  `drug_record_number` varchar(6) CHARACTER SET latin1 DEFAULT NULL,
  `sequence_no_1` varchar(2) CHARACTER SET latin1 DEFAULT NULL,
  `sequence_no_2` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `sequence_no_3` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `sequence_no_4` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `generic` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `drug_name` varchar(455) DEFAULT NULL,
  `health_program` varchar(255) DEFAULT NULL,
  `name_specifier` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `market_authorization_number` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `market_authorization_date` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `market_authorization_withdrawal_date` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `country` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `company` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `marketing_authorization_holder` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `source_code` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `source_country` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `source_year` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `product_type` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `product_group` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `create_date` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `date_changed` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- --------------------------------------------------------

--
-- Table structure for table `facility_codes`
--

CREATE TABLE `facility_codes` (
  `id` int(11) NOT NULL,
  `facility_code` varchar(17) DEFAULT NULL,
  `facility_name` varchar(250) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `sub_county` varchar(255) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `sub_location` varchar(250) DEFAULT NULL,
  `description_of_location` tinytext,
  `constituency` varchar(250) DEFAULT NULL,
  `nearest_town` varchar(250) DEFAULT NULL,
  `beds` varchar(50) DEFAULT NULL,
  `cots` varchar(50) DEFAULT NULL,
  `keph_level` varchar(255) DEFAULT NULL,
  `official_landline` varchar(20) DEFAULT NULL,
  `official_fax` varchar(20) DEFAULT NULL,
  `official_mobile` varchar(20) DEFAULT NULL,
  `official_email` varchar(50) DEFAULT NULL,
  `official_address` varchar(50) DEFAULT NULL,
  `official_alternate_number` varchar(20) DEFAULT NULL,
  `town` varchar(100) DEFAULT NULL,
  `post_code` varchar(20) DEFAULT NULL,
  `in_charge` varchar(100) DEFAULT NULL,
  `job_title_of_in_charge` varchar(150) DEFAULT NULL,
  `open_24hrs` varchar(50) DEFAULT NULL,
  `open_weekends` varchar(50) DEFAULT NULL,
  `operational_status` varchar(50) DEFAULT NULL,
  `anc` varchar(10) DEFAULT NULL,
  `art` varchar(10) DEFAULT NULL,
  `beoc` varchar(10) DEFAULT NULL,
  `blood` varchar(10) DEFAULT NULL,
  `caes_sec` varchar(10) DEFAULT NULL,
  `ceoc` varchar(10) DEFAULT NULL,
  `c_imci` varchar(10) DEFAULT NULL,
  `epi` varchar(10) DEFAULT NULL,
  `fp` varchar(10) DEFAULT NULL,
  `growm` varchar(10) DEFAULT NULL,
  `hbc` varchar(10) DEFAULT NULL,
  `hct` varchar(10) DEFAULT NULL,
  `ipd` varchar(10) DEFAULT NULL,
  `opd` varchar(10) DEFAULT NULL,
  `outreach` varchar(10) DEFAULT NULL,
  `pmtct` varchar(10) DEFAULT NULL,
  `rad_xray` varchar(10) DEFAULT NULL,
  `rhtc_rhdc` varchar(10) DEFAULT NULL,
  `tb_diag` varchar(10) DEFAULT NULL,
  `tb_labs` varchar(10) DEFAULT NULL,
  `tb_treat` varchar(10) DEFAULT NULL,
  `Youth` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `email` char(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `feedback` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `frequencies`
--

CREATE TABLE `frequencies` (
  `id` int(11) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `icsr_code` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `help_infos`
--

CREATE TABLE `help_infos` (
  `id` int(11) NOT NULL,
  `field_name` varchar(25) DEFAULT NULL,
  `field_label` varchar(255) DEFAULT NULL,
  `place_holder` varchar(140) DEFAULT NULL,
  `help_type` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `content` text,
  `help_text` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `khis`
--

CREATE TABLE `khis` (
  `id` int(11) NOT NULL,
  `elementId` varchar(255) DEFAULT NULL,
  `elementName` varchar(255) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `list_of_devices`
--

CREATE TABLE `list_of_devices` (
  `id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `manufacture_date` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meddras`
--

CREATE TABLE `meddras` (
  `id` int(8) DEFAULT NULL,
  `llt_name` varchar(100) DEFAULT NULL,
  `pt_code` int(8) DEFAULT NULL,
  `llt_whoart_code` varchar(10) DEFAULT NULL,
  `llt_harts_code` varchar(10) DEFAULT NULL,
  `llt_costart_sym` varchar(10) DEFAULT NULL,
  `llt_icd9_code` varchar(10) DEFAULT NULL,
  `llt_icd9cm_code` varchar(10) DEFAULT NULL,
  `llt_icd10_code` varchar(10) DEFAULT NULL,
  `llt_currency` varchar(1) DEFAULT NULL,
  `llt_jart_code` varchar(10) DEFAULT NULL,
  `COL_12` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `medication_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `adverse_reaction` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `date_of_event` date DEFAULT NULL,
  `time_of_event` varchar(10) DEFAULT NULL,
  `name_of_institution` varchar(100) DEFAULT NULL,
  `institution_code` varchar(100) DEFAULT NULL,
  `institution_contact` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `pregnancy_status` varchar(55) DEFAULT NULL,
  `patient_phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age_years` varchar(70) DEFAULT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `clinic` varchar(255) DEFAULT NULL,
  `pharmacy` varchar(255) DEFAULT NULL,
  `accident` varchar(255) DEFAULT NULL,
  `location_other` varchar(255) DEFAULT NULL,
  `description_of_error` text,
  `process_occur` varchar(255) DEFAULT NULL,
  `process_occur_specify` varchar(255) DEFAULT NULL,
  `reach_patient` varchar(25) DEFAULT NULL,
  `correct_medication` varchar(25) DEFAULT NULL,
  `direct_result` text,
  `outcome` varchar(255) DEFAULT NULL,
  `outcome_error` varchar(255) DEFAULT NULL,
  `outcome_death` varchar(255) DEFAULT NULL,
  `error_cause_inexperience` tinyint(1) DEFAULT NULL,
  `error_cause_knowledge` tinyint(1) DEFAULT NULL,
  `error_cause_distraction` tinyint(1) DEFAULT NULL,
  `error_cause_sound` tinyint(1) DEFAULT NULL,
  `error_cause_medication` tinyint(1) DEFAULT NULL,
  `error_cause_packaging` tinyint(1) DEFAULT NULL,
  `error_cause_workload` tinyint(1) DEFAULT NULL,
  `error_cause_peak` tinyint(1) DEFAULT NULL,
  `error_cause_stock` tinyint(1) DEFAULT NULL,
  `error_cause_procedure` tinyint(1) DEFAULT NULL,
  `error_cause_abbreviations` tinyint(1) DEFAULT NULL,
  `error_cause_illegible` tinyint(1) DEFAULT NULL,
  `error_cause_inaccurate` tinyint(1) DEFAULT NULL,
  `error_cause_labelling` tinyint(1) DEFAULT NULL,
  `error_cause_computer` tinyint(1) DEFAULT NULL,
  `error_cause_other` tinyint(1) DEFAULT NULL,
  `error_cause_specify` text,
  `recommendations` text,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `report_type` varchar(25) DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `deleted` tinyint(4) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `vigiflow_message` text,
  `vigiflow_ref` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `medication_products`
--

CREATE TABLE `medication_products` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) DEFAULT NULL,
  `generic_name_i` varchar(255) DEFAULT NULL,
  `product_name_i` varchar(255) DEFAULT NULL,
  `dosage_form_i` varchar(255) DEFAULT NULL,
  `dosage_i` varchar(255) DEFAULT NULL,
  `manufacturer_i` varchar(255) DEFAULT NULL,
  `strength_i` varchar(255) DEFAULT NULL,
  `container_i` varchar(255) DEFAULT NULL,
  `generic_name_ii` varchar(255) DEFAULT NULL,
  `product_name_ii` varchar(255) DEFAULT NULL,
  `dosage_form_ii` varchar(255) DEFAULT NULL,
  `dosage_ii` varchar(255) DEFAULT NULL,
  `manufacturer_ii` varchar(255) DEFAULT NULL,
  `strength_ii` varchar(255) DEFAULT NULL,
  `container_ii` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modifed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` char(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text,
  `sms` varchar(355) DEFAULT NULL,
  `type` char(30) DEFAULT NULL,
  `style` varchar(250) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages_delete`
--

CREATE TABLE `messages_delete` (
  `id` int(11) NOT NULL,
  `name` char(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text,
  `type` char(30) DEFAULT NULL,
  `style` varchar(250) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` char(70) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `system_message` text,
  `user_message` text,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `padrs`
--

CREATE TABLE `padrs` (
  `id` int(11) NOT NULL,
  `padr_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `relation` varchar(55) DEFAULT NULL,
  `vigiflow_ref` varchar(255) DEFAULT NULL,
  `vigiflow_message` text,
  `vigiflow_date` varchar(255) DEFAULT NULL,
  `report_title` varchar(100) DEFAULT NULL,
  `report_type` varchar(20) DEFAULT NULL,
  `report_sadr` varchar(55) DEFAULT NULL,
  `sadr_vomiting` tinyint(1) DEFAULT NULL,
  `sadr_dizziness` tinyint(1) DEFAULT NULL,
  `sadr_headache` tinyint(1) DEFAULT NULL,
  `sadr_joints` tinyint(1) DEFAULT NULL,
  `sadr_rash` tinyint(1) DEFAULT NULL,
  `sadr_mouth` tinyint(1) DEFAULT NULL,
  `sadr_stomach` tinyint(1) DEFAULT NULL,
  `sadr_urination` tinyint(1) DEFAULT NULL,
  `sadr_eyes` tinyint(1) DEFAULT NULL,
  `sadr_died` tinyint(1) DEFAULT NULL,
  `pqmp_label` tinyint(1) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `pqmp_material` tinyint(1) DEFAULT NULL,
  `date_of_birth` varchar(20) DEFAULT NULL,
  `age_group` varchar(40) DEFAULT NULL,
  `patient_address` varchar(100) DEFAULT NULL,
  `pqmp_color` tinyint(1) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `pqmp_smell` tinyint(1) DEFAULT NULL,
  `pqmp_working` tinyint(1) DEFAULT NULL,
  `pqmp_bottle` tinyint(1) DEFAULT NULL,
  `pregnancy_status` varchar(20) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `diagnosis` text,
  `medical_history` text,
  `date_of_onset_of_reaction` varchar(20) DEFAULT NULL,
  `date_of_end_of_reaction` varchar(25) DEFAULT NULL,
  `description_of_reaction` text,
  `reaction_resolve` varchar(55) DEFAULT NULL,
  `reaction_reappear` varchar(55) DEFAULT NULL,
  `lab_investigation` text,
  `severity` varchar(100) DEFAULT NULL,
  `serious` varchar(255) DEFAULT NULL,
  `serious_reason` varchar(255) DEFAULT NULL,
  `action_taken` varchar(100) DEFAULT NULL,
  `outcome` varchar(100) DEFAULT NULL,
  `causality` varchar(100) DEFAULT NULL,
  `any_other_comment` text,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `emails` tinyint(2) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `device` tinyint(2) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `notified` tinyint(2) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `reaction_on` varchar(25) DEFAULT NULL,
  `consent` varchar(255) DEFAULT NULL,
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `padr_list_of_medicines`
--

CREATE TABLE `padr_list_of_medicines` (
  `id` int(11) NOT NULL,
  `padr_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `medicine_source` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modifed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pints`
--

CREATE TABLE `pints` (
  `id` int(11) NOT NULL,
  `transfusion_id` int(11) DEFAULT NULL,
  `component_type` varchar(255) DEFAULT NULL,
  `pint_no` varchar(55) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `volume_transfused` varchar(55) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pockets`
--

CREATE TABLE `pockets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `required` tinyint(1) DEFAULT '0',
  `item_number` int(5) DEFAULT NULL,
  `type` char(10) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pqmps`
--

CREATE TABLE `pqmps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `facility_name` varchar(100) DEFAULT NULL,
  `facility_code` varchar(100) DEFAULT NULL,
  `facility_address` varchar(100) DEFAULT NULL,
  `facility_phone` varchar(100) DEFAULT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `batch_number` varchar(100) DEFAULT NULL,
  `manufacture_date` varchar(20) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `name_of_manufacturer` varchar(100) DEFAULT NULL,
  `country_of_origin` varchar(100) DEFAULT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `supplier_address` varchar(100) DEFAULT NULL,
  `product_formulation` varchar(70) DEFAULT NULL,
  `product_formulation_specify` varchar(250) DEFAULT NULL,
  `colour_change` tinyint(1) DEFAULT NULL,
  `separating` tinyint(1) DEFAULT NULL,
  `powdering` tinyint(1) DEFAULT NULL,
  `caking` tinyint(1) DEFAULT NULL,
  `moulding` tinyint(1) DEFAULT NULL,
  `odour_change` tinyint(1) DEFAULT NULL,
  `mislabeling` tinyint(1) DEFAULT NULL,
  `incomplete_pack` tinyint(1) DEFAULT NULL,
  `therapeutic_ineffectiveness` tinyint(1) DEFAULT NULL,
  `particulate_matter` tinyint(1) DEFAULT NULL,
  `complaint_other` tinyint(1) DEFAULT NULL,
  `complaint_other_specify` text,
  `complaint_description` text,
  `require_refrigeration` varchar(10) DEFAULT NULL,
  `product_at_facility` varchar(10) DEFAULT NULL,
  `returned_by_client` varchar(10) DEFAULT NULL,
  `stored_to_recommendations` varchar(10) DEFAULT NULL,
  `other_details` text,
  `comments` text,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(255) DEFAULT NULL,
  `contact_number` varchar(100) DEFAULT NULL,
  `adverse_reaction` varchar(255) DEFAULT NULL,
  `reaction_comments` text,
  `medication_error` varchar(255) DEFAULT NULL,
  `emails` tinyint(2) DEFAULT '0',
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `device` tinyint(2) DEFAULT '0',
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `notified` tinyint(2) DEFAULT '0',
  `deleted` tinyint(4) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medicinal_product` tinyint(1) DEFAULT NULL,
  `blood_products` tinyint(1) DEFAULT NULL,
  `herbal_product` tinyint(1) DEFAULT NULL,
  `medical_device` tinyint(1) DEFAULT NULL,
  `cosmeceuticals` tinyint(1) DEFAULT NULL,
  `product_other` tinyint(1) DEFAULT NULL,
  `product_specify` varchar(255) DEFAULT NULL,
  `product_vaccine` tinyint(1) DEFAULT NULL,
  `packaging` tinyint(1) DEFAULT NULL,
  `labelling` tinyint(1) DEFAULT NULL,
  `sampling` tinyint(1) DEFAULT NULL,
  `mechanism` tinyint(1) DEFAULT NULL,
  `electrical` tinyint(1) DEFAULT NULL,
  `device_data` tinyint(1) DEFAULT NULL,
  `software` tinyint(1) DEFAULT NULL,
  `environmental` tinyint(1) DEFAULT NULL,
  `failure_to_calibrate` tinyint(1) DEFAULT NULL,
  `results` tinyint(1) DEFAULT NULL,
  `readings` tinyint(1) DEFAULT NULL,
  `cold_chain` text,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `queued_tasks`
--

CREATE TABLE `queued_tasks` (
  `id` int(10) NOT NULL,
  `jobtype` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `group` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `notbefore` datetime DEFAULT NULL,
  `fetched` datetime DEFAULT NULL,
  `progress` float(3,2) DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `failed` int(3) NOT NULL DEFAULT '0',
  `failure_message` text COLLATE utf8_unicode_ci,
  `workerkey` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` int(4) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `saefi_id` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `comment` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `icsr_code` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sadrs`
--

CREATE TABLE `sadrs` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `medication_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `vigiflow_id` varchar(50) DEFAULT NULL,
  `report_title` varchar(100) DEFAULT NULL,
  `report_type` varchar(20) DEFAULT NULL,
  `report_sadr` tinyint(1) DEFAULT NULL,
  `report_therapeutic` tinyint(1) DEFAULT NULL,
  `report_misuse` tinyint(4) DEFAULT NULL,
  `report_off_label` tinyint(4) DEFAULT NULL,
  `product_category` varchar(255) DEFAULT NULL,
  `medicinal_product` tinyint(1) DEFAULT NULL,
  `blood_products` tinyint(1) DEFAULT NULL,
  `herbal_product` tinyint(1) DEFAULT NULL,
  `cosmeceuticals` tinyint(1) DEFAULT NULL,
  `product_other` tinyint(1) DEFAULT NULL,
  `product_specify` varchar(255) DEFAULT NULL,
  `name_of_institution` varchar(100) DEFAULT NULL,
  `institution_code` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `ip_no` varchar(100) DEFAULT NULL,
  `date_of_birth` varchar(20) DEFAULT NULL,
  `age_group` varchar(40) DEFAULT NULL,
  `patient_address` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `known_allergy` varchar(3) DEFAULT NULL,
  `known_allergy_specify` varchar(100) DEFAULT NULL,
  `pregnant` varchar(10) DEFAULT NULL,
  `pregnancy_status` varchar(20) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `diagnosis` text,
  `reaction` varchar(500) DEFAULT NULL,
  `medical_history` text,
  `date_of_onset_of_reaction` varchar(20) DEFAULT NULL,
  `description_of_reaction` text,
  `reaction_resolve` varchar(55) DEFAULT NULL,
  `reaction_reappear` varchar(55) DEFAULT NULL,
  `lab_investigation` text,
  `severity` varchar(100) DEFAULT NULL,
  `serious` varchar(255) DEFAULT NULL,
  `serious_reason` varchar(255) DEFAULT NULL,
  `action_taken` varchar(100) DEFAULT NULL,
  `outcome` varchar(100) DEFAULT NULL,
  `causality` varchar(100) DEFAULT NULL,
  `any_other_comment` text,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `emails` tinyint(2) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `device` tinyint(2) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `archived` tinyint(1) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `notified` tinyint(2) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `vigiflow_message` text,
  `vigiflow_ref` varchar(255) DEFAULT NULL,
  `vigiflow_date` datetime DEFAULT NULL,
  `webradr_ref` varchar(255) DEFAULT NULL,
  `webradr_date` datetime DEFAULT NULL,
  `submitted_date` datetime DEFAULT NULL,
  `webradr_message` varchar(255) DEFAULT NULL,
  `copied` tinyint(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sadr_descriptions`
--

CREATE TABLE `sadr_descriptions` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sadr_followups`
--

CREATE TABLE `sadr_followups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `description_of_reaction` text,
  `outcome` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `emails` tinyint(2) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `device` tinyint(2) DEFAULT '0',
  `notified` tinyint(2) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `sadr_list_of_drugs`
--

CREATE TABLE `sadr_list_of_drugs` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `sadr_followup_id` int(11) DEFAULT NULL,
  `dose_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `frequency_id` int(11) DEFAULT NULL,
  `frequency_id_other` varchar(255) DEFAULT NULL,
  `drug_name` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `stop_date` date DEFAULT NULL,
  `indication` varchar(100) DEFAULT NULL,
  `suspected_drug` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sadr_list_of_medicines`
--

CREATE TABLE `sadr_list_of_medicines` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `sadr_followup_id` int(11) DEFAULT NULL,
  `dose_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `frequency_id` int(11) DEFAULT NULL,
  `frequency_other` varchar(255) DEFAULT NULL,
  `drug_name` varchar(100) DEFAULT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `stop_date` date DEFAULT NULL,
  `indication` varchar(100) DEFAULT NULL,
  `suspected_drug` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sadr_reaction`
--

CREATE TABLE `sadr_reaction` (
  `id` int(11) NOT NULL,
  `sadr_id` int(11) DEFAULT NULL,
  `reaction` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saefis`
--

CREATE TABLE `saefis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `saefi_id` int(11) DEFAULT NULL,
  `initial_id` int(11) DEFAULT NULL,
  `messageid` varchar(55) DEFAULT NULL,
  `vigiflow_ref` varchar(255) DEFAULT NULL,
  `vigiflow_date` varchar(255) DEFAULT NULL,
  `webradr_ref` varchar(255) DEFAULT NULL,
  `webradr_date` varchar(255) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `aefi_report_ref` varchar(255) DEFAULT NULL,
  `name_of_vaccination_site` varchar(255) DEFAULT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `place_vaccination` varchar(101) DEFAULT NULL,
  `place_vaccination_other` varchar(250) DEFAULT NULL,
  `site_type` varchar(100) DEFAULT NULL,
  `site_type_other` varchar(100) DEFAULT NULL,
  `vaccination_in` varchar(100) DEFAULT NULL,
  `vaccination_in_other` varchar(255) DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `complete_date` date DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `telephone` varchar(40) DEFAULT NULL,
  `mobile` varchar(40) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `patient_name` varchar(200) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `patient_address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age_at_onset_years` int(11) DEFAULT NULL,
  `age_at_onset_months` int(11) DEFAULT NULL,
  `age_at_onset_days` int(11) DEFAULT NULL,
  `age_group` varchar(255) DEFAULT NULL,
  `symptom_date` datetime DEFAULT NULL,
  `hospitalization_date` date DEFAULT NULL,
  `status_on_date` varchar(255) DEFAULT NULL,
  `died_date` datetime DEFAULT NULL,
  `autopsy_done` varchar(40) DEFAULT NULL,
  `autopsy_done_date` date DEFAULT NULL,
  `autopsy_planned` varchar(55) DEFAULT NULL,
  `autopsy_planned_date` datetime DEFAULT NULL,
  `past_history` varchar(55) DEFAULT NULL,
  `past_history_remarks` text,
  `adverse_event` varchar(100) DEFAULT NULL,
  `adverse_event_remarks` text,
  `allergy_history` varchar(100) DEFAULT NULL,
  `allergy_history_remarks` text,
  `comorbidity_disorder` varchar(255) DEFAULT NULL,
  `comorbidity_disorder_remarks` text,
  `covid_positive` varchar(255) DEFAULT NULL,
  `covid_positive_remarks` text,
  `existing_illness` varchar(100) DEFAULT NULL,
  `existing_illness_remarks` text,
  `hospitalization_history` varchar(100) DEFAULT NULL,
  `hospitalization_history_remarks` text,
  `medication_vaccination` varchar(100) DEFAULT NULL,
  `medication_vaccination_remarks` text,
  `faith_healers` varchar(100) DEFAULT NULL,
  `faith_healers_remarks` text,
  `family_history` varchar(100) DEFAULT NULL,
  `family_history_remarks` text,
  `pregnant` varchar(100) DEFAULT NULL,
  `pregnant_weeks` varchar(50) DEFAULT NULL,
  `breastfeeding` varchar(100) DEFAULT NULL,
  `infant` varchar(100) DEFAULT NULL,
  `birth_weight` int(10) DEFAULT NULL,
  `delivery_procedure` varchar(205) DEFAULT NULL,
  `delivery_procedure_specify` text,
  `source_examination` tinyint(1) DEFAULT NULL,
  `source_documents` tinyint(1) DEFAULT NULL,
  `source_verbal` tinyint(1) DEFAULT NULL,
  `verbal_source` text,
  `source_other` tinyint(1) DEFAULT NULL,
  `source_other_specify` text,
  `examiner_name` varchar(205) DEFAULT NULL,
  `other_sources` text,
  `signs_symptoms` text,
  `person_details` varchar(255) DEFAULT NULL,
  `person_designation` varchar(205) DEFAULT NULL,
  `person_date` datetime DEFAULT NULL,
  `medical_care` text,
  `not_medical_care` text,
  `final_diagnosis` text,
  `when_vaccinated` varchar(255) DEFAULT NULL,
  `vaccine_given` varchar(255) DEFAULT NULL,
  `when_vaccinated_specify` text,
  `prescribing_error` varchar(255) DEFAULT NULL,
  `prescribing_error_specify` text,
  `vaccine_unsterile` varchar(255) DEFAULT NULL,
  `vaccine_unsterile_specify` text,
  `vaccine_condition` varchar(255) DEFAULT NULL,
  `vaccine_condition_specify` text,
  `vaccine_reconstitution` varchar(255) DEFAULT NULL,
  `vaccine_reconstitution_specify` text,
  `vaccine_handling` varchar(255) DEFAULT NULL,
  `vaccine_handling_specify` text,
  `vaccine_administered` varchar(255) DEFAULT NULL,
  `vaccine_administered_specify` text,
  `vaccinated_vial` int(11) DEFAULT NULL,
  `vaccinated_session` int(11) DEFAULT NULL,
  `vaccinated_locations` int(11) DEFAULT NULL,
  `vaccinated_locations_specify` text,
  `vaccinated_cluster` varchar(255) DEFAULT NULL,
  `vaccinated_cluster_number` int(11) DEFAULT NULL,
  `vaccinated_cluster_vial` varchar(255) DEFAULT NULL,
  `vaccinated_cluster_vial_number` int(11) DEFAULT NULL,
  `syringes_used` varchar(255) DEFAULT NULL,
  `syringes_used_specify` varchar(255) DEFAULT NULL,
  `syringes_used_other` varchar(255) DEFAULT NULL,
  `syringes_used_findings` text,
  `injection_dose_route` varchar(255) DEFAULT NULL,
  `injection_time_mentioned` varchar(255) DEFAULT NULL,
  `injection_no_touch` varchar(255) DEFAULT NULL,
  `injection_contraindications` varchar(255) DEFAULT NULL,
  `injection_reported` varchar(255) DEFAULT NULL,
  `injection_vaccines` varchar(255) DEFAULT NULL,
  `injection_vaccines_yes` varchar(255) DEFAULT NULL,
  `injection_observations` text,
  `reconstitution_multiple` varchar(55) DEFAULT NULL,
  `reconstitution_different` varchar(55) DEFAULT NULL,
  `reconstitution_vial` varchar(55) DEFAULT NULL,
  `reconstitution_syringe` varchar(55) DEFAULT NULL,
  `reconstitution_vaccines` varchar(55) DEFAULT NULL,
  `reconstitution_observations` text,
  `cold_temperature` varchar(55) DEFAULT NULL,
  `cold_temperature_deviation` varchar(55) DEFAULT NULL,
  `cold_temperature_specify` text,
  `procedure_followed` varchar(55) DEFAULT NULL,
  `other_items` varchar(55) DEFAULT NULL,
  `partial_vaccines` varchar(55) DEFAULT NULL,
  `unusable_vaccines` varchar(55) DEFAULT NULL,
  `unusable_diluents` varchar(55) DEFAULT NULL,
  `additional_observations` text,
  `cold_transportation` varchar(55) DEFAULT NULL,
  `vaccine_carrier` varchar(55) DEFAULT NULL,
  `coolant_packs` varchar(55) DEFAULT NULL,
  `transport_findings` text,
  `similar_events` varchar(55) DEFAULT NULL,
  `similar_events_describe` text,
  `similar_events_episodes` int(11) DEFAULT NULL,
  `affected_vaccinated` int(11) DEFAULT NULL,
  `affected_not_vaccinated` int(11) DEFAULT NULL,
  `affected_unknown` varchar(255) DEFAULT NULL,
  `community_comments` text,
  `relevant_findings` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `action_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted` int(2) NOT NULL DEFAULT '0',
  `resubmit` varchar(15) DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `report_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'UnSubmitted',
  `active` tinyint(2) DEFAULT '1',
  `emails` tinyint(2) DEFAULT '0',
  `signature` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `copied` tinyint(4) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) CHARACTER SET swe7 COLLATE swe7_bin DEFAULT NULL,
  `reporter_name_diff` varchar(100) DEFAULT NULL,
  `reporter_designation_diff` varchar(100) DEFAULT NULL,
  `reporter_email_diff` varchar(100) DEFAULT NULL,
  `reporter_phone_diff` varchar(100) DEFAULT NULL,
  `reporter_date_diff` varchar(100) DEFAULT NULL,
  `patient_phone` varchar(255) DEFAULT NULL,
  `patient_street_name` varchar(255) DEFAULT NULL,
  `syringes_used_other_specify` varchar(255) DEFAULT NULL,
  `vaccinator_training` varchar(255) DEFAULT NULL,
  `description_of_reaction` varchar(255) DEFAULT NULL,
  `name_of_person_first_treated` varchar(255) DEFAULT NULL,
  `name_of_the_person_treating` varchar(255) DEFAULT NULL,
  `other_source_of_info` varchar(255) DEFAULT NULL,
  `patient_house_number` varchar(255) DEFAULT NULL,
  `time_of_first_symptom` varchar(255) DEFAULT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `date_first_reported` varchar(255) DEFAULT NULL,
  `date_form_filled` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `saes`
--

CREATE TABLE `saes` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `sae_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `form_type` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `patient_initials` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age_years` int(11) DEFAULT NULL,
  `gender` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `causality` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `administration_date` date DEFAULT NULL,
  `latest_date` date DEFAULT NULL,
  `reaction_onset` date DEFAULT NULL,
  `reaction_end_date` date DEFAULT NULL,
  `patient_died` tinyint(1) DEFAULT '0',
  `prolonged_hospitalization` tinyint(1) DEFAULT '0',
  `incapacity` tinyint(1) DEFAULT '0',
  `life_threatening` tinyint(1) DEFAULT '0',
  `reaction_other` tinyint(1) DEFAULT '0',
  `reaction_description` text,
  `relevant_history` text,
  `manufacturer_name` varchar(255) DEFAULT NULL,
  `mfr_no` varchar(255) DEFAULT NULL,
  `manufacturer_date` date DEFAULT NULL,
  `source_study` tinyint(1) DEFAULT '0',
  `source_literature` tinyint(1) DEFAULT '0',
  `source_health_professional` tinyint(1) DEFAULT '0',
  `report_date` date DEFAULT NULL,
  `submitted_date` datetime DEFAULT NULL,
  `report_type` varchar(30) CHARACTER SET latin1 DEFAULT 'Initial',
  `approved` tinyint(2) DEFAULT '0',
  `approved_by` int(11) DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET latin1 NOT NULL,
  `reporter_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `reporter_phone` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `reporter_email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_counties`
--

CREATE TABLE `sub_counties` (
  `id` int(11) NOT NULL,
  `county_id` int(11) DEFAULT NULL,
  `sub_county_name` varchar(50) DEFAULT NULL,
  `county_name` varchar(50) DEFAULT NULL,
  `Province` varchar(50) DEFAULT NULL,
  `Pop_2009` varchar(50) DEFAULT NULL,
  `RegVoters` varchar(50) DEFAULT NULL,
  `AreaSqKms` varchar(50) DEFAULT NULL,
  `CAWards` varchar(50) DEFAULT NULL,
  `MainEthnicGroup` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suspected_drugs`
--

CREATE TABLE `suspected_drugs` (
  `id` int(11) NOT NULL,
  `sae_id` int(11) DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `indication` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `therapy_duration` varchar(255) DEFAULT NULL,
  `reaction_abate` varchar(100) DEFAULT NULL,
  `reaction_reappear` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_drugs`
--

CREATE TABLE `tmp_drugs` (
  `ectd_product_id` int(5) DEFAULT NULL,
  `product_trade_name` varchar(132) DEFAULT NULL,
  `product_registration_no` varchar(49) DEFAULT NULL,
  `local_foreign` varchar(7) DEFAULT NULL,
  `inn_of_api` varchar(286) DEFAULT NULL,
  `api_strength_per_dosage` varchar(271) DEFAULT NULL,
  `AL` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transfusions`
--

CREATE TABLE `transfusions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pqmp_id` int(11) DEFAULT NULL,
  `transfusion_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `form_type` varchar(25) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `patient_address` varchar(255) DEFAULT NULL,
  `patient_phone` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(20) DEFAULT NULL,
  `age_years` varchar(70) DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `pre_hb` varchar(25) DEFAULT NULL,
  `transfusion_reason` varchar(255) DEFAULT NULL,
  `current_medications` text,
  `obstetric_history` varchar(255) DEFAULT NULL,
  `previous_transfusion` varchar(255) DEFAULT NULL,
  `transfusion_comment` text,
  `previous_reactions` varchar(255) DEFAULT NULL,
  `reaction_comment` text,
  `reaction_general` varchar(255) DEFAULT NULL,
  `reaction_fever` varchar(55) DEFAULT NULL,
  `reaction_chills` varchar(55) DEFAULT NULL,
  `reaction_flushing` varchar(55) DEFAULT NULL,
  `reaction_vomiting` varchar(55) DEFAULT NULL,
  `reaction_dermatological` varchar(255) DEFAULT NULL,
  `reaction_cardiac` varchar(255) DEFAULT NULL,
  `reaction_chest` varchar(55) DEFAULT NULL,
  `reaction_dyspnoea` varchar(55) DEFAULT NULL,
  `reaction_hypotension` varchar(55) DEFAULT NULL,
  `reaction_tachycardia` varchar(55) DEFAULT NULL,
  `reaction_renal` varchar(255) DEFAULT NULL,
  `reaction_anuria` varchar(55) DEFAULT NULL,
  `reaction_oliguria` varchar(55) DEFAULT NULL,
  `reaction_dark` varchar(55) DEFAULT NULL,
  `reaction_haematological` varchar(255) DEFAULT NULL,
  `reaction_other` text,
  `vital_start_bp` varchar(55) DEFAULT NULL,
  `vital_start_t` varchar(55) DEFAULT NULL,
  `vital_start_p` varchar(55) DEFAULT NULL,
  `vital_start_r` varchar(55) DEFAULT NULL,
  `vital_during_bp` varchar(55) DEFAULT NULL,
  `vital_during_t` varchar(55) DEFAULT NULL,
  `vital_during_p` varchar(55) DEFAULT NULL,
  `vital_during_r` varchar(55) DEFAULT NULL,
  `vital_stop_bp` varchar(55) DEFAULT NULL,
  `vital_stop_t` varchar(55) DEFAULT NULL,
  `vital_stop_p` varchar(55) DEFAULT NULL,
  `vital_stop_r` varchar(55) DEFAULT NULL,
  `nurse_name` varchar(255) DEFAULT NULL,
  `lab_hemolysis` varchar(100) DEFAULT NULL,
  `lab_hemolysis_present` varchar(55) DEFAULT NULL,
  `recipient_blood` varchar(25) DEFAULT NULL,
  `hae_wbc` varchar(255) DEFAULT NULL,
  `hae_hb` varchar(255) DEFAULT NULL,
  `hae_rbc` varchar(255) DEFAULT NULL,
  `hae_hct` varchar(255) DEFAULT NULL,
  `hae_mcv` varchar(255) DEFAULT NULL,
  `hae_mch` varchar(255) DEFAULT NULL,
  `hae_mchc` varchar(255) DEFAULT NULL,
  `hae_plt` varchar(255) DEFAULT NULL,
  `film_rbc` varchar(255) DEFAULT NULL,
  `film_wbc` varchar(255) DEFAULT NULL,
  `film_plt` varchar(255) DEFAULT NULL,
  `donor_hemolysis` varchar(255) DEFAULT NULL,
  `donor_age` varchar(55) DEFAULT NULL,
  `culture_donor_pack` text,
  `culture_recipient_blood` text,
  `compatible_saline_rt` varchar(55) DEFAULT NULL,
  `compatible_saline_ii` varchar(55) DEFAULT NULL,
  `compatible_ahg` varchar(55) DEFAULT NULL,
  `compatible_albumin` varchar(55) DEFAULT NULL,
  `negative_result` text,
  `anti_a` varchar(255) DEFAULT NULL,
  `anti_b` varchar(255) DEFAULT NULL,
  `urinalysis` varchar(255) DEFAULT NULL,
  `evaluation` varchar(255) DEFAULT NULL,
  `adverse_reaction` varchar(255) DEFAULT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_email` varchar(100) DEFAULT NULL,
  `reporter_phone` varchar(100) DEFAULT NULL,
  `report_type` varchar(55) DEFAULT NULL,
  `submitted` tinyint(2) DEFAULT '0',
  `submitted_date` datetime DEFAULT NULL,
  `copied` tinyint(2) DEFAULT '0',
  `archived` tinyint(4) DEFAULT '0',
  `archived_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `reporter_date` date DEFAULT NULL,
  `person_submitting` varchar(55) DEFAULT NULL,
  `reporter_name_diff` varchar(255) DEFAULT NULL,
  `reporter_designation_diff` int(11) DEFAULT NULL,
  `reporter_email_diff` varchar(255) DEFAULT NULL,
  `reporter_phone_diff` varchar(255) DEFAULT NULL,
  `reporter_date_diff` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `clinic_venue` varchar(155) DEFAULT NULL,
  `specimens_post_transfusion` tinyint(4) DEFAULT NULL,
  `specimens_edta_blood` tinyint(4) DEFAULT NULL,
  `specimens_void_urine` tinyint(4) DEFAULT NULL,
  `specimens_blood_reacted` tinyint(4) DEFAULT NULL,
  `specimens_blood_bags` tinyint(4) DEFAULT NULL,
  `clinic_code` varchar(55) DEFAULT NULL,
  `donor_number` varchar(55) DEFAULT NULL,
  `national_number` varchar(55) DEFAULT NULL,
  `home_phone` varchar(55) DEFAULT NULL,
  `cell_phone` varchar(55) DEFAULT NULL,
  `email_address` varchar(55) DEFAULT NULL,
  `residence` varchar(55) DEFAULT NULL,
  `nurse_date` date DEFAULT NULL,
  `low_volume` varchar(75) DEFAULT NULL,
  `venepuncture` varchar(85) DEFAULT NULL,
  `hematoma` varchar(85) DEFAULT NULL,
  `faint` varchar(55) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(140) NOT NULL,
  `confirm_password` char(140) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` char(50) NOT NULL DEFAULT '',
  `group_id` int(11) NOT NULL,
  `name_of_institution` varchar(100) DEFAULT NULL,
  `institution_address` varchar(100) DEFAULT NULL,
  `institution_code` varchar(100) DEFAULT NULL,
  `institution_email` varchar(255) DEFAULT NULL,
  `institution_contact` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `phone_no` varchar(100) DEFAULT NULL,
  `forgot_password` tinyint(2) DEFAULT '0',
  `initial_email` tinyint(2) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '0',
  `is_admin` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_type` varchar(55) DEFAULT NULL,
  `sponsor_email` varchar(55) DEFAULT NULL,
  `health_program` varchar(105) DEFAULT NULL,
  `active_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `id` int(11) NOT NULL,
  `vaccine_name` varchar(55) DEFAULT NULL,
  `description` text,
  `health_program` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `who_drugs`
--

CREATE TABLE `who_drugs` (
  `id` int(10) NOT NULL,
  `MedId` varchar(35) DEFAULT NULL,
  `drug_record_number` varchar(255) DEFAULT NULL,
  `sequence_no_1` varchar(2) DEFAULT NULL,
  `sequence_no_2` varchar(3) DEFAULT NULL,
  `sequence_no_3` varchar(10) DEFAULT NULL,
  `sequence_no_4` varchar(10) DEFAULT NULL,
  `generic` char(1) DEFAULT NULL,
  `drug_name` longtext,
  `name_specifier` varchar(30) DEFAULT NULL,
  `market_authorization_number` varchar(30) DEFAULT NULL,
  `market_authorization_date` varchar(8) DEFAULT NULL,
  `market_authorization_withdrawal_date` varchar(8) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `company` varchar(10) DEFAULT NULL,
  `marketing_authorization_holder` varchar(10) DEFAULT NULL,
  `source_code` varchar(10) DEFAULT NULL,
  `source_country` varchar(10) DEFAULT NULL,
  `source_year` varchar(3) DEFAULT NULL,
  `product_type` varchar(10) DEFAULT NULL,
  `product_group` varchar(10) DEFAULT NULL,
  `create_date` varchar(8) DEFAULT NULL,
  `date_changed` varchar(8) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acos`
--
ALTER TABLE `acos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aefis`
--
ALTER TABLE `aefis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aefi_descriptions`
--
ALTER TABLE `aefi_descriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aefi_list_of_vaccines`
--
ALTER TABLE `aefi_list_of_vaccines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aefi_reactions`
--
ALTER TABLE `aefi_reactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aggregates`
--
ALTER TABLE `aggregates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aggregate_list_of_signals`
--
ALTER TABLE `aggregate_list_of_signals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aros`
--
ALTER TABLE `aros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aros_acos`
--
ALTER TABLE `aros_acos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments_old`
--
ALTER TABLE `attachments_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authorities`
--
ALTER TABLE `authorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_drugs`
--
ALTER TABLE `auto_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ce2bs`
--
ALTER TABLE `ce2bs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ce2b_list_of_drugs`
--
ALTER TABLE `ce2b_list_of_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ce2b_reaction`
--
ALTER TABLE `ce2b_reaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `concomittant_drugs`
--
ALTER TABLE `concomittant_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_tasks`
--
ALTER TABLE `cron_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disproportionalities`
--
ALTER TABLE `disproportionalities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doses`
--
ALTER TABLE `doses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_dictionaries`
--
ALTER TABLE `drug_dictionaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_dictionaries_old`
--
ALTER TABLE `drug_dictionaries_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_codes`
--
ALTER TABLE `facility_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frequencies`
--
ALTER TABLE `frequencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_infos`
--
ALTER TABLE `help_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khis`
--
ALTER TABLE `khis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_of_devices`
--
ALTER TABLE `list_of_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meddras`
--
ALTER TABLE `meddras`
  ADD KEY `llt_name` (`llt_name`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medication_products`
--
ALTER TABLE `medication_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages_delete`
--
ALTER TABLE `messages_delete`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `padrs`
--
ALTER TABLE `padrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `padr_list_of_medicines`
--
ALTER TABLE `padr_list_of_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pints`
--
ALTER TABLE `pints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pockets`
--
ALTER TABLE `pockets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pqmps`
--
ALTER TABLE `pqmps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queued_tasks`
--
ALTER TABLE `queued_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `priority` (`priority`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadrs`
--
ALTER TABLE `sadrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadr_descriptions`
--
ALTER TABLE `sadr_descriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadr_followups`
--
ALTER TABLE `sadr_followups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadr_list_of_drugs`
--
ALTER TABLE `sadr_list_of_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadr_list_of_medicines`
--
ALTER TABLE `sadr_list_of_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadr_reaction`
--
ALTER TABLE `sadr_reaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saefis`
--
ALTER TABLE `saefis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saes`
--
ALTER TABLE `saes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_counties`
--
ALTER TABLE `sub_counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suspected_drugs`
--
ALTER TABLE `suspected_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfusions`
--
ALTER TABLE `transfusions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `who_drugs`
--
ALTER TABLE `who_drugs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acos`
--
ALTER TABLE `acos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aefis`
--
ALTER TABLE `aefis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aefi_descriptions`
--
ALTER TABLE `aefi_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aefi_list_of_vaccines`
--
ALTER TABLE `aefi_list_of_vaccines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aefi_reactions`
--
ALTER TABLE `aefi_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aggregates`
--
ALTER TABLE `aggregates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aggregate_list_of_signals`
--
ALTER TABLE `aggregate_list_of_signals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aros`
--
ALTER TABLE `aros`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aros_acos`
--
ALTER TABLE `aros_acos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attachments_old`
--
ALTER TABLE `attachments_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `authorities`
--
ALTER TABLE `authorities`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auto_drugs`
--
ALTER TABLE `auto_drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ce2bs`
--
ALTER TABLE `ce2bs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ce2b_list_of_drugs`
--
ALTER TABLE `ce2b_list_of_drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ce2b_reaction`
--
ALTER TABLE `ce2b_reaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `concomittant_drugs`
--
ALTER TABLE `concomittant_drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_tasks`
--
ALTER TABLE `cron_tasks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disproportionalities`
--
ALTER TABLE `disproportionalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `doses`
--
ALTER TABLE `doses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drugs`
--
ALTER TABLE `drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drug_dictionaries`
--
ALTER TABLE `drug_dictionaries`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drug_dictionaries_old`
--
ALTER TABLE `drug_dictionaries_old`
  MODIFY `id` mediumint(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `facility_codes`
--
ALTER TABLE `facility_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `frequencies`
--
ALTER TABLE `frequencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help_infos`
--
ALTER TABLE `help_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `khis`
--
ALTER TABLE `khis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `list_of_devices`
--
ALTER TABLE `list_of_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medication_products`
--
ALTER TABLE `medication_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages_delete`
--
ALTER TABLE `messages_delete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `padrs`
--
ALTER TABLE `padrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `padr_list_of_medicines`
--
ALTER TABLE `padr_list_of_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pints`
--
ALTER TABLE `pints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pockets`
--
ALTER TABLE `pockets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pqmps`
--
ALTER TABLE `pqmps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queued_tasks`
--
ALTER TABLE `queued_tasks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadrs`
--
ALTER TABLE `sadrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadr_descriptions`
--
ALTER TABLE `sadr_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadr_followups`
--
ALTER TABLE `sadr_followups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadr_list_of_drugs`
--
ALTER TABLE `sadr_list_of_drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadr_list_of_medicines`
--
ALTER TABLE `sadr_list_of_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sadr_reaction`
--
ALTER TABLE `sadr_reaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `saefis`
--
ALTER TABLE `saefis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `saes`
--
ALTER TABLE `saes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sub_counties`
--
ALTER TABLE `sub_counties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suspected_drugs`
--
ALTER TABLE `suspected_drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfusions`
--
ALTER TABLE `transfusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `who_drugs`
--
ALTER TABLE `who_drugs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
