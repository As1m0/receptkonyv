-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 14, 2025 at 04:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ReceptkonyvDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `flag` varchar(100) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`flag`, `content`) VALUES
('about-cover', 'about-cover-img.jpg'),
('about-text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean mattis est nec nibh interdum, nec lacinia mi accumsan. In tristique, sapien vitae ultricies finibus, felis risus rutrum ex, quis vehicula ipsum eros nec massa. Integer iaculis, orci in eleifend varius, purus sapien molestie ex, eget pharetra leo augue ut quam. Fusce semper malesuada dui, eu eleifend nulla rutrum vel. Phasellus elementum maximus arcu nec mollis. Morbi dictum nibh mauris, eu viverra lorem ultrices non. Praesent luctus mi quis nibh venenatis, sit amet efficitur dui fermentum. Ut tempor fermentum lectus.\r\n\r\nDuis id enim suscipit, malesuada libero non, luctus eros. Sed et auctor est. Cras tempus laoreet neque in pellentesque. Curabitur egestas odio sem, sit amet maximus augue mattis vel. Proin imperdiet justo et commodo viverra. Donec venenatis nunc sit amet lectus malesuada mollis. In convallis eros vel aliquam feugiat. Proin odio neque, vehicula vel tincidunt eu, viverra id purus.'),
('account-cover', 'search-background-img.jpg'),
('bg-img', 'background-pattern.png'),
('nav-logo', 'logo.png'),
('search-cover', 'search-background-img.jpg'),
('welcome-text', 'Receptek mindenkinek!');

-- --------------------------------------------------------

--
-- Table structure for table `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `felh_id` int(11) NOT NULL,
  `veznev` varchar(255) NOT NULL,
  `kernev` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `pic_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `groupMember` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `felhasznalok`
--

INSERT INTO `felhasznalok` (`felh_id`, `veznev`, `kernev`, `email`, `password_hash`, `pic_name`, `created_at`, `groupMember`) VALUES
(1, 'Ujvárossy', 'Ábel', 'ujvarossyabel@gmail.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', '4953d17588caf7', '2024-12-12 13:46:07', 1),
(2, 'Ujvárossy', 'Samu', 'ujvarossysamu@gmail.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', NULL, '2024-12-12 13:48:49', 1),
(3, 'Kiss', 'János', 'kisjanos@test.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', '4953d17588caf7', '2024-12-16 10:13:36', 0),
(5, 'Ujvárossy', 'Ábel', 'ujvarossyabel@test.hu', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', 'a01668a0f5df92', '2024-12-16 12:10:33', 1),
(6, 'Nagy', 'Balázs', 'nagybalazs@test.hu', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', NULL, '2024-12-16 12:14:41', 0),
(7, 'Kovács', 'József', 'kovacsjozsef@test.hu', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', NULL, '2024-12-16 12:14:41', 0),
(8, 'Kiss', 'Balázs', 'kisbalazs@test.hu', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', NULL, '2024-12-16 12:14:41', 0),
(9, 'Ujvárossy', 'Lujza', 'ujvarossyLujza@gmail.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', NULL, '2024-12-12 13:46:07', 1),
(10, 'Cserepes', 'Virág', 'cserepesvirag@test.hu', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', '5237616aacd0bc', '2024-12-19 12:30:54', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hozzavalok`
--

CREATE TABLE `hozzavalok` (
  `hozzavalo_id` int(11) NOT NULL,
  `recept_id` int(11) NOT NULL,
  `nev` varchar(255) NOT NULL,
  `mennyiseg` double NOT NULL,
  `mertekegyseg` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hozzavalok`
--

INSERT INTO `hozzavalok` (`hozzavalo_id`, `recept_id`, `nev`, `mennyiseg`, `mertekegyseg`) VALUES
(1, 8, 'liszt', 200, 'g'),
(2, 8, 'cukor', 150, 'g'),
(3, 8, 'kakaópor', 50, 'g'),
(4, 8, 'sütőpor', 10, 'g'),
(5, 8, 'tojás', 2, 'db'),
(6, 8, 'vaj', 100, 'g'),
(7, 8, 'tej', 150, 'ml'),
(8, 8, 'étcsokoládé darabkák', 100, 'g'),
(9, 9, 'sütőtök', 1000, 'g'),
(10, 9, 'hagyma', 1, 'db'),
(11, 9, 'fokhagyma', 2, 'gerezd'),
(12, 9, 'zöldség alaplé', 750, 'ml'),
(13, 9, 'tejszín', 150, 'ml'),
(14, 9, 'só', 1, 'tk'),
(15, 9, 'bors', 0.5, 'tk'),
(16, 9, 'szerecsendió', 1, 'csipet'),
(45, 13, 'érett banán', 3, 'db'),
(46, 13, 'liszt', 200, 'g'),
(47, 13, 'cukor', 100, 'g'),
(48, 13, 'sütőpor', 1, 'csomag'),
(49, 13, 'tojás', 2, 'db'),
(50, 13, 'olaj', 100, 'ml'),
(51, 13, 'fahéj', 1, 'tk'),
(71, 16, 'liszt', 500, 'g'),
(72, 16, 'szárított élesztő', 7, 'g'),
(73, 16, 'só', 1, 'tk'),
(74, 16, 'cukor', 1, 'tk'),
(75, 16, 'langyos víz', 300, 'ml'),
(76, 16, 'olívaolaj', 3, 'ek'),
(77, 16, 'paradicsomszósz', 200, 'ml'),
(78, 16, 'reszelt sajt', 200, 'g'),
(79, 16, 'tetszőleges feltétek', 150, 'g'),
(80, 17, 'cukkini', 2, 'db'),
(81, 17, 'liszt', 100, 'g'),
(82, 17, 'tojás', 2, 'db'),
(83, 17, 'zsemlemorzsa', 150, 'g'),
(84, 17, 'olaj', 1, 'l'),
(85, 17, 'só', 1, 'tk'),
(100, 20, 'liszt', 200, 'g'),
(101, 20, 'cukor', 150, 'g'),
(102, 20, 'kakaópor', 50, 'g'),
(103, 20, 'sütőpor', 1, 'tk'),
(104, 20, 'növényi tej', 150, 'ml'),
(105, 20, 'növényi olaj', 100, 'ml'),
(106, 20, 'vanília kivonat', 1, 'tk'),
(107, 20, 'dió', 100, 'g'),
(108, 21, 'padlizsán', 2, 'db'),
(109, 21, 'fokhagyma', 2, 'gerezd'),
(110, 21, 'citromlé', 2, 'ek'),
(111, 21, 'só', 1, 'tk'),
(112, 21, 'olívaolaj', 4, 'ek'),
(113, 21, 'bazsalikom', 1, 'tk'),
(123, 23, 'marhahús', 500, 'g'),
(124, 23, 'hagyma', 1, 'db'),
(125, 23, 'fokhagyma', 2, 'gerezd'),
(126, 23, 'sárgarépa', 2, 'db'),
(127, 23, 'krumpli', 500, 'g'),
(128, 23, 'paradicsom', 2, 'db'),
(129, 23, 'pirospaprika', 1, 'tk'),
(130, 23, 'só', 1, 'tk'),
(131, 23, 'bors', 1, 'tk'),
(132, 23, 'babérlevél', 2, 'db'),
(140, 25, 'spenót', 100, 'g'),
(141, 25, 'banán', 1, 'db'),
(142, 25, 'alma', 1, 'db'),
(143, 25, 'avokádó', 1, 'db'),
(144, 25, 'mandulatej', 200, 'ml'),
(145, 25, 'méz', 1, 'ek'),
(146, 26, 'liszt', 250, 'g'),
(147, 26, 'tojás', 2, 'db'),
(148, 26, 'tej', 300, 'ml'),
(149, 26, 'cukor', 2, 'ek'),
(150, 26, 'vanília kivonat', 1, 'tk'),
(151, 26, 'olaj', 1, 'ek'),
(163, 46, 'cukor', 200, 'gramm'),
(164, 46, 'kifli', 4, 'db'),
(165, 46, 'tej', 200, 'ml'),
(166, 46, 'vanília puding', 1, 'csomag'),
(170, 49, 'Liszt', 1, 'kg'),
(171, 49, 'porcukor', 30, 'dkg'),
(172, 49, 'szódabikarbóna', 3, 'tk'),
(173, 49, 'mézeskalács fűszerkeverék', 14, 'gramm'),
(174, 49, 'margarin', 30, 'dkg'),
(175, 49, 'méz', 250, 'ml'),
(176, 49, 'tojás', 3, 'db'),
(177, 7, 'cukor', 200, 'g'),
(178, 7, 'liszt', 200, 'g'),
(179, 7, 'kifli', 4, 'db'),
(180, 7, 'vanília puding', 3, 'csomag'),
(181, 7, 'tej', 200, 'ml'),
(182, 50, 'burgonya', 1, 'kg'),
(183, 50, 'só', 1, 'tk'),
(184, 50, 'bors', 3, 'csipet'),
(185, 50, 'majoranna', 1, 'tk'),
(186, 50, 'csirkemell', 50, 'dkg'),
(187, 50, 'gyros fűszerkeverék', 1, 'csomag'),
(188, 50, 'fokhagyma', 2, 'gerezd'),
(203, 59, 'margarin', 1, 'ek'),
(204, 59, 'olívaolaj', 2, 'ek'),
(205, 59, 'só', 1, 'tk'),
(206, 59, 'csiperkegomba', 50, 'dkg'),
(207, 59, 'fokhagyma', 3, 'gerezd'),
(208, 59, 'fehérbor', 1, 'dl'),
(209, 59, 'pirospaprika', 1, 'ek');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `name` varchar(64) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`name`, `enabled`) VALUES
('AdminNavModule', 1),
('FooterModule', 1),
('MainSearchModule', 1),
('NavigationModule', 1),
('RecentRecepiesModule', 1),
('SearchKeyLoggerModule', 1),
('StarReceptModule', 1),
('VisitLoggerModule', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `pageKey` varchar(16) NOT NULL,
  `template` varchar(100) NOT NULL,
  `fullTemplate` tinyint(1) NOT NULL DEFAULT 0,
  `class` varchar(100) NOT NULL,
  `parent` varchar(16) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `permission` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageKey`, `template`, `fullTemplate`, `class`, `parent`, `enabled`, `permission`) VALUES
('about', 'about.html', 0, 'AboutPage', 'indexGroup', 1, 0),
('account', 'account-page.html', 0, 'AccountPage', 'indexGroup', 1, 0),
('admin', 'admin_home.html', 0, 'adminHomePage', 'adminGroup', 1, 1),
('adminGroup', 'admin.html', 1, '', NULL, 1, 0),
('editText', 'edit-text.html', 0, 'editTextPage', 'adminGroup', 1, 1),
('index', 'main.html', 0, 'IndexPage', 'indexGroup', 1, 0),
('indexGroup', 'index.html', 1, '', NULL, 1, 0),
('login', 'login.html', 0, 'LoginPage', 'indexGroup', 1, 0),
('recept-aloldal', 'recept-datasheet.html', 0, 'receptDatasheetPage', 'indexGroup', 1, 0),
('recept-feltoltes', 'recept-feltoltes.html', 0, 'UploadPage', 'indexGroup', 1, 0),
('receptek', 'receptek.html', 0, 'ReceptekPage', 'indexGroup', 1, 0),
('register', 'register.html', 0, 'RegisterPage', 'indexGroup', 1, 0),
('searchLog', 'search-log.html', 0, 'searchLogPage', 'adminGroup', 1, 1),
('users', 'users.html', 0, 'usersPage', 'adminGroup', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recept`
--

CREATE TABLE `recept` (
  `recept_id` int(11) NOT NULL,
  `recept_neve` varchar(255) NOT NULL,
  `kategoria` varchar(64) NOT NULL,
  `leiras` longtext NOT NULL,
  `elk_ido` int(11) NOT NULL,
  `adag` int(11) NOT NULL,
  `nehezseg` varchar(64) NOT NULL,
  `felh_id` int(11) NOT NULL,
  `pic_name` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recept`
--

INSERT INTO `recept` (`recept_id`, `recept_neve`, `kategoria`, `leiras`, `elk_ido`, `adag`, `nehezseg`, `felh_id`, `pic_name`, `created_at`) VALUES
(7, 'Mákos guba', 'sütemény', 'A tejet kezdjük el melegíteni. A tojások sárgáját keverjük el a cukorral és a vaníliás cukorral, adjuk a tejhez. (Ne hagyjuk felfőni.)\r\nA mákot keverjük el a porcukorral.\r\nVajazzunk ki egy jénai tálat, és rétegezzük a tál aljára a karikára vágott kifliket. Locsoljuk meg egy adag tejes keverékkel, és szórjunk rá mákot. Folytassuk így, amíg el nem fogynak a hozzávalók.\r\nTegyük előmelegített sütőbe, és süssük 180 fokon 20-25 percig.\r\nVaníliasodó\r\nKeverjük ki a tojások sárgáját a cukorral és a vaníliás cukorral.\r\nHa már elkeveredett és kissé habos, adjunk hozzá kevés lisztet és tejet. (Mindig kevés liszttel kezdjük, hogy ne legyen csomós.)\r\nGőz fölött sűrítsük be, és már tálalhatjuk is a gubához.', 70, 4, 'közepes', 5, 'fd7637456be3d4', '2024-12-16 18:22:47'),
(8, 'Csokis muffin', 'sütemények', 'Keverd össze a száraz és nedves hozzávalókat, majd öntsd muffin formákba. Süsd 180 fokon 20-25 percig, amíg aranybarna nem lesz.', 30, 4, 'kezdő', 1, '7afbd85a8b3ad1', '2024-12-13 08:06:45'),
(9, 'Sütőtök krémleves', 'levesek', 'Pirítsd meg a hagymát, majd add hozzá a sütőtököt és az alaplevet. Főzd puhára, majd turmixold össze és ízesítsd.', 25, 3, 'kezdő', 7, 'ajlh235kjassda', '2024-12-13 08:06:45'),
(11, 'Gombapörkölt', 'főétel', 'Pirítsd meg a hagymát, majd add hozzá a gombát és a fűszereket. Főzd puhára, és tálald rizzsel vagy galuskával.', 45, 4, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(12, 'Töltött paprika', 'főétel', 'Töltsd meg a paprikákat darált hússal és rizzsel, majd főzd paradicsomszószban puhára.', 90, 5, 'haladó', 8, NULL, '2024-12-13 08:06:45'),
(13, 'Banánkenyér', 'sütemények', 'Keverd össze a pépesített banánt a többi hozzávalóval, majd öntsd sütőformába. Süsd 180 fokon 50-60 percig.', 50, 8, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(14, 'Vegán bolognai spagetti', 'vegán', 'Főzd meg a spagettit. Készíts szószt lencséből, paradicsomból és fűszerekből, majd keverd össze a tésztával.', 40, 4, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(15, 'Zöldséges rizottó', 'vegetáriánus', 'Pirítsd meg a rizst olajon, majd fokozatosan add hozzá az alaplevet. Keverd hozzá a párolt zöldségeket.', 35, 2, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(16, 'Házi pizza', 'főétel', 'Készíts pizzatésztát, majd kend meg szósszal, és helyezz rá feltéteket. Süsd előmelegített sütőben 15-20 percig.', 75, 4, 'haladó', 1, NULL, '2024-12-13 08:06:45'),
(17, 'Rántott cukkini', 'főétel', 'Szeleteld fel a cukkinit, panírozd be lisztbe, tojásba és zsemlemorzsába, majd süsd aranybarnára.', 25, 3, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(18, 'Rakott krumpli', 'rakott ételek', 'Rétegezd a főtt krumplit, tojást és kolbászt egy tepsiben. Öntsd le tejföllel, és süsd 200 fokon 40 percig.', 80, 6, 'haladó', 2, NULL, '2024-12-13 08:06:45'),
(19, 'Csirkepörkölt', 'húsétel', 'Pirítsd meg a hagymát, majd add hozzá a csirkét és a fűszereket. Főzd puhára, és tálald galuskával.', 70, 5, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(20, 'Vegán brownie', 'diétás desszertek', 'Keverd össze a száraz és nedves hozzávalókat, majd öntsd tepsibe. Süsd 180 fokon 25-30 percig.', 45, 8, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(21, 'Padlizsánkrém', 'vegán', 'Süsd meg a padlizsánt, majd kanalazd ki a húsát. Turmixold össze fokhagymával, citromlével és olívaolajjal.', 30, 4, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(22, 'Vegetáriánus chili', 'vegetáriánus', 'Főzd össze a babot, paradicsomot és fűszereket. Adj hozzá zöldségeket, és tálald rizzsel vagy tortillával.', 50, 4, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(23, 'Gulyásleves', 'levesek', 'Pirítsd meg a hagymát és a húst, majd add hozzá a zöldségeket és a fűszereket. Főzd lassú tűzön puhára.', 120, 8, 'haladó', 1, NULL, '2024-12-13 08:06:45'),
(24, 'Vegán sushi', 'vegán', 'Tölts nori lapokat rizs és zöldségek keverékével, majd tekerd fel és szeleteld. Tálald szójaszósszal.', 60, 4, 'profi', 2, NULL, '2024-12-13 08:06:45'),
(25, 'Zöld turmix', 'smoothiek', 'Turmixold össze a spenótot, banánt, almát és egy kis vizet. Frissen tálald.', 10, 2, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(26, 'Palacsinta', 'palacsinták', 'Keverd össze a hozzávalókat, és süss belőle vékony tésztákat serpenyőben. Töltsd meg ízlés szerint.', 30, 6, 'kezdő', 1, NULL, '2024-12-13 08:06:45'),
(27, 'Vegán quiche', 'vegán', 'Készíts tésztát, majd töltsd meg zöldségekkel és növényi krémmel. Süsd 180 fokon 40-45 percig.', 55, 4, 'közepes', 2, NULL, '2024-12-13 08:06:45'),
(46, 'Mákosguba 2', 'sütemény', 'A kifliket másfél ujjnyi távolságra karikára vágjuk és egy nagy keverőtálba rakjuk.\r\nA tejet, 50 g porcukrot és a vaníliás cukrot összekeverjük egy lábosban és felforraljuk.\r\nA maradék porcukrot összekeverjük a darált mákkal.\r\nA mákos keverék felét rászórjuk a kiflikarikákra és ráöntjük a cukros tej felét. Átkeverjük és a maradék mákot és tejet is hozzáadjuk.\r\nEgy kisebb méretű tepsit (kb. 20cm x 40 cm) kivajazunk és eloszlatjuk benne a mákos gubát.\r\n170 fokra előmelegített sütőben 10 percig sütjük.\r\nA sodóhoz a tojássárgáját alaposan kikeverjük a cukorral, vaníliáscukorral és keményítővel.\r\nRészletekben hozzáadjuk a tejet és vízgőz felett sűrű krémet főzünk belőle.\r\nA mákos gubát vaníliasodóval kínáljuk.', 40, 4, 'közepes', 6, 'a9bc6fca12f28e', '2024-12-18 08:49:48'),
(49, 'Mézeskalács', 'sütemény', '<b>tészta</b>\r\nElőször a száraz hozzávalókat keverjük alaposan össze, majd jöhet a tojás, a méz és a picit megolvasztott margarin. Addig gyúrjuk, míg egynemű, illatos, rugalmas tésztát nem kapunk.\r\n\r\n3 részre osztjuk, majd egyenként kinyújtjuk 0,5 cm-es vastagságúra a tésztát. Kiszaggatjuk, sütőlemezre tesszük, ne szorosan, mert sülés közben összenőnek. Megsütjük.\r\n\r\nmáz\r\n2 tojásfehérjét félig felverünk robotgéppel, majd 30 dkg porcukrot apránként hozzádolgozunk, és keményedésig keverjük.', 40, 6, 'könnyű', 6, '158f4883dd7ac0', '2024-12-18 12:00:56'),
(50, 'Gyrostál', 'főétel', 'A sült krumplihoz a burgonyát meghámozzuk, majd cikkekre vágjuk. Sózzuk, borsozzuk, megszórjuk majoránnával, oregánóval és fokhagymaporral. Leöntjük az olívaolajjal, majd alaposan összekeverjük.\r\n\r\nEgy sütőpapírral bélelt tepsibe rendezzük a krumplikat, majd megszórjuk a pirospaprikával. 200 fokra előmelegített sütőben 20 percig sütjük. Érdemes sütés közben többször is átkeverni, hogy mindenhol egyenletesen süljön.\r\n\r\nA gíroszhús elkészítéséhez a csirkemellet csíkokra vágjuk, megszórjuk a gírosz-fűszerkeverékkel, hozzáadjuk a reszelt fokhagymát és az olívaolajat, majd összeforgatjuk. Kevés olajon megsütjük.\r\n\r\nA szószhoz a joghurtot sózzuk, borsozzuk, majd hozzáadjuk a reszelt fokhagymát, és összekeverjük.\r\nA csirkemellet a sült krumplival, a csíkokra vágott salátával és lila káposztával, a kockákra vágott paradicsommal és a felszeletelt hagymával tálaljuk.\r\n\r\nVégül leöntjük a fokhagymás szósszal, és pitával kínáljuk.', 60, 4, 'közepes', 5, '24d4763595bb19', '2024-12-18 17:59:50'),
(59, 'Gombapaprikás', 'főétel', 'Egy nagyobb serpenyőben összemelegítjük a margarint és az olívaolajat, majd alaposan megpároljuk rajta az aprított hagymát és fokhagymát, kicsit akár karamellizálhatjuk is.\r\n\r\nÍzesítjük sóval, borssal, majorannával (a videóból ez kimaradt), majd hozzáadjuk a szeletelt gombát. Felöntjük egy kevés fehérborral, majd hagyjuk, hogy összeessen a gomba. Akár kevés vizet is önthetünk alá, illetve a bort is cserélhetjük vízre vagy alaplére.\r\n\r\nHa már jól összeesett a gomba, gyakori kevergetés mellett puhára főzzük, és ezzel együtt elfőzzük a leve nagy részét is.\r\n\r\nAmikor a gomba már kellemesen puha, meggszórjuk az egészet egy kevés keményítővel és jó adag füstölt fűszerpaprikával is. Alaposan elkeverjük, majd hozzáadjuk a növényi tejfölt és azzal is alaposan elkavarjuk. Ha nagyon besűrűsödne, kevés vízzel korrigáljuk.\r\n\r\nAzon melegében fogyasszuk jó adag petrezselyemmel megszórva, bulgurral, rizzsel vagy tésztával egyaránt isteni.', 30, 4, 'könnyű', 6, '39fc41bb511e81', '2025-01-01 18:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `recept_id` int(11) DEFAULT NULL,
  `felh_id` int(11) DEFAULT NULL,
  `komment` text DEFAULT NULL,
  `ertekeles` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `recept_id`, `felh_id`, `komment`, `ertekeles`, `created_at`) VALUES
(1, 8, 7, 'Nagyon ízlett a muffin.', 5, '2024-12-18 07:32:49'),
(2, 7, 5, 'Nagyon nehéz volt elkészíteni, de finom volt.', 4, '2024-12-18 07:33:55'),
(25, 7, 1, 'Nagyon finom lett, mindenkinek ízlett!', 5, '2024-12-18 07:46:59'),
(26, 8, 2, 'Nem volt rossz, de lehetne kicsit fűszeresebb.', 3, '2024-12-18 07:46:59'),
(29, 11, 5, 'Elég egyszerű, nem nyűgözött le.', 3, '2024-12-18 07:46:59'),
(30, 12, 6, 'Gyors és finom, biztos újra elkészítem.', 4, '2024-12-18 07:46:59'),
(31, 13, 7, 'Nem az én ízlésemnek való, de lehet másnak bejön.', 2, '2024-12-18 07:46:59'),
(32, 14, 8, 'Kicsit sok időbe telt elkészíteni, de a végeredmény jó lett.', 4, '2024-12-18 07:46:59'),
(33, 15, 9, 'Az egyik kedvenc receptem lett!', 5, '2024-12-18 07:46:59'),
(34, 16, 1, 'Nem volt elég részletes az elkészítési útmutató.', 2, '2024-12-18 07:46:59'),
(35, 17, 2, 'Egyszerű és nagyszerű recept.', 4, '2024-12-18 07:46:59'),
(36, 18, 3, 'Kicsit kevesebb cukorral jobb lenne.', 3, '2024-12-18 07:46:59'),
(38, 20, 5, 'Túl sok hozzávaló kellett, nem érte meg.', 2, '2024-12-18 07:46:59'),
(39, 21, 6, 'A család imádta, biztosan újra megcsinálom!', 5, '2024-12-18 07:46:59'),
(40, 22, 7, 'Szerintem túlságosan bonyolult volt.', 2, '2024-12-18 07:46:59'),
(41, 23, 8, 'Szuper recept, de egy kis extra sóval még jobb lett.', 4, '2024-12-18 07:46:59'),
(42, 24, 9, 'Tökéletes desszert, mindenki imádta.', 5, '2024-12-18 07:46:59'),
(44, 26, 8, 'Nem volt rossz, de nem is kiemelkedő.', 3, '2024-12-18 07:46:59'),
(45, 27, 3, 'Rémesen bonyolult, nem érte meg az időt.', 1, '2024-12-18 07:46:59'),
(46, 46, 8, 'Nagyon tetszett.', 5, '2024-12-18 08:50:55'),
(47, 46, 9, 'Nekem nem annyira tetszett.', 2, '2024-12-18 12:50:16'),
(51, 9, 5, 'Nagyon finom volt a recept.', 5, '2024-12-19 11:57:10'),
(52, 7, 5, '', 2, '2024-12-19 12:03:55'),
(53, 7, 6, 'Szuper recept!', 5, '2024-12-19 12:29:09'),
(54, 8, 10, 'Nagyon szerették az unokáim.', 5, '2024-12-19 12:35:48'),
(55, 12, 10, 'Nem volt túl finom.', 2, '2024-12-19 12:36:41'),
(57, 9, NULL, '', 4, '2024-12-20 12:32:46'),
(59, 49, 5, 'Nagyon finom volt! :)', 4, '2024-12-23 09:12:47'),
(60, 50, 5, 'Nekem a fűszerezése nem annyira tetszett, egyébként rendben volt.', 3, '2024-12-23 09:34:25'),
(64, 59, 6, '', 3, '2025-01-01 18:09:26'),
(75, NULL, 5, '', 4, '2025-01-09 17:06:14'),
(79, 13, 5, '', 5, '2025-01-09 17:09:39'),
(84, 9, 5, '', 3, '2025-01-14 15:29:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`flag`);

--
-- Indexes for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`felh_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  ADD PRIMARY KEY (`hozzavalo_id`),
  ADD KEY `recept_id` (`recept_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`pageKey`),
  ADD KEY `pages_pages_parent_FK` (`parent`);

--
-- Indexes for table `recept`
--
ALTER TABLE `recept`
  ADD PRIMARY KEY (`recept_id`),
  ADD KEY `felh_id` (`felh_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `recept_id` (`recept_id`),
  ADD KEY `felh_id` (`felh_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `felh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  MODIFY `hozzavalo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `recept`
--
ALTER TABLE `recept`
  MODIFY `recept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  ADD CONSTRAINT `hozzavalok_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`);

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_pages_parent_FK` FOREIGN KEY (`parent`) REFERENCES `pages` (`pageKey`);

--
-- Constraints for table `recept`
--
ALTER TABLE `recept`
  ADD CONSTRAINT `felh_id` FOREIGN KEY (`felh_id`) REFERENCES `felhasznalok` (`felh_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`felh_id`) REFERENCES `felhasznalok` (`felh_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
