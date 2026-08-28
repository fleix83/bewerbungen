-- ============================================
-- MIGRATION: Automatic free slot generation
-- Free slots are no longer generated manually into free_slots.
-- Instead, every slot inside the weekly schedule
-- (availability_settings) is free by default, minus:
--   - manually blocked slots (blocked_slots, via admin Slot Manager)
--   - blocking events (bookings / orga)
--   - blocked dates (holidays)
--
-- Weekly schedule:
--   Mo 14-17, Di 8-12, Mi 14-17, Do 8-14, Fr 8-16, Sa+So closed
-- ============================================

-- 1. New weekly schedule for user 1
UPDATE availability_settings SET start_slot = 14, end_slot = 17, active = TRUE  WHERE user_id = 1 AND day_of_week = 1; -- Montag
UPDATE availability_settings SET start_slot = 8,  end_slot = 12, active = TRUE  WHERE user_id = 1 AND day_of_week = 2; -- Dienstag
UPDATE availability_settings SET start_slot = 14, end_slot = 17, active = TRUE  WHERE user_id = 1 AND day_of_week = 3; -- Mittwoch
UPDATE availability_settings SET start_slot = 8,  end_slot = 14, active = TRUE  WHERE user_id = 1 AND day_of_week = 4; -- Donnerstag
UPDATE availability_settings SET start_slot = 8,  end_slot = 16, active = TRUE  WHERE user_id = 1 AND day_of_week = 5; -- Freitag
UPDATE availability_settings SET active = FALSE                                 WHERE user_id = 1 AND day_of_week IN (0, 6); -- So, Sa

-- 2. Manually blocked slots (replaces the free_slots release model)
CREATE TABLE IF NOT EXISTS blocked_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL DEFAULT 1,
    slot_date DATE NOT NULL,
    slot_hour TINYINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(50) DEFAULT 'admin',

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_blocked_slot (user_id, slot_date, slot_hour),
    INDEX idx_blocked_slot_date (slot_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Schedule-driven availability view (rolling 90 days)
DROP VIEW IF EXISTS v_available_slots;

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
    -- Heute + 89 Tage
    SELECT DATE_ADD(CURDATE(), INTERVAL a.N + b.N * 10 DAY) AS date_value
    FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
          UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
         (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
          UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8) b
) d
CROSS JOIN (
    SELECT 8 AS slot_hour UNION SELECT 9 UNION SELECT 10 UNION SELECT 11
    UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15
    UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
    UNION SELECT 20 UNION SELECT 21
) s
-- Nur Slots innerhalb des Wochenplans (day_of_week: 0=So .. 6=Sa)
JOIN availability_settings av
    ON av.user_id = 1
    AND av.active = TRUE
    AND av.day_of_week = DAYOFWEEK(d.date_value) - 1
    AND s.slot_hour >= av.start_slot
    AND s.slot_hour < av.end_slot
-- Feiertage / gesperrte Tage
WHERE d.date_value NOT IN (
    SELECT blocked_date FROM blocked_dates
    WHERE user_id = 1 OR user_id IS NULL
)
-- Manuell blockierte Slots (Slot Manager)
AND NOT EXISTS (
    SELECT 1 FROM blocked_slots bs
    WHERE bs.user_id = 1
    AND bs.slot_date = d.date_value
    AND bs.slot_hour = s.slot_hour
)
-- Belegte Slots (Buchungen / blockierende Events)
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

-- 4. free_slots ist obsolet (Freigabe-Modell abgeloest)
DROP TABLE IF EXISTS free_slots;

-- 5. Verification
SELECT 'Migration 002_automatic_slots completed successfully!' AS status;
SELECT day_of_week, start_slot, end_slot, active
FROM availability_settings WHERE user_id = 1 ORDER BY day_of_week;
