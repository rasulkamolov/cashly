-- Users
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    avatar TEXT,
    currency TEXT DEFAULT 'USD',
    monthly_spending_limit REAL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT CHECK(type IN ('income', 'expense')) NOT NULL,
    amount REAL NOT NULL,
    category TEXT NOT NULL,
    recipient_name TEXT,
    status TEXT CHECK(status IN ('completed', 'pending', 'failed')) DEFAULT 'completed',
    transaction_date DATETIME NOT NULL,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    type TEXT CHECK(type IN ('income', 'expense', 'budget')) NOT NULL,
    icon TEXT
);

-- Cards
CREATE TABLE IF NOT EXISTS cards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    card_number TEXT NOT NULL, -- masked, store only last 4
    cardholder_name TEXT NOT NULL,
    expiry_date TEXT NOT NULL, -- MM/YY
    card_type TEXT NOT NULL, -- visa, mastercard, amex, other
    nickname TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Goals
CREATE TABLE IF NOT EXISTS goals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    target_amount REAL NOT NULL,
    current_amount REAL DEFAULT 0,
    target_date DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Budgets
CREATE TABLE IF NOT EXISTS budgets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category TEXT NOT NULL,
    allocated_amount REAL NOT NULL,
    spent_amount REAL DEFAULT 0,
    month TEXT NOT NULL -- YYYY-MM
);

-- Seed Categories
INSERT INTO categories (name, type, icon) VALUES
('Salary', 'income', 'briefcase'),
('Freelance', 'income', 'laptop'),
('Investments', 'income', 'trending-up'),
('Rental Income', 'income', 'home'),
('Gifts', 'income', 'gift'),
('Refunds', 'income', 'corner-up-left'),
('Other', 'income', 'plus-circle'),

('Food & Grocery', 'expense', 'shopping-cart'),
('Transportation', 'expense', 'bus'),
('Entertainment', 'expense', 'film'),
('Healthcare', 'expense', 'heart'),
('Shopping', 'expense', 'shopping-bag'),
('Bills & Utilities', 'expense', 'zap'),
('Travel', 'expense', 'plane'),
('Education', 'expense', 'book'),
('Subscriptions', 'expense', 'calendar'),
('Other', 'expense', 'more-horizontal'),

('Investment', 'budget', 'trending-up'),
('Travelling', 'budget', 'plane'),
('Food & Grocery', 'budget', 'shopping-cart'),
('Entertainment', 'budget', 'film'),
('Healthcare', 'budget', 'heart');

-- Seed User
INSERT INTO users (name, email, currency, monthly_spending_limit) VALUES
('John Doe', 'john.doe@example.com', 'USD', 5000.00);

-- Seed Transactions (Sample Data)
INSERT INTO transactions (type, amount, category, recipient_name, status, transaction_date, notes) VALUES
('income', 5000.00, 'Salary', 'Tech Corp Inc.', 'completed', date('now', 'start of month', '+1 day'), 'Monthly Salary'),
('expense', 120.50, 'Food & Grocery', 'Whole Foods', 'completed', date('now', '-2 days'), 'Weekly groceries'),
('expense', 45.00, 'Transportation', 'Uber', 'completed', date('now', '-5 days'), 'Ride to airport'),
('expense', 15.99, 'Subscriptions', 'Netflix', 'completed', date('now', '-10 days'), 'Monthly subscription'),
('expense', 250.00, 'Bills & Utilities', 'Electric Company', 'completed', date('now', '-15 days'), 'Electricity bill'),
('income', 200.00, 'Freelance', 'Client X', 'completed', date('now', '-3 days'), 'Logo design'),
('expense', 80.00, 'Entertainment', 'Cinema City', 'completed', date('now', '-1 day'), 'Movie night'),
('expense', 1200.00, 'Other', 'Landlord', 'pending', date('now', '+1 day'), 'Rent payment'),
('expense', 35.00, 'Shopping', 'Amazon', 'completed', date('now', '-7 days'), 'Books');

-- Seed Cards
INSERT INTO cards (card_number, cardholder_name, expiry_date, card_type, nickname) VALUES
('4242', 'John Doe', '12/25', 'visa', 'Primary Visa'),
('8888', 'John Doe', '06/24', 'mastercard', 'Shopping Card');

-- Seed Goals
INSERT INTO goals (name, target_amount, current_amount, target_date) VALUES
('New Car', 25000.00, 5000.00, date('now', '+1 year')),
('Emergency Fund', 10000.00, 2500.00, date('now', '+6 months'));

-- Seed Budgets
INSERT INTO budgets (category, allocated_amount, month) VALUES
('Food & Grocery', 600.00, strftime('%Y-%m', 'now')),
('Entertainment', 300.00, strftime('%Y-%m', 'now')),
('Healthcare', 200.00, strftime('%Y-%m', 'now'));
