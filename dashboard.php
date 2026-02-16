<?php
require_once __DIR__ . '/includes/functions.php';

$balance = get_total_balance();
$income = get_monthly_income();
$expense = get_monthly_expenses();
$savings = get_total_savings();
$recent_transactions = get_recent_transactions();
$cards = get_cards();
$budget_status = get_budget_status();

// Prepare data for charts
$income_breakdown = get_income_breakdown();
$income_labels = [];
$income_data = [];
foreach ($income_breakdown as $item) {
    $income_labels[] = $item['category'];
    $income_data[] = $item['total'];
}

$budget_labels = [];
$budget_spent = [];
$budget_limits = [];
foreach ($budget_status as $item) {
    $budget_labels[] = $item['category'];
    $budget_spent[] = $item['spent'];
    $budget_limits[] = $item['limit'];
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-jm-primary dark:text-white">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400">Welcome back, John! Here's your financial overview.</p>
        </div>
        <a href="transactions.php?add=true" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Transaction
        </a>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Balance -->
        <div class="card bg-white dark:bg-jm-navy border-l-4 border-jm-secondary">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Balance</p>
                    <h3 class="text-2xl font-bold mt-1 text-jm-black dark:text-white"><?php echo format_currency($balance); ?></h3>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-jm-secondary">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center font-medium">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +12.5%
                </span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>

        <!-- Income -->
        <div class="card bg-white dark:bg-jm-navy border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Monthly Income</p>
                    <h3 class="text-2xl font-bold mt-1 text-jm-black dark:text-white"><?php echo format_currency($income); ?></h3>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-600">
                    <i data-lucide="arrow-down-left" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center font-medium">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +4.3%
                </span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>

        <!-- Expense -->
        <div class="card bg-white dark:bg-jm-navy border-l-4 border-red-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Monthly Expense</p>
                    <h3 class="text-2xl font-bold mt-1 text-jm-black dark:text-white"><?php echo format_currency($expense); ?></h3>
                </div>
                <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg text-red-600">
                    <i data-lucide="arrow-up-right" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-red-500 flex items-center font-medium">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +2.1%
                </span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>

        <!-- Savings -->
        <div class="card bg-white dark:bg-jm-navy border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Savings</p>
                    <h3 class="text-2xl font-bold mt-1 text-jm-black dark:text-white"><?php echo format_currency($savings); ?></h3>
                </div>
                <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600">
                    <i data-lucide="piggy-bank" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center font-medium">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +8.2%
                </span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Income Chart -->
        <div class="card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-jm-primary dark:text-white">Income Breakdown</h3>
                <select class="text-sm border-gray-200 rounded-md shadow-sm focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option>This Month</option>
                    <option>Last 3 Months</option>
                    <option>Last 6 Months</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <!-- Budget Chart -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-jm-primary dark:text-white">Budget vs Spent</h3>
                <select class="text-sm border-gray-200 rounded-md shadow-sm focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option>This Month</option>
                </select>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-jm-primary dark:text-white">Recent Transactions</h3>
                <a href="transactions.php" class="text-sm text-jm-secondary hover:text-jm-primary font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <th class="py-3 font-medium text-sm">Transaction</th>
                            <th class="py-3 font-medium text-sm">Category</th>
                            <th class="py-3 font-medium text-sm">Date</th>
                            <th class="py-3 font-medium text-sm text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php foreach ($recent_transactions as $t): ?>
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="py-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                    <?php echo $t['type'] == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                                    <?php echo strtoupper(substr($t['recipient_name'] ?: 'Unknown', 0, 2)); ?>
                                </div>
                                <div>
                                    <p class="font-medium text-jm-black dark:text-white"><?php echo htmlspecialchars($t['recipient_name'] ?: 'Unknown'); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo ucfirst($t['status']); ?></p>
                                </div>
                            </td>
                            <td class="py-3 text-sm text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars($t['category']); ?></td>
                            <td class="py-3 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($t['transaction_date'])); ?></td>
                            <td class="py-3 text-right font-medium <?php echo $t['type'] == 'income' ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $t['type'] == 'income' ? '+' : '-'; ?><?php echo format_currency($t['amount']); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_transactions)): ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No transactions found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Widgets -->
        <div class="space-y-6">
            <!-- Spending Limits -->
            <div class="card">
                <h3 class="text-lg font-bold text-jm-primary dark:text-white mb-4">Spending Limits</h3>
                <div class="space-y-4">
                    <?php
                    // Show top 3 budget categories
                    $top_budgets = array_slice($budget_status, 0, 3);
                    foreach ($top_budgets as $b):
                        $percent = $b['limit'] > 0 ? min(100, ($b['spent'] / $b['limit']) * 100) : 0;
                        $color = $percent > 90 ? 'bg-red-500' : ($percent > 75 ? 'bg-yellow-500' : 'bg-green-500');
                    ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars($b['category']); ?></span>
                            <span class="text-gray-500"><?php echo format_currency($b['spent']); ?> / <?php echo format_currency($b['limit']); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="<?php echo $color; ?> h-2 rounded-full transition-all duration-500" style="width: <?php echo $percent; ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- My Cards -->
            <div class="card bg-gradient-to-br from-jm-primary to-jm-secondary text-white border-none relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i data-lucide="credit-card" class="w-32 h-32"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-blue-100 text-sm">Current Balance</p>
                            <h3 class="text-2xl font-bold"><?php echo format_currency($balance); ?></h3>
                        </div>
                        <i data-lucide="wifi" class="w-6 h-6 rotate-90"></i>
                    </div>

                    <?php if (!empty($cards)):
                        $card = $cards[0];
                    ?>
                    <div class="mb-6">
                        <p class="font-mono text-xl tracking-widest">**** **** **** <?php echo substr($card['card_number'], -4); ?></p>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs text-blue-100 uppercase">Card Holder</p>
                            <p class="font-medium"><?php echo htmlspecialchars($card['cardholder_name']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-blue-100 uppercase">Expires</p>
                            <p class="font-medium"><?php echo htmlspecialchars($card['expiry_date']); ?></p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <p class="mb-2">No cards added</p>
                        <a href="wallet.php" class="text-sm underline">Add a card</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Income Chart Data
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    new Chart(incomeCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($income_labels); ?>,
            datasets: [{
                label: 'Income by Category',
                data: <?php echo json_encode($income_data); ?>,
                backgroundColor: '#4A5FD9',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Budget Chart Data
    const budgetCtx = document.getElementById('budgetChart').getContext('2d');
    new Chart(budgetCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($budget_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($budget_spent); ?>,
                backgroundColor: [
                    '#2E3A8C', '#4A5FD9', '#10B981', '#F59E0B', '#EF4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
