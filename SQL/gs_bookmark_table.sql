-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2024 年 8 月 28 日 09:23
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gs_db_kadai4`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_bookmark_table`
--

CREATE TABLE `gs_bookmark_table` (
  `id` int(12) NOT NULL,
  `category` varchar(64) NOT NULL,
  `book_name` varchar(64) NOT NULL,
  `book_url` text NOT NULL,
  `book_comment` text DEFAULT NULL,
  `book_status` varchar(10) NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `gs_bookmark_table`
--

INSERT INTO `gs_bookmark_table` (`id`, `category`, `book_name`, `book_url`, `book_comment`, `book_status`, `date`, `image`) VALUES
(1, 'literature', 'テスト', 'https://ssss.xxxx', '面白い ！', 'inProgress', '2024-07-15 12:41:13', 'img/66ceaccd95789.png'),
(2, 'art', 'テスト2 test2', 'https:www//qqqq.bbb', '普通 テスト２', 'completed', '2024-07-15 13:10:31', 'img/66cebe5f70647.png'),
(3, 'literature', '百年の孤独', 'https://www.amazon.co.jp/%E7%99%BE%E5%B9%B4%E3%81%AE%E5%AD%A4%E7%8B%AC-%E6%96%B0%E6%BD%AE%E6%96%87%E5%BA%AB-24-2-%E3%82%AC%E3%83%96%E3%83%AA%E3%82%A8%E3%83%AB%E3%83%BB%E3%82%AC%E3%83%AB%E3%82%B7%E3%82%A2-%E3%83%9E%E3%83%AB%E3%82%B1%E3%82%B9/dp/4102052127', '', 'notStarted', '2024-07-15 15:34:42', NULL),
(5, 'others', 'プロジェクトヘイルメアリー', 'https://www.amazon.co.jp/dp/B09Q59CL75?binding=kindle_edition&searchxofy=true&ref_=dbs_s_aps_series_rwt_tkin&qid=1721223652&sr=8-1', '面白い', 'completed', '2024-07-17 22:41:54', NULL),
(6, 'art', '13歳からのアート思考', 'https://www.amazon.co.jp/%E3%80%8C%E8%87%AA%E5%88%86%E3%81%A0%E3%81%91%E3%81%AE%E7%AD%94%E3%81%88%E3%80%8D%E3%81%8C%E8%A6%8B%E3%81%A4%E3%81%8B%E3%82%8B-13%E6%AD%B3%E3%81%8B%E3%82%89%E3%81%AE%E3%82%A2%E3%83%BC%E3%83%88%E6%80%9D%E8%80%83-%E6%9C%AB%E6%B0%B8-%E5%B9%B8%E6%AD%A9/dp/4478109184/ref=zg_bs_g_500608_d_sccl_1/355-4357538-0759752?psc=1', 'アート', 'notStarted', '2024-07-21 10:22:04', NULL),
(7, 'art', '13歳からのアート思考', 'https://www.amazon.co.jp/%E3%80%8C%E8%87%AA%E5%88%86%E3%81%A0%E3%81%91%E3%81%AE%E7%AD%94%E3%81%88%E3%80%8D%E3%81%8C%E8%A6%8B%E3%81%A4%E3%81%8B%E3%82%8B-13%E6%AD%B3%E3%81%8B%E3%82%89%E3%81%AE%E3%82%A2%E3%83%BC%E3%83%88%E6%80%9D%E8%80%83-%E6%9C%AB%E6%B0%B8-%E5%B9%B8%E6%AD%A9/dp/4478109184/ref=zg_bs_g_500608_d_sccl_1/355-4357538-0759752?psc=1', 'アート', 'notStarted', '2024-07-21 10:22:20', NULL),
(8, 'history', 'サピエンス全史', 'https://www.amazon.co.jp/gp/product/B071NVR71M/ref=bpbB071NVR71M?pf_rd_m=AN1VRQENFRJN5&pf_rd_s=product-alert&pf_rd_r=V7SA00V75D63101H3KDM&pf_rd_r=V7SA00V75D63101H3KDM&pf_rd_t=201&pf_rd_p=8fd70df4-682d-484e-a640-d6b3767f356b&pf_rd_p=8fd70df4-682d-484e-a640-d6b3767f356b&pf_rd_i=430922671X', '', 'notStarted', '2024-07-21 10:51:56', NULL),
(9, 'others', 'テスト5-6', 'https://test4.xxxx', 'テスト5-6', 'completed', '2024-07-21 10:57:15', 'img/66ce98951adbd.png'),
(13, 'others', 'test image', 'test image', 'test image', 'inProgress', '2024-08-28 11:21:16', 'img/66ce899ca19c5.png'),
(16, 'Entertainment', '星を継ぐもの', 'https://www.amazon.co.jp/%E6%98%9F%E3%82%92%E7%B6%99%E3%81%90%E3%82%82%E3%81%AE-%E5%89%B5%E5%85%83SF%E6%96%87%E5%BA%AB-%E3%82%B8%E3%82%A7%E3%82%A4%E3%83%A0%E3%82%BA%E3%83%BBP%E3%83%BB%E3%83%9B%E3%83%BC%E3%82%AC%E3%83%B3/dp/448866301X', 'SF', 'completed', '2024-08-28 14:18:00', 'img/66ceb31a2da9e.png');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_bookmark_table`
--
ALTER TABLE `gs_bookmark_table`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_bookmark_table`
--
ALTER TABLE `gs_bookmark_table`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
