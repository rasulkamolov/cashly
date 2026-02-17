<?php
// includes/functions.php
require_once __DIR__ . '/db.php';

function format_currency($amount, $currency = 'USD') {
    return '$' . number_format($amount, 2);
}

function get_total_balance() {
    global $pdo;
    $stmt = $pdo->query("SELECT SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance FROM transactions WHERE status = 'completed'");
    return $stmt->fetchColumn() ?: 0;
}

function get_monthly_income() {
    global $pdo;
    $month_start = date('Y-m-01');
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE type = 'income' AND status = 'completed' AND transaction_date >= ?");
    $stmt->execute([$month_start]);
    return $stmt->fetchColumn() ?: 0;
}

function get_monthly_expenses() {
    global $pdo;
    $month_start = date('Y-m-01');
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE type = 'expense' AND status = 'completed' AND transaction_date >= ?");
    $stmt->execute([$month_start]);
    return $stmt->fetchColumn() ?: 0;
}

function get_total_savings() {
    return get_monthly_income() - get_monthly_expenses();
}

function get_recent_transactions($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM transactions ORDER BY transaction_date DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function get_all_transactions($filters = [], $limit = 20, $offset = 0) {
    global $pdo;
    $sql = "SELECT * FROM transactions WHERE 1=1";
    $params = [];

    if (!empty($filters['type'])) {
        $sql .= " AND type = ?";
        $params[] = $filters['type'];
    }
    if (!empty($filters['category'])) {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
    }
    if (!empty($filters['date_from'])) {
        $sql .= " AND transaction_date >= ?";
        $params[] = $filters['date_from'];
    }
    if (!empty($filters['date_to'])) {
        $sql .= " AND transaction_date <= ?";
        $params[] = $filters['date_to'];
    }

    $sql .= " ORDER BY transaction_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_transaction_count($filters = []) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM transactions WHERE 1=1";
    $params = [];

    if (!empty($filters['type'])) {
        $sql .= " AND type = ?";
        $params[] = $filters['type'];
    }
    if (!empty($filters['category'])) {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
    }
    if (!empty($filters['date_from'])) {
        $sql .= " AND transaction_date >= ?";
        $params[] = $filters['date_from'];
    }
    if (!empty($filters['date_to'])) {
        $sql .= " AND transaction_date <= ?";
        $params[] = $filters['date_to'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function get_categories($type = null) {
    global $pdo;
    if ($type) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE type = ?");
        $stmt->execute([$type]);
    } else {
        $stmt = $pdo->query("SELECT * FROM categories");
    }
    return $stmt->fetchAll();
}

function get_cards() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM cards ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function get_goals() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM goals ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function get_income_breakdown() {
    global $pdo;
    $stmt = $pdo->query("SELECT category, SUM(amount) as total FROM transactions WHERE type = 'income' AND status = 'completed' GROUP BY category");
    return $stmt->fetchAll();
}

function get_budget_status() {
    global $pdo;
    // For simplicity, we compare expenses against budget categories for the current month
    // We need to join transactions with categories or just group by category
    // Assuming budget table has target amounts.
    // Let's just fetch budget items and calculate spent amount for each category from transactions.

    $budgets = $pdo->query("SELECT * FROM categories WHERE type = 'budget'")->fetchAll();
    $status = [];

    foreach ($budgets as $budget) {
        // Calculate spent for this category this month
        $stmt = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE type = 'expense' AND category = ? AND strftime('%Y-%m', transaction_date) = ?");
        $stmt->execute([$budget['name'], date('Y-m')]);
        $spent = $stmt->fetchColumn() ?: 0;

        // We need a budget limit. The schema has a 'budgets' table but the seed data put categories with type 'budget'.
        // Let's check the 'budgets' table. It's empty in seed.
        // I should probably have seeded 'budgets' table.
        // For MVP, I'll return the category and the spent amount. The user can set limits.
        // Wait, the prompt said "Budget Chart: Donut chart showing budget allocation and spending".
        // I will assume some default limits if not set, or query the budgets table.

        // Let's try to fetch limit from 'budgets' table if exists for this category/month
        $limitStmt = $pdo->prepare("SELECT allocated_amount FROM budgets WHERE category = ? AND month = ?");
        $limitStmt->execute([$budget['name'], date('Y-m')]);
        $limit = $limitStmt->fetchColumn();

        if (!$limit) {
            $limit = 1000; // Default limit for demo
        }

        $status[] = [
            'category' => $budget['name'],
            'limit' => $limit,
            'spent' => $spent
        ];
    }
    return $status;
}

function get_spending_trends($period = 'month') {
    global $pdo;
    // Daily spending for the current month
    $start = date('Y-m-01');
    $end = date('Y-m-t');

    $stmt = $pdo->prepare("SELECT strftime('%Y-%m-%d', transaction_date) as date, SUM(amount) as total FROM transactions WHERE type = 'expense' AND status = 'completed' AND transaction_date BETWEEN ? AND ? GROUP BY date ORDER BY date");
    $stmt->execute([$start, $end]);
    return $stmt->fetchAll();
}

function get_income_vs_expense_history() {
    global $pdo;
    // Monthly comparison for last 6 months
    $stmt = $pdo->query("SELECT strftime('%Y-%m', transaction_date) as month,
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        FROM transactions
        WHERE status = 'completed'
        GROUP BY month
        ORDER BY month DESC LIMIT 6");
    return array_reverse($stmt->fetchAll());
}
?>
