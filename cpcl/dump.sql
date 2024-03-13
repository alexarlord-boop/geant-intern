--- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: simplesamlphp
-- ------------------------------------------------------
-- Server version       10.11.6-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adfs_idp_hosted`
--

DROP TABLE IF EXISTS `adfs_idp_hosted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adfs_idp_hosted` (
                                   `entity_id` varchar(255) NOT NULL,
                                   `entity_data` mediumtext NOT NULL,
                                   PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adfs_idp_hosted`
--

LOCK TABLES `adfs_idp_hosted` WRITE;
/*!40000 ALTER TABLE `adfs_idp_hosted` DISABLE KEYS */;
/*!40000 ALTER TABLE `adfs_idp_hosted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adfs_sp_remote`
--

DROP TABLE IF EXISTS `adfs_sp_remote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adfs_sp_remote` (
                                  `entity_id` varchar(255) NOT NULL,
                                  `entity_data` mediumtext NOT NULL,
                                  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adfs_sp_remote`
--

LOCK TABLES `adfs_sp_remote` WRITE;
/*!40000 ALTER TABLE `adfs_sp_remote` DISABLE KEYS */;
/*!40000 ALTER TABLE `adfs_sp_remote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_access_token`
--

DROP TABLE IF EXISTS `oidc_access_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_access_token` (
                                     `id` varchar(191) NOT NULL,
                                     `scopes` text DEFAULT NULL,
                                     `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                     `user_id` varchar(191) NOT NULL,
                                     `client_id` varchar(191) NOT NULL,
                                     `is_revoked` tinyint(1) NOT NULL DEFAULT 0,
                                     `auth_code_id` varchar(191) DEFAULT NULL,
                                     `requested_claims` text DEFAULT NULL,
                                     PRIMARY KEY (`id`),
                                     KEY `FK_43C1650EA76ED395` (`user_id`),
                                     KEY `FK_43C1650E19EB6921` (`client_id`),
                                     CONSTRAINT `FK_43C1650E19EB6921` FOREIGN KEY (`client_id`) REFERENCES `oidc_client` (`id`) ON DELETE CASCADE,
                                     CONSTRAINT `FK_43C1650EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `oidc_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_access_token`
--

LOCK TABLES `oidc_access_token` WRITE;
/*!40000 ALTER TABLE `oidc_access_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_access_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_allowed_origin`
--

DROP TABLE IF EXISTS `oidc_allowed_origin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_allowed_origin` (
                                       `client_id` varchar(191) NOT NULL,
                                       `origin` varchar(191) NOT NULL,
                                       PRIMARY KEY (`client_id`,`origin`),
                                       CONSTRAINT `FK_A027AF1E19EB6921` FOREIGN KEY (`client_id`) REFERENCES `oidc_client` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_allowed_origin`
--

LOCK TABLES `oidc_allowed_origin` WRITE;
/*!40000 ALTER TABLE `oidc_allowed_origin` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_allowed_origin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_auth_code`
--

DROP TABLE IF EXISTS `oidc_auth_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_auth_code` (
                                  `id` varchar(191) NOT NULL,
                                  `scopes` text DEFAULT NULL,
                                  `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                  `user_id` varchar(191) NOT NULL,
                                  `client_id` varchar(191) NOT NULL,
                                  `is_revoked` tinyint(1) NOT NULL DEFAULT 0,
                                  `redirect_uri` text NOT NULL,
                                  `nonce` text DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `FK_97D32CA7A76ED395` (`user_id`),
                                  KEY `FK_97D32CA719EB6921` (`client_id`),
                                  CONSTRAINT `FK_97D32CA719EB6921` FOREIGN KEY (`client_id`) REFERENCES `oidc_client` (`id`) ON DELETE CASCADE,
                                  CONSTRAINT `FK_97D32CA7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `oidc_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_auth_code`
--

LOCK TABLES `oidc_auth_code` WRITE;
/*!40000 ALTER TABLE `oidc_auth_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_auth_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_client`
--

DROP TABLE IF EXISTS `oidc_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_client` (
                               `id` varchar(191) NOT NULL,
                               `secret` varchar(255) NOT NULL,
                               `name` varchar(255) NOT NULL,
                               `description` varchar(255) NOT NULL,
                               `auth_source` varchar(255) DEFAULT NULL,
                               `redirect_uri` text NOT NULL,
                               `scopes` text NOT NULL,
                               `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
                               `is_confidential` tinyint(1) NOT NULL DEFAULT 0,
                               `owner` varchar(191) DEFAULT NULL,
                               `post_logout_redirect_uri` text DEFAULT NULL,
                               `backchannel_logout_uri` text DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_client`
--

LOCK TABLES `oidc_client` WRITE;
/*!40000 ALTER TABLE `oidc_client` DISABLE KEYS */;
INSERT INTO `oidc_client` VALUES
    ('_ccc2f28ef0243aadd606324a7ed9b2492f76014ae4','_ce393694c94fc7a63e0f46789e2050e24ae90b1368','Test RP','','default-sp','[\"https:\\/\\/alpe4.incubator.geant.org\\/simplesaml\\/module.php\\/authoauth2\\/linkback.php\"]','[\"openid\",\"email\",\"private\"]',1,1,NULL,'[]',NULL);
/*!40000 ALTER TABLE `oidc_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_migration_versions`
--

DROP TABLE IF EXISTS `oidc_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_migration_versions` (
                                           `version` varchar(191) NOT NULL,
                                           PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_migration_versions`
--

LOCK TABLES `oidc_migration_versions` WRITE;
/*!40000 ALTER TABLE `oidc_migration_versions` DISABLE KEYS */;
INSERT INTO `oidc_migration_versions` VALUES
                                          ('20180305180300'),
                                          ('20180425203400'),
                                          ('20200517071100'),
                                          ('20200901163000'),
                                          ('20210714113000'),
                                          ('20210823141300'),
                                          ('20210827111300'),
                                          ('20210902113500'),
                                          ('20210908143500'),
                                          ('20210916153400'),
                                          ('20210916173400');
/*!40000 ALTER TABLE `oidc_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_refresh_token`
--

DROP TABLE IF EXISTS `oidc_refresh_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_refresh_token` (
                                      `id` varchar(191) NOT NULL,
                                      `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                      `access_token_id` varchar(191) NOT NULL,
                                      `is_revoked` tinyint(1) NOT NULL DEFAULT 0,
                                      `auth_code_id` varchar(191) DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `FK_636B86402CCB2688` (`access_token_id`),
                                      CONSTRAINT `FK_636B86402CCB2688` FOREIGN KEY (`access_token_id`) REFERENCES `oidc_access_token` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_refresh_token`
--

LOCK TABLES `oidc_refresh_token` WRITE;
/*!40000 ALTER TABLE `oidc_refresh_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_refresh_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_session_logout_ticket`
--

DROP TABLE IF EXISTS `oidc_session_logout_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_session_logout_ticket` (
                                              `sid` varchar(191) NOT NULL,
                                              `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_session_logout_ticket`
--

LOCK TABLES `oidc_session_logout_ticket` WRITE;
/*!40000 ALTER TABLE `oidc_session_logout_ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_session_logout_ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oidc_user`
--

DROP TABLE IF EXISTS `oidc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oidc_user` (
                             `id` varchar(191) NOT NULL,
                             `claims` text DEFAULT NULL,
                             `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
                             `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oidc_user`
--

LOCK TABLES `oidc_user` WRITE;
/*!40000 ALTER TABLE `oidc_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `oidc_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saml20_idp_hosted`
--

DROP TABLE IF EXISTS `saml20_idp_hosted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saml20_idp_hosted` (
                                     `entity_id` varchar(255) NOT NULL,
                                     `entity_data` mediumtext NOT NULL,
                                     PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saml20_idp_hosted`
--

LOCK TABLES `saml20_idp_hosted` WRITE;
/*!40000 ALTER TABLE `saml20_idp_hosted` DISABLE KEYS */;
/*!40000 ALTER TABLE `saml20_idp_hosted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saml20_idp_remote`
--

DROP TABLE IF EXISTS `saml20_idp_remote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saml20_idp_remote` (
                                     `entity_id` varchar(255) NOT NULL,
                                     `entity_data` mediumtext NOT NULL,
                                     PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saml20_idp_remote`
--

LOCK TABLES `saml20_idp_remote` WRITE;
/*!40000 ALTER TABLE `saml20_idp_remote` DISABLE KEYS */;
/*!40000 ALTER TABLE `saml20_idp_remote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saml20_sp_remote`
--

DROP TABLE IF EXISTS `saml20_sp_remote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saml20_sp_remote` (
                                    `entity_id` varchar(255) NOT NULL,
                                    `entity_data` mediumtext NOT NULL,
                                    PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saml20_sp_remote`
--

LOCK TABLES `saml20_sp_remote` WRITE;
/*!40000 ALTER TABLE `saml20_sp_remote` DISABLE KEYS */;
/*!40000 ALTER TABLE `saml20_sp_remote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-18 10:02:21






