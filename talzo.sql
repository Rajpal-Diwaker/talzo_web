/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 5.7.29-0ubuntu0.18.04.1 : Database - talzo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Admin Unique ID',
  `name` varchar(50) NOT NULL COMMENT 'Admin Full Name',
  `email` varchar(50) NOT NULL COMMENT 'Admin Email',
  `password` varchar(70) NOT NULL COMMENT 'Admin Password',
  `forgot_token` varchar(15) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `admin` */

LOCK TABLES `admin` WRITE;

insert  into `admin`(`admin_id`,`name`,`email`,`password`,`forgot_token`) values 
(1,'Techugo','admin@techugo.com','e10adc3949ba59abbe56e057f20f883e',''),
(2,'Shubham','shubhamtyagi9643@gmail.com','e10adc3949ba59abbe56e057f20f883e','ZFR4OUxhOG');

UNLOCK TABLES;

/*Table structure for table `advertisements` */

DROP TABLE IF EXISTS `advertisements`;

CREATE TABLE `advertisements` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Advertisement ID',
  `user_id` int(10) unsigned NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  `user_add` varchar(200) NOT NULL COMMENT 'User Add url',
  `description` text NOT NULL COMMENT 'Adds Description',
  `views` int(11) NOT NULL COMMENT 'No Of view',
  `ads_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=false,1=true',
  `verified_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Admin accept reject status 0=false,1=true',
  `expired_date` date DEFAULT NULL,
  `created_at` date NOT NULL COMMENT 'Created Date',
  PRIMARY KEY (`add_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `advertisements` */

LOCK TABLES `advertisements` WRITE;

UNLOCK TABLES;

/*Table structure for table `allreason` */

DROP TABLE IF EXISTS `allreason`;

CREATE TABLE `allreason` (
  `reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_title` varchar(300) NOT NULL,
  PRIMARY KEY (`reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `allreason` */

LOCK TABLES `allreason` WRITE;

insert  into `allreason`(`reason_id`,`reason_title`) values 
(1,'Nudity or sexual activity'),
(2,'Hate speech or symbols'),
(3,'Violence or dangerous organizations'),
(4,'Sale of illegal or regulated goods'),
(5,'Bullying or harassment'),
(6,'Intellectual property violation'),
(7,'Suicide or self-injury'),
(8,'Scam or fraud'),
(9,'False Information'),
(10,'I just don\'t like it');

UNLOCK TABLES;

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_en` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_ar` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_hi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_ch` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_ko` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_ja` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_fr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_ru` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `category` */

LOCK TABLES `category` WRITE;

insert  into `category`(`category_id`,`category_en`,`category_ar`,`category_hi`,`category_ch`,`category_ko`,`category_ja`,`category_fr`,`category_ru`) values 
(1,'Actor','الممثل','अभिनेता','演员','배우','俳優','Actrice','Актер'),
(2,'Aviation activity','نشاط الطيران','विमानन गतिविधि','航空活动','항공 활동','航空活動','Activité aéronautique','Авиационная деятельность'),
(3,'Basket ball','كرة سلة','बास्केटबाल','篮球','농구','バスケットボール','Basketball','Баскетбол'),
(4,'Body building','بناء الجسم','बॉडी बिल्डिंग','运动','보디 빌딩','ボディービル','La musculation','Бодибилдинг'),
(5,'Bicycle control/Drive','دراجة التحكم / محرك','साइकिल नियंत्रण / ड्राइव','自行车控制/驱动','자전거 제어 / 운전','自転車制御/ドライブ','Contrôle / conduite de vélo','Управление велосипедом / Drive'),
(6,'Billiards/Snooker Bowling','البلياردو / السنوكر البولينج','बिलियर्ड्स / स्नूकर बॉलिंग','台球/斯诺克保龄球','당구 / 스누커 볼링','ビリヤード/スヌーカーボウリング','Billard / Snooker Bowling','Бильярд / Снукер Боулинг'),
(7,'Car control/Drive','سيارة تحكم / محرك','कार नियंत्रण / ड्राइव','汽车控制/驱动','자동차 제어 / 운전','カーコントロール/ドライブ','Contrôle / conduite de voiture','Управление автомобилем / Драйв'),
(8,'Car customized','سيارة مخصصة','कार अनुकूलित','汽车定制','차 주문을 받아서 만드는','カスタマイズされた車','Voiture personnalisée','Автомобиль настроен'),
(9,'Cooking','طبخ','खाना बनाना','烹饪','조리','料理','Cuisine','Готовка'),
(10,'Dance','رقص','नृत्य','舞蹈','댄스','ダンス','Danse','танец'),
(11,'Drawing','رسم','चित्रकारी','画画','그림','お絵かき','Dessin','Рисование'),
(12,'Falcon/Eagles','فالكون / النسور','फाल्कन / ईगल्स','猎鹰/鹰','팔콘 / 이글','ファルコン/イーグルス','Falcon / Eagles','Фалкон / Орлы'),
(13,'Fashion design','تصميم الأزياء','फैशन डिजाइन','时尚设计','패션 디자인','ファッションデザイン','Dessin de mode','Модный дизайн'),
(14,'Fighting sports','قتال الرياضة','खेल लड़ना','格斗运动','싸우는 스포츠','格闘技','Sports de combat','Боевые виды спорта'),
(15,'Fishing','صيد السمك','मछली पकड़ना','钓鱼','어업','釣り','Pêche','На рыбалке'),
(16,'Football','كرة القدم','फ़ुटबॉल','足球','축구','フットボール','Football','Футбол'),
(17,'Handball','كرة يد','हेन्डबोल','手球','핸드볼','ハンドボール','Handball','гандбол'),
(18,'Handicraft','حرفي - حرفة يدوية','हस्तशिल्प','手工业','손재주','手芸','Artisanat','ремесленный'),
(19,'Hiking','التنزه','लंबी पैदल यात्रा','健行','등산','ハイキング','Randonnée','Пеший туризм'),
(20,'Horse ridding','ركوب الخيل','घुड़सवारी','骑马','말 타기','乗馬','Équitation','Верховая езда'),
(21,'Hunting','الصيد','शिकार करना','狩猎','수렵','狩猟','Chasse','охота'),
(22,'Innovation','ابتكار','नवोन्मेष','革新','혁신','革新','Innovation','новаторство'),
(23,'Lecturer','محاضر','व्याख्याता','讲师','강사','講師','Maître de conférences','преподаватель'),
(24,'Makeup/Cosmetology ','ماكياج / مستحضرات التجميل','मेकअप / सौंदर्य प्रसाधन','化妆/美容','메이크업 / 화장품','メイク/美容','Maquillage / Cosmétologie','Косметика / Косметика'),
(25,'Medical','طبي','मेडिकल','医疗类','의료','メディカル','Médicale','медицинская'),
(26,'Motorcycle control/Drive','دراجة نارية السيطرة / محرك','मोटरसाइकिल नियंत्रण / ड्राइव','摩托车控制/驱动','오토바이 제어 / 운전','オートバイ制御/駆動','Commande / conduite moto','Управление мотоциклом / Привод'),
(27,'Motorcycle customized','دراجة نارية مخصصة','मोटरसाइकिल अनुकूलित','摩托车定制','오토바이 맞춤형','カスタマイズされたオートバイ','Moto personnalisée','Мотоцикл подгонять'),
(28,'Musician/Band ','موسيقي / فرقة','संगीतकार / बैंड','音乐家/乐队','음악가 / 밴드','ミュージシャン/バンド','Musicien / Groupe','Музыкант / группа'),
(29,'Photography','التصوير','फोटोग्राफी','摄影','사진술','写真撮影','La photographie','фотография'),
(30,'Poetry recitation','تلاوة شعرية','कविता पाठ','诗歌朗诵','시 암송','詩の朗読','Récitation de poésie','Поэтическое чтение'),
(31,'Professional job','وظيفة مهنية','पेशेवर नौकरी','专业工作','전문직','プロの仕事','Emploi professionnel','Профессиональная работа'),
(32,'Shooting','اطلاق الرصاص','शूटिंग','射击','촬영','撮影','Tournage','стрельба'),
(33,'Singing','الغناء','गायन','唱歌','명음','歌う','En chantant','пение'),
(34,'Snow sports','رياضات الثلج','स्नो स्पोर्ट्स','冰雪运动','스노우 스포츠','スノースポーツ','Sports de neige','Зимние виды спорта'),
(35,'Swimming/Water activity','السباحة / نشاط الماء','तैराकी / जल गतिविधि','游泳/水上活动','수영 / 물 활동','水泳/ウォーターアクティビティ','Natation / Activité aquatique','Плавание / Водные развлечения'),
(36,'Taming wild animals','ترويض الحيوانات البرية','जंगली जानवरों को छेड़ना','驯服野生动物','야생 동물 길들이기','野生動物を飼いならす','Apprivoiser les animaux sauvages','Укрощение диких животных'),
(37,'Technology skills','مهارات التكنولوجيا','प्रौद्योगिकी कौशल','技术技能','기술 능력','技術スキル','Compétences technologiques','Технологические навыки');

UNLOCK TABLES;

/*Table structure for table `chat` */

DROP TABLE IF EXISTS `chat`;

CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) CHARACTER SET latin1 NOT NULL,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `thumb_url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `msg_type` enum('text','media') CHARACTER SET latin1 NOT NULL DEFAULT 'text',
  `chat_type` enum('normal','group') CHARACTER SET latin1 NOT NULL,
  `read_status` varchar(25) CHARACTER SET latin1 NOT NULL,
  `chat_status` enum('active','inactive') CHARACTER SET latin1 NOT NULL,
  `delete_for_everyone` enum('0','1') CHARACTER SET latin1 NOT NULL,
  `deleted_by` varchar(4) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*Data for the table `chat` */

LOCK TABLES `chat` WRITE;

insert  into `chat`(`id`,`s_id`,`r_id`,`group_id`,`msg`,`action`,`url`,`thumb_url`,`msg_type`,`chat_type`,`read_status`,`chat_status`,`delete_for_everyone`,`deleted_by`,`date_added`) values 
(1,1,2,0,'hii','','','','text','normal','2','active','0','2','2020-02-10 12:19:19'),
(2,1,2,0,'hii everyone','','','','text','normal','2','active','0','','2020-02-11 11:11:27'),
(3,1,2,0,'hii everyoneU+1F607','','','','text','normal','2','active','0','','2020-02-11 11:14:38'),
(4,1,2,0,'hii everyoneU+1F607','','','','text','normal','2','active','0','','2020-02-11 11:17:54'),
(5,1,2,0,'hii everyoneU+1F607 :-)','','','','text','normal','2','active','0','','2020-02-11 11:21:12'),
(6,1,2,0,' :-)','','','','text','normal','2','active','0','','2020-02-11 11:21:44'),
(7,1,2,0,'?','','','','text','normal','2','active','0','','2020-02-11 11:22:18');

UNLOCK TABLES;

/*Table structure for table `chat_block_users` */

DROP TABLE IF EXISTS `chat_block_users`;

CREATE TABLE `chat_block_users` (
  `b_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `block_status` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `chat_block_users` */

LOCK TABLES `chat_block_users` WRITE;

UNLOCK TABLES;

/*Table structure for table `check_users_accept` */

DROP TABLE IF EXISTS `check_users_accept`;

CREATE TABLE `check_users_accept` (
  `c_a_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `accept_id` int(11) NOT NULL,
  `accept_status` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`c_a_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `check_users_accept` */

LOCK TABLES `check_users_accept` WRITE;

insert  into `check_users_accept`(`c_a_id`,`user_id`,`accept_id`,`accept_status`) values 
(1,2,1,'1');

UNLOCK TABLES;

/*Table structure for table `followers` */

DROP TABLE IF EXISTS `followers`;

CREATE TABLE `followers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `is_follow` enum('0','1') NOT NULL COMMENT '0=Unfollow,1=follow',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `followers` */

LOCK TABLES `followers` WRITE;

insert  into `followers`(`id`,`user_id`,`following_id`,`is_follow`) values 
(1,1,3,'1'),
(2,1,4,'1');

UNLOCK TABLES;

/*Table structure for table `group_users` */

DROP TABLE IF EXISTS `group_users`;

CREATE TABLE `group_users` (
  `g_u_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`g_u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `group_users` */

LOCK TABLES `group_users` WRITE;

insert  into `group_users`(`g_u_id`,`group_id`,`user_id`) values 
(1,1,1),
(2,1,2),
(3,1,3);

UNLOCK TABLES;

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(60) NOT NULL,
  `group_admin` int(10) unsigned NOT NULL,
  `group_image` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `groupname` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `groups` */

LOCK TABLES `groups` WRITE;

insert  into `groups`(`group_id`,`group_name`,`group_admin`,`group_image`,`created_on`) values 
(1,'Total',1,'','2020-01-24 10:05:36');

UNLOCK TABLES;

/*Table structure for table `notification` */

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `noti_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` varchar(300) NOT NULL,
  `read_status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `notification` */

LOCK TABLES `notification` WRITE;

insert  into `notification`(`noti_id`,`user_id`,`title`,`body`,`read_status`,`date_time`) values 
(1,1,'Talzo','Test','unread','2020-01-24 11:56:11'),
(4,4,'Talzo','Test','unread','2020-01-24 11:56:11');

UNLOCK TABLES;

/*Table structure for table `post_stars` */

DROP TABLE IF EXISTS `post_stars`;

CREATE TABLE `post_stars` (
  `p_star_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Post Star ID',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User ID',
  `post_id` int(10) unsigned NOT NULL COMMENT 'Post ID',
  `star_status` enum('0','1') NOT NULL COMMENT '0=unlike ,1=like',
  PRIMARY KEY (`p_star_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `post_stars` */

LOCK TABLES `post_stars` WRITE;

insert  into `post_stars`(`p_star_id`,`user_id`,`post_id`,`star_status`) values 
(1,1,1,'0');

UNLOCK TABLES;

/*Table structure for table `post_views` */

DROP TABLE IF EXISTS `post_views`;

CREATE TABLE `post_views` (
  `view_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL COMMENT 'Post ID',
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  PRIMARY KEY (`view_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `post_views` */

LOCK TABLES `post_views` WRITE;

insert  into `post_views`(`view_id`,`post_id`,`user_id`) values 
(1,1,1);

UNLOCK TABLES;

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Post ID',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User ID',
  `thumb_url` varchar(255) NOT NULL,
  `user_post` varchar(200) NOT NULL COMMENT 'User post Image or video',
  `description` text NOT NULL COMMENT 'Post Description',
  `category_id` int(10) unsigned NOT NULL COMMENT 'User Talent',
  `views` bigint(10) unsigned NOT NULL COMMENT 'NO of view in post',
  `stars` bigint(10) unsigned NOT NULL COMMENT 'NO of star in post',
  `post_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Unactive,1=Active',
  `created_at` date NOT NULL COMMENT 'Created date',
  `updated_at` date DEFAULT NULL COMMENT 'Updated date',
  `created_time` time DEFAULT NULL COMMENT 'Created Time',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `posts` */

LOCK TABLES `posts` WRITE;

insert  into `posts`(`post_id`,`user_id`,`thumb_url`,`user_post`,`description`,`category_id`,`views`,`stars`,`post_status`,`created_at`,`updated_at`,`created_time`) values 
(1,1,'','http://localhost/talzo/assets/video/movie.mp4','Test',1,1,0,'1','2020-01-08','2020-01-29','11:33:38'),
(2,2,'','https://talzo.s3.ap-south-1.amazonaws.com/posts/1578483218_funlogo.jpeg','Test',1,5,7,'1','2019-12-10','2020-01-09','25:13:12');

UNLOCK TABLES;

/*Table structure for table `reports` */

DROP TABLE IF EXISTS `reports`;

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL COMMENT 'Post Id',
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `reason_id` int(11) NOT NULL COMMENT 'Reason Id',
  `created_at` date NOT NULL COMMENT 'Created BY',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `reports` */

LOCK TABLES `reports` WRITE;

insert  into `reports`(`report_id`,`post_id`,`user_id`,`reason_id`,`created_at`) values 
(1,1,2,1,'2020-01-28');

UNLOCK TABLES;

/*Table structure for table `subscription` */

DROP TABLE IF EXISTS `subscription`;

CREATE TABLE `subscription` (
  `subs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Subscription ID',
  `subs_name` varchar(200) NOT NULL COMMENT 'Subscription Name',
  `price` varchar(20) NOT NULL COMMENT 'Subscription Price',
  `subs_status` enum('0','1') NOT NULL COMMENT '0=unactive 1=active',
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`subs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `subscription` */

LOCK TABLES `subscription` WRITE;

insert  into `subscription`(`subs_id`,`subs_name`,`price`,`subs_status`,`created_at`,`updated_at`) values 
(1,'Test','123','0','2020-01-15','2020-01-25');

UNLOCK TABLES;

/*Table structure for table `user_block` */

DROP TABLE IF EXISTS `user_block`;

CREATE TABLE `user_block` (
  `block_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT 'User ID',
  `block_user_id` int(10) unsigned NOT NULL COMMENT 'Block User Id',
  `block_status` enum('0','1') NOT NULL COMMENT '0=block ,1=unblock',
  `created_at` date NOT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_block` */

LOCK TABLES `user_block` WRITE;

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User Unique ID',
  `name` varchar(50) NOT NULL COMMENT 'User Full Name',
  `email` varchar(50) NOT NULL COMMENT 'User Email',
  `country_code` varchar(10) NOT NULL,
  `country_name` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL COMMENT 'User Phone',
  `user_image` varchar(200) NOT NULL COMMENT 'User Image',
  `bio` text NOT NULL,
  `device_type` enum('android','ios') NOT NULL,
  `device_token` varchar(300) NOT NULL COMMENT 'Device Token',
  `notification_status` enum('0','1') NOT NULL DEFAULT '1',
  `user_token` varchar(15) NOT NULL COMMENT ' User Token',
  `user_status` enum('0','1') NOT NULL COMMENT '0=InActive,1=Active',
  `user_lang` varchar(10) NOT NULL COMMENT 'User Language',
  `socket_id` varchar(255) NOT NULL,
  `chat_with` int(11) NOT NULL,
  `created_at` date NOT NULL COMMENT 'Created Date',
  `updated_at` date DEFAULT NULL COMMENT 'Updated Date',
  `delete_status` enum('0','1') NOT NULL COMMENT 'Deleted Status',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`user_id`,`name`,`email`,`country_code`,`country_name`,`phone`,`user_image`,`bio`,`device_type`,`device_token`,`notification_status`,`user_token`,`user_status`,`user_lang`,`socket_id`,`chat_with`,`created_at`,`updated_at`,`delete_status`) values 
(1,'vishal Tyagi','shubhamtyagi9641@gmail.com','+31','','9871250778','https://talzo.s3.ap-south-1.amazonaws.com/users_image/1578571323_gj.jpg','','android','dpPX-ZWhONg:APA91bGXjYVQUGsocQzFzHUaNLGyJQ_sTo3v_AAFVVciywWTIFbzhSeWFCM4iAQXsU5b-o_6rWvStvuslUZ_LugSQcOiDup43m3Hn-Kqe8lbC53sJwe7F_JzVfwA7uzhk634_UXwuj-7','0','HiqUtk2Wc1','1','en','qUZFGmo4X4GqXNkdAAAB',2,'2020-01-08','2020-01-15','0'),
(2,'Raghu Tyagi','shubhamtyagi9642@gmail.com','+31','','9716429353','https://talzo.s3.ap-south-1.amazonaws.com/posts/1578483218_funlogo.jpeg','','android','','1','HiqUtk2Wcu','1','en','okeyoomjFrgteW9MAAAD',1,'2020-01-08',NULL,'1'),
(3,'Shinu','shinu@gmail.com','+31','IN','9833333333','','','android','','1','HiqUtk2Wcz','1','en','',0,'2020-01-15',NULL,'1'),
(4,'Shinu','saifi','+31','IN','9876553399','','test','android','','1','qwerty','1','en','',0,'2020-01-20','2020-01-20','0');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
