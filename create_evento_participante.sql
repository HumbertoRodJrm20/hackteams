CREATE TABLE IF NOT EXISTS `evento_participante` (
  `evento_id` bigint unsigned NOT NULL,
  `participante_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`evento_id`, `participante_id`),
  KEY `fk_evento_participante_participante` (`participante_id`),
  CONSTRAINT `fk_evento_participante_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_evento_participante_participante` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
