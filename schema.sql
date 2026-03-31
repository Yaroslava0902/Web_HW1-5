-- ============================================================
-- schema.sql — Структура бази даних для сайту Genomics DB
-- Виконайте цей файл один раз: mysql -u root -p < schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS genomics_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE genomics_db;

-- ── Таблиця SNP-варіантів ────────────────────────────────
CREATE TABLE IF NOT EXISTS snp_variants (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rs_id        VARCHAR(20)  NOT NULL UNIQUE COMMENT 'dbSNP identifier, напр. rs334',
    gene         VARCHAR(50)  NOT NULL COMMENT 'Назва гену, напр. HBB',
    chromosome   TINYINT UNSIGNED NOT NULL COMMENT 'Номер хромосоми (1–22, 23=X, 24=Y)',
    position     BIGINT UNSIGNED  NOT NULL COMMENT 'Позиція в геномі (GRCh38)',
    ref_allele   CHAR(1)      NOT NULL COMMENT 'Референсний нуклеотид',
    alt_allele   CHAR(1)      NOT NULL COMMENT 'Альтернативний нуклеотид',
    maf          DECIMAL(6,4) DEFAULT NULL COMMENT 'Minor Allele Frequency (0–1)',
    consequence  ENUM('missense','nonsense','silent','splice','intergenic','regulatory')
                 NOT NULL DEFAULT 'missense',
    disease      VARCHAR(255) DEFAULT NULL COMMENT 'Асоційоване захворювання',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Таблиця контактних запитів (AJAX-форма) ──────────────
CREATE TABLE IF NOT EXISTS contact_requests (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    subject    VARCHAR(200) DEFAULT NULL,
    message    TEXT NOT NULL,
    ip_address VARCHAR(45)  DEFAULT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Тестові дані SNP ─────────────────────────────────────
INSERT IGNORE INTO snp_variants
    (rs_id, gene, chromosome, position, ref_allele, alt_allele, maf, consequence, disease)
VALUES
    ('rs334',      'HBB',   11, 5246696,   'A', 'T', 0.0024, 'missense',   'Серпоподібноклітинна анемія'),
    ('rs1801516',  'ATM',   11, 108236235, 'G', 'A', 0.0150, 'missense',   'Радіочутливість / рак молочної залози'),
    ('rs25487',    'XRCC1', 19, 43549038,  'G', 'A', 0.3500, 'missense',   'Ризик після радіотерапії'),
    ('rs2788612',  'KCND3',  1, 112128405, 'C', 'T', 0.0500, 'intergenic', 'Ректальне нетримання після ПТ'),
    ('rs13116075', 'CCRN4L', 4, 140287637, 'A', 'G', 0.1500, 'intergenic', 'Загальна токсичність радіотерапії'),
    ('rs1042522',  'TP53',  17, 7676154,   'G', 'C', 0.3900, 'missense',   'Онкологічна схильність'),
    ('rs28897696', 'BRCA1', 17, 43094692,  'C', 'T', 0.0010, 'nonsense',   'Рак молочної залози / яєчників'),
    ('rs80357914', 'BRCA2', 13, 32338765,  'A', 'T', 0.0008, 'nonsense',   'Рак молочної залози / яєчників'),
    ('rs12353488', 'unknown',9, 83705231,  'A', 'G', 0.1100, 'intergenic', 'Частота сечовипускання після ПТ'),
    ('rs4655套',   'RAD51', 15, 41282134,  'G', 'A', 0.0900, 'missense',   'Відповідь на опромінення');

-- виправляємо останній рядок (кирилиця в rs_id — лише для прикладу, замінюємо)
DELETE FROM snp_variants WHERE rs_id LIKE '%套%';
INSERT IGNORE INTO snp_variants
    (rs_id, gene, chromosome, position, ref_allele, alt_allele, maf, consequence, disease)
VALUES
    ('rs1801321', 'RAD51', 15, 41282134, 'G', 'T', 0.0900, 'missense', 'Відповідь на опромінення');
