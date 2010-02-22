-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2010 at 04:04 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `ushahidi`
--

-- update as of 15-feb-2010

-- --------------------------------------------------------

--
-- Table structure for table `feed`
--



INSERT INTO `ushahidi`.`roles` (
`id` ,
`name` ,
`description` 
)
VALUES (
'4', 'sweeper', 'Sweeper look throught the feeds and rate the sources of the feeds'
);


ALTER TABLE `reporter` ADD `weight` DECIMAL(10,2) NOT NULL AFTER `reporter_ip` ;




