-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 09:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hello`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `EXPIRY` ()  NO SQL BEGIN
SELECT p_id,sup_id,med_id,p_qty,p_cost,pur_date,mfg_date,exp_date FROM purchase where exp_date between CURDATE() and DATE_SUB(CURDATE(), INTERVAL -6 MONTH);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SEARCH_INVENTORY` (IN `search` VARCHAR(255))  NO SQL BEGIN
DECLARE mid DECIMAL(6);
DECLARE mname VARCHAR(50);
DECLARE mqty INT;
DECLARE mcategory VARCHAR(20);
DECLARE mprice DECIMAL(6,2);
DECLARE location VARCHAR(30);
DECLARE exit_loop BOOLEAN DEFAULT FALSE;
DECLARE MED_CURSOR CURSOR FOR SELECT MED_ID,MED_NAME,MED_QTY,CATEGORY,MED_PRICE,LOCATION_RACK FROM MEDS;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop=TRUE;
CREATE TEMPORARY TABLE IF NOT EXISTS T1 (medid decimal(6),medname varchar(50),medqty int,medcategory varchar(20),medprice decimal(6,2),medlocation varchar(30));
OPEN MED_CURSOR;
med_loop: LOOP
FETCH FROM MED_CURSOR INTO mid,mname,mqty,mcategory,mprice,location;
IF exit_loop THEN
LEAVE med_loop;
END IF;

IF(CONCAT(mid,mname,mcategory,location) LIKE CONCAT('%',search,'%')) THEN
INSERT INTO T1(medid,medname,medqty,medcategory,medprice,medlocation)
VALUES(mid,mname,mqty,mcategory,mprice,location);
END IF;
END LOOP med_loop;
CLOSE MED_CURSOR;
SELECT medid,medname,medqty,medcategory,medprice,medlocation FROM T1; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `STOCK` ()  NO SQL BEGIN
SELECT med_id, med_name,med_qty,category,med_price,location_rack FROM meds where med_qty<=50;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TOTAL_AMT` (IN `ID` INT, OUT `AMT` DECIMAL(8,2))  NO SQL BEGIN
UPDATE SALES SET S_DATE=SYSDATE(),S_TIME=CURRENT_TIMESTAMP(),TOTAL_AMT=(SELECT SUM(TOT_PRICE) FROM SALES_ITEMS WHERE SALES_ITEMS.SALE_ID=ID) WHERE SALES.SALE_ID=ID;
SELECT TOTAL_AMT INTO AMT FROM SALES WHERE SALE_ID=ID;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `P_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) DETERMINISTIC NO SQL BEGIN
DECLARE PAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(P_COST) INTO PAMT FROM PURCHASE WHERE PUR_DATE >= start AND PUR_DATE<= end;
RETURN PAMT;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `S_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) NO SQL BEGIN
DECLARE SAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(TOTAL_AMT) INTO SAMT FROM SALES WHERE S_DATE >= start AND S_DATE<= end;
RETURN SAMT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` decimal(7,0) NOT NULL,
  `A_USERNAME` varchar(50) NOT NULL,
  `A_PASSWORD` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `A_USERNAME`, `A_PASSWORD`) VALUES
(1, 'nazim', 'nazim');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `C_ID` int(11) NOT NULL,
  `C_FNAME` varchar(30) NOT NULL,
  `C_LNAME` varchar(30) DEFAULT NULL,
  `C_AGE` int(11) NOT NULL,
  `C_SEX` varchar(6) NOT NULL,
  `C_PHNO` decimal(10,0) NOT NULL,
  `C_MAIL` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`C_ID`, `C_FNAME`, `C_LNAME`, `C_AGE`, `C_SEX`, `C_PHNO`, `C_MAIL`) VALUES
(987101, 'Zia', 'ul haq', 45, 'Male', 3226931124, 'ziahulhaq632@gmail.com'),
(987102, 'Najam', 'ul hassan', 18, 'Male', 3236496130, 'najamulhassan@gmail.com'),
(987103, 'Shahid', 'basheer', 50, 'Male', 3262576253, 'shahid1122@gmail.com'),
(987104, 'Zain', 'ul abideen', 24, 'Male', 3055611658, 'zain69789@gmail.com'),
(987105, 'Mehnaz', 'Aqeela', 39, 'Female', 3214433688, 'mehnazaqeela145@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `emplogin`
--

CREATE TABLE `emplogin` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_USERNAME` varchar(20) NOT NULL,
  `E_PASS` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emplogin`
--

INSERT INTO `emplogin` (`E_ID`, `E_USERNAME`, `E_PASS`) VALUES
(4567002, 'ahmad', 'ahmad'),
(4567001, 'ali', 'ali');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_FNAME` varchar(30) NOT NULL,
  `E_LNAME` varchar(30) DEFAULT NULL,
  `BDATE` date NOT NULL,
  `E_AGE` int(11) NOT NULL,
  `E_SEX` varchar(6) NOT NULL,
  `E_TYPE` varchar(20) NOT NULL,
  `E_JDATE` date NOT NULL,
  `E_SAL` decimal(8,2) NOT NULL,
  `E_PHNO` decimal(10,0) NOT NULL,
  `E_MAIL` varchar(40) DEFAULT NULL,
  `E_ADD` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`E_ID`, `E_FNAME`, `E_LNAME`, `BDATE`, `E_AGE`, `E_SEX`, `E_TYPE`, `E_JDATE`, `E_SAL`, `E_PHNO`, `E_MAIL`, `E_ADD`) VALUES
(1, 'nazim', 'ali', '1989-05-24', 21, 'Male', 'Admin', '2009-06-24', 95000.00, 9874563219, 'admin@pharmacia.com', 'Okara'),
(4567001, 'Ali', 'Aslam', '1995-10-05', 20, 'Male', 'Pharmacist', '2017-11-12', 20000.00, 3204903908, 'ahmadrana@gmail.com', 'Okara'),
(4567002, 'Ahmad', 'Akram', '2000-10-03', 20, 'Male', 'Pharmacist', '2012-10-06', 25000.00, 3262576253, 'akramahmadi@gmail.com', 'Renala Khurd');

-- --------------------------------------------------------

--
-- Table structure for table `meds`
--

CREATE TABLE `meds` (
  `MED_ID` int(11) NOT NULL,
  `MED_NAME` varchar(50) NOT NULL,
  `MED_QTY` int(11) NOT NULL,
  `CATEGORY` varchar(20) DEFAULT NULL,
  `MED_PRICE` decimal(6,2) NOT NULL,
  `LOCATION_RACK` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meds`
--

INSERT INTO `meds` (`MED_ID`, `MED_NAME`, `MED_QTY`, `CATEGORY`, `MED_PRICE`, `LOCATION_RACK`) VALUES
(123001, 'Dolo 650 MG', 355, 'Tablet', 1.00, 'rack 5'),
(123002, 'Panadol Cold & Flu', 875, 'Tablet', 2.50, 'rack 6'),
(123003, 'Livogen', 100, 'Capsule', 5.00, 'rack 3'),
(123004, 'Gelusil', 200, 'Tablet', 1.25, 'rack 4'),
(123005, 'Cyclopam', 210, 'Tablet', 6.00, 'rack 2'),
(123006, 'Benadryl 200 ML', 18, 'Syrup', 50.00, 'rack 10'),
(123007, 'Lopamide', 120, 'Capsule', 5.00, 'rack 7'),
(123008, 'Vitamic C', 140, 'Tablet', 3.00, 'rack 8'),
(123009, 'Omeprazole', 40, 'Capsule', 4.00, 'rack 3'),
(123010, 'Concur 5 MG', 450, 'Tablet', 3.50, 'rack 9'),
(123011, 'Augmentin 250 ML', 200, 'Syrup', 80.00, 'rack 7'),
(123012, 'Amoxil', 25, 'Syrup', 100.00, 'rack 10'),
(123013, 'Combiflam', 252, 'Syrup', 47.44, 'rack 6'),
(123014, 'Paracetamol', 101, 'Syrup', 59.30, 'rack 5'),
(123015, 'Loratadine', 348, 'Capsule', 12.80, 'rack 4'),
(123016, 'Metformin', 78, 'Tablet', 88.59, 'rack 6'),
(123017, 'Diclofenac', 104, 'Tablet', 54.67, 'rack 5'),
(123018, 'Insulin', 318, 'Syrup', 5.13, 'rack 10'),
(123019, 'Haloperidol', 265, 'Syrup', 3.64, 'rack 2'),
(123020, 'Naproxen', 65, 'Tablet', 17.01, 'rack 4'),
(123021, 'Sertraline', 28, 'Tablet', 14.97, 'rack 6'),
(123022, 'Aceclofenac', 34, 'Capsule', 80.81, 'rack 2'),
(123023, 'Ibuprofen', 140, 'Tablet', 46.81, 'rack 1'),
(123024, 'Vicks Vaporub', 341, 'Tablet', 8.83, 'rack 3'),
(123025, 'Carbamazepine', 149, 'Capsule', 80.66, 'rack 3'),
(123026, 'Erythromycin', 191, 'Tablet', 78.20, 'rack 5'),
(123027, 'Carvedilol', 428, 'Syrup', 97.80, 'rack 2'),
(123028, 'Azithromycin', 459, 'Tablet', 39.66, 'rack 3'),
(123029, 'Clindamycin', 320, 'Tablet', 65.44, 'rack 10'),
(123030, 'Neosporin', 452, 'Syrup', 2.68, 'rack 1'),
(123031, 'Gaviscon', 214, 'Capsule', 70.45, 'rack 6'),
(123032, 'Fluconazole', 178, 'Tablet', 3.50, 'rack 1'),
(123033, 'Spasmonil', 374, 'Syrup', 66.77, 'rack 3'),
(123034, 'Zincovit', 352, 'Tablet', 89.23, 'rack 5'),
(123035, 'Timolol', 276, 'Tablet', 16.89, 'rack 6'),
(123036, 'Ofloxacin', 494, 'Capsule', 33.89, 'rack 3'),
(123037, 'Ciprofloxacin', 324, 'Capsule', 20.50, 'rack 7'),
(123038, 'Domperidone', 167, 'Tablet', 24.34, 'rack 9'),
(123039, 'Amitriptyline', 484, 'Capsule', 41.90, 'rack 9'),
(123040, 'Pantoprazole', 144, 'Capsule', 89.50, 'rack 2'),
(123041, 'Levofloxacin', 326, 'Capsule', 36.27, 'rack 10'),
(123042, 'Crocin', 354, 'Capsule', 7.73, 'rack 3'),
(123043, 'Nystatin', 405, 'Tablet', 89.40, 'rack 3'),
(123044, 'Becosules', 470, 'Capsule', 58.12, 'rack 4'),
(123045, 'Escitalopram', 398, 'Tablet', 92.71, 'rack 10'),
(123046, 'Prednisolone', 90, 'Tablet', 81.18, 'rack 2'),
(123047, 'Dorzolamide', 143, 'Syrup', 99.62, 'rack 7'),
(123048, 'Moov', 231, 'Capsule', 21.92, 'rack 1'),
(123049, 'Warfarin', 348, 'Capsule', 1.16, 'rack 3'),
(123050, 'Bimatoprost', 62, 'Syrup', 98.44, 'rack 3'),
(123051, 'Diazepam', 329, 'Tablet', 71.17, 'rack 10'),
(123052, 'Betadine', 393, 'Syrup', 93.36, 'rack 3'),
(123053, 'T-Bact', 430, 'Capsule', 14.57, 'rack 10'),
(123054, 'Chlorpheniramine', 324, 'Capsule', 33.26, 'rack 4'),
(123055, 'Moxifloxacin', 197, 'Tablet', 90.28, 'rack 9'),
(123056, 'Phenytoin', 260, 'Tablet', 11.80, 'rack 10'),
(123057, 'Olanzapine', 432, 'Tablet', 70.66, 'rack 2'),
(123058, 'Tramadol', 115, 'Tablet', 61.04, 'rack 1'),
(123059, 'Montelukast', 382, 'Capsule', 18.88, 'rack 7'),
(123060, 'ORS', 179, 'Tablet', 2.14, 'rack 7'),
(123061, 'Losartan', 297, 'Capsule', 24.91, 'rack 10'),
(123062, 'Volini', 215, 'Capsule', 41.25, 'rack 10'),
(123063, 'Duloxetine', 472, 'Capsule', 22.32, 'rack 1'),
(123064, 'Valproate', 124, 'Capsule', 20.62, 'rack 1'),
(123065, 'Fexofenadine', 192, 'Syrup', 36.86, 'rack 5'),
(123066, 'Citalopram', 171, 'Tablet', 24.79, 'rack 1'),
(123067, 'D-Cold', 395, 'Syrup', 15.74, 'rack 6'),
(123068, 'Levothyroxine', 233, 'Capsule', 21.88, 'rack 9'),
(123069, 'Digene', 160, 'Capsule', 71.30, 'rack 3'),
(123070, 'Amoxicillin', 320, 'Tablet', 26.91, 'rack 10'),
(123071, 'Calpol', 296, 'Capsule', 36.74, 'rack 4'),
(123072, 'Brimonidine', 154, 'Capsule', 90.20, 'rack 10'),
(123073, 'Bisoprolol', 449, 'Capsule', 53.97, 'rack 9'),
(123074, 'Quetiapine', 272, 'Capsule', 71.77, 'rack 9'),
(123075, 'Enoxaparin', 126, 'Tablet', 55.85, 'rack 6'),
(123076, 'Timolol', 166, 'Tablet', 67.96, 'rack 3'),
(123077, 'Salbutamol', 410, 'Tablet', 96.29, 'rack 1'),
(123078, 'Aspirin', 374, 'Capsule', 35.28, 'rack 4'),
(123079, 'Nortriptyline', 460, 'Capsule', 5.44, 'rack 2'),
(123080, 'Ranitidine', 183, 'Tablet', 17.93, 'rack 4'),
(123081, 'Moxifloxacin', 169, 'Syrup', 80.17, 'rack 3'),
(123082, 'Iodex', 328, 'Tablet', 97.41, 'rack 9'),
(123083, 'Phenylephrine', 196, 'Capsule', 96.34, 'rack 6'),
(123084, 'Alprazolam', 135, 'Capsule', 42.86, 'rack 2'),
(123085, 'Doxycycline', 366, 'Capsule', 59.15, 'rack 7'),
(123086, 'Olopatadine', 244, 'Capsule', 7.77, 'rack 4'),
(123087, 'Ketoconazole', 227, 'Capsule', 63.72, 'rack 10'),
(123088, 'Propranolol', 362, 'Tablet', 25.33, 'rack 5'),
(123089, 'Latanoprost', 114, 'Tablet', 83.67, 'rack 5'),
(123090, 'Soframycin', 405, 'Tablet', 90.47, 'rack 2'),
(123091, 'Heparin', 230, 'Syrup', 37.30, 'rack 2'),
(123092, 'Bimatoprost', 353, 'Tablet', 86.99, 'rack 6'),
(123093, 'Rifampicin', 224, 'Syrup', 38.87, 'rack 1'),
(123094, 'Cetrizine', 241, 'Tablet', 31.95, 'rack 6'),
(123095, 'Lorazepam', 213, 'Capsule', 95.69, 'rack 6'),
(123096, 'Venlafaxine', 248, 'Tablet', 48.44, 'rack 5'),
(123097, 'Becosules', 195, 'Capsule', 80.48, 'rack 6'),
(123098, 'Lamotrigine', 355, 'Tablet', 34.92, 'rack 1'),
(123099, 'Hydroxychloroquine', 205, 'Tablet', 91.38, 'rack 2'),
(123100, 'Montelukast', 179, 'Tablet', 12.11, 'rack 2'),
(123101, 'Amlodipine', 269, 'Tablet', 20.87, 'rack 3'),
(123102, 'Pantoprazole', 392, 'Tablet', 75.56, 'rack 1'),
(123103, 'Calpol', 100, 'Capsule', 4.63, 'rack 2'),
(123104, 'Moov', 221, 'Capsule', 26.85, 'rack 4'),
(123105, 'Sinarest', 468, 'Capsule', 58.68, 'rack 2'),
(123106, 'Becosules', 455, 'Syrup', 94.43, 'rack 6'),
(123107, 'Dexamethasone', 135, 'Tablet', 65.35, 'rack 6'),
(123108, 'ORS', 386, 'Tablet', 89.30, 'rack 2'),
(123109, 'Disprin', 398, 'Tablet', 79.67, 'rack 4'),
(123110, 'Azithromycin', 332, 'Capsule', 83.13, 'rack 7'),
(123111, 'Valproate', 437, 'Tablet', 87.47, 'rack 5'),
(123112, 'Cetirizine', 175, 'Capsule', 69.57, 'rack 1');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `P_ID` decimal(4,0) NOT NULL,
  `SUP_ID` decimal(3,0) NOT NULL,
  `MED_ID` int(11) NOT NULL,
  `P_QTY` int(11) NOT NULL,
  `P_COST` decimal(8,2) NOT NULL,
  `PUR_DATE` date NOT NULL,
  `MFG_DATE` date NOT NULL,
  `EXP_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`P_ID`, `SUP_ID`, `MED_ID`, `P_QTY`, `P_COST`, `PUR_DATE`, `MFG_DATE`, `EXP_DATE`) VALUES
(1001, 171, 123001, 200, 200.00, '2025-04-25', '2025-03-01', '2027-03-01'),
(1002, 172, 123005, 150, 900.00, '2025-04-26', '2025-02-10', '2026-08-10'),
(1003, 173, 123010, 300, 1050.00, '2025-04-26', '2025-01-15', '2026-01-15'),
(1004, 174, 123007, 100, 500.00, '2025-04-27', '2024-12-01', '2026-06-01'),
(1005, 175, 123011, 80, 6400.00, '2025-04-28', '2025-03-10', '2027-03-10');

--
-- Triggers `purchase`
--
DELIMITER $$
CREATE TRIGGER `QTYDELETE` AFTER DELETE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `QTYINSERT` AFTER INSERT ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `QTYUPDATE` AFTER UPDATE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=new.MED_ID;
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SALE_ID` int(11) NOT NULL,
  `C_ID` int(11) NOT NULL,
  `S_DATE` date DEFAULT NULL,
  `S_TIME` time DEFAULT NULL,
  `TOTAL_AMT` decimal(8,2) DEFAULT NULL,
  `E_ID` decimal(7,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SALE_ID`, `C_ID`, `S_DATE`, `S_TIME`, `TOTAL_AMT`, `E_ID`) VALUES
(5, 987103, '2020-04-21', '15:24:43', 45.00, 1),
(6, 987102, '2020-03-11', '10:24:43', 140.00, 4567001),
(7, 987105, '2020-04-24', '00:25:54', 350.00, 1),
(8, 987104, '2020-04-24', '00:47:47', 35.00, 4567001),
(12, 987103, '2020-04-24', '19:33:16', 60.00, 1),
(13, 987104, '2020-04-24', '21:15:56', 62.50, 4567001),
(17, 987103, '2020-12-04', '19:35:56', 57.50, 1),
(18, 987105, '2020-12-04', '19:36:56', 160.00, 4567001),
(20, 987103, '2025-05-12', '20:56:25', 85.05, 4567001);

--
-- Triggers `sales`
--
DELIMITER $$
CREATE TRIGGER `SALE_ID_DELETE` BEFORE DELETE ON `sales` FOR EACH ROW BEGIN
DELETE from sales_items WHERE sales_items.SALE_ID=old.SALE_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `SALE_ID` int(11) NOT NULL,
  `MED_ID` decimal(6,0) NOT NULL,
  `SALE_QTY` int(11) NOT NULL,
  `TOT_PRICE` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`SALE_ID`, `MED_ID`, `SALE_QTY`, `TOT_PRICE`) VALUES
(5, 123001, 45, 45.00),
(6, 123006, 2, 100.00),
(6, 123009, 10, 40.00),
(7, 123001, 100, 100.00),
(7, 123003, 50, 250.00),
(20, 123020, 5, 85.05);

--
-- Triggers `sales_items`
--
DELIMITER $$
CREATE TRIGGER `SALEDELETE` AFTER DELETE ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+old.SALE_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `SALEINSERT` AFTER INSERT ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-new.SALE_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SUP_ID` decimal(3,0) NOT NULL,
  `SUP_NAME` varchar(25) NOT NULL,
  `SUP_ADD` varchar(30) NOT NULL,
  `SUP_PHNO` decimal(10,0) NOT NULL,
  `SUP_MAIL` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SUP_ID`, `SUP_NAME`, `SUP_ADD`, `SUP_PHNO`, `SUP_MAIL`) VALUES
(171, 'GlaxoSmithKline Pakistan', 'Karachi', 2111147545, 'pk.medinfo@gsk.com'),
(172, 'Pfizer Pakistan Ltd', 'Karachi', 2132115555, 'medinfo.pakistan@pfizer.com'),
(173, 'Getz Pharma', 'Karachi', 2135017971, 'info@getzpharma.com'),
(174, 'The Searle Company Ltd', 'Karachi', 2135250114, 'info@searlecompany.com'),
(175, 'Martin Dow Ltd', 'Karachi', 2135181001, 'info@martindow.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_USERNAME`),
  ADD UNIQUE KEY `USERNAME` (`A_USERNAME`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`C_ID`),
  ADD UNIQUE KEY `C_PHNO` (`C_PHNO`),
  ADD UNIQUE KEY `C_MAIL` (`C_MAIL`);

--
-- Indexes for table `emplogin`
--
ALTER TABLE `emplogin`
  ADD PRIMARY KEY (`E_USERNAME`),
  ADD KEY `E_ID` (`E_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`E_ID`);

--
-- Indexes for table `meds`
--
ALTER TABLE `meds`
  ADD PRIMARY KEY (`MED_ID`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`P_ID`,`MED_ID`),
  ADD KEY `SUP_ID` (`SUP_ID`),
  ADD KEY `MED_ID` (`MED_ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SALE_ID`),
  ADD KEY `C_ID` (`C_ID`),
  ADD KEY `E_ID` (`E_ID`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`SALE_ID`,`MED_ID`),
  ADD KEY `MED_ID` (`MED_ID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SUP_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=987116;

--
-- AUTO_INCREMENT for table `meds`
--
ALTER TABLE `meds`
  MODIFY `MED_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123120;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SALE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `emplogin`
--
ALTER TABLE `emplogin`
  ADD CONSTRAINT `emplogin_ibfk_1` FOREIGN KEY (`E_ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`SUP_ID`) REFERENCES `suppliers` (`SUP_ID`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`C_ID`) REFERENCES `customer` (`C_ID`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`E_ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`SALE_ID`) REFERENCES `sales` (`SALE_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
