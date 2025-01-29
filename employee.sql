-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2024 at 10:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `Employee ID` int(11) NOT NULL,
  `Employee Name` text DEFAULT NULL,
  `Bank Account name` text DEFAULT NULL,
  `Bank account number` bigint(20) DEFAULT NULL,
  `Bank Name` text DEFAULT NULL,
  `IFSC Code` text DEFAULT NULL,
  `Branch address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`Employee ID`, `Employee Name`, `Bank Account name`, `Bank account number`, `Bank Name`, `IFSC Code`, `Branch address`) VALUES
(1, 'Ansh', 'Ansh', 1234567890123450, 'ICICI', 'ICIC0001234', 'Mumbai'),
(2, 'sakshi', 'sakshi', 9876543210987650, 'HDFC Bank', 'HDFC0001234', 'Mumbai'),
(3, 'tejal', 'tejal', 5001234567890000, 'SBI (State Bank of India)', 'SBIN0001234', 'Mumbai'),
(4, 'harsh', 'harsh', 2023456789012340, 'Axis Bank', 'UTIB0001234', 'Gujarat'),
(5, 'sejal', 'sejal', 8765432109876540, 'Kotak Mahindra Bank', 'KKBK0001234', 'Gujarat'),
(6, 'chetna', 'chetna', 4123456789012340, 'Punjab National Bank (PNB)', 'PUNB0001234', 'Mumbai'),
(7, 'shubham', 'shubham', 2987465123487650, 'Bank of Baroda (BoB)', 'BARB0001234', 'Mumbai'),
(8, 'raj', 'raj', 6543210987654320, 'IDFC First Bank', 'IDFB0001234', 'Delhi'),
(9, 'prerna', 'prerna', 1029384756102930, 'Yes Bank', 'YESB0001234', 'Mumbai'),
(10, 'maya', 'maya', 8372641587392010, 'IndusInd Bank', 'INDB0001234', 'Mumbai'),
(11, 'sheetal', 'sheetal', 3098765432109870, 'RBL Bank (Ratnakar Bank)', 'RATN0001234', 'Mumbai'),
(12, 'vansh', 'vansh', 5612345678901230, 'Union Bank of India', 'UBIN0001234', 'Delhi'),
(13, 'vedant', 'vedant', 4738291056729830, 'Canara Bank', 'CNRB0001234', 'Mumbai'),
(14, 'aarush', 'aarush', 8392045612938470, 'IDBI Bank', 'IBKL0001234', 'Bhopal'),
(15, 'alok', 'alok', 6789012345678900, 'Federal Bank', 'FDRL0001234', 'Mumbai');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `Employee ID` int(11) NOT NULL,
  `Employee Name` text DEFAULT NULL,
  `Highest level of education` text DEFAULT NULL,
  `Year of completion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`Employee ID`, `Employee Name`, `Highest level of education`, `Year of completion`) VALUES
(1, 'Ansh', 'Bachelors', 2023),
(2, 'sakshi', 'Bachelors', 2020),
(3, 'tejal', 'Bachelors', 2019),
(4, 'harsh', 'Bachelors', 2018),
(5, 'sejal', 'Bachelors', 2015),
(6, 'chetna', 'Bachelors', 2020),
(7, 'shubham', 'Masters', 2023),
(8, 'raj', 'Masters', 2022),
(9, 'prerna', 'Masters', 2021),
(10, 'maya', 'Bachelors', 2020),
(11, 'sheetal', 'Bachelors', 2015),
(12, 'vansh', 'Bachelors', 2023),
(13, 'vedant', 'Masters', 2014),
(14, 'aarush', 'Masters', 2023),
(15, 'alok', 'Masters', 2021),
(16, 'Soham', 'Bachelors', 2033),
(17, 'q', 'MASTERS', 2021),
(18, 'SNEHA', 'bachelors', 2021),
(19, 'drishti', 'Bachelors', 2033),
(20, 'v', 'bachelors', 2023),
(21, 'SNE', 'MASTERS', 2033),
(22, 'b', 'Bachelors', 2033),
(23, 'SNEHA', 'Bachelors', 2023),
(24, 'SNEHA', 'Bachelors', 2023),
(25, 'SNEHA', 'MASTERS', 2023),
(26, 'SNE', 'bachelors', 2023),
(27, 'SNEHA', 'MASTERS', 2023),
(28, 'b', 'MASTERS', 2033),
(43, 'SNEHA', 'MASTERS', 2023),
(44, 'SNEHA', 'MASTERS', 2023),
(45, 'SNEHA', 'MASTERS', 2023),
(46, 'SNEHA', 'MASTERS', 2023),
(47, 'SNEHA', 'MASTERS', 2023);

-- --------------------------------------------------------

--
-- Table structure for table `empdata1`
--

CREATE TABLE `empdata1` (
  `Employee ID` int(11) NOT NULL,
  `Employee Name` text DEFAULT NULL,
  `Present address` text DEFAULT NULL,
  `Present  Area` text DEFAULT NULL,
  `Present Pincode` int(11) DEFAULT NULL,
  `Permanent address` text DEFAULT NULL,
  `Permanent Area` text DEFAULT NULL,
  `Permanent Pincode` int(11) DEFAULT NULL,
  `State` text DEFAULT NULL,
  `Mobile number` bigint(20) DEFAULT NULL,
  `It PAN` text DEFAULT NULL,
  `Aadhar no` double DEFAULT NULL,
  `DOB` text DEFAULT NULL,
  `Gender` text DEFAULT NULL,
  `Marital status` text DEFAULT NULL,
  `Date of joining` text DEFAULT NULL,
  `Probation period` text DEFAULT NULL,
  `End date` text DEFAULT NULL,
  `Annual CTC Salary` text DEFAULT NULL,
  `Emergency contact no` bigint(20) DEFAULT NULL,
  `Emergency contact name` text DEFAULT NULL,
  `Social media` text DEFAULT NULL,
  `Nature of employment` text DEFAULT NULL,
  `Resignation date` text DEFAULT NULL,
  `Relievation date` text DEFAULT NULL,
  `First Pay Revision Due Date` text DEFAULT NULL,
  `Work hours` text DEFAULT NULL,
  `Physically fit` text DEFAULT NULL,
  `under medication/treatment` text DEFAULT NULL,
  `relived as on date` text DEFAULT NULL,
  `job profile communicated` text DEFAULT NULL,
  `work timing confirmed` text DEFAULT NULL,
  `accept job` text DEFAULT NULL,
  `Eligible Paid Leaves` text DEFAULT NULL,
  `Number of Paid Leaves` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empdata1`
--

INSERT INTO `empdata1` (`Employee ID`, `Employee Name`, `Present address`, `Present  Area`, `Present Pincode`, `Permanent address`, `Permanent Area`, `Permanent Pincode`, `State`, `Mobile number`, `It PAN`, `Aadhar no`, `DOB`, `Gender`, `Marital status`, `Date of joining`, `Probation period`, `End date`, `Annual CTC Salary`, `Emergency contact no`, `Emergency contact name`, `Social media`, `Nature of employment`, `Resignation date`, `Relievation date`, `First Pay Revision Due Date`, `Work hours`, `Physically fit`, `under medication/treatment`, `relived as on date`, `job profile communicated`, `work timing confirmed`, `accept job`, `Eligible Paid Leaves`, `Number of Paid Leaves`, `address`) VALUES
(1, 'Ansh', '123, XYZ Street', 'Andheri West', 400058, '456, ABC Lane', 'Andheri East', 400241, 'Maharashtra', 8054124122, 'PAN4474k8K', 500000000000, '02-06-2003', 'M', 'Unmarried', '12-03-2024', '3 months', '12-06-2024', '? 50,000.00', 9851254622, 'Jane Smith', 'linkedin', 'Intern', 'NA', '01-01-2025', '01-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'yes', 5, 'mumbai'),
(2, 'sumit', '456, Elmwood Avenue', 'Bandra East', 400051, '321 Cherry Circle', 'Andheri West', 400058, 'Maharashtra', 8412412574, 'ABCDE1234F', 841000000000, '15-01-1990', 'M', 'Unmarried', '20-07-2022', '4 months', '13-06-2024', '? 50,000.00', 9125874102, 'Ethan Blake', 'indeed', 'Intern', 'NA', '02-01-2025', '02-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'yes', 4, 'nashik'),
(3, 'tejal', '789, Maple Lane', 'Juhu Beach', 400049, '654 Aspen Parkway', 'Bandra East', 400051, 'Maharashtra', 9715024258, 'XYZAB5678K', 542000000000, '25-03-1985', 'F', 'married', '07-04-2021', '5 months', '14-06-2024', '? 60,000.00', 4881254215, 'Marco Salazar', 'linkedin', 'Intern', 'NA', '03-01-2025', '03-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 1, 'nagpur'),
(4, 'harsh', '321, Oak Drive', 'Lower Parel', 400013, '432 Sycamore Street', 'Vashi', 400703, 'Kerala', 9422251201, 'MNBOP6789D', 543000000000, '10-12-1995', 'M', 'Unmarried', '22-08-2018', '6 months', '15-06-2024', '? 75,000.00', 9710541055, 'Liam Jensen', 'instagram', 'Intern', 'NA', '04-01-2025', '04-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 1, 'mumbai'),
(5, 'sejal', '987, Pine Street', 'Worli', 400018, '987, Pine Street', 'Borivali West', 400018, 'Kerala', 5251235055, 'PQRST9876B', 981234100, '03-07-2000', 'F', 'Unmarried', '20-05-2023', '3 months', '16-06-2024', '? 50,000.00', 5520114741, 'Kaius Volkov', 'linkedin', 'Intern', 'NA', '05-01-2025', '05-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 0, 'mumbai'),
(6, 'chetna', '654, Birch Boulevard', 'Borivali West', 400092, '456 Maple Road', 'Lower Parel', 488556, ' Madhya Pradesh', 9874125421, 'KLMPQ2345H', 963000000000, '20-08-1992', 'F', 'married', '04-09-2022', '4 months', '17-06-2024', '? 50,000.00', 8412412574, 'Xavier Montgomery', 'facebook', 'Intern', 'NA', '06-01-2025', '06-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 2, 'mumbai'),
(7, 'shubham', '432, Willow Road', 'Versova', 400061, '789 Birch Lane', 'Juhu', 453785, 'Madhya Pradesh', 9856320147, 'DJKLO1123N', 5220000000000, '09-09-1988', 'M', 'Unmarried', '05-03-2020', '5 months', '18-06-2024', '? 60,000.00', 9715024258, 'Ryu Tanaka', 'linkedin', 'Intern', 'NA', '07-01-2025', '07-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 1, 'mumbai'),
(8, 'raj', '567, Cedar Crescent', 'Goregaon East', 400063, '101 Elm Drive', 'Mulund East', 474411, 'Madhya Pradesh', 8411025455, 'WXYZT4567E', 74106522100, '17-02-1975', 'M', 'married', '12-08-2022', '1 year', '19-06-2024', '? 1,60,000.00', 9422251201, 'Zane Rivers', 'indeed', 'job', 'NA', '08-01-2025', '08-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 7, 'mumbai'),
(9, 'prerna', '890, Rosewood Place', 'Chembur', 400089, '234 Cedar Boulevard', 'Kandivali West', 485366, 'Maharashtra', 5202441225, 'DFGHI0987P', 5110000000000, '06-05-1999', 'F', 'Unmarried', '05-02-2023', '2 year', '20-06-2024', '? 2,00,000.00', 5251235055, 'Tariq Hassan', 'facebook', 'job', 'NA', '09-01-2025', '09-01-2025', 'virtual', 'no', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 3, 'mumbai'),
(10, 'maya', '246, Sunset Parkway', 'Andheri East', 400069, '246, Sunset Parkway', 'Andheri East', 400069, ' Gujarat', 9422055200, 'LJKLO6789Q', 5522001551, '14-11-1991', 'F', 'married', '07-09-2022', '3 year', '21-06-2024', '? 25,00,000.00', 9874125421, 'Miles Keegan', 'indeed', 'job', 'NA', '10-01-2025', '10-01-2025', 'virtual', 'no', 'no', 'NA', 'no', 'yes', 'yes', 'no', 8, 'mumbai'),
(11, 'sheetal', '135, Riverfront Road', 'Dadar West', 400028, '135, Riverfront Road', 'Dadar West', 400028, ' Gujarat', 5200185555, 'QWERT1234Z', 51100055555, '30-04-2005', 'F', 'Unmarried', '10-10-2024', '3 months', '22-06-2024', '? 50,000.00', 9856320147, 'Amara Vance', 'inkedin', 'Intern', 'NA', '11-01-2025', '11-01-2025', 'virtual', 'yes', 'yes', 'NA', 'no', 'yes', 'yes', 'no', 2, 'mumbai'),
(12, 'vansh', '864, Highview Terrace', 'Colaba', 400005, '123 Oak Avenue', 'Shivaji Nagar, Pune ', 411005, 'Maharashtra', 9125874102, 'DFGHI0987P', 55100000000000, '27-10-1982', 'M', 'Unmarried', '10-08-2023', '4 months', '23-06-2024', '? 50,000.00', 8411025455, 'Sofia Cabrera', 'instagram', 'Intern', 'NA', '12-01-2025', '12-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 5, 'mumbai'),
(13, 'vedant', '753, Springhill Drive', 'Santacruz East', 400055, '345 Maple Crescent', 'Kothrud, Pune ', 411038, 'Uttar Pradesh', 4881254215, 'LJKLO6789Q', 45211551212, '22-02-2000', 'M', 'Unmarried', '08-05-2023', '5 months', '24-06-2024', '? 60,000.00', 5202441225, 'Elena Novak', 'linkedin', 'Intern', 'NA', '13-01-2025', '13-01-2025', 'Onsite', 'yes', 'yes', 'NA', 'yes', 'yes', 'yes', 'no', 2, 'mumbai'),
(14, 'aarush', '963, Highland Street', 'Khar West', 400052, '678 Cedar Ridge', 'Camp, Pune', 411001, 'Uttar Pradesh', 9710541055, 'QWERT1234Z', 841000000000, '18-03-1980', 'M', 'Unmarried', '04-02-2024', '6 months', '25-06-2024', '? 75,000.00', 8054124122, 'Iris Zhang', 'indeed', 'Intern', 'NA', '14-01-2025', '14-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 1, 'mumbai'),
(15, 'alok', '159, Bluebell Way', 'Thane West', 400601, '234 Rosewood Boulevard', 'Viman Nagar', 441523, 'Andhra Pradesh', 5520114741, 'LMNOP9876S', 841000000000, '01-01-1993', 'M', 'married', '04-05-2021', '3 months', '26-06-2024', '? 50,000.00', 8412412574, 'Ivan Petrov', 'facebook', 'Intern', 'NA', '15-01-2025', '15-01-2025', 'Onsite', 'yes', 'no', 'NA', 'yes', 'yes', 'yes', 'no', 4, 'mumbai'),
(16, 'VV', 'mum', NULL, 123456, 'hh', 'n', 123456, 'mah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'VV', 'mum', NULL, 123456, 'hh', 'n', 123456, 'mah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `family`
--

CREATE TABLE `family` (
  `Employee ID` int(11) DEFAULT NULL,
  `Sr_no` int(11) NOT NULL,
  `Family  member name` text DEFAULT NULL,
  `Family relation` text DEFAULT NULL,
  `mobile number` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family`
--

INSERT INTO `family` (`Employee ID`, `Sr_no`, `Family  member name`, `Family relation`, `mobile number`) VALUES
(1, 1, 'Geeta ', 'Mother', 9435248455),
(1, 2, 'Sanjay', 'Father', 9974550422),
(2, 3, 'Meera', 'Mother', 5151444555),
(2, 4, 'Rajesh ', 'Father', 9741587444),
(3, 5, 'Anjali ', 'Mother', 9715024258),
(3, 6, 'Vikram ', 'Father', 9422251201),
(3, 7, 'Neelam ', 'Sister', 5251235055),
(4, 8, 'Sunita ', 'Mother', 9874125421),
(4, 9, 'Ramesh ', 'Father', 9856320147),
(5, 10, 'Kavita', 'Mother', 8411025455),
(5, 11, 'Prakash ', 'Father', 5202441225),
(6, 12, 'Pooja ', 'Mother', 9422055200),
(7, 13, 'Rekha ', 'Mother', 5200185555),
(7, 14, 'Manoj ', 'Father', 9125874102),
(7, 15, 'Ashok', 'Brother', 4881254215),
(8, 16, 'Rama', 'Mother', 9710541055),
(8, 17, 'Harish ', 'Father', 9710541055),
(9, 18, 'Madhuri', 'Mother', 5520114741),
(9, 19, 'Krishna ', 'Father', 8412412574),
(10, 20, 'Rani ', 'Mother', 9715024258),
(10, 21, 'Ajay ', 'Father', 9422251201),
(11, 22, 'Shiv ', 'Father', 5251235055),
(12, 23, 'Chandrika', 'Mother', 9874125421),
(12, 24, 'Ravi ', 'Father', 9856320147),
(12, 25, 'Kailash ', 'Brother', 8411025455),
(13, 26, 'Vandana ', 'Mother', 5202441225),
(13, 27, 'Suresh ', 'Father', 8054124122),
(14, 28, 'Laxmi ', 'Mother', 9874874747),
(14, 29, 'Dinesh ', 'Father', 9874171014),
(15, 30, 'Shobha', 'Mother', 9542414200),
(15, 31, 'Pankaj ', 'Father', 9741302577),
(NULL, 32, 'JJ', 'FATHER', 2728282828),
(NULL, 33, 'hh', 'mother', 2334);

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `username` varchar(255) NOT NULL,
  `password` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`username`, `password`) VALUES
('a', '1'),
('a', '1'),
('a', '1'),
('a', '1'),
('a', '1'),
('a', '1'),
('a', '1');

-- --------------------------------------------------------

--
-- Table structure for table `last employment`
--

CREATE TABLE `last employment` (
  `Employee ID` int(11) NOT NULL,
  `Employer name` text DEFAULT NULL,
  `Immediate_superior_name` varchar(255) DEFAULT NULL,
  `Email id` text DEFAULT NULL,
  `Date of joining` text DEFAULT NULL,
  `Date of termination` text DEFAULT NULL,
  `obligation pertaining to last employment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `last employment`
--

INSERT INTO `last employment` (`Employee ID`, `Employer name`, `Immediate_superior_name`, `Email id`, `Date of joining`, `Date of termination`, `obligation pertaining to last employment`) VALUES
(1, 'Ansh', 'tcs', 'tcs@123gmai.com', '22-May-23', '', ''),
(2, 'sakshi', 'infosys', 'info@456mail.com', '23-May-23', '', ''),
(3, 'tejal', 'xyz', 'contact@789outlook.com', '24-May-23', '', ''),
(4, 'harsh', 'abc', 'support@321yahoo.com', '25-May-23', '', ''),
(5, 'sejal', 'tata', 'sales@567gmail.com', '26-May-23', '', ''),
(6, 'chetna', 'xyz', 'help@999outlook.com', '27-May-23', '', ''),
(7, 'shubham', 'abc', 'admin@876yahoo.com', '28-May-23', '', ''),
(8, 'raj', 'infosys', 'hr@234hotmail.com', '29-May-23', '', ''),
(9, 'prerna', 'tcs', 'care@345live.com', '30-May-23', '', ''),
(10, 'maya', 'xyz', 'team@654aol.com', '31-May-23', '', ''),
(11, 'sheetal', 'infosys', 'admin@987icloud.com', '01-Jun-23', '', ''),
(12, 'vansh', 'tcs', 'hr@654hotmail.com', '02-Jun-23', '', ''),
(13, 'vedant', 'abc', 'team@432aol.com', '03-Jun-23', '', ''),
(14, 'aarush', 'pqr', 'help@876live.com', '04-Jun-23', '', ''),
(15, 'alok', 'xyz', 'care@543mail.com', '05-Jun-23', '', ''),
(16, 's', 'z', '123@g', '2024-11-05', '2024-11-12', 'z'),
(17, 's', 'z', '123@g', '2024-11-05', '2024-11-12', 'z');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`Employee ID`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`Employee ID`);

--
-- Indexes for table `empdata1`
--
ALTER TABLE `empdata1`
  ADD PRIMARY KEY (`Employee ID`);

--
-- Indexes for table `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`Sr_no`);

--
-- Indexes for table `last employment`
--
ALTER TABLE `last employment`
  ADD PRIMARY KEY (`Employee ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `Employee ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `Employee ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `empdata1`
--
ALTER TABLE `empdata1`
  MODIFY `Employee ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `family`
--
ALTER TABLE `family`
  MODIFY `Sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `last employment`
--
ALTER TABLE `last employment`
  MODIFY `Employee ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
