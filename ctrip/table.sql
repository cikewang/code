-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 10 月 27 日 15:33
-- 服务器版本: 5.6.10
-- PHP 版本: 5.4.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `pbi_tour`
--

-- --------------------------------------------------------

--
-- 表的结构 `xc_area`
--

CREATE TABLE IF NOT EXISTS `xc_area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_cate_id` int(11) NOT NULL,
  `area_id_xc` int(11) NOT NULL,
  `area_name` varchar(20) NOT NULL,
  `area_type` char(10) NOT NULL,
  `city_id_xc` int(11) NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4019 ;

-- --------------------------------------------------------

--
-- 表的结构 `xc_area_category`
--

CREATE TABLE IF NOT EXISTS `xc_area_category` (
  `area_cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_cate_name` varchar(10) NOT NULL,
  `area_cate_code` char(10) NOT NULL,
  PRIMARY KEY (`area_cate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `xc_city`
--

CREATE TABLE IF NOT EXISTS `xc_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_code` char(10) DEFAULT NULL,
  `city_id_xc` int(11) NOT NULL,
  `city_name` varchar(20) NOT NULL,
  `pinyin` char(20) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `is_domestic_city` tinyint(4) DEFAULT NULL,
  `group` char(1) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=337 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
