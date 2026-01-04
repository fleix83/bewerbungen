-- ============================================
-- MIGRATION: Add free_slots table and update views
-- Run this after the main database schema is created
-- ============================================

-- 1. Create the free_slots table
CREATE TABLE IF NOT EXISTS free_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL DEFAULT 1,
    slot_date DATE NOT NULL,
    slot_hour TINYINT NOT NULL,  -- 8-16 (hour when slot starts, ends at slot_hour + 1)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(50) DEFAULT 'system',

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_slot (user_id, slot_date, slot_hour),
    INDEX idx_slot_date (slot_date),
    INDEX idx_user_date (user_id, slot_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Drop and recreate v_available_slots to use free_slots table
DROP VIEW IF EXISTS v_available_slots;

CREATE VIEW v_available_slots AS
SELECT
    fs.slot_date AS event_date,
    fs.slot_hour AS start_slot,
    fs.slot_hour + 1 AS end_slot,
    CONCAT(
        LPAD(fs.slot_hour, 2, '0'), ':00 - ',
        LPAD(fs.slot_hour + 1, 2, '0'), ':00'
    ) AS time_display
FROM free_slots fs
WHERE fs.user_id = 1
-- Exclude blocked dates (holidays)
AND fs.slot_date NOT IN (
    SELECT blocked_date FROM blocked_dates
    WHERE user_id = 1 OR user_id IS NULL
)
-- Exclude slots already booked by blocking events
AND NOT EXISTS (
    SELECT 1 FROM events e
    JOIN event_types et ON e.event_type_id = et.id
    WHERE e.user_id = 1
    AND e.event_date = fs.slot_date
    AND et.blocks_availability = TRUE
    AND e.status != 'cancelled'
    AND fs.slot_hour >= e.start_slot
    AND fs.slot_hour < e.end_slot
)
-- Only show future dates (or today)
AND fs.slot_date >= CURDATE();

-- 3. Verification
SELECT 'Migration 001_add_free_slots completed successfully!' AS status;
SELECT COUNT(*) AS free_slots_table_exists FROM information_schema.tables
WHERE table_schema = DATABASE() AND table_name = 'free_slots';
