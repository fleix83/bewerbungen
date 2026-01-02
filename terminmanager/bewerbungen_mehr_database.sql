-- ============================================
-- BEWERBUNGEN & MEHR - Terminbuchungssystem
-- Datenbankstruktur v1.0
-- 
-- Slots: Mo-So, 8:00 - 22:00 Uhr (14 Stunden-Slots)
-- ============================================

-- Datenbank erstellen (optional, falls noch nicht vorhanden)
-- CREATE DATABASE IF NOT EXISTS bewerbungen_mehr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE bewerbungen_mehr;

-- ============================================
-- TABELLEN LÖSCHEN (falls vorhanden)
-- ============================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS blocked_dates;
DROP TABLE IF EXISTS availability_settings;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS event_types;
DROP TABLE IF EXISTS users;
DROP VIEW IF EXISTS v_available_slots;
DROP VIEW IF EXISTS v_daily_schedule;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 1. BENUTZER (du und Mitmieterin)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. EVENT-TYPEN (verschiedene Terminarten)
-- ============================================
CREATE TABLE event_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    color VARCHAR(7) DEFAULT '#FFD700',
    blocks_availability BOOLEAN DEFAULT TRUE,
    is_customer_bookable BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. KUNDEN (für Bewerbungen & Mehr)
-- ============================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_number VARCHAR(20) UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(30),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_customer_number (customer_number),
    INDEX idx_customer_name (last_name, first_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. SERVICES/DIENSTLEISTUNGEN
-- ============================================
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    duration_slots INT DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. VERFÜGBARKEITS-EINSTELLUNGEN
-- ============================================
CREATE TABLE availability_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    day_of_week TINYINT NOT NULL,
    start_slot TINYINT NOT NULL,
    end_slot TINYINT NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_day (user_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. FEIERTAGE/BLOCKIERTE TAGE
-- ============================================
CREATE TABLE blocked_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    blocked_date DATE NOT NULL,
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_blocked_date (blocked_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. EVENTS/TERMINE (Haupttabelle)
-- ============================================
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_type_id INT NOT NULL,
    
    -- Zeitinformationen
    event_date DATE NOT NULL,
    start_slot TINYINT NOT NULL,
    end_slot TINYINT NOT NULL,
    
    -- Optionale Kundenverknüpfung
    customer_id INT NULL,
    
    -- Allgemeine Felder
    title VARCHAR(255),
    notes TEXT,
    
    -- Status
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    
    -- Metadaten
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_type_id) REFERENCES event_types(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    
    INDEX idx_availability (user_id, event_date, start_slot, end_slot),
    INDEX idx_event_date (event_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. BUCHUNGEN (Services pro Termin)
-- ============================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    service_id INT NOT NULL,
    price_at_booking DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INITIALE DATEN
-- ============================================

-- Benutzer anlegen
INSERT INTO users (username, display_name, email, phone) VALUES 
('felix', 'Felix Weissheimer', 'felix@bewerbungenundmehr.ch', '076 575 60 52'),
('mitmieterin', 'Mitmieterin', NULL, NULL);

-- Event-Typen für Felix (user_id = 1)
INSERT INTO event_types (user_id, name, color, blocks_availability, is_customer_bookable, sort_order) VALUES 
(1, 'Termine Kunden Bewerbungen & Mehr', '#FFD700', TRUE, TRUE, 1),
(1, 'Arbeit Web Kunden', '#4A90D9', TRUE, FALSE, 2),
(1, 'Private Projekte', '#7B68EE', TRUE, FALSE, 3),
(1, 'Custom Termine', '#20B2AA', TRUE, FALSE, 4),
(1, 'Präsenzzeit Raum', '#90EE90', FALSE, FALSE, 5);

-- Event-Typen für Mitmieterin (user_id = 2)
INSERT INTO event_types (user_id, name, color, blocks_availability, is_customer_bookable, sort_order) VALUES 
(2, 'Kundentermine', '#FF6B6B', TRUE, FALSE, 1),
(2, 'Projektarbeit', '#4ECDC4', TRUE, FALSE, 2),
(2, 'Custom Termine', '#20B2AA', TRUE, FALSE, 3),
(2, 'Präsenzzeit Raum', '#90EE90', FALSE, FALSE, 4);

-- Services/Dienstleistungen
INSERT INTO services (name, duration_slots, price, sort_order) VALUES 
('Lebenslauf', 1, 30.00, 1),
('Bewerbungsschreiben', 1, 30.00, 2),
('Etwas anderes', 1, 30.00, 3);

-- Standard-Verfügbarkeit Felix (Mo-So, 8-22 Uhr)
INSERT INTO availability_settings (user_id, day_of_week, start_slot, end_slot) VALUES 
(1, 0, 8, 22),  -- Sonntag
(1, 1, 8, 22),  -- Montag
(1, 2, 8, 22),  -- Dienstag
(1, 3, 8, 22),  -- Mittwoch
(1, 4, 8, 22),  -- Donnerstag
(1, 5, 8, 22),  -- Freitag
(1, 6, 8, 22);  -- Samstag

-- Standard-Verfügbarkeit Mitmieterin (Mo-So, 8-22 Uhr)
INSERT INTO availability_settings (user_id, day_of_week, start_slot, end_slot) VALUES 
(2, 0, 8, 22),  -- Sonntag
(2, 1, 8, 22),  -- Montag
(2, 2, 8, 22),  -- Dienstag
(2, 3, 8, 22),  -- Mittwoch
(2, 4, 8, 22),  -- Donnerstag
(2, 5, 8, 22),  -- Freitag
(2, 6, 8, 22);  -- Samstag

-- Schweizer Feiertage 2026 (Basel-Stadt)
INSERT INTO blocked_dates (user_id, blocked_date, reason) VALUES 
(NULL, '2026-01-01', 'Neujahr'),
(NULL, '2026-01-02', 'Berchtoldstag'),
(NULL, '2026-04-03', 'Karfreitag'),
(NULL, '2026-04-06', 'Ostermontag'),
(NULL, '2026-05-01', 'Tag der Arbeit'),
(NULL, '2026-05-14', 'Auffahrt'),
(NULL, '2026-05-25', 'Pfingstmontag'),
(NULL, '2026-08-01', 'Nationalfeiertag'),
(NULL, '2026-12-25', 'Weihnachten'),
(NULL, '2026-12-26', 'Stephanstag');

-- ============================================
-- VIEWS
-- ============================================

-- View: Freie Slots für Kundenbuchungen (User 1 = Felix)
CREATE VIEW v_available_slots AS
SELECT 
    d.date_value AS event_date,
    s.slot_hour AS start_slot,
    s.slot_hour + 1 AS end_slot,
    CONCAT(
        LPAD(s.slot_hour, 2, '0'), ':00 - ', 
        LPAD(s.slot_hour + 1, 2, '0'), ':00'
    ) AS time_display
FROM (
    -- Generiere Daten für die nächsten 60 Tage
    SELECT DATE_ADD(CURDATE(), INTERVAL n DAY) AS date_value
    FROM (
        SELECT a.N + b.N * 10 AS n
        FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
              UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
             (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) b
    ) numbers
) d
CROSS JOIN (
    -- Slots von 8:00 bis 21:00 (letzter Slot endet um 22:00)
    SELECT 8 AS slot_hour UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 
    UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 
    UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 
    UNION SELECT 20 UNION SELECT 21
) s
WHERE d.date_value NOT IN (
    SELECT blocked_date FROM blocked_dates 
    WHERE user_id = 1 OR user_id IS NULL
)
AND NOT EXISTS (
    SELECT 1 FROM events e
    JOIN event_types et ON e.event_type_id = et.id
    WHERE e.user_id = 1
    AND e.event_date = d.date_value
    AND et.blocks_availability = TRUE
    AND e.status != 'cancelled'
    AND s.slot_hour >= e.start_slot 
    AND s.slot_hour < e.end_slot
);

-- View: Tagesübersicht für Kalender
CREATE VIEW v_daily_schedule AS
SELECT 
    e.id,
    e.user_id,
    u.display_name AS user_name,
    e.event_date,
    e.start_slot,
    e.end_slot,
    CONCAT(
        LPAD(e.start_slot, 2, '0'), ':00 - ', 
        LPAD(e.end_slot, 2, '0'), ':00'
    ) AS time_display,
    et.id AS event_type_id,
    et.name AS event_type,
    et.color,
    et.is_customer_bookable,
    et.blocks_availability,
    c.id AS customer_id,
    c.customer_number,
    c.first_name AS customer_first_name,
    c.last_name AS customer_last_name,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    c.email AS customer_email,
    c.phone AS customer_phone,
    e.title,
    e.notes,
    e.status,
    e.created_at,
    e.updated_at
FROM events e
JOIN users u ON e.user_id = u.id
JOIN event_types et ON e.event_type_id = et.id
LEFT JOIN customers c ON e.customer_id = c.id
ORDER BY e.event_date, e.start_slot;

-- View: Buchungsübersicht mit Services
CREATE VIEW v_booking_details AS
SELECT 
    e.id AS event_id,
    e.event_date,
    e.start_slot,
    e.end_slot,
    CONCAT(
        LPAD(e.start_slot, 2, '0'), ':00 - ', 
        LPAD(e.end_slot, 2, '0'), ':00'
    ) AS time_display,
    c.customer_number,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    c.email AS customer_email,
    c.phone AS customer_phone,
    s.name AS service_name,
    b.price_at_booking,
    b.notes AS booking_notes,
    e.status,
    e.created_at
FROM events e
JOIN event_types et ON e.event_type_id = et.id
JOIN customers c ON e.customer_id = c.id
JOIN bookings b ON e.id = b.event_id
JOIN services s ON b.service_id = s.id
WHERE et.is_customer_bookable = TRUE
ORDER BY e.event_date DESC, e.start_slot;

-- ============================================
-- HILFSFUNKTIONEN / STORED PROCEDURES
-- ============================================

DELIMITER //

-- Prozedur: Prüfe ob Slots verfügbar sind
CREATE PROCEDURE sp_check_availability(
    IN p_user_id INT,
    IN p_event_date DATE,
    IN p_start_slot TINYINT,
    IN p_end_slot TINYINT,
    OUT p_is_available BOOLEAN
)
BEGIN
    DECLARE conflict_count INT;
    
    -- Prüfe auf blockierte Tage
    SELECT COUNT(*) INTO conflict_count
    FROM blocked_dates
    WHERE blocked_date = p_event_date
    AND (user_id = p_user_id OR user_id IS NULL);
    
    IF conflict_count > 0 THEN
        SET p_is_available = FALSE;
    ELSE
        -- Prüfe auf überlappende Events
        SELECT COUNT(*) INTO conflict_count
        FROM events e
        JOIN event_types et ON e.event_type_id = et.id
        WHERE e.user_id = p_user_id
        AND e.event_date = p_event_date
        AND et.blocks_availability = TRUE
        AND e.status != 'cancelled'
        AND (
            (p_start_slot >= e.start_slot AND p_start_slot < e.end_slot)
            OR (p_end_slot > e.start_slot AND p_end_slot <= e.end_slot)
            OR (p_start_slot <= e.start_slot AND p_end_slot >= e.end_slot)
        );
        
        SET p_is_available = (conflict_count = 0);
    END IF;
END //

-- Prozedur: Erstelle Kundenbuchung
CREATE PROCEDURE sp_create_customer_booking(
    IN p_customer_number VARCHAR(20),
    IN p_first_name VARCHAR(100),
    IN p_last_name VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_phone VARCHAR(30),
    IN p_event_date DATE,
    IN p_start_slot TINYINT,
    IN p_end_slot TINYINT,
    IN p_service_ids VARCHAR(255),
    IN p_notes TEXT,
    OUT p_event_id INT,
    OUT p_success BOOLEAN,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_customer_id INT;
    DECLARE v_is_available BOOLEAN;
    DECLARE v_event_type_id INT;
    DECLARE v_service_id INT;
    DECLARE v_service_price DECIMAL(10,2);
    DECLARE v_pos INT DEFAULT 1;
    DECLARE v_len INT;
    DECLARE v_item VARCHAR(10);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_success = FALSE;
        SET p_message = 'Datenbankfehler bei der Buchung';
    END;
    
    START TRANSACTION;
    
    -- Prüfe Verfügbarkeit
    CALL sp_check_availability(1, p_event_date, p_start_slot, p_end_slot, v_is_available);
    
    IF NOT v_is_available THEN
        SET p_success = FALSE;
        SET p_message = 'Der gewählte Zeitraum ist nicht mehr verfügbar';
        ROLLBACK;
    ELSE
        -- Kunde anlegen oder finden
        SELECT id INTO v_customer_id FROM customers 
        WHERE email = p_email OR (first_name = p_first_name AND last_name = p_last_name)
        LIMIT 1;
        
        IF v_customer_id IS NULL THEN
            INSERT INTO customers (customer_number, first_name, last_name, email, phone)
            VALUES (p_customer_number, p_first_name, p_last_name, p_email, p_phone);
            SET v_customer_id = LAST_INSERT_ID();
        END IF;
        
        -- Event-Type für Kundenbuchungen holen
        SELECT id INTO v_event_type_id FROM event_types 
        WHERE user_id = 1 AND is_customer_bookable = TRUE
        LIMIT 1;
        
        -- Event erstellen
        INSERT INTO events (user_id, event_type_id, event_date, start_slot, end_slot, customer_id, notes, status)
        VALUES (1, v_event_type_id, p_event_date, p_start_slot, p_end_slot, v_customer_id, p_notes, 'confirmed');
        
        SET p_event_id = LAST_INSERT_ID();
        
        -- Services verarbeiten (kommagetrennte IDs)
        SET v_len = LENGTH(p_service_ids);
        
        WHILE v_pos <= v_len DO
            SET v_item = SUBSTRING_INDEX(SUBSTRING_INDEX(p_service_ids, ',', v_pos), ',', -1);
            SET v_service_id = CAST(TRIM(v_item) AS UNSIGNED);
            
            IF v_service_id > 0 THEN
                SELECT price INTO v_service_price FROM services WHERE id = v_service_id;
                
                INSERT INTO bookings (event_id, service_id, price_at_booking)
                VALUES (p_event_id, v_service_id, v_service_price);
            END IF;
            
            SET v_pos = v_pos + 1;
            
            -- Abbruch wenn keine weiteren Kommas
            IF LOCATE(',', p_service_ids, v_pos) = 0 AND v_pos <= v_len THEN
                SET v_item = SUBSTRING(p_service_ids, v_pos);
                SET v_service_id = CAST(TRIM(v_item) AS UNSIGNED);
                
                IF v_service_id > 0 THEN
                    SELECT price INTO v_service_price FROM services WHERE id = v_service_id;
                    
                    INSERT INTO bookings (event_id, service_id, price_at_booking)
                    VALUES (p_event_id, v_service_id, v_service_price);
                END IF;
                
                SET v_pos = v_len + 1;
            END IF;
        END WHILE;
        
        COMMIT;
        SET p_success = TRUE;
        SET p_message = 'Buchung erfolgreich erstellt';
    END IF;
END //

-- Prozedur: Hole freie Slots für ein Datum
CREATE PROCEDURE sp_get_available_slots_for_date(
    IN p_user_id INT,
    IN p_event_date DATE,
    IN p_required_slots INT
)
BEGIN
    SELECT 
        s.slot_hour AS start_slot,
        s.slot_hour + p_required_slots AS end_slot,
        CONCAT(
            LPAD(s.slot_hour, 2, '0'), ':00 - ', 
            LPAD(s.slot_hour + p_required_slots, 2, '0'), ':00'
        ) AS time_display,
        CASE 
            WHEN bd.id IS NOT NULL THEN 'blocked_day'
            WHEN e.id IS NOT NULL THEN 'occupied'
            ELSE 'available'
        END AS status
    FROM (
        SELECT 8 AS slot_hour UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 
        UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 
        UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 
        UNION SELECT 20 UNION SELECT 21
    ) s
    LEFT JOIN blocked_dates bd ON bd.blocked_date = p_event_date 
        AND (bd.user_id = p_user_id OR bd.user_id IS NULL)
    LEFT JOIN events e ON e.user_id = p_user_id 
        AND e.event_date = p_event_date
        AND e.status != 'cancelled'
        AND s.slot_hour >= e.start_slot 
        AND s.slot_hour < e.end_slot
        AND e.event_type_id IN (
            SELECT id FROM event_types WHERE blocks_availability = TRUE
        )
    WHERE s.slot_hour + p_required_slots <= 22
    ORDER BY s.slot_hour;
END //

DELIMITER ;

-- ============================================
-- BEISPIEL-DATEN (zum Testen)
-- ============================================

-- Beispiel-Kunde
INSERT INTO customers (customer_number, first_name, last_name, email, phone) VALUES 
('K-2026-001', 'Max', 'Mustermann', 'max.mustermann@example.com', '079 123 45 67');

-- Beispiel-Event (Kundentermin)
INSERT INTO events (user_id, event_type_id, event_date, start_slot, end_slot, customer_id, title, notes, status) VALUES 
(1, 1, '2026-01-07', 14, 16, 1, 'Lebenslauf + Bewerbung', 'Bewerbung für Stelle als Projektleiter', 'confirmed');

-- Beispiel-Buchungen für den Event
INSERT INTO bookings (event_id, service_id, price_at_booking) VALUES 
(1, 1, 30.00),  -- Lebenslauf
(1, 2, 30.00);  -- Bewerbungsschreiben

-- Beispiel: Eigener Termin (Web Kunde)
INSERT INTO events (user_id, event_type_id, event_date, start_slot, end_slot, title, notes, status) VALUES 
(1, 2, '2026-01-08', 10, 12, 'Website Redesign Kunde XY', 'Besprechung neues Layout', 'confirmed');

-- Beispiel: Präsenzzeit (blockiert NICHT)
INSERT INTO events (user_id, event_type_id, event_date, start_slot, end_slot, title, status) VALUES 
(1, 5, '2026-01-08', 8, 18, 'Bürotag', 'confirmed');

-- ============================================
-- ABSCHLUSS
-- ============================================

SELECT 'Datenbank erfolgreich erstellt!' AS status;
SELECT COUNT(*) AS anzahl_users FROM users;
SELECT COUNT(*) AS anzahl_event_types FROM event_types;
SELECT COUNT(*) AS anzahl_services FROM services;
SELECT COUNT(*) AS anzahl_feiertage FROM blocked_dates;
