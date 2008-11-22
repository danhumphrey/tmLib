-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 22, 2008 at 05:59 PM
-- Server version: 5.0.33
-- PHP Version: 5.2.1
-- 
-- Database: `orm_test`
-- 

use `orm_test`;
-- --------------------------------------------------------

-- 
-- Table structure for table `t_articles`
-- 

CREATE TABLE `t_articles` (
  `id` int(11) NOT NULL auto_increment,
  `category` int(11) default NULL,
  `title` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `t_articles`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `t_categories`
-- 

CREATE TABLE `t_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `t_categories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `t_comments`
-- 

CREATE TABLE `t_comments` (
  `id` int(11) NOT NULL auto_increment,
  `article` int(11) NOT NULL,
  `comment` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `t_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `t_tags`
-- 

CREATE TABLE `t_tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `t_tags`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `t_tags2articles`
-- 

CREATE TABLE `t_tags2articles` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `t_tags2articles`
-- 

