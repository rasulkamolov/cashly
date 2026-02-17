<?php
require_once __DIR__ . '/includes/functions.php';

$spending_trends = get_spending_trends();
$income_vs_expense = get_income_vs_expense_history();

// Data for Spending Trends
$daily_labels = [];
$daily_data = [];
foreach ($spending_trends as $day) {
    $daily_labels[] = date('d', strtotime($day['date']));
    $daily_data[] = $day['total'];
}

// Data for Income vs Expense
$monthly_labels = [];
$monthly_income = [];
$monthly_expense = [];
foreach ($income_vs_expense as $month) {
    $monthly_labels[] = date('M Y', strtotime($month['month'] . '-01'));
    $monthly_income[] = $month['income'];
    $monthly_expense[] = $month['expense'];
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-jm-primary dark:text-white">Analytics</h1>
            <p class="text-gray-500 dark:text-gray-400">Detailed insights into your financial habits.</p>
        </div>
        <div class="flex gap-2">
            <select class="text-sm border-gray-200 rounded-lg shadow-sm focus:border-jm-primary focus:ring focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <option>This Month</option>
                <option>Last 3 Months</option>
                <option>This Year</option>
            </select>
        </div>
    </div>

    <!-- Spending Trends -->
    <div class="card">
        <h3 class="text-lg font-bold text-jm-primary dark:text-white mb-4">Daily Spending (This Month)</h3>
        <div class="h-72">
            <canvas id="spendingTrendsChart"></canvas>
        </div>
    </div>

    <!-- Income vs Expense -->
    <div class="card">
        <h3 class="text-lg font-bold text-jm-primary dark:text-white mb-4">Income vs Expense (Last 6 Months)</h3>
        <div class="h-72">
            <canvas id="incomeVsExpenseChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Spending Trends
    const spendingCtx = document.getElementById('spendingTrendsChart').getContext('2d');
    new Chart(spendingCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($daily_labels); ?>,
            datasets: [{
                label: 'Daily Spending',
                data: <?php echo json_encode($daily_data); ?>,
                borderColor: '#4A5FD9',
                backgroundColor: 'rgba(74, 95, 217, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Income vs Expense
    const comparisonCtx = document.getElementById('incomeVsExpenseChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthly_labels); ?>,
            datasets: [
                {
                    label: 'Income',
                    data: <?php echo json_encode($monthly_income); ?>,
                    backgroundColor: '#10B981',
                    borderRadius: 4,
                },
                {
                    label: 'Expense',
                    data: <?php echo json_encode($monthly_expense); ?>,
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
