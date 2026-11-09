-- ============================================
-- MIGRATION: Prices as on the Preise page
-- CHF 50 per document, "Formular ausfüllen" stays CHF 30.
-- Services not listed on the Preise page (Mac/PC support,
-- Portfolio Webseite, Webapp) keep their price.
-- price_at_booking of existing bookings is not changed.
-- ============================================

UPDATE services SET price = 50.00
WHERE name IN (
    'Lebenslauf',
    'Bewerbungsschreiben',
    'Etwas anderes',
    'Brief, persönlich',
    'Brief, Korrespondenz, administrativ',
    'Steuererklärung'
);

UPDATE services SET price = 30.00 WHERE name = 'Formular ausfüllen';
