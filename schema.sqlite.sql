CREATE TABLE IF NOT EXISTS assets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    code TEXT NOT NULL DEFAULT '',
    category TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'in_use',
    location TEXT NOT NULL DEFAULT '',
    purchase_date TEXT NOT NULL,
    notes TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL
);
