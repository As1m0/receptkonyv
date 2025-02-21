-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2025 at 01:13 PM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `receptko_ReceptkonyvDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `flag` varchar(100) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`flag`, `content`) VALUES
('about-cover', 'about-cover-img.jpg'),
('about-text', 'Ez a weboldal egy vizsgamunkának indult. Azonban hamar rájöttem, hogy valóban hasznos lehet, mivel a családi receptjeink sok-sok más weboldalon vannak könyvjelzőkbe elmentve.\r\n\r\nEzen az oldalon könnyedén össze tudjuk gyűjteni kedvenc receptjeinket, és kereshetünk köztük, így segítve a jövő heti menü összeállítását.\r\n\r\nA weboldal bizalommal került feltöltésre, hogy bárki hozzáférjen ehhez a könyvtárhoz, és lehetőséget nyújtson kedvenc receptjei feltöltésére, valamint mások receptjeinek böngészésére és véleményezésére.\r\n\r\nHasználd az oldalt egészséggel! :)\r\n\r\nÜdvözlettel, Receptkönyved.hu'),
('account-cover', 'search-background-img.jpg'),
('bg-img', 'background-pattern.png'),
('nav-logo', 'logo.png'),
('search-cover', 'search-background-img.jpg'),
('welcome-text', 'Receptek mindenkinek!');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `recept_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`user_id`, `recept_id`) VALUES
(29, 147);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `felhasznalok`
--

INSERT INTO `felhasznalok` (`felh_id`, `veznev`, `kernev`, `email`, `password_hash`, `pic_name`, `created_at`, `groupMember`) VALUES
(1, 'Ujvárossy', 'Ábel', 'ujvarossyabel@gmail.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', '6952d11dc939d6', '2024-12-12 13:46:07', 1),
(28, 'Ujvarossy', 'Luca', 'ujvarossy.luca@gmail.com', 'e5c02d92c70cf4d20039491d8decbfa795ce5249fdb0d294187fc1b4403b511d', 'fbcc88fda9bd0b', '2025-02-11 15:03:28', 0),
(29, 'Sallay', 'Böbe', 'erzsebet.sallay87@gmail.com', '2bf8c852edfddfcac02e41388e68e115243cddfa05df89090f1b443653d49e57', '3634b4c02ba2d2', '2025-02-13 15:33:51', 0),
(30, 'Ujvárossy', 'Bea', 'ujvarossy@gmail.com', '50205badf92f810ebb94a636c14b7da82bf24c743702c1065755648fcf54610e', NULL, '2025-02-15 10:36:52', 0),
(31, 'Ujvárossy', 'Zsolt', 'ujvarossy.zsolt@gmail.com', '292c13be12318802edb4e26a992d326e305258458bec597ee0b40554963dcfb9', NULL, '2025-02-16 09:10:06', 0),
(38, 'test', 'user', 'testuser@gmail.com', '436972c11b206f80d55dec5b2bb1a26e0d7ad56fbd6ebe2249fc45df643991f5', '2719a13ecf5496', '2025-02-20 08:00:47', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hozzavalok`
--

INSERT INTO `hozzavalok` (`hozzavalo_id`, `recept_id`, `nev`, `mennyiseg`, `mertekegyseg`) VALUES
(247, 83, 'kukoricakonzerv', 2, 'db'),
(248, 83, 'vöröshagyma', 2, 'db'),
(249, 83, 'fokhagyma', 2, 'gerezd'),
(250, 83, 'tejszín', 200, 'ml'),
(251, 83, 'víz', 1, 'liter'),
(252, 83, 'citrom', 1, 'db'),
(253, 83, 'lime', 1, 'db'),
(254, 83, 'só', 1, 'csipet'),
(255, 83, 'bors', 1, 'csipet'),
(256, 83, 'cheddar sajt', 100, 'gramm'),
(257, 84, 'kifli', 5, 'db'),
(258, 84, 'darált mák', 100, 'gramm'),
(259, 84, 'cukor', 4, 'ek'),
(260, 84, 'tej', 4, 'dl'),
(261, 84, 'citrom reszelt héja', 1, 'db'),
(262, 84, 'vaniliás cukor', 10, 'gramm'),
(263, 84, 'krémhez tojássárgája', 4, 'db'),
(264, 84, 'cukor', 4, 'ek'),
(265, 84, 'liszt', 2, 'ek'),
(266, 84, 'tej', 3, 'dl'),
(267, 84, 'tojásfehérje', 4, 'db'),
(268, 85, 'liszt', 40, 'dkg'),
(269, 85, 'sütőpor', 10, 'gramm'),
(270, 85, 'reszelt trapista', 30, 'dkg'),
(271, 85, 'reszelt parmezán', 12, 'dkg'),
(272, 85, 'tejszín', 2, 'dl'),
(286, 87, 'garnéla rák', 500, 'gramm'),
(287, 87, 'paradicsom', 1, 'db'),
(288, 87, 'kígyóuborka', 0.5, 'db'),
(289, 87, 'főtt tojás', 2, 'db'),
(290, 87, 'kukorica konzerv', 0.5, 'db'),
(291, 87, 'újhagyma', 2, 'szál'),
(292, 87, 'piros retek', 3, 'db'),
(293, 87, 'kevert saláta', 1, 'csomag'),
(294, 87, 'tejföl', 4, 'ek'),
(295, 87, 'majonéz', 5, 'ek'),
(296, 87, 'fél citrom leve', 1, 'db'),
(297, 87, 'mozarella golyók', 1, 'db'),
(298, 87, 'só', 1, 'csipet'),
(299, 87, 'bors', 1, 'csipet'),
(300, 87, 'cukor', 1, 'csipet'),
(301, 87, 'fokhagyma', 1, 'gerezd'),
(302, 88, 'édesburgonya', 2, 'db'),
(303, 88, 'currypor', 1, 'tk'),
(304, 88, 'fokhagyma', 2, 'gerezd'),
(305, 88, 'görögjoghurt', 1, 'bögre'),
(306, 88, 'só', 1, 'csipet'),
(307, 88, 'bors', 1, 'csipet'),
(308, 83, 'tortilla chips', 1, 'csomag'),
(309, 89, 'tortilla lap', 1, 'csomag'),
(310, 89, 'csirmell filé', 500, 'gramm'),
(311, 89, 'olaj', 3, 'ek'),
(312, 89, 'szójaszósz', 2, 'ek'),
(313, 89, '3 színű kaliforniai paprika', 1, 'csomag'),
(314, 89, 'lilahagyma', 1, 'db'),
(315, 89, 'kígyóuborka', 1, 'db'),
(316, 89, 'guakamolehoz avokádó', 2, 'db'),
(317, 89, 'citrom leve', 1, 'db'),
(318, 89, 'koktélparadicsom', 10, 'db'),
(319, 89, 'fokhagyma', 1, 'gerezd'),
(320, 89, 'só', 1, 'csipet'),
(321, 89, 'bors', 1, 'csipet'),
(322, 89, 'tejfölös szószhoz tejföl', 1, 'bögre'),
(323, 89, 'fokhagyma', 1, 'gerezd'),
(324, 89, 'rómaikömény', 1, 'csipet'),
(325, 90, 'kenyér', 2, 'szelet'),
(326, 90, 'olivaolaj', 30, 'ml'),
(327, 90, 'vöröshagyma', 450, 'gramm'),
(328, 90, 'fokhagyma', 4, 'gerezd'),
(329, 90, 'csiperke gomba', 200, 'gramm'),
(330, 90, 'fehérbor', 150, 'ml'),
(331, 90, 'alaplé', 1, 'liter'),
(332, 90, 'penne tészta', 350, 'gramm'),
(333, 90, 'parmezán', 110, 'gramm'),
(334, 90, 'citrom leve', 1, 'db'),
(335, 90, 'kakukkfű', 2, 'csipet'),
(336, 90, 'só', 1, 'csipet'),
(337, 90, 'bors', 1, 'csipet'),
(338, 91, 'szeletelt bacon', 200, 'gramm'),
(339, 91, 'liszt', 50, 'gramm'),
(340, 91, 'tesz', 500, 'ml'),
(341, 91, 'vörös cheddar', 250, 'gramm'),
(342, 91, 'vaj', 50, 'gramm'),
(343, 91, 'petrezselyem', 1, 'csokor'),
(344, 91, 'penne', 400, 'gramm'),
(345, 91, 'edami sajt', 200, 'gramm'),
(346, 92, 'lasagne tészta', 9, 'db'),
(347, 92, 'oliva olaj', 70, 'ml'),
(348, 92, 'csiperke gomba', 500, 'gramm'),
(349, 92, 'vöröshagyma', 1, 'db'),
(350, 92, 'bébispenót', 100, 'gramm'),
(351, 92, 'fokhagyma', 4, 'gerezd'),
(352, 92, 'vaj', 50, 'gramm'),
(353, 92, 'liszt', 2, 'ek'),
(354, 92, 'tej', 250, 'ml'),
(355, 92, 'parmezán', 100, 'gramm'),
(356, 92, 'trapista sajt', 200, 'gramm'),
(357, 92, 'só', 1, 'csipet'),
(358, 92, 'bors', 1, 'csipet'),
(359, 92, 'oregánó', 1, 'csipet'),
(360, 93, 'krumpli', 1.5, 'kg'),
(361, 93, 'tojás', 7, 'db'),
(362, 93, 'parasztkolbász', 1, 'szál'),
(363, 93, 'vaj', 50, 'gramm'),
(364, 93, 'liszt', 2, 'ek'),
(365, 93, 'tej', 250, 'ml'),
(366, 93, 'tejföl', 1, 'bögre'),
(367, 93, 'trapista', 100, 'gramm'),
(368, 93, 'só', 1, 'csipet'),
(369, 93, 'bors', 1, 'csipet'),
(370, 94, 'vaj', 250, 'gramm'),
(371, 94, 'keserűcsoki', 200, 'gramm'),
(372, 94, 'cukor', 360, 'gramm'),
(373, 94, 'kakaópor', 80, 'gramm'),
(374, 94, 'liszt', 65, 'gramm'),
(375, 94, 'sütőpor', 1, 'tk'),
(376, 94, 'tojás', 4, 'db'),
(377, 95, 'koktélrák', 400, 'gramm'),
(378, 95, 'vaj', 3, 'ek'),
(379, 95, 'fokhagyma', 3, 'gerezd'),
(380, 95, 'paradicsomsűrítmény', 1, 'doboz'),
(381, 95, 'gnocchi', 500, 'gramm'),
(382, 95, 'alaplé', 200, 'ml'),
(383, 95, 'koktélparadicsom', 300, 'gramm'),
(384, 95, 'habtejszín', 200, 'ml'),
(385, 95, 'mozarella', 3, 'csomag'),
(386, 95, 'só', 1, 'csipet'),
(387, 95, 'bors', 1, 'csipet'),
(388, 95, 'bazsalikom', 1, 'csipet'),
(389, 95, 'petrezselyem', 1, 'csipet'),
(390, 95, 'parmezán', 2, 'ek'),
(391, 95, 'oregánó', 1, 'csipet'),
(392, 96, 'a csirkemellhez: csirkemell', 500, 'gramm'),
(393, 96, 'liszt', 2, 'ek'),
(394, 96, 'só', 1, 'csipet'),
(395, 96, 'bors', 1, 'csipet'),
(396, 96, 'A szószhoz: olaj', 1, 'ek'),
(397, 96, 'fokhagyma', 1, 'gerezd'),
(398, 96, 'friss gyömbér', 1, 'szelet'),
(399, 96, 'víz', 100, 'ml'),
(400, 96, 'szójaszósz', 50, 'ml'),
(401, 96, 'barna cukor', 2, 'ek'),
(402, 96, 'keményítő', 1, 'tk'),
(403, 96, 'tálaláshoz: pirított szezámmag', 2, 'ek'),
(404, 96, 'főtt rízs', 500, 'gramm'),
(405, 97, 'piskótához: tojás', 6, 'db'),
(406, 97, 'cukor', 120, 'gramm'),
(407, 97, 'liszt', 50, 'gramm'),
(408, 97, 'darált mák', 100, 'gramm'),
(409, 97, 'sütőpor', 0.5, 'csomag'),
(410, 97, 'vaj', 2, 'ek'),
(411, 97, 'pudinghoz: tejszíníző vagy vanilliás pudingpor', 2, 'csomag'),
(412, 97, 'cukor', 70, 'gramm'),
(413, 97, 'tej', 1, 'liter'),
(414, 97, 'fehér csoki', 200, 'gramm'),
(415, 97, 'expressz zselatin', 1, 'csomag'),
(416, 97, 'a tejszínhabhoz: Hulala', 350, 'ml'),
(417, 97, 'porcukor', 100, 'gramm'),
(418, 97, 'expressz zselatin', 1, 'csomag'),
(419, 98, 'gomba', 500, 'gramm'),
(420, 98, 'vöröshagyma', 1, 'fej'),
(421, 98, 'sárgarépa', 200, 'gramm'),
(422, 98, 'fehérrépa', 100, 'gramm'),
(423, 98, 'tejföl', 2, 'ek'),
(424, 98, 'pirospaprika', 1, 'tk'),
(425, 98, 'babérlevél', 1, 'db'),
(426, 98, 'galuskához: tojás', 1, 'db'),
(427, 98, 'liszt', 60, 'gramm'),
(428, 98, 'só', 1, 'csipet'),
(429, 99, 'olivaolaj', 1, 'ek'),
(430, 99, 'vöröshagyma', 1, 'fej'),
(431, 99, 'fokhagyma', 3, 'gerezd'),
(432, 99, 'répa', 2, 'db'),
(433, 99, 'csiperke gomba', 500, 'gramm'),
(434, 99, 'száraz fehérbor', 1.5, 'dl'),
(435, 99, 'só', 1, 'csipet'),
(436, 99, 'bors', 1, 'csipet'),
(437, 99, 'pirospaprika', 2, 'csipet'),
(438, 99, 'zöldségalaplé', 750, 'ml'),
(439, 99, 'görögjoghurt', 150, 'ml'),
(440, 99, 'mozarella', 150, 'gramm'),
(441, 99, 'kapor feldarabolva', 1, 'csokor'),
(442, 99, 'citromlé', 1, 'ek'),
(443, 100, 'zöldborsó', 500, 'gramm'),
(444, 100, 'petrezselyem', 1, 'csokor'),
(445, 100, 'alaplé', 2, 'liter'),
(446, 100, 'liszt', 2, 'ek'),
(447, 100, 'sárgarépa', 150, 'gramm'),
(448, 100, 'fehérrépa', 100, 'gramm'),
(449, 100, 'hagyma', 0.5, 'fej'),
(450, 100, 'fokhagyma', 1, 'gerezd'),
(451, 100, 'paradicsom', 1, 'db'),
(452, 100, 'pirospaprika', 3, 'ek'),
(453, 100, 'só', 1, 'csipet'),
(454, 100, 'bors', 1, 'csipet'),
(455, 100, 'cukor', 1, 'csipet'),
(456, 100, 'gluskához: liszt', 150, 'gramm'),
(457, 100, 'tojás', 1, 'db'),
(458, 100, 'só', 1, 'csipet'),
(459, 100, 'víz', 200, 'ml'),
(460, 101, 'csirkemell', 500, 'gramm'),
(461, 101, 'vöröshagyma', 2, 'db'),
(462, 101, 'fokhagyma', 3, 'gerezd'),
(463, 101, 'sárgarépa', 3, 'db'),
(464, 101, 'zeller', 3, 'szál'),
(465, 101, 'fehérrépa', 2, 'db'),
(466, 101, 'vaj', 50, 'gramm'),
(467, 101, 'só', 1, 'csipet'),
(468, 101, 'bors', 1, 'csipet'),
(469, 101, 'tárkony', 1, 'csipet'),
(470, 101, 'petrezselyem', 1, 'csipet'),
(471, 101, 'liszt', 1, 'ek'),
(472, 101, 'víz', 3, 'liter'),
(473, 101, 'krumpli', 3, 'db'),
(474, 101, 'levestészta', 1, 'marék'),
(475, 101, 'főzőtejszín', 2, 'dl'),
(478, 103, 'tortellini', 1, 'csomag'),
(479, 103, 'vaj', 50, 'gramm'),
(480, 103, 'liszt', 2, 'ek'),
(481, 103, 'paradicsomsűrítmény', 1, 'doboz'),
(482, 103, 'alaplé', 3, 'dl'),
(483, 103, 'főzőtejszín', 3, 'dl'),
(484, 103, 'kaliforniai paprika', 1, 'db'),
(485, 103, 'trapista sajt', 150, 'gramm'),
(486, 103, 'bazsalikom', 1, 'csokor'),
(487, 103, 'oregánó', 2, 'csipet'),
(488, 103, 'só', 1, 'csipet'),
(489, 103, 'bors', 1, 'csipet'),
(491, 105, 'spagetti', 300, 'gramm'),
(492, 105, 'vöröshagyma', 80, 'gramm'),
(493, 105, 'sárgarépa', 70, 'gramm'),
(494, 105, 'fokhagyma', 3, 'gerezd'),
(495, 105, 'ázsiai zöldségmix keverék', 400, 'gramm'),
(496, 105, 'csirkemell', 400, 'gramm'),
(497, 105, 'tojás', 3, 'db'),
(498, 105, 'szójaszósz', 50, 'ml'),
(499, 105, 'méz', 1, 'tk'),
(500, 105, 'őrölt gyömbér', 1, 'tk'),
(501, 105, 'pörkölt földimogyoró', 100, 'gramm'),
(502, 105, 'só', 1, 'csipet'),
(503, 105, 'bors', 1, 'csipet'),
(504, 106, 'alaphoz: daráltkeksz', 250, 'gramm'),
(505, 106, 'olvasztott vaj', 150, 'gramm'),
(506, 106, 'krémhez: mascarpone', 250, 'gramm'),
(507, 106, 'natur krémsajt', 250, 'gramm'),
(508, 106, 'tejföl', 250, 'gramm'),
(509, 106, 'liszt', 2, 'ek'),
(510, 106, 'cukor', 150, 'gramm'),
(511, 106, 'vaniliás cukor', 2, 'csomag'),
(512, 106, 'citrom héja', 1, 'db'),
(513, 106, 'tojás', 3, 'db'),
(514, 107, 'vaj', 100, 'gramm'),
(515, 107, 'étolaj', 2, 'ek'),
(516, 107, 'vöröshagyma', 5, 'fej'),
(517, 107, 'fokhagyma', 6, 'gerezd'),
(518, 107, 'só', 1, 'csipet'),
(519, 107, 'bors', 1, 'csipet'),
(520, 107, 'kakukkfű', 1, 'csipet'),
(521, 107, 'alaplé', 1.5, 'liter'),
(522, 107, 'habtejszín', 200, 'ml'),
(523, 107, 'cheddar sajt', 150, 'gramm'),
(524, 108, 'camambert sajt', 2, 'csomag'),
(525, 108, 'tojás', 2, 'db'),
(526, 108, 'só', 1, 'csipet'),
(527, 108, 'búzaliszt', 1, 'pohár'),
(528, 108, 'zsemlemorzsa', 1, 'pohár'),
(529, 108, 'áfonyalekvár', 1, 'doboz'),
(558, 111, 'olaj', 3, 'ek'),
(559, 111, 'vöröshagyma', 1, 'fej'),
(560, 111, 'sárgarépa', 1, 'szál'),
(561, 111, 'angolzeller', 2, 'szál'),
(562, 111, 'darált marha', 500, 'gramm'),
(563, 111, 'vörösbor', 150, 'ml'),
(564, 111, 'passata', 250, 'ml'),
(565, 111, 'só, bors', 1, 'csipet'),
(566, 111, 'szerecsendió', 1, 'csipet'),
(567, 111, 'babérlevél', 2, 'db'),
(568, 111, 'fokhagyma', 2, 'gerezd'),
(569, 111, 'kakukkfű', 1, 'csipet'),
(570, 111, 'rozmaring', 1, 'csipet'),
(571, 111, 'Worcester-szósz', 1, 'tk'),
(572, 111, 'víz', 3, 'dl'),
(573, 111, 'tej', 1, 'dl'),
(574, 111, 'spagetti', 400, 'gramm'),
(575, 111, 'parmezán', 50, 'gramm'),
(576, 112, 'spárga', 500, 'gramm'),
(577, 112, 'nagy póréhagyma', 1, 'db'),
(578, 112, 'olivaolaj', 3, 'ek'),
(579, 112, 'liszt', 5, 'dkg'),
(580, 112, 'cukor', 2, 'tk'),
(581, 112, 'só', 1, 'tk'),
(582, 112, 'bors', 1, 'csipet'),
(583, 112, 'szerecsendió', 1, 'csipet'),
(584, 112, 'krumpli felkockázva', 25, 'dkg'),
(585, 112, 'alaplé', 8, 'dl'),
(586, 112, 'főzőtejszín', 2, 'dl'),
(587, 112, 'citrom leve', 0.5, 'db'),
(597, 114, 'cukkini', 2, 'db'),
(598, 114, 'feta sajt', 1, 'csomag'),
(599, 114, 'koktélparadicsom', 1, 'doboz'),
(600, 114, 'tojás', 2, 'db'),
(601, 114, 'petrezselyem', 1, 'csomag'),
(602, 114, 'só, bors', 1, 'csipet'),
(603, 114, 'trapista sajt', 200, 'gramm'),
(604, 115, 'penne', 350, 'gramm'),
(605, 115, 'olivaolaj', 50, 'ml'),
(606, 115, 'fokhagyma', 5, 'gerezd'),
(607, 115, 'szardella', 1, 'doboz'),
(608, 115, 'fekete olivabogyó', 150, 'gramm'),
(609, 115, 'zöld olivabogyó', 150, 'gramm'),
(610, 115, 'koktélparadicsom', 200, 'gramm'),
(611, 115, 'paradicsomszósz', 300, 'ml'),
(612, 115, 'mozarella golyók', 250, 'gramm'),
(613, 115, 'trapista', 200, 'gramm'),
(614, 115, 'só, bors', 1, 'csipet'),
(615, 116, 'liszt', 250, 'gramm'),
(616, 116, 'tojás', 1, 'db'),
(617, 116, 'tejcsoki', 100, 'gramm'),
(618, 116, 'margarin', 100, 'gramm'),
(619, 116, 'banán', 1, 'db'),
(620, 116, 'cukor', 120, 'gramm'),
(621, 116, 'sütőpor', 3, 'tk'),
(622, 116, 'fahéj', 1, 'tk'),
(623, 116, 'tej', 2.5, 'dl'),
(624, 116, 'só', 1, 'csipet'),
(645, 119, 'vaj', 50, 'gramm'),
(646, 119, 'étcsoki', 150, 'gramm'),
(647, 119, 'zabkeksz', 150, 'gramm'),
(648, 119, 'mandula', 60, 'gramm'),
(649, 119, 'pisztácia', 60, 'gramm'),
(650, 119, 'narancshéj', 1, 'db'),
(651, 119, 'holland kakaópor', 2, 'ek'),
(652, 120, 'apróra vágott csirkemell', 500, 'gramm'),
(653, 120, 'kevert sajt', 200, 'gramm'),
(654, 120, 'elősütött bacon', 6, 'szelet'),
(655, 120, 'krémsajt', 1, 'csomag'),
(656, 120, 'fokhagymapor', 1, 'tk'),
(657, 120, 'só, bors', 1, 'csipet'),
(658, 120, 'tortilla lap', 2, 'csomag'),
(659, 120, 'olvasztott vaj', 50, 'gramm'),
(660, 120, 'majonéz', 1, 'pohár'),
(661, 120, 'tejföl', 1, 'pohár'),
(662, 120, 'trapista sajt', 150, 'gramm'),
(663, 120, 'só, bors', 1, 'csipet'),
(672, 122, 'érett avokádó', 2, 'db'),
(673, 122, 'görög joghurt vagy tejföl', 1, 'ek'),
(674, 122, 'lime leve', 1, 'db'),
(675, 122, 'olivaolaj', 1, 'tk'),
(676, 122, 'só, bors', 1, 'csipet'),
(677, 123, 'tejföl', 6, 'ek'),
(678, 123, 'mustár', 2, 'ek'),
(679, 123, 'citrom leve', 0.5, 'db'),
(680, 123, 'csirkecomb', 700, 'gramm'),
(681, 123, 'vöröshagyma', 1, 'fej'),
(682, 123, 'sárgarépa', 3, 'db'),
(683, 123, 'zellerszár', 3, 'db'),
(684, 123, 'fokhagyma', 3, 'gerezd'),
(685, 123, 'só, bors', 1, 'csipet'),
(686, 123, 'citrom héja', 0.5, 'db'),
(687, 123, 'fűszerpaprika', 1, 'tk'),
(688, 123, 'babérlevél', 1, 'db'),
(689, 123, 'víz', 1, 'dl'),
(690, 123, 'csigatészta', 400, 'gramm'),
(691, 124, 'gomba', 200, 'gramm'),
(692, 124, 'vöröshagyma', 0.5, 'db'),
(693, 124, 'fokhagyma', 1, 'gerezd'),
(694, 124, 'petrezselyemzöld', 1, 'ek'),
(695, 124, 'tejföl', 2, 'ek'),
(696, 124, 'főzőtejszín', 1, 'dl'),
(697, 124, 'liszt', 1, 'ek'),
(698, 124, 'só, bors', 1, 'csipet'),
(699, 124, 'vaj', 1, 'ek'),
(704, 127, 'krumpli', 1, 'kg'),
(705, 127, 'camambert', 250, 'gramm'),
(706, 127, 'vöröshagyma', 2, 'fej'),
(707, 127, 'bacon egész', 200, 'gramm'),
(708, 127, 'fehérbor', 1, 'dl'),
(709, 127, 'tejföl', 350, 'gramm'),
(710, 127, 'só, bors', 1, 'csipet'),
(711, 128, 'fokhagyma', 1, 'fej'),
(712, 128, 'olivaolaj', 30, 'ml'),
(713, 128, 'vaj', 50, 'gramm'),
(714, 128, 'só, bors', 1, 'csipet'),
(715, 128, 'póréhagyma', 1, 'szál'),
(716, 128, 'vöröshagyma', 2, 'fej'),
(717, 128, 'alaplé', 1.5, 'liter'),
(718, 128, 'szerecsendió', 1, 'csipet'),
(719, 128, 'főzőtejszín', 150, 'ml'),
(720, 128, 'szelet kenyér', 4, 'db'),
(721, 128, 'trapista sajt', 150, 'gramm'),
(722, 129, 'olaj', 100, 'ml'),
(723, 129, 'paradicsom', 1, 'db'),
(724, 129, 'paprika', 1, 'db'),
(725, 129, 'fokhagyma', 5, 'db'),
(726, 129, 'só, bors', 1, 'csipet'),
(727, 129, 'vöröshagyma', 2, 'fej'),
(728, 129, 'pirospaprika', 2, 'ek'),
(729, 129, 'csiperke gomba', 1, 'kg'),
(730, 129, 'tejföl', 100, 'gramm'),
(731, 129, 'főzőtejszín', 100, 'gramm'),
(732, 129, 'liszt', 1, 'ek'),
(733, 130, 'gouda sajt', 200, 'gramm'),
(734, 130, 'friss petrezselyem', 1, 'csokor'),
(735, 130, 'maradék sonka', 100, 'gramm'),
(736, 130, 'só, bors', 1, 'csipet'),
(737, 130, 'fokhagymapor', 1, 'tk'),
(738, 130, 'liszt', 4, 'ek'),
(739, 130, 'bundázáshoz: tojás', 2, 'db'),
(740, 130, 'liszt', 150, 'gramm'),
(741, 130, 'zsemlemorzsa', 200, 'gramm'),
(742, 130, 'só, bors', 1, 'csipet'),
(743, 131, 'liszt', 400, 'gramm'),
(744, 131, 'sütőpor', 4, 'tk'),
(745, 131, 'só', 1, 'csipet'),
(746, 131, 'porcukor', 2, 'ek'),
(747, 131, 'tojás', 6, 'db'),
(748, 131, 'vaj', 500, 'gramm'),
(749, 131, 'tej', 4, 'dl'),
(750, 131, 'díszítéshez: banán', 5, 'db'),
(751, 131, 'áfonya', 1, 'csomag'),
(752, 131, 'gyümölcs lekvár', 6, 'ek'),
(753, 131, 'olaj', 3, 'ek'),
(754, 131, 'sajt', 3, 'szelet'),
(786, 134, 'tojás', 2, 'db'),
(787, 134, 'cukor', 50, 'gramm'),
(788, 134, 'rögös túrü', 500, 'gramm'),
(789, 134, 'olvasztott vaj', 2, 'ek'),
(790, 134, 'só', 1, 'csipet'),
(791, 134, 'citrom héja', 0.5, 'db'),
(792, 134, 'búzadara', 220, 'gramm'),
(793, 134, 'zsemlemorzsa', 200, 'gramm'),
(794, 135, 'zöldalma', 2, 'db'),
(795, 135, 'zellerszár', 4, 'db'),
(796, 135, 'konzerv kukorica', 1, 'db'),
(797, 135, 'kis lilahagyma', 1, 'db'),
(798, 135, 'friss petrezselyem', 1, 'csokor'),
(799, 135, 'só, bors', 1, 'csipet'),
(800, 135, 'majonéz', 5, 'ek'),
(801, 135, 'tejföl', 5, 'ek'),
(802, 135, 'tej', 1, 'dl'),
(813, 136, 'magos mustár', 2, 'ek'),
(814, 136, 'méz', 2, 'ek'),
(815, 136, 'fokhagyma', 3, 'gerezd'),
(816, 136, 'csirkemell', 500, 'gramm'),
(817, 136, 'bacon kockázva', 150, 'gramm'),
(818, 136, 'főzővíz', 1, 'dl'),
(819, 136, 'főzőtejszín', 4, 'dl'),
(820, 136, 'tészta', 250, 'gramm'),
(821, 136, 'újhagyma', 3, 'szál'),
(822, 136, 'só, bors', 1, 'csipet'),
(907, 132, 'vöröshagyma', 1, 'fej'),
(908, 132, 'fokhagyma', 4, 'gerezd'),
(909, 132, 'darált marhahús', 400, 'gramm'),
(910, 132, 'babérlevél', 3, 'db'),
(911, 132, 'fahéj', 1, 'csipet'),
(912, 132, 'oregánó', 2, 'tk'),
(913, 132, 'só, bors', 1, 'csipet'),
(914, 132, 'vörösbor', 200, 'ml'),
(915, 132, 'víz', 300, 'ml'),
(916, 132, 'passata', 350, 'gramm'),
(917, 132, 'darabolt paradicsom konzerv', 400, 'gramm'),
(918, 132, 'padlizsán', 600, 'gramm'),
(919, 132, 'krumpli', 300, 'gramm'),
(920, 132, 'olaj', 4, 'ek'),
(921, 132, 'zsemlemorzsa', 4, 'ek'),
(922, 132, 'vaj - Besamelhez', 40, 'gramm'),
(923, 132, 'finomliszt', 40, 'gramm'),
(924, 132, 'tej', 400, 'ml'),
(925, 132, 'tojás sárgája', 2, 'db'),
(926, 132, 'só, bors', 1, 'csipet'),
(927, 132, 'reszelt szerecsendió', 0.3, 'db'),
(928, 132, 'fetasajt', 75, 'gramm'),
(929, 110, 'foghagyma', 2, 'gerezd'),
(930, 110, 'vöröshagyma', 2, 'fej'),
(931, 110, 'csirkecomb', 4, 'db'),
(932, 110, 'tv paprika', 2, 'db'),
(933, 110, 'fűszerpaprika', 2, 'ek'),
(934, 110, 'libazsír', 3, 'ek'),
(935, 110, 'paradicsom', 2, 'db'),
(936, 110, 'só', 3, 'csipet'),
(937, 110, 'víz', 300, 'ml'),
(938, 110, 'tejföl', 200, 'gramm'),
(939, 110, 'búzaliszt', 1, 'ek'),
(940, 110, 'szeretet', 1, 'csipet'),
(941, 138, 'tojás', 6, 'db'),
(942, 138, 'vaj', 50, 'gramm'),
(943, 138, 'tejföl', 2, 'ek'),
(944, 138, 'majonéz', 2, 'ek'),
(945, 138, 'mustár', 1, 'tk'),
(946, 138, 'újhagyma', 3, 'szál'),
(947, 138, 'só, bors, zöldfűszerek', 1, 'csipet'),
(955, 140, 'padlizsán', 800, 'gramm'),
(956, 140, 'olaj', 4, 'ek'),
(957, 140, 'só, bors', 1, 'csipet'),
(958, 140, 'fokhagyma', 3, 'gerezd'),
(959, 140, 'vöröshagyma', 1, 'fej'),
(960, 140, 'fehérbor', 1, 'dl'),
(961, 140, 'passata', 700, 'gramm'),
(962, 140, 'víz', 0.6, 'dl'),
(963, 140, 'tészta', 500, 'gramm'),
(964, 140, 'trapista', 200, 'gramm'),
(965, 141, 'cukkini', 2, 'db'),
(966, 141, 'étolaj', 2, 'ek'),
(967, 141, 'natur sajtkrém', 200, 'gramm'),
(968, 141, 'bazsalikom', 1, 'csokor'),
(969, 141, 'fokhagyma', 2, 'gerezd'),
(970, 141, 'újhagyma', 1, 'szál'),
(971, 141, 'petrezselyem', 0.5, 'csokor'),
(972, 141, 'só, bors', 1, 'csipet'),
(973, 142, 'vaj, olivaolaj', 2, 'ek'),
(974, 142, 'bacon', 6, 'szelet'),
(975, 142, 'vöröshagyma', 1, 'fej'),
(976, 142, 'fokhagymapor', 1, 'tk'),
(977, 142, 'fűszerpaprika', 1, 'tk'),
(978, 142, 'só, bors', 1, 'csipet'),
(979, 142, 'szárított paradicsom', 250, 'gramm'),
(980, 142, 'fokhagyma', 4, 'gerezd'),
(981, 142, 'krémsajt', 100, 'gramm'),
(982, 142, 'főzőtejszín', 150, 'ml'),
(983, 142, 'brokkoli', 1, 'fej'),
(984, 142, 'főzővíz', 1, 'dl'),
(985, 142, 'tészta', 300, 'gramm'),
(986, 142, 'trapista sajt', 200, 'gramm'),
(987, 143, 'krumpli', 1, 'kg'),
(988, 143, 'csirkecombfilé', 500, 'gramm'),
(989, 143, 'étolaj', 3, 'ek'),
(990, 143, 'vöröshagyma', 1, 'fej'),
(991, 143, 'fokhagyma', 2, 'gerezd'),
(992, 143, 'só, bors', 1, 'csipet'),
(993, 143, 'kakukkfű', 1, 'tk'),
(994, 143, 'alaplé', 300, 'ml'),
(995, 143, 'citrom leve', 0.5, 'db'),
(996, 143, 'habtejszín', 200, 'ml'),
(997, 143, 'camambert', 1, 'csomag'),
(998, 143, 'snidling', 1, 'csokor'),
(999, 139, 'friss paradicsom', 1, 'kg'),
(1000, 139, 'lilahagyma', 1, 'fej'),
(1001, 139, 'friss petrezselyem', 1, 'csokor'),
(1002, 139, 'hideg víz', 500, 'ml'),
(1003, 139, 'porcukor', 3, 'ek'),
(1004, 139, 'ecet', 100, 'ml'),
(1005, 139, 'só, bors', 1, 'csipet'),
(1041, 109, 'liszt', 50, 'dkg'),
(1042, 109, 'só', 1, 'ek'),
(1043, 109, 'élesztő', 2, 'dkg'),
(1044, 109, 'tej', 500, 'ml'),
(1045, 109, 'cukor', 5, 'gramm'),
(1046, 109, 'olaj', 5, 'ek'),
(1047, 109, 'víz', 3, 'dl'),
(1048, 109, 'oregánó', 1, 'tk'),
(1049, 109, 'bors', 1, 'csipet'),
(1050, 109, 'olaj - szószhoz', 50, 'ml'),
(1051, 109, 'vöröshagyma', 1, 'fej'),
(1052, 109, 'fokhagyma', 1, 'gerezd'),
(1053, 109, 'sűrített paradicsom', 70, 'gramm'),
(1054, 109, 'só, bors', 1, 'csipet'),
(1055, 109, 'cukor', 1, 'ek'),
(1056, 109, 'gyömbér por', 1, 'tk'),
(1057, 109, 'gomba', 500, 'gramm'),
(1058, 145, 'spagetti tészta', 500, 'gramm'),
(1059, 145, 'tojás', 2, 'db'),
(1060, 145, 'tojássárgája', 2, 'db'),
(1061, 145, 'parmezán', 150, 'gramm'),
(1062, 145, 'házi szalonna vagy bacon', 250, 'gramm'),
(1063, 145, 'só, bors', 1, 'csipet'),
(1064, 146, 'kígyóuborka', 2, 'db'),
(1065, 146, 'só', 1, 'tk'),
(1066, 146, 'tejföl', 1, 'pohár'),
(1067, 146, 'ecet', 1, 'ek'),
(1068, 146, 'fokhagyma', 1, 'gerezd'),
(1069, 146, 'cukor', 2, 'tk'),
(1070, 146, 'bors', 1, 'csipet'),
(1071, 146, 'pirospaprika', 2, 'csipet'),
(1072, 147, 'a csirkepáchoz: csirkecombfilé', 500, 'gramm'),
(1073, 147, 'tejföl', 150, 'ml'),
(1074, 147, 'római kömény', 1, 'tk'),
(1075, 147, 'kurkuma', 1, 'tk'),
(1076, 147, 'fűszerpaprika', 1, 'tk'),
(1077, 147, 'só, bors', 1, 'tk'),
(1078, 147, 'szerecsendió', 1, 'tk'),
(1079, 147, 'fahéj', 1, 'tk'),
(1080, 147, 'étolaj', 2, 'ek'),
(1081, 147, 'a raguhoz: vöröshagyma', 1, 'fej'),
(1082, 147, 'étolaj', 2, 'ek'),
(1083, 147, 'fokhagyma', 3, 'gerezd'),
(1084, 147, 'gyömbér', 3, 'szelet'),
(1085, 147, 'passata', 200, 'gramm'),
(1086, 147, 'víz', 50, 'ml'),
(1087, 147, 'só, bors', 1, 'csipet'),
(1088, 147, 'szerecsendió', 0.5, 'tk'),
(1089, 147, 'fahéj', 0.5, 'tk'),
(1090, 147, 'főzőtejszín', 100, 'ml'),
(1091, 147, 'koriander', 1, 'csokor'),
(1092, 147, 'tortilla lap', 1, 'csomag'),
(1093, 148, 'liszt', 500, 'gramm'),
(1094, 148, 'cukor', 4, 'ek'),
(1095, 148, 'élesztő', 25, 'gramm'),
(1096, 148, 'tej', 2.5, 'dl'),
(1097, 148, 'tojás sárgája', 3, 'db'),
(1098, 148, 'vaj', 70, 'gramm'),
(1099, 148, 'citrom héja', 1, 'db'),
(1100, 148, 'só', 1, 'csipet'),
(1101, 148, 'olaj', 3, 'ek'),
(1109, 149, 'padlizsán', 2, 'db'),
(1110, 149, 'füstölt paprika', 0.5, 'tk'),
(1111, 149, 'őrőlt római kömény', 1, 'tk'),
(1112, 149, 'fokhagyma', 2, 'gerezd'),
(1113, 149, 'tahini', 3, 'ek'),
(1114, 149, 'petrezselyem', 0.3, 'csokor'),
(1115, 149, 'citrom', 0.5, 'db'),
(1116, 149, 'só', 1, 'csipet'),
(1117, 118, 'áfonya', 180, 'gramm'),
(1118, 118, 'tojás', 2, 'db'),
(1119, 118, 'vaj', 100, 'gramm'),
(1120, 118, 'cukor', 100, 'gramm'),
(1121, 118, 'vaniliás cukor', 2, 'csomag'),
(1122, 118, 'liszt', 150, 'gramm'),
(1123, 118, 'só', 1, 'csipet'),
(1124, 118, 'sütőpor', 1, 'tk'),
(1125, 118, 'görög joghurt', 5, 'dkg'),
(1126, 118, 'krémsajt', 5, 'dkg'),
(1141, 150, 'paradicsomsalátához: paradicsom', 6, 'db'),
(1142, 150, 'lilahagyma', 1, 'db'),
(1143, 150, 'fokhagyma', 1, 'db'),
(1144, 150, 'víz', 1, 'liter'),
(1145, 150, 'ecet', 200, 'ml'),
(1146, 150, 'kristálycukor', 5, 'ek'),
(1147, 150, 'só', 1, 'ek'),
(1148, 150, 'galuska', 500, 'gramm'),
(1149, 150, 'szalonna', 50, 'gramm'),
(1150, 150, 'fokhagyma', 4, 'gerezd'),
(1151, 150, 'tejföl', 300, 'gramm'),
(1152, 150, 'túró', 250, 'gramm'),
(1153, 150, 'só, bors', 1, 'csipet'),
(1154, 150, 'kapor', 1, 'csokor'),
(1155, 151, 'darálthús keverék', 500, 'gramm'),
(1156, 151, 'tortilal chips', 1, 'csomag'),
(1157, 151, 'tejföl', 1, 'pohár'),
(1158, 151, 'só', 1, 'tk'),
(1159, 151, 'chilipehely', 0.5, 'tk'),
(1160, 151, 'fűszerpaprika', 1, 'tk'),
(1161, 151, 'római kömény', 1, 'tk'),
(1162, 151, 'oregánó', 1, 'tk'),
(1163, 151, 'bazsalikom', 2, 'tk'),
(1164, 151, 'babérlevél', 1, 'db'),
(1165, 151, 'őrölt gyömbér', 1, 'tk'),
(1166, 151, 'kakukkfű', 1, 'tk'),
(1167, 151, 'fahéj', 1, 'tk'),
(1168, 151, 'bab', 1, 'doboz'),
(1169, 151, 'kukorica', 1, 'doboz'),
(1170, 133, 'cukkini', 500, 'gramm'),
(1171, 133, 'só, bors', 1, 'csipet'),
(1172, 133, 'petrezselyem', 0.5, 'csokor'),
(1173, 133, 'bazsalikom', 0.5, 'csokor'),
(1174, 133, 'fokhagyma', 2, 'gerezd'),
(1175, 133, 'panko morzsa', 50, 'gramm'),
(1176, 133, 'cheddar sajt', 50, 'gramm'),
(1177, 133, 'tojás', 1, 'db'),
(1178, 133, 'keményítő', 2, 'ek'),
(1179, 121, 'liszt', 250, 'gramm'),
(1180, 121, 'tojás', 4, 'db'),
(1181, 121, 'tej', 350, 'ml'),
(1182, 121, 'vaj', 50, 'gramm'),
(1183, 121, 'só', 1, 'csipet'),
(1184, 121, 'cukor', 80, 'gramm'),
(1185, 121, 'vaniliás cukor', 1, 'csomag'),
(1186, 121, 'porcukor', 100, 'gramm'),
(1187, 117, 'vöröshagyma', 2, 'fej'),
(1188, 117, 'fokhagyma', 6, 'gerezd'),
(1189, 117, 'darálthús', 500, 'gramm'),
(1190, 117, 'majoranna', 2, 'ek'),
(1191, 117, 'só, bors', 1, 'csipet'),
(1192, 117, 'fűszerpaprika', 1, 'ek'),
(1193, 117, 'paradicsom', 2, 'db'),
(1194, 117, 'zöldbab', 500, 'gramm'),
(1195, 117, 'rízs', 150, 'gramm'),
(1196, 117, 'tejföl', 500, 'gramm');

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
('favorites', 'favorites.html', 0, 'FavoritesPage', 'indexGroup', 1, 0),
('index', 'main.html', 0, 'IndexPage', 'indexGroup', 1, 0),
('indexGroup', 'index.html', 1, '', NULL, 1, 0),
('login', 'login.html', 0, 'LoginPage', 'indexGroup', 1, 0),
('recept-aloldal', 'recept-datasheet.html', 0, 'receptDatasheetPage', 'indexGroup', 1, 0),
('recept-feltoltes', 'recept-feltoltes.html', 0, 'UploadPage', 'indexGroup', 1, 0),
('receptek', 'receptek.html', 0, 'ReceptekPage', 'indexGroup', 1, 0),
('register', 'register.html', 0, 'RegisterPage', 'indexGroup', 1, 0),
('searchLog', 'search-log.html', 0, 'searchLogPage', 'adminGroup', 1, 1),
('update-recepie', 'update-recepie.html', 0, 'UpdateRecepiePage', 'indexGroup', 1, 0),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recept`
--

INSERT INTO `recept` (`recept_id`, `recept_neve`, `kategoria`, `leiras`, `elk_ido`, `adag`, `nehezseg`, `felh_id`, `pic_name`, `created_at`) VALUES
(83, 'kukorica krémleves', 'leves', 'A konzerv kukoricát leszűrjük, átöblítjük, lecsepegtetjük, majd egy sütőpapírral bélelt tepsiben szétterítjük. Meglocsoljuk kevés olajjal, sózzuk, borsozzuk, kicsit átkeverjük és 230 fokos sütőbe tesszük grill fokozatra, amíg meg nem pirul (kb 7-8perc ).\r\n\r\nEgy kevés olajon elkezdjük dinsztelni a durvára vágott vöröshagymát, hozzáadunk egy egész fokhagymát és elkeverjük. Kivesszük a sütőből a lepirított kukoricát és egy keveset félreteszünk belőle a tálaláshoz. A többi kukoricát a lábasba öntjük és jól átforgatjuk az egészet. Mehet bele a tejszín, majd hozzáöntjük a vizet és leturmixoljuk az egészet. Ízlés szerint ekkor mehet még bele só, bors vagy cayenne bors és egy fél citrom leve. Végül beletesszük a reszelt sajtot és tálaljuk. Mehet rá a kukorica, a csirke, a felszeletelt jalapeno paprika, petrezselyem, egy kevés tejföl, egy szelet lime és egy marék tortilla chips.', 40, 4, 'könnyű', 29, '9a9e51c25d71c1', '2025-02-13 15:38:43'),
(84, 'Mákosguba', 'Édesség', '1\r\nA kifliket felkarikázom, a tejet a cukorral és vaníliás cukorral összemelegítem, ráöntöm a kiflire, beleteszem a citromhéjat és darált mákot, jól összekeverem, félre teszem.\r\n\r\n2\r\nA tojás sárgáját a cukorral és liszttel csomómentesre keverem, apránként hozzáadom a tejet és állandó keverés mellett, sűrűre főzöm.\r\n\r\n3\r\nA tojásfehérjét kemény habbá verem, majd kanalanként hozzáadom a cukrot.\r\n\r\n4\r\nKivajazott hőálló tálba teszem a mákos kiflit, a krémet eloszlatom a tetején, végül bevonom a tojáshabbal, 180 C-ra előmelegített sütőben 15 perc alatt készre sütöm.', 35, 4, 'közepes', 29, 'd6441d103d3cb4', '2025-02-13 15:44:41'),
(85, 'Sajtgolyók', 'előétel', 'A lisztet elkeverem a sütőporral, hozzáadom a reszelt sajtokat és a tejszínt, majd alaposan összedolgozom.\r\nA tésztából tetszőleges méretű golyókat formázok és 180 fokra előmelegített sütőben körülbelül 18 perc alatt aranybarnára sütöm.A sütési idő függ a tésztagolyók méretétől. Sonkával és ízlés szerint zöldségekkel kínálom.', 30, 4, 'könnyű', 29, 'a96a721c448ea0', '2025-02-13 15:49:02'),
(87, 'Ráksaláta', 'saláta', '1\r\nHozzávaló zöldséget megmosni és apró kockára vágni. Addig a tojást megfőzni keményre. A mozarella sajtot is beleteszem citrom levével meg locsolom.\r\n2\r\nA salátát fűszerezzük a majonézt kikeverem a tejföllel és cukorral ízlés szerint sózom és borsózom. A főtt tojást tisztítom és felkockázom. A garnélarákot vajon és fokhagymán megpirítom és felaprítom és a főtt tojással a salátához adom. Öntettel összekeverem\r\n3\r\nTálalom pirítóssal vagy ön magába is nagyon finom', 30, 4, 'könnyű', 29, '0dc66f40179857', '2025-02-13 16:05:09'),
(88, 'currys édesburgonya leves', 'leves', 'Az édesburgonyát meghámozom, felkockázom és egy lábasba teszem. Sózom, borsozom, hozzáadom a curryport, a fokhagymát, felengedem annyi vízzel ami ellepi és fedő alatt puhára főzöm.\r\n\r\nAmikor a burgonya megpuhult, hozzáadom a joghurtot/kókuszkrémet, botmixerrell pürésítem.\r\n\r\nTovábbi víz hozzáadásával tetszőleges állagúra higítható.', 30, 4, 'könnyű', 29, '68f64d2c5c0c60', '2025-02-13 16:08:30'),
(89, 'Csirkés, tejfölös tortilla', 'főétel', 'Куриное филе маринуем от получаса в растительном масле и соевом соусе, из специй добавляем сушёный чеснок и перец по 0,5 чайной ложки. Также можете добавить любимые специи по вкусу. Пока филе маринуется нарезаем все овощи соломкой.\r\n\r\nДалее обжариваем филе до полуготовности и добавляем к нему овощи: болгарский перец и лук. Продолжаем обжаривать до готовности курицы и мягкости овощей.\r\n\r\nДля соуса гуакамоле мякоть авокадо сбрызгиваем лимонным соком и разминаем вилкой, добавляем нарезанные томаты и хорошо перешиваем. Можно добавить соль и перец по вкусу.\r\n\r\nДля сметанного соуса смешиваем сметану, измельчённый чеснок и нарезанную петрушку.\r\n\r\nШаурму собираем как на видео: лаваш смазываем белым соусом, выкладываем курицу, сверху гуакамоле, огурец и снова белый соус. Сворачиваем и поджариваем, чтобы лаваш был хрустящим.\r\n\r\nШаурма получается очень вкусной и сочной. Приятного аппетита ❤️', 40, 4, 'közepes', 29, '7f81a9e3a6442a', '2025-02-13 16:18:15'),
(90, 'Francia hagymakrémleves penne', 'tészta', 'A francia hagymakrémes penne első lépéseként két szelet kenyeret alaposan meglocsolunk olívaolajjal és 200 fokra előmelegített sütőbe dobjuk 8-10 percre úgy, hogy félidőnél megfordítjuk. Mikor szép aranybarna, kikapjuk a sütőből és félretesszük.\r\n\r\nEgy lábasban felolvasztjuk a vajat, majd rádobjuk a félkarikára vágott hagymát és alaposan megkaramellizáljuk, kb. 5-6 perc alatt. Ezután mehet bele a felaprított fokhagyma és gomba, majd további 3-4 percig pirítjuk. Alaposan befűszerezzük sóval, borssal és friss kakukkfűvel, majd felöntjük fehérborral, amit közepes lángon hagyunk rotyogni 2-3 percet, hogy elpárologjon az alkoholtartalma. Ezután felöntjük alaplével és mikor felforrt az alapunk, mehet hozzá a tészta.\r\n\r\nA tésztánk 5-6 perc alatt al dentére fő, ekkor alacsony lángra kapcsolunk és feltuningoljuk annyi parmezánnal, amennyit nem szégyellünk. Alaposan elkeverjük, hogy elolvadjon benne a sajt, majd hogy balanszoljuk az ízeket, mehet bele egy citrom leve, és készen is vagyunk a francia hagymakrémes pennével. Vagyis készen lennénk, DE a megpirított kenyérből csinálunk egy izgi, ropogós textúrát a tésztánkra, hogy az eredeti recepthez hűen ehhez a kajához is kerüljön pirítós, mint a leveshez.\r\n\r\nA kenyereket kisebb darabokra törjük, majd egy kevés sóval és fokhagyma-granulátummal porállagúvá zúzzuk egy késes aprító segítségével.\r\n\r\nTálalásnál reszelünk még rá parmezánt, valamint megszórjuk a kenyérmorzsával és friss kakukkfűvel.', 35, 4, 'közepes', 29, 'b420090ead2bc9', '2025-02-13 19:50:41'),
(91, 'dupla sajtos tészta baconnal', 'tészta', 'A dupla sajtos tészta elkészítéséhez először a bacont 1 cm-es csíkokra vágjuk és közepes hőfokon kb. 8 perc alatt kisütjük a zsírját egy lábasban, majd félrerakjuk a szalonnát.\r\n\r\nA visszamaradt zsírhoz adjuk a lisztet és közepesen erős hőfokon fél perc alatt lepirítjuk. 2-3 részletben felöntjük a tejjel és egy habverő segítségével csomómentesre keverjük. Hozzáadjuk a lereszelt cheddar sajtot és addig melegítjük, amíg el nem olvad – ez kb. a forráspontot jelenti. Ha szükséges, sózzuk, de nekem nem hiányzott. Hozzáadjuk a vajat, majd a finomra vágott petrezselymet elkeverjük benne.\r\n\r\nA tésztát sós, bő, lobogó vízben fogkeményre főzzük a csomagoláson feltüntetett idő alatt, majd leszűrjük és a szószba keverjük. Tálalásnál a tésztát megszórjuk a reszelt edami sajttal és a korábban lepirított baconnel.', 20, 4, 'könnyű', 29, '2c5b2d8a59d3ac', '2025-02-13 19:55:39'),
(92, 'Gombás lasagne', 'tészta', 'Lasagna tésztákat vízbe áztatjuk.\r\nGombát sóval, borssal kis oregánóval kis olajon megpirítjuk, majd félretesszük.\r\nBasamel mártást készítünk: vöröshagyma, 4 fej fokhagyma apróra, vaj, tej, és liszttel besűríteni.\r\nRétegek:\r\nAlulra beáztatott lasagna lapok, majd besamell, aztán gomba, bébispenót. Minden rétegre kis parmezán.\r\nBesamell, gomba + sok trapista a tetejére. \r\nAlufóliával letakarjuk és 200 fokon 20 perc alatt készre sütjük. Levesszük róla az alufóliát és még 5 percet rápirítunk a sajtra.', 30, 4, 'könnyű', 29, 'bc0a1c2548f2e9', '2025-02-13 20:07:57'),
(93, 'Rakott krumpli', 'főétel', 'A krumplit sózott, forró vízben, héjában megfőzöm (kb. 20 perc).\r\n\r\n5 db tojást keményre főzök (forrástól számítva 10 perc), hideg vízbe teszem. A kolbászról lehúzom a bőrét, és karikára szeletelem.\r\n\r\nGyorsforralóban felhevítem a vajat, habverővel elkeverek benne 2 púpos evőkanál lisztet, picit pirítom, majd felöntöm a tejjel. Ízesítem sóval, 1 evőkanál vegetával. Mézszerűre beforralom állandó keverés mellett. Tűzről lehúzom, beleteszem a tejfölt, egy tojás sárgáját, és a reszelt sajtot. Elkeverem, kész az öntet.\r\n\r\nKivajazok egy tepsit, a burgonyát meghámozom, nem túl vékonyan szeletelem. Elkezdem a rétegezést:\r\n\r\nEgy sor krumpli szelet, 1 sor főtt tojáskarika, 1 sor parasztkolbász szeletke. Közben krumplit és a tojáskarikákat enyhén megszórom vegetával. A mártás 1/3-ával meglocsolom.\r\n\r\nMásodik sor ugyanaz, végül a legtetejére krumpli karikák kerüljenek csak, és a maradék öntet!\r\n\r\nElőmelegített sütőben, 200C fokon, alsó-felsőállásnál 50-60 perc alatt szép rózsásra sütöm. Ha idő előtt pirulna a teteje, takarjuk le egy alufóliával.', 60, 4, 'haladó', 29, 'cc22f523180db9', '2025-02-13 20:17:19'),
(94, 'Brownie', 'sütemény', 'Melegítsd elő a sütőt 180 fokra, öntsd egy tálba a kristálycukrot, szitáld hozzá a lisztet, sütőport és a kakaóport.\r\n\r\nGőzfürdő fölött, folyamatosan keverve olvaszd meg a vajat és a csokit, amíg szép fényes krémet nem kapsz.\r\n\r\nA brownie-hoz szokás diót vagy aszalt cseresznyét adni, ezek ropogóssága vagy savanykássága kellmesen ellensúlyozza a brownie jó értelemben vett csokis töménységét. Ha diót vagy szárított cseresznyét is teszel bele, akkor most add hozzá a csokihoz.\r\n\r\n\r\nVedd le a csokit a gőzről, és keverd hozzá a száraz anyagokat (liszt, cukor, sütőpor, kakaó).\r\n\r\nAdd hozzá a tojásokat egyenként, és keverd el őket teljesen a csokis masszában. Amikor az utolsó tojást is hozzáadtad, az egész csodálatosan csillogó krémmé áll össze, ami alig tapad az edény falához és leginkább arra \r\n\r\nSütőpapírral bélelj ki egy tűzálló tálat, és öntsd bele a csokis masszát.\r\n\r\nLegalább 5 cm magas, kb. 25*25 cm vagy 18*30 cm-es tűzálló edényt használj. Ebben a méretben a brownie egész magas lesz (kb. 5 cm).\r\n\r\nAz igazán jó brownie a sütésen múlik, és nagyon könnyű elrontani. Ez az a süti, amit semmiképp sem szabad teljesen átsütni – a száraz, megsült brownie inkább fulladást mint élvezetet okoz.\r\n\r\nA sütés titka igazából a tapasztalat, hogy hány percig legyen bent az függ a sütőtől és a brownie magasságától. A magasabb változatnak kb. 33-35 perc kell, vékonyabb brownie-nál 25 perc is elég. Én nem használok hozzá légkeverést, szerintem a brownie-t tönkreteszi, ha túlságosan feljön.', 35, 4, 'könnyű', 29, 'e3ca8b358d7360', '2025-02-13 20:22:25'),
(95, 'Toszkán-tejszínes gnocchi', 'tészta', 'Gnocchit készre főzzük.\r\nKoktélrákot fokhagymás vajon megpirítom.\r\nVajra rá az apró fokhagymát, paradicsomsűrítményt, hozzáadom a felvágott koktélparit. Majd sózom, borsozom.\r\nJöhet hozzá az alaplé + tejszín + parmezánnal megszórom. \r\nFűszerezem: oregánó, bazsalikom, petrezselyem. \r\nSűrűre főzöm, majd hozzáadom a koktélrákot és a gnocchit.', 30, 4, 'könnyű', 29, '1fff62cca0d955', '2025-02-13 20:33:42'),
(96, 'Teriyaki csirkemell', 'főétel', 'A teriyaki csirkemell elkészítéséhez először a csirkemellet 7-8 cm-es darabokra vágjuk, sózzuk, borsozzuk, és lisztbe forgatjuk. Egy kevés olajon mindkét oldalát aranybarnára sütjük. Ha kicsit több rajta az olaj, akkor leitatjuk róla.\r\n\r\nA szószhoz egy gyorsforralóban felmelegítjük az olajat, ráreszeljük a fokhagymát és a gyömbért, majd kicsit megpirítjuk. A keményítő kivételével mindent belerakunk (a vizet, a szójaszószt, a cukrot, a mirint és a rizsecetet), felforraljuk, ezután a keményítőt kikeverjük egy kevés vízzel, a szószhoz öntjük, és összeforraljuk.\r\n\r\nA húst visszadobjuk a serpenyőbe, majd ráöntjük a szószt és összemelegítjük. Végül megszórjuk szezámmaggal és vékonyra szeletelt újhagymával, majd főtt rizzsel tálaljuk.', 45, 4, 'közepes', 29, '1a9c0092973dc8', '2025-02-14 08:43:51'),
(97, 'Mákos krémes', 'sütemény', 'A piskótához való tojásokat kettéválasztjuk. A fehérjékhez hozzáadjuk a cukrot és kemény habbá verjük robotgéppel. Ezután beleszitáljuk a lisztet, és hozzáadjuk a mákot, a rumaromát és a sütőport. Óvatosan összeforgatjuk egy spatulával, hogy ne törjük össze a habot. A sárgájákat félretesszük, azokat majd a pudinghoz fogjuk felhasználni. A tepsit kikenjük a vajjal, beleöntjük a piskótatésztát és 180 fokra előmelegített sütőben 15 perc alatt készre sütjük. Óvatosan kiemeljük a tepsiből és rácsra téve hagyjuk szoba-hőmérsékletűre hűlni.\r\n\r\nEgy nagyobb gyorsforralóba tesszük a pudingport, a cukrot és a félretett  tojássárgájákat, majd robotgéppel kihabosítjuk. Hozzáöntjük a tejet, egy habverő segítségével csomómentesre keverjük és közepes lángon elkezdjük forralni. Folyamatosan kevergetjük addig, amíg besűrűsödik, ez forrástól számítva 1-2 perc. Ekkor lehúzzuk a tűzről és belekeverjük a fehér csokoládét. Miután teljesen felolvadt, szoba-hőmérsékletűre hűtjük, aztán belekeverjük az expressz zselatint és hűtőhidegre hűtjük.\r\n\r\nA tejszínt és porcukrot robotgéppel kemény habbá verjük, majd mikor megkeményedett, hozzáadjuk az expressz zselatint és ezt is beledolgozzuk.\r\n\r\nA tepsit, amiben a piskótát sütöttük, elmossuk, majd kibéleljük folpackkal úgy, hogy kilógjon a tepsiből minden oldalon egy nagyobb darab, így könnyen ki tudjuk majd emelni a süteményt belőle. Az alján lévő fóliára egy darab sütőpapírt helyezünk, amit pontosan akkorára vágunk, mint a tepsi alja. Ezután belerakjuk a piskótát, erre rákenjük a fehér csokis krémet, a tetejére pedig a kemény habbá vert tejszínt. Elsimítjuk szépen a tetejét, majd hűtőbe rakjuk minimum 8 órára. Végül kiemeljük a formából, levágjuk a széleit és egyenlő méretű négyzetekre szeletelve tálaljuk.', 60, 4, 'haladó', 29, '41fcf5470b9df7', '2025-02-14 08:49:18'),
(98, 'Gombaleves', 'leves', 'A klasszikus gombaleves készítése nem túl bonyolult folyamat, az eredmény pedig tuti befutó lesz! Első lépésként az olajon üvegesre pároljuk a finomra vágott hagymát, hozzáadjuk a karikákra vágott sárgarépát és fehérrépát, és alacsony lángon 10 percig dinszteljük. Közben a gombát 3-4 mm vastag szeletekre vágjuk, majd hozzáadjuk a zöldségekhez, és újabb 3 percig pároljuk. Meghintjük pirospaprikával, sózzuk, borsozzuk, fűszerezzük, elkeverjük, felöntjük 1,2 liter vízzel és alacsony lángon főzni kezdjük a levest. Forrástól számított 15 perc után a tejfölt és a keményítőt alaposan összekeverjük. Hozzámerünk 4-5 evőkanálnyi levet, kikeverjük, és visszaöntjük a levesbe.\r\n\r\nGaluska:\r\nA tojást kikeverjük egy csipet sóval és annyi liszttel, hogy laza galuska állagot kapjunk és  beleszaggatjuk a levesbe, további 3-4 percig főzzük. Végül durvára vágott petrezselymet szórunk a levesbe, levesszük a tűzről, és pár perc pihentetés után tálaljuk is.', 35, 4, 'közepes', 29, '02e2b3ba75f373', '2025-02-14 08:55:06'),
(99, 'Krémes gombaleves kaporral', 'leves', 'A hagymát pirítsuk üvegesre az olajon, majd adjuk hozzá a fokhagymát, és együtt is pirítsuk őket 1 percig. Adjuk hozzá a répát és a gombát, és közepes hőfokon, gyakran átkeverve addig pirítsuk, amíg a gomba aranybarnára sül, a répa pedig közepesen megpuhul. Ez kb. 5-6 percet vesz igénybe. \r\n\r\nKeverjük hozzá a fehérbort, és főzzük 2-3 percig, majd sózzuk, borsozzuk, ízesítsük pirospaprikával, és öntsük fel az alaplével. Közepes hőfokon 10 percig főzzük. \r\n\r\nHa felforrt, keverjünk belőle keveset a kókuszjoghurthoz, majd csurgassuk vissza a levesbe. Adjuk hozzá a reszelt mozzarellát, a kaprot és a citromlevet is, és addig keverjük, amíg a sajt teljesen elolvad.\r\n\r\nVegyük le a tűzről, és azonnal fogyasszuk.', 35, 4, 'közepes', 29, 'ced94b6d9e73f5', '2025-02-14 08:59:19'),
(100, 'Borsóleves galuskával', 'leves', 'A borsóleves elkészítéséhez a répát és a fehérrépát felkarikázzuk, majd a kis kockákra vágott hagymával és a zöldborsóval együtt egy lábasba tesszük. Felöntjük kb. 700 ml vízzel, belekanalazzuk a zsírt, és elkezdjük főzni. Hozzáadjuk az aprított fokhagymát, sózzuk, cukrozzuk, majd beletesszük a paprikát és a gerezdekre vágott paradicsomot, illetve a petrezselymet is.\r\n\r\nAddig főzzük, míg a zöldségek meg nem puhulnak. Ekkor a leves tetejére szórjuk a lisztet, ezzel sűrítve a levét, majd megszórjuk pirospaprikával is. Elkeverjük, majd felöntjük vízzel, beállítva a sűrűségét, és végül felforraljuk.\r\n\r\nEgy kisebb tálban összekeverjük a galuska hozzávalóit, és ha felforrt a leves, egy deszka és egy kés segítségével beleszaggatjuk a galuskát. Ezt követően további 2-3 percig főzzük, majd lekapcsoljuk alatta a tűzhelyet. Tányérba szedjük a levest, és rákanalazzuk a tejfölt.', 30, 4, 'könnyű', 29, 'c3d577e8e1e52e', '2025-02-14 12:05:43'),
(101, 'Mindent bele tárkonyos leves', 'leves', 'Csirkemellet kevés olajon sóval, borsóval megpirítom, félreteszem.\r\nUtána olajon hagyma, fokhagyma, zöldségek. Felöntöm a vízzel, fűszerezem. Mehet bele a főzőtejszín.\r\nPuhára főzőm a zöldségeket, majd mehet bele a félretett felkockázott csirkemell, és 1 marék levestészta. \r\nPár percig még főzöm és kész.', 40, 4, 'közepes', 29, '28bef36176923f', '2025-02-14 12:12:48'),
(103, 'Tortellini', 'tészta', 'A vajat felolvasztom, rádobom a zúzott fokhagymát, pár másodpercet pirítom, majd rászórom a lisztet, zsemle színűre pirítom, hozzáadom a paradicsompürét, majd egy kézi habverővel, folyamatosan keverve elkezdem lassan hozzáönteni az alaplevet.\r\n\r\nAmikor már az összeset hozzáöntöttem és szép sima a szósz, fűszerezem, felöntöm a tejszínnel, hozzáadom az apró kockára vágott paprikát, a reszelt sajtot, alacsony hőmérsékleten, folyamatos kevergetés mellett tetszőleges sűrűségűre forralom, végül mehet bele a kifőtt és lecsepegtetett tortellinit.\r\n\r\nÍzlés szerint petrezselyemzölddel megszórva kínálom. Adhatunk hozzá friss zöldsalátát is.', 30, 4, 'könnyű', 29, 'dfcfcd1a1bc5b6', '2025-02-14 12:18:37'),
(105, 'Kamu pad-thai', 'főétel', 'A kamu pad thai pontosan azt mutatja be, hogy nem kell megijedni, ha nincs otthon mindenféle alapanyagotok egy ételhez, simán feltalálhatjátok magatokat a kamrában talált dolgokból.\r\n\r\nA tésztát forrásban lévő, sós vízben készre főzzük, majd leszűrjük és egyből lehűtjük. A hagymát és a répát vékony csíkokra, a fokhagymát apróra vágjuk. A fagyasztott zöldségmixet kiolvasztjuk, majd amennyire csak tudjuk, leszárítjuk. Tűzforró serpenyőben, 4-5 evőkanál olajon először a vékony csíkokra vágott csirkét pár percig. Ezután az összes zöldséget alaposan lepirítjuk. Ügyeljünk arra, hogy a lehető legmagasabb hőmérsékletet használjuk folyamatosan (füstölögjön az a serpenyő!!!!).\r\n\r\n Amikor aranybarnára pirultak a zöldségek, félrehúzzuk őket, és beleütünk a serpenyőbe 2 egész tojást, kicsit összekeverve szárazra pirítjuk. Ez után jöhet még 4-5 evőkanál olaj és a tészta. Pár percig pirítjuk még, ráöntjük a szójaszószt, hozzáadjuk a mézet, a chilit, a gyömbért és a földimogyorót, borsozzuk, összeforgatjuk, és 2-3 perc pirítás után készen is vagyunk.', 50, 4, 'haladó', 29, '60706ef410e6c6', '2025-02-14 12:23:25'),
(106, 'Sajttorta', 'sütemény', 'A darált kekszet az olvasztott vajjal összekverem sütőformába beteszem. 160 fokos előmelegített sütőbe 10 percre beteszem.\r\nKözben összekverem a krém eleimeit. Tojásokat egyesével hozzáadom. Ráteszem a krémet az alapra, madj 150 fokos sütőben 40 percig sütöm. Lassan kell kihűteni! Először csak a sütő ajtaját nyitom ki, majd kiveszem és csak utána mehet a hűtőbe 1 éjszakára.', 50, 4, 'közepes', 29, '6697d46cf67a9c', '2025-02-14 12:30:14'),
(107, 'Karamelizált hagymaleves', 'leves', 'A karamellizált hagymaleves elkészítésének első lépése, hogy egy lábasban felmelegítjük a vajat és az olajat közepes hőmérsékleten, és ezen fogjuk lassan megkaramellizálni a félfőre vágott vöröshagymát, valamint a szeletelt fokhagymát. Ez kb. 20-25 percet is igénybe vehet, de legyünk türelmesek, mert megéri a fáradozást.\r\n\r\nTehát ha szép aranybarnára sült a hagymánk, akkor sózzuk, borsozzuk, jöhet bele a kakukkfű, és felöntjük az alaplével, majd közepes hőmérsékleten, 20 percet rotyogtatjuk a levest. A végén már csak a tejszínt kell hozzáadjuk, és még 2 percig forraljuk. \r\n\r\nTányérba szedjük, megszórjuk finomra vágott snidlinggel, és reszelt cheddar sajttal tálaljuk.', 30, 4, 'könnyű', 29, '18652b2d7a29bb', '2025-02-14 12:35:47'),
(108, 'Rántott camambert', 'főétel', 'A camembert sajtot előzőleg jól hűtsük be, hogy szépen tudjuk szeletelni, majd egy éles késsel vágjuk 4 egyenlő cikkre.\r\nEgy tálban egy nagyobb csipet sóval keverjük ki a tojást.\r\n\r\nA tálban készítsük elő a szokásos panírozáshoz való anyagokat. A zsemlemorzsát, a lisztet, és a sóval kikevert tojást.\r\n\r\nEkkor már az étolajat is odatehetjük melegedni a serpenyőben. 1-5, 2 cm mély legyen csak, hogy a sajtok feléig érjen.\r\n\r\nA sajtcikkeket forgassuk lisztbe először.\r\n\r\nMajd forgassuk a tojásba.\r\n\r\nIsmét a liszt következik.\r\n\r\nMásodszor is tojásba mártjuk.\r\n\r\nVégül pedig a zsemlemorzsába. (Sokan írják a kommentelők közül, hogy ilyenkor még fagyasztóba teszik a bepanírozott sajtot sütés előtt, hogy biztosan ne follyon ki.)\r\n\r\nAir fryerben 180 fokon 7-7 perc olalanként', 40, 4, 'közepes', 29, 'cb1c9befb86775', '2025-02-14 12:42:50'),
(109, 'Gyömbéres Pizza', 'főétel', '1) 1/2 dl langyos, cukros tejben felfuttatjuk az élesztőt.\r\n\r\n2) A liszthez öntjük, és a többi hozzávalóval együtt közepesen lágy tésztát készítünk. Kelni hagyjuk.\r\n\r\n3) A szószhoz felmelegítjük az olajat, belereszeljük a hagymát, és megdinszteljük. Mikor majdnem kész, a zúzott fokhagymát is hozzátesszük.\r\n\r\n4) Belekeverjük a paradicsomsűrítményt és 1 kis konzervdoboznyi vizet, fűszerezzük, és jól összefőzzük az egészet.\r\n\r\n5) A megkelt pizza tésztát tepsibe lapítjuk, rákenjük a pizza szószt, tetszőleges feltétet teszünk rá, és megszórjuk reszelt sajttal.\r\n\r\n6) 200 fokos hőségkeveréses sütőben 20 perc alatt készre sütjük.', 60, 6, 'könnyű', 1, '63fb231f71bff8', '2025-02-14 13:14:35'),
(110, 'Csirkepaprikás', 'főétel', '1) A fokhagymát apróra vágjuk, a vöröshagymát, paprikát felkockázzuk, paradicsomot felcikkezzük.\r\n\r\n2) A csirkecombokat az izületnél elvágjuk.\r\n\r\n3) A zsírt megolvasztjuk, majd rátesszük a felkockázott paprikát elsőként. Pirítjuk, míg kis színt kap. Azért így csináljuk, mert a sült paprikának jobb íze van, mint a főttnek, és ha később tesszük bele, csak főni tud.\r\n\r\n4) Hozzáadjuk a felkockázott vöröshagymát, és a fokhagymát is. Megfonnyasztjuk, zsírjára pirítjuk. A &quot;zsírjára pirítjuk&quot; kifejezés azt jelenti, hogy a hagymából, és a paprikából már elpárolog a víz, és csak a zsír marad alatta.\r\n\r\n5) Elzárjuk alatta a hőt, hozzáadjuk a fűszerpaprikát. Alaposan elkeverjük benne. Hozzáadjuk a paradicsomot. Felöntjük kb. fél dl vízzel, csak hogy a paprika ne keseredjen meg. A hőt visszakapcsoljuk alatta.\r\n\r\n6) Beletesszük a csirkecombokat, átkeverjük.\r\n\r\n7) Felöntjük annyi vízzel, amennyi a csirkecombok magasságának kb. a feléig ér. Megsózzuk, és lefedve főzzük tovább. Főzés közben az elfőtt vizet mindig pótoljuk.\r\n\r\n8) Kb. 60 perc főzés után behabarjuk. Ehhez egy tálba tesszük a tejfölt. Kikeverjük a liszttel.\r\n\r\n9) Hozzáteszünk egy keveset a csirkepaprikás forró levéből, és azzal is kikeverjük. Ezt nevezzük hőkiegyenlítésnek.\r\n\r\n10) A liszttel, főzőlével kikevert tejfölt visszacsurgatjuk a csirkecombokra, összekeverjük, és pár percen át főzzük.\r\n\r\n11) Ezzel el is készült a csirkepaprikás. Nokedlivel tálaljuk. Jó étvágyat!', 60, 4, 'könnyű', 1, '55841016514f9b', '2025-02-14 18:31:23'),
(111, 'Bolognai spagetti', 'tészta', 'A bolognai spagetti a világ egyik legegyszerűbb kajája, mégis sokan készítik porból. Egy olyan receptet mutatunk, ami szerintünk TÖKÉLETES, de persze ha nektek már megvan a jól bevált változatotok, amit az egész család imád, miattunk ne változtassatok rajta. \r\n\r\nAz olajon üvegesre pároljuk az apróra vágott vöröshagymát, majd hozzádobjuk a lereszelt sárgarépát és angol zellert és együtt pirítjuk őket: ez a soffritto. Amikor már kicsit oda is kapott, hozzáadjuk a darált húst, “fehéredésig” pirítjuk, majd a vörösbor és a passzírozott paradicsom következik.\r\n\r\nFűszerezzük ízlés szerint sóval, borssal, szerecsendióval, hozzáadjuk a babérlevelet, a zúzott fokhagymát, a kakukkfüvet és a rozmaringot, és opcionálisan kevés Worcester-szószt is. Felöntjük a vízzel, és alacsony lángon egy órán át rotyogtatjuk. Titkos hozzávalóként beleöntjük a tejet, és még 10 percig főzzük. Tökéletes, al dentére főtt tésztán tálaljuk, reszelt parmezánnal!', 40, 4, 'könnyű', 29, '4dfa824e5ceba9', '2025-02-16 08:18:34'),
(112, 'spárgakrémleves', 'leves', 'A spárgát tisztítsuk meg: csak a fás részeket kell eltávolítani!! Daraboljuk 2-3 cm-es darabokra, és tegyük félre.\r\n\r\nAz olívaolajon pirítsuk 2 percig a póréhagymát, majd szórjuk rá a lisztet, a cukrot, a sót, a borsot és a szerecsendiót, és alaposan keverjük el, hogy a póréhagymát mindenhol befedje.\r\n\r\nAdjuk hozzá a krumplit és a spárgát, folyamatosan keverve, közepes hőfokon 1-2 percig pirítsuk, majd több részletben adjuk hozzá az alaplevet (vagy vizet), és keverjük csomómentesre.\r\n\r\nFedő alatt főzzük 15 percig, vagy amíg a zöldségek teljesen meg nem puhulnak. Adjuk hozzá a kókuszkrémet és a citromlevet, vegyük le a tűzről, és botmixerrel dolgozzuk selymesre.\r\n\r\nMég melegen tálaljuk, pirítóssal vagy házi krutonnal fogyasszuk.', 25, 4, 'könnyű', 29, 'd0ce244990ba50', '2025-02-16 08:25:08'),
(114, 'Feta sajttal töltött cukkini', 'főétel', 'A cukkinikat kettévágjuk, egy kiskanállal beljesejét kikaparjuk és félretesszük.\r\nA fetasajtot és a koktélparadicsomokat felkockázzuk, majd a tojással és petrezselyemmel együtt olajon megpirítjuk. Hozzáadjuk a cukkini belsejét, és a petrezselyemet is, sózzuk, borsozzuk. \r\nA kész töltelékkel megtöltjuk a kikapart cukkinikat és sajtot reszelünk rájuk.\r\n200 fokra előmelegített sütőbe 30 percre betesszük a cukkinikat.', 30, 4, 'könnyű', 29, '570c5dc78d15d2', '2025-02-16 08:46:52'),
(115, 'sült puttanesca', 'tészta', 'Egy lábasban vizet melegítünk, majd amikor forrni kezd, alaposan megsózzuk. Beletesszük a száraz tésztát, és lefedve, időnként megkeverve 11 perc alatt készre főzzük.\r\nA kész tészta főzővizéből félreteszünk egy csészényit, majd leszűrjük a tésztát.\r\n\r\nEgy tűzálló serpenyőben olívaolajat melegítünk, majd picit megfuttatjuk rajta az aprított fokhagymát, szardellát és chilipelyhet. Addig főzzük, ameddig szét nem mállik az olajban.\r\nBelekeverjük a kapribogyót és az aprított olajbogyókat.\r\n\r\nMehetnek hozzá a színpompás koktélparadicsomok és a paradicsomszósz is. Az egészet alaposan átkeverjük, és kis lángon tovább főzzük néhány percig.\r\nA leszűrt tésztát a paradicsomos alapra borítjuk, hozzáöntünk kb. 1 deci főzővizet, és jól átforgatjuk.\r\nGazdagon megszórjuk mozzarellagolyókkal és reszelt mozzarellával.\r\n\r\nSózzuk-borsozzuk ízlés szerint, majd betoljuk a 180 fokos sütőbe 20-25 percre.\r\nFrissen, melegen tálaljuk friss aprított petrezselyemmel szórva, olívaolajjal meglocsolva.', 60, 4, 'haladó', 29, '147dc8af65c2b4', '2025-02-16 09:26:20'),
(116, 'csokis banános muffin', 'sütemény', 'A sütőt előmelegítjük 175 fokra és a muffintepsibe beletesszük a papírkapszlikat.\r\n\r\nA vajat a mikróban folyósra olvasztjuk, és amíg kihűl, a csokoládét apró, de észrevehető méretű darabokra vágjuk.\r\nA tésztához a lisztet, a sütőport, a szódabikarbónát, az őrölt fahéjat, a szerecsendiót, a sót és a csokidarabokat összekeverjük. Ezután a banánt villával pépesre nyomkodjuk.\r\n\r\nA tojást, a tejet, a cukrot és az olvadt, kihűlt vajat kézi habverővel simára dolgozzuk egy edényben.\r\n\r\nÖsszeállítjuk a tésztát: a lisztes keverékhez hozzáöntjük a tejes-tojásos-cukros keveréket, fakanállal nagyjából összevegyítjük, aztán hozzáadjuk a banánpépet, és még keverünk rajta párat.\r\n\r\nA tésztát szétosztjuk a muffintepsiben, és a muffinokat kb. 25 perc alatt megsütjük.', 35, 12, 'könnyű', 29, 'ea6ce67355fd86', '2025-02-16 09:35:04'),
(117, 'Rakott zöldbab', 'rakott étel', 'A legfinomabb rakott zöldbab első lépéseként a húsos ragut készítjük el. Ehhez egy lábosban felolvasztjuk a zsírt, és közepes lángon megpirítjuk rajta az apróra vágott vöröshagymát és fokhagymát. Ezután mehet rá a kétféle darált hús. Ezeket is átpirítjuk a hagymákkal, folyamatos kevergetés mellett, hogy ne álljon  össze „gombóccá” a darálékunk. Ha ezzel is megvagyunk, mehetnek rá a fűszerek és az apró kockára vágott paradicsom, és alacsony lángon 20 percig főzzük a ragut. Ha szükséges, kevés vizet adhatunk hozzá, hogy ne legyen teljesen száraz.  \r\n\r\nKözben a zöldbabot sós vízben ressre főzzük és leszűrjük. Azért nem kell teljesen puhára készíteni, mert sütés közben is tovább fog puhulni. A rizst pedig bő, sózott vízben puhára főzzük, majd leszűrjük.  \r\n\r\nHa mindezekkel megvagyunk, elkezdjük összeállítani a rétegeket. Először a zöldbab felét helyezzük a sütőtálba, amit megkenünk a tejföl felével. Erre mehet rá a rizs, szépen elegyengetjük, majd a húsos ragu következik. Végezetül az egészet beborítjuk a maradék zöldbabbal és tejföllel. És már mehet is be a 180 fokra előmelegített sütőbe 40 percre. \r\n\r\nMiután elkészült, 10 perc pihentetés után tányérokba szedjük egy jó adag tejföl és az apróra vágott snidling kíséretében', 40, 4, 'közepes', 29, 'e14bdbf008da7d', '2025-02-16 09:40:30'),
(118, 'Áfonyás, krémsajtos muffin', 'sütemény', 'Az áfonyákat mossuk meg és csepegtessük le.\r\n\r\nA tojásokat a vajjal és a cukrokkal keverjük habosra.\r\n\r\nA lisztet szitáljuk tálba, és forgassuk egybe a sóval és a sütőporral. Ezután apránként adjuk a tojásos keverékhez a joghurttal és a krémsajttal együtt.\r\n\r\nA simára kevert masszához az áfonya felét is forgassuk hozzá. Ezután adagoljuk papírral bélelt muffintepsibe.\r\n\r\nA maradék áfonyát pakoljuk a formába adagolt masszára.\r\n\r\nA muffint toljuk 180 fokosra előmelegített sütőbe, és tűpróbáig süssük (körülbelül 20-25 perc).', 20, 12, 'könnyű', 29, '0621aebd5a077f', '2025-02-16 09:48:42'),
(119, 'kaka süti', 'sütemény', 'A vajat a csokival megolvasztom és félreteszem.\r\nEgy tálba keverem az apróra tört zabkekszet, mandulát, pisztáciát, hozzáadom a fűszereket, majd mehet rá a csokikrém. \r\nFolpakba kiöntöm a keveréket, rúdszerúre formázom, becsomagolom és mehet a hűtőbe minimum 1 órára.\r\nKiveszem, porcukor a tetejére és már kész is.', 30, 4, 'könnyű', 29, '9a6cb7582e24cd', '2025-02-16 09:55:40'),
(120, 'Csirkés sajtos tortilla sütőben', 'főétel', 'Az összetevőket összekeverem egy tálban, majd megtöltöm velük a tortilla lapokat.\r\nA kész tortillákat egy jénaitálba teszem a tetejüket megkenem olvasztott vajjal. \r\n200 fokra előmelegített sütőbe beteszem a tortillákat 10 percre\r\nAmíg a tortillák sülnek a reszelt trapistát összekeverem a majonézzel és a tejföllel, sózom borsozom. Majd a tortillák tetejére teszem a keveréket.\r\nVisszateszem a tortillákat 10 percre és készre sütöm őket.', 45, 4, 'közepes', 29, '31d5f9a47ae681', '2025-02-16 10:05:25'),
(121, 'Császármorzsa', 'Édesség', '2. lépés\r\nVálasszuk szét a tojásokat, ügyelve arra, hogy könnyedén megtörhetnek, és nem szeretnénk, hogy a tojássárgája belekerüljön a tojásfehérjének a tartályába.\r\n\r\n3. Lépés\r\nDolgozzuk össze a tojássárgáját a pudingporral, sóval és a tejjel. Dig mixerrel keverjük össze, nem kell teljesen simának lennie. Csepegtessük bele hozzá a mazsolát, mert így nem fogja megégetni a serpenyő alját.\r\n\r\n4. Lépés\r\nA másik edényben kezdjük el felverni a tojásfehérjét. Nagyon fontos, hogy teljesen száraz legyen a keverőtartály, mert különben nem lesz habos. A másik fontos dolog az, hogy ne keverjük túl a tojásfehérjét, mert az túlságosan száraz lesz, így a sütemény struktúrája nem lesz tökéletes.\r\n\r\n5. Lépés\r\nKeverjük hozzá a tojásfehérjét a lisztes-tejes masszához és óvatosan vegyítjük össze az egészet.\r\n\r\n6. Lépés\r\nOlvasszuk fel a vajat egy serpenyőben, adjuk hozzá a masszát és süssük 10 percig.\r\n\r\n7. Lépés\r\nFordítsuk meg a palacsintát és süssük még 10 percig.\r\n\r\n8. Lépés\r\nEbben a pontban célszerű előkészíteni a tányérokat és az esetleges köretet. Ez általában azok számára is hasznos lehet, akik szeretnek mindent frissen és forrón tálalni.\r\n\r\n9. Lépés\r\nTálalás előtt vágjuk fel a süteményt kisebb darabokra, majd szórjuk meg a porcukorral és vanília cukorral.', 30, 4, 'könnyű', 29, '479df3e318c193', '2025-02-17 06:46:07'),
(122, 'avokádókrém', 'előétel', 'Az avokádót vágd ketté és a belsejét egy kanál segítségével kapard bele egy aprítóba vagy egy turmixgépbe.\r\n\r\nSózd, borsozd és locsold meg a lime levével, majd az olívaolajjal. Adj hozzá még egy kanál natúr joghurtot vagy tejfölt, majd turmixold krémesre.\r\n\r\nTipp: Tedd hűtőbe, és hagyd 1-2 órát állni, hogy az ízek összeérjenek.', 20, 4, 'könnyű', 29, 'cec375a5657fd2', '2025-02-17 07:16:58'),
(123, 'szaftos csirkecombos tészta', 'tészta', 'A csirkecombot apro kockákra vágjuk, olajon elkezdjük pirítani. Mehetnek hozzá a szálra vágott zöldségek: hagyma, zeller, répa, fokhagyma. Sózzuk, borsozzuk, majd a citrom héját is bele tesszük. 1 db babérlevelet hozzárakunk és kb 10-ig fedél alatt főzzük. \r\nAmíg fő a hús, összekeverjük a szószt: mustár, citrom leve, tejföl 1 tálba. A kész húsra ezt ráöntjük összekeverjük vele.\r\nA kifőtt tésztával a kész ételt összekeverjük és kész.', 40, 4, 'közepes', 29, 'c99ff3d5cc73ba', '2025-02-17 07:32:25'),
(124, 'gombakrémes melegszendvics', 'főétel', 'A gombát megmossuk és lereszeljük, nagylyukú sajtreszelőn, vagy fel is apríthatjuk tetszés szerint. A hagymákat finomra vágjuk.\r\n\r\nEgy tapadásmentes serpenyőben felhevítjük az olajat és megdinszteljük a hagymákat, majd nagy lángon a gombát is átforgatjuk benne és fűszerezzük.\r\nA só miatt levet fog ereszteni, de mire megpuhul a leve is elillan, amíg ez megtörténik kikeverjük a lisztet a tejföllel és higítjuk a tejszínnel, majd a zsírjára sült gombához adjuk, folyamatosan kevergetve. Szinte azonnal besűrűsödik és ha egyet forrtyant, le is vehetjük a tűzről és elkeverjük benne a vajat, majd – időnként megkeverve – hagyjuk hülni, míg a többit előkészítjük.\r\n\r\nElőmelegítjük a sütőt 200°-ra, kenyeret szelünk, sajtot reszelünk és összeállítjuk a szendvicseket, majd tepsire téve kb. 10 perc alatt összesütjük. Tálalhatjuk is.', 25, 4, 'könnyű', 29, 'e7efc9277f4efb', '2025-02-17 07:36:33'),
(127, 'Racott krumpli baconnal camambert-rel', 'főétel', 'A krumplikat mossuk meg alaposan, majd forrásban lévő, sós vízben főzzük addig, míg egy villa könnyen bele nem megy a húsukba. (Kb. 25-30 perc a méretüktől függően.) Hagyjuk őket picit hűlni, majd hámozzuk meg, vágjuk karikákra.\r\n\r\nMíg készül a krumpli, a vöröshagymát pucoljuk meg, vágjuk félbe, majd vékony szeletekre. Ha kockázott bacont használunk, nincs vele pluszdolgunk, ha szeletelt, akkor vágjuk csíkokra.\r\n\r\nDobjuk a bacont egy serpenyőbe, süssük ki a zsírja nagy részét, de nem kell teljesen ropogósra pirítani. Szedjük egy tányérra.\r\n\r\nA kisült zsírra dobjuk rá a hagymát, sózzuk, borsozzuk, dinszteljük addig, míg puha, picit karamellizált, aranybarnás színű nem lesz, kb. 10-15 percig. (Ha szükséges, tegyünk még alá olajat.) Ekkor adjuk hozzá a bacont, majd öntsük fel a fehérborral. Főzzük addig, amíg az alkohol elpárolog. Húzzuk le a tűzről.\r\n\r\nA camembert-t közben vágjuk szeletekre.\r\n\r\nTegyük egy 20x25 centis sütőtál aljába (ha nem tapadásmentes, olajozzuk ki) a krumpliszeletek felét, enyhén sózzuk, borsozzuk. Oszlassuk el rajta a hagymás-baconös keverék felét, simítsuk rá a tejföl felét, majd a camembert-szeletek felét is rendezzük el rajta. Utána ismét krumpli, só, bors, a maradék hagymás-baconös keverék, tejföl és camembert következik.\r\n\r\nToljuk 180 fokos sütőbe kb. 25-30 percre, míg a camembert megpirul a tetején.', 40, 4, 'könnyű', 29, 'ef34ead94d956b', '2025-02-17 17:27:57'),
(128, 'Háromhagyma-krémleves', 'leves', 'A háromhagyma-krémleves elkészítéséhez a fokhagymafejet megskalpoljuk, hogy a gerezdek szemmel láthatóak legyenek, de igyekezzünk minél kisebb részt levágni róla. Meglocsoljuk az olívaolajjal, sózzuk, borsozzuk, becsomagoljuk alufóliába, majd betesszük a 200 fokra előmelegített sütőbe kb. 30 percre, amíg krémesre puhulnak a gerezdek. \r\n\r\nA vajat felhevítjük egy fazékban, hozzáadjuk a karikára vágott póréhagymát, a szeletekre vágott vöröshagymát és belenyomjuk a megsült fokhagymagerezdeket is, majd közepes hőfokon 6-8 percig dinszteljük. Sózzuk, borsozzuk, felöntjük az alaplével, ízesítjük szerecsendióval, és közepes hőfokon, forrástól számítva kb. 15 percig főzzük. \r\n\r\nAmíg fő a leves, a kenyereket megcsorgatjuk az olívaolajjal, ráreszeljük a sajtot és betesszük a 220 fokra előmelegített sütőbe kb. 5 percre, amíg a sajt rá nem olvad szépen a tetejére, majd kivesszük a sütőből. \r\n\r\nA levest egy botmixer segítségével leturmixoljuk, hozzáadjuk a tejszínt, egyet forralunk rajta. Ha túl sűrűnek találjuk, egy kevés vízzel hígítjuk. Tányérba szedjük a levest és a sajtos pirítóssal tálaljuk.', 40, 4, 'közepes', 29, '5d58bea3021c3e', '2025-02-17 17:32:17'),
(129, 'Gombapörkölt galuskával', 'főétel', 'A paprikás elkészítéséhez egy lábasban, a felforrósított olajon először a félbevágott  paradicsomot pirítjuk meg, majd a zöldpaprikát is. Megsózzuk, majd ha szépen kioldódtak a színek, mehet rá a zúzott fokhagyma és a finomra vágott vöröshagyma is. Mikor jól összepirul, kevés vizet (kb. 50 ml) öntünk alá, és ha újra felforr, mehet rá a pirospaprika és a bors, majd felöntjük 500 ml vízzel, és zsírjára pirítjuk.\r\n\r\nAddig van időnk összevágni a gombát. A kicsik maradhatnak egészben, a többit pedig igazítsuk ehhez a mérethez. Félbe, a nagy fejeket esetleg a háromba, négybe vágjuk. Az előkészített gombát egy forró serpenyőben, szárazon megpirítjuk több részletben.\r\n\r\nMire ezzel megvagyunk, kész a pörköltalap, most kihorgászhatjuk belőle a paprika és a paradicsom cellulózos héját, illetve, ha szeretnénk, krémesíthetjük egy botmixerrel. \r\n\r\nHozzátesszük a gombát, felöntjük újabb 500 ml forró vízzel, majd ha felforrt, hozzáadjuk a tejfölt és tejszínt, és a lisztet 50 ml vízzel elkevert habarással sűrűre forraljuk a gombapaprikást.', 40, 4, 'közepes', 29, 'adfbdae07d71dc', '2025-02-17 17:37:01'),
(130, 'Húsvéti rántott sajt', 'főétel', 'A sajtot lereszeljük, majd összekeverjük az apróra vágott petrezselyemmel, sonkával, a fűszerekkel és a liszttel és alaposan átdolgozzuk. A masszát letakarjuk, és egy fél órát állni hagyjuk a hűtőben. Ezután 6-7 cm átmérőjű golyókat formázunk belőle. \r\n\r\nA tojást felverjük egy kevés sóval és borssal, majd a klasszikus módon lisztbe, tojásba és zsemlemorzsába forgatjuk a sajtgolyókat. A fagyasztóba tesszük 30 percre. Ezután 160 fokon Air fryer-ben 6-7 perc alatt készre sütjük.', 35, 4, 'közepes', 29, '565c626f343517', '2025-02-17 17:41:51'),
(131, 'Húsvéti nyuszi palacsinta', 'Édesség', 'Először is készítsük el az amerikai palacsintát. A folyamat leírását ide kattintva találjátok meg, a hozzávalókat fentebb soroljuk.\r\n\r\nMinden nyuszi arcához három palacsintára lesz szükség: egy nagyra, ami kb. 12-13 cm átmérőjű, és két kicsire, amelyek 4 cm átmérőjűek.\r\n\r\nA díszítéshez hámozzuk meg a banánt és vágjuk félbe. Az egyik felét karikázzuk fel kb. 2 cm-es darabokra. A másik felét hosszában vágjuk félbe, mert utóbbiból lesznek majd a nyuszifülek.\r\n\r\nAz epret mossuk meg, majd vágjuk félbe, ez lesz a nyuszi orra.\r\n\r\nAz áfonyát szintén mossuk meg, mert ettől lesz a nyuszinak szembogara.\r\n\r\nA sajtot vágjuk fel kb. 6-8 mm-es csíkokra, ez adja majd meg a nyuszi bajszát. Összesen 18 db csíkra lesz szükségünk.\r\n\r\nA kész palacsintákat a következők szerint rendezzük el a tányéron: a nagy palacsinta lesz a nyuszi arca, amire a két kisebb palacsintát úgy helyezzük el, hogy azok a nyuszi pofazacskójának helyén legyenek.\r\n\r\nEzután pedig már csak a gyümölcsös dekorálás van hátra a képen is látható módon: a nyuszi szeme 1-1 banánkarika legyen, amelyeknek a közepére 1-1 szem áfonya kerüljön. A nyuszi orra egy szem eper fele lesz, míg a bajszát sajtból vágjuk ki és illesztjük a helyére.\r\n\r\nAmint minden elem a helyére került, már tálalhatjuk is a vidám, reggeli fogást. Ebből a mennyiségből 6 darab nyuszis palacsinta készíthető el.', 35, 4, 'könnyű', 29, '4c82d05e1926fd', '2025-02-17 17:47:44'),
(132, 'Görög Muszaka', 'rakott étel', 'A görög muszaka ragujához az olajon üvegesre pároljuk az apróra vágott hagymát és fokhagymát, rárakjuk a húst és alaposan lepirítjuk. Fűszerezzük, felöntjük a borral és ha elpárolgott, rárakjuk a vizet, a passatát, a darabolt paradicsomot, felforraljuk és 30-40 percig főzzük.\r\n\r\nA padlizsánt 4-5 mm vastag szeletekre vágjuk, sózzuk, és hagyjuk állni 30 percig.\r\nHa állt, egy papírtörlővel leitatjuk a felesleges nedvességet és sót. A krumplit 3-4 mm vastag szeletekre vágjuk. A padlizsánt és a krumplit összeforgatjuk 2-2 evőkanál olajjal, sóval, borssal és tepsire rakjuk úgy, hogy ne fedjék egymást, majd mehet a 180 fokos sütőbe 15 percre.\r\n\r\nA besamelhez a felolvasztott vajon megpirítjuk kicsit a lisztet, felöntjük a tejjel, csomómentesre főzzük, fűszerezzük, belerakjuk a fetát, elkeverjük és elzárjuk. A besamelhez óvatosan hozzákeverjük a tojássárgáját.\r\n\r\nEgy 20×30 cm átmérőjű tepsit kivajazunk, megszórjuk morzsával és az elősütött padlizsán felét lerakjuk alsó rétegnek. Erre jöhet a ragu fele, a krumpli, majd a maradék ragu és erre a padlizsán. Leöntjük a besamellel, megszórjuk a morzsával, és 180 fokos sütőben 30-40 perc alatt megsütjük.', 120, 4, 'haladó', 1, 'ac20b9ed4744cf', '2025-02-17 17:48:33'),
(133, 'Cukkini golyók air fryerben', 'főétel', 'A cukkinigolyók első lépésként a cukkinit lereszeljük, besózzuk, állni hagyjuk 8 percig, majd egy textilpelenkába csomagoljuk és kinyomjuk belőle az összes levet.\r\n\r\nEzután mehetnek a reszelt cukkinihez a felaprított zöldfűszerek, a zúzott fokhagyma és a többi hozzávaló is. Alaposan elkeverjük, majd 40 grammos golyókat formázunk belőle. \r\n\r\nAz így kapott golyókat egy sütőpapírral borított tálcára pakoljuk és a fagyasztóba dobjuk 10-12 percre. Ezután az air fryerben 180 fokon 16-18 perc alatt aranybarnára sütjük őket.', 35, 4, 'könnyű', 29, '7c09c0589085dc', '2025-02-17 17:53:33'),
(134, 'túrógombóc', 'főétel', 'Először a tojást a cukorral habosra keverem. \r\nMajd egy külön tálba a rögös túróhoz hozzáadom a vajat, a sót és a citrom héját, összedolgozom őket. Mehet hozzá a tojáskeverék, majd legvégén a búzadara. Jól összedolgozom az összetevőket és minimum 1 órára a hűtőbe teszem.\r\nEnyhén lobogó kicsit sós vízben kifőzöm a gombócokat, majd a kész gombócokat pirított zsemlemorzsába hempergetem és kész is.', 35, 4, 'könnyű', 29, '83cb517ab23ce6', '2025-02-18 07:55:27'),
(135, 'almás majonézes kukoricasaláta', 'saláta', 'Az összetevőket összekeverem. Tetszés szerint adok hozzá majonézt, tejfölt és tejet. Végén sózom borsozom és kész.', 20, 4, 'könnyű', 29, '7ff430bce717e8', '2025-02-18 10:47:24'),
(136, 'Pinkáns csirkés tészta', 'tészta', 'A tésztát a csomagoláson feltüntetett utasítás szerint kifőzöm és egy merőkanállal félre teszek a főzővízből.\r\n\r\nA mustárokat elkeverem a mézzel, a zúzott fokhagymával, a sóval és a borssal, majd beleforgatom a kockára vágott csirkemellet.\r\n\r\nA bacont csíkokra vágom, kevés olajon megpirítom, majd tányérra szedem és a visszamaradt zsiradékon aranysárgára pirítom az előzőleg bepácolt csirkemellet.\r\n\r\nAmikor a csirke megpirult hozzáadom a félretett főzővízet, a tejszínt, kiforralom, beleteszem a kifőtt tésztát, a megpirított bacont, a felaprított újhagymát, összeforgatom újraforralom és forrón tálalom.', 35, 4, 'közepes', 29, 'ac6c046b1648f0', '2025-02-18 10:51:05'),
(138, 'Húsvéti majonézes tojáskrém', 'saláta', 'A tojásokat főzzük keményre forrástól számítva 10 perc alatt. Hűtsük le hideg víz alatt, majd hámozzuk meg. Vágjuk kis kockákra, vagy sajtreszelőn reszeljük le. \r\n\r\n Az újhagymát aprítsuk fel.\r\n\r\nA tojásokat rakjuk egy tálba, tegyük hozzá a hagymát, és ízlés szerint sózzuk, borsozzuk.\r\n\r\nA puha vajat, a majonézt, a mustárt és a tejfölt dolgozzuk simára. Óvatosan keverjük a tojáshoz, kóstoljuk meg, hogy nem kell-e bele még fűszer. \r\n\r\nÍzlés szerint adhatunk hozzá még friss petrezselymet vagy más zöldfűszert.', 20, 4, 'könnyű', 29, '0a71873705046d', '2025-02-18 14:52:58'),
(139, 'Paradicsomsaláta', 'saláta', 'A paradicsomsaláta első lépéseként a paradicsomot és a lila hagymát felszeleteljük, a petrezselymet apróra vágjuk. Az ecetes léhez jól összekeverjük az almaecetet, a vizet, a sót, a borsot és a porcukrot.\r\n\r\nHa ezzel megvagyunk, ráöntjük a paradicsomos keverékre.\r\nFogyasztás előtt legalább 15 percet hagyjuk állni a hűtőben.', 20, 4, 'könnyű', 29, 'd2a0eeaf7ad733', '2025-02-18 14:56:33'),
(140, 'Padlizsános tészta', 'tészta', 'Melegítsük elő a sütőt 220 fokra. Béleljünk ki egy tepsit sütőpapírral.\r\n\r\nA padlizsánt alaposan mossuk meg, majd vágjuk 2-3 centis kockákra. Locsoljuk meg az olaj felével, sózzuk, borsozzuk, forgassuk össze. Szórjuk a tepsire, oszlassuk el rajta, majd toljuk a sütőbe 25-30 percre, míg megpuhul és picit megpirul. Félidőnél forgassuk át a padlizsánkockákat. \r\n\r\nKözben a szószhoz pucoljuk meg a hagymákat, a fokhagymát reszeljük le, a vöröset vágjuk finomra.\r\n\r\nA tésztát főzzük ki a csomagoláson írtak szerint, majd szűrjük le, tegyük félre.\r\n\r\nMíg fő a tészta, a maradék olajat forrósítsuk fel egy serpenyőben, dobjuk rá a vöröshagymát. Pár percig dinszteljük, majd dobjuk rá a fokhagymát, pirítsuk azzal együtt is 1 percig. Öntsük fel a fehérborral, majd gyöngyözve főzzük, míg a bor elpárolog. \r\n\r\nÖntsük fel a passzírozott paradicsommal. A vízzel öblítsük ki a paradicsomos üveget/dobozt, azt is adjuk a szószhoz. Adjuk hozzá a fűszereket, majd alacsony lángon, gyöngyözve főzzük 5 percig. Kóstoljuk meg, szükség szerint sózzuk, borsozzuk.\r\n\r\nKeverjük bele a sült padlizsánt, melegítsük egybe. \r\n\r\nA kifőtt tésztával tálaljuk.', 35, 4, 'közepes', 29, '054d1f24b8e411', '2025-02-18 14:59:39'),
(141, 'cukkinikrém', 'előétel', 'A cukkinikrém első lépéseként a cukkinit 2 cm-es darabokra szeleteljük, és egy forró serpenyőben aranybarnára sütjük az olajon. Ezután egy szűrőbe tesszük és megvárjuk, míg a nedvesség kicsepeg belőle és kihűl. Ha ezzel megvagyunk, akkor a többi alapanyaggal együtt egy turmixgépbe tesszük és pürésítjük, míg el nem érjük a kívánt állagot. Ezzel pedig készen is van a krém. Hűtőbe tesszük 10 percre, és utána már kenhetjük is gazdagon a friss pirítósra.', 20, 4, 'könnyű', 29, '9fca70e9c3d5cd', '2025-02-18 15:04:23'),
(142, 'Baconos brokkolis tészta', 'tészta', 'A bacont serpenyőben megpirítom, kiveszem, félreteszem. \r\nMajd vajon és olajon a felaprított hagymát, fokhagymát pirítom, mehet hozzá a szárított paradicsom és a  többi összetevő. Sózon, borsozom majd hozzáadom a kész bacont is, végül pedig a kifőzött tésztával összekeverem. Sajttal találom', 35, 4, 'közepes', 29, '5db2ea86a1a10c', '2025-02-18 15:12:32'),
(143, 'Tejszínes-citromos csirkeragu', 'főétel', 'A tejszínes-citromos csirkeragu elkészítéséhez a krumplit megpucoljuk, sós vízben félpuhára főzzük és leszűrjük. A felkockázott combot egy serpenyőben, magas hőmérsékleten körbepirítjuk az olajon, majd kiszedjük, és a visszamaradt szaftra dobjuk az apróra vágott hagymát, fokhagymát. Sózzuk, borsozzuk, megszórjuk kakukkfűvel, kicsit lepirítjuk, rárakjuk a krumplit és azzal is pirítjuk még 2-3 percet.\r\n\r\nRárakjuk a csirkét, felöntjük az alaplével és ha elfőtt, meglocsoljuk a citromlével. Felöntjük a tejszínnel, felforraljuk, majd rárakjuk a kockákra vágott sajtot és 200 fokra előmelegített sütőben, grillfokozaton rápirítunk a tetejére kb. 15 perc alatt. Friss snidlinggel megszórva tálaljuk.', 40, 4, 'közepes', 29, 'a14ef250cfa9e6', '2025-02-18 15:20:57'),
(145, 'carbonara spagetti', 'tészta', 'Első lépésként a tésztát kell megfőznünk enyhén sós vízben. Amíg fő a tésztánk, addig kezdjük el lepirítani a felkockázott szalonnát, hogy azzal is időt spóroljunk meg. A szalonnából kisült zsírt semmiképpen se öntsük le, ez is fontos alapját fogja képezni a szószunknak. Ha a tésztánk elkészült, adjunk egy kis főzőlevet a szalonnához, majd tegyük hozzá a tésztát is, és alaposan melegítsük össze a kettőt, hogy a szalonna kisült zsírja befedje a tésztánkat. \r\n\r\nAmíg ez melegszik, addig verjük fel a tojásokat és a tojássárgákat (sót első körben ne adjunk hozzá, mert sós a szalonnánk is.) Ízlés szerint reszelhető sajt is a felvert tojásba, hogy egy kis pluszt adjunk még az ételünkhöz, de az étel tetejére is bőven elég, ha szórunk belőle. Ha a tésztánkat befedte a szalonnazsír, vegyük le a tűzről, öntsük nyakon a tojásos lével, és gyors mozdulatokkal forgassuk össze úgy, hogy a tojás még ne kezdjen el megsülni. Ha esetleg sűrűnek találjuk a szószt, egy kis főzőlével lehet rajta hígítani. \r\n\r\nRögtön tálalhatjuk is az ételt, szórjuk meg egy kis frissen őrölt borssal és parmezán sajttal. Jó étvágyat kívánunk hozzá!', 20, 4, 'könnyű', 29, '1f2680da7c1b66', '2025-02-19 15:23:07'),
(146, 'tejfölös uborkasaláta', 'saláta', 'Az uborkát meghámozzuk és szeletelővel vagy kézzel vékony karikákra vágjuk. Sóval meghintjük, összekeverjük, és 1-2 órát állni hagyjuk.\r\n\r\nAz uborka kieresztett levét leöntjük, a zöldséget alaposan kinyomkodjuk.\r\n\r\nEgy salátástálba tesszük, hozzáadjuk a tejfölt, az ecetet, a zúzott fokhagymát és a cukrot, majd alaposan összekeverjük.\r\n\r\nKis tálkákban, pirospaprikával és borssal meghintve tálaljuk.', 20, 4, 'könnyű', 29, '711f21b5aad9b1', '2025-02-19 15:26:57');
INSERT INTO `recept` (`recept_id`, `recept_neve`, `kategoria`, `leiras`, `elk_ido`, `adag`, `nehezseg`, `felh_id`, `pic_name`, `created_at`) VALUES
(147, 'csirke tikka masala', 'főétel', 'A tejfölös csirke tikka masala recept első lépéseként a pácot keverjük be. Ehhez a tejfölt összekeverjük a fűszerekkel, és beleforgatjuk a csirkecombfiléket. A húsba jól belemasszírozzuk a fűszeres tejfölt, és félretesszük kb. 1 órára a hűtőbe. Amikor lejárt a csirke pihenőideje, egy forró serpenyőben, nagy lángon, kevés olajon megsütjük a filéket.\r\n\r\nA ragu elkészítése:\r\nA hagymát félkarikákra vágjuk és egy lábasban, nagy lángon lepirítjuk az olajon. Mehet rá a szeletelt fokhagyma és a nagyobb darabokra vágott gyömbér, és ezeket is lepirítjuk. Ha minden kapott egy kis színt, felöntjük a paradicsommal és egy kevés vízzel. Sózzuk (ne felejtsük el, hogy a csirke nem sós!) és belerakjuk a fűszereket, majd közepes lángon 20-30 percet rotyogtatjuk. Ha lejárt az idő, mehet a mártásalapba a tejszín, 1-2 percet még főzzük, majd kiszedjük belőle a gyömbért (de a hardcore rajongók benne is hagyhatják 😀) és leturmixoljuk. \r\n\r\nA lepirított csirkecombot felaprítjuk, belekeverjük a mártásba, majd még egyszer utoljára összerottyantjuk az egészet. A tikka masalát aprított korianderrel megszórva, a naan kenyérrel együtt tálaljuk.', 60, 4, 'haladó', 29, '962e68fd76edf1', '2025-02-19 15:34:46'),
(148, 'farsangi fánk air fryer-ben', 'Édesség', 'Langyos cukros tejben felfuttatjuk az élesztőt.\r\n\r\nHozzákeverjük a hozzávalókat az olaj kivételével és összedolgozzuk a tésztát.\r\n\r\nEgy órát meleg helyen kelni hagyjuk.\r\n\r\nLisztezett felületen 1 cm vastagra nyújtjuk a tésztát és kiszaggatjuk.\r\n\r\nNegyed órát pihentetjük a tésztát.\r\n\r\nEcsettel a tésztakorongok mindkét felét bekenjük olajjal.\r\n\r\n180 °C-on 8 perc alatt megsütjük az airfryer-ben, úgy, hogy 4-5 perc környékén megfordítjuk a fánkokat fejjel lefelé. A sütési idő a saját készülék tulajdonságaitól függően változhat!\r\n\r\nKivéve megtölthetjük lekvárral, megszórjuk porcukorral.', 30, 4, 'könnyű', 29, '188ca91b580ca8', '2025-02-19 17:02:57'),
(149, 'Sült padlizsánkrém (baba ganoush)', 'előétel', '1) A sütőt előmelegítjük 200-220 fokra. Egy tepsire sütőpapírt rakunk, erre helyezzük a megmosott, megtörölt padlizsánokat. Ha nem akarjuk, hogy sütés közben szétrobbanjon, szurkáljuk meg 1-2 helyen a felületüket. Ezután tegyük a sütőbe nagyjából 45-50 percre, amíg a padlizsán héja megfeketedik. Ezután hagyjuk kicsit hűlni, majd kézzel távolítsuk el héját és a szárát is.\r\n\r\n2) A sült padlizsánhúst egy késes aprítóba tesszük, majd sózzuk, fűszerezzük füstölt paprikával, római köménnyel, fokhagymával. Hozzáadjuk a tahinit, a petrezselyem felét, és végül citromlevet facsarunk hozzá. Ezután a késes aprítóval homogén krémmé pépesítjük.\r\n\r\n3) Az elkészült padlizsánkrémet megkóstoljuk, ha szükséges, még fűszerezhetjük, egy kis tálba kanalazzuk, majd meglocsoljuk kevés extraszűz olívaolajjal, megszórjuk gránátalmával, és aprított petrezselyemmel.', 70, 4, 'közepes', 1, '5b5dfd31beade0', '2025-02-20 13:14:49'),
(150, 'Túrós-kapros galuska paradicsomsalátával', 'főétel', 'Először a paradicsomsalátát készítjük el.\r\nMajd a szalonnát apró kockákra vágjuk, serpenyőbe megpirítjuk, félretesszük.\r\nA megmaradt zsíron 3-4 perc alatt átpirítjuk a galuskát és a fokhagymagerezdeket. Összekeverjük a tejföllel és a túróval, ízlés szerint sózzuk, borsozzuk, aztán beleforgatjuk az apróra vágott kaprot. \r\nVégül hozzáadjuk a szalonnapörcöt is.\r\n220 fokos sütőbe 10 percre berakjuk.', 25, 4, 'könnyű', 29, '0eba1177b5eedc', '2025-02-20 15:36:18'),
(151, 'Chilis bab felturbózva', 'főétel', 'A darálthúst a fűszerekekkel összekeverjük és kevés olajon lepirítom. Hozzáadom a lecsöpögetett konzerv babot és kukoricát majd fedő alatt kicsi lángon kb 10 percig még forralom. \r\n\r\nA kész ragut tortilla chipssel és tejföllel tálalom.', 20, 4, 'könnyű', 29, '7413cd64479927', '2025-02-20 15:48:22');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `recept_id`, `felh_id`, `komment`, `ertekeles`, `created_at`) VALUES
(98, 88, 1, '', 5, '2025-02-13 19:41:55'),
(99, 95, 1, '', 5, '2025-02-13 21:49:17'),
(100, 92, 1, '', 5, '2025-02-14 09:28:49'),
(108, 97, 1, '', 5, '2025-02-14 11:56:37'),
(110, 101, 1, '', 5, '2025-02-14 12:14:07'),
(111, 105, 1, 'Ezt én szoktam csinálni és nagyon finom!', 5, '2025-02-14 12:24:25'),
(112, 109, 29, 'Nyami', 5, '2025-02-14 13:15:57'),
(113, 119, 31, '', 5, '2025-02-16 11:40:19'),
(115, 89, 1, '', 5, '2025-02-17 11:56:00'),
(118, 133, 1, '', 5, '2025-02-17 17:57:41'),
(120, 112, 1, '', 5, '2025-02-18 14:28:57'),
(121, 110, 29, '', 5, '2025-02-18 16:09:11'),
(122, 132, 29, '', 5, '2025-02-18 16:09:52'),
(123, 122, 38, '', 5, '2025-02-20 09:37:14'),
(124, 112, 38, '', 5, '2025-02-20 13:06:41'),
(125, 151, 1, '', 5, '2025-02-20 18:22:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`flag`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`recept_id`),
  ADD KEY `recept_id` (`recept_id`);

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
  MODIFY `felh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `hozzavalok`
--
ALTER TABLE `hozzavalok`
  MODIFY `hozzavalo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1197;

--
-- AUTO_INCREMENT for table `recept`
--
ALTER TABLE `recept`
  MODIFY `recept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `felhasznalok` (`felh_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`recept_id`);

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
