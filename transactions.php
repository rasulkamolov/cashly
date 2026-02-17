<?php
require_once __DIR__ . '/includes/functions.php';

// Pagination & Filters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$filters = [
    'type' => $_GET['type'] ?? '',
    'category' => $_GET['category'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
];

$transactions = get_all_transactions($filters, $limit, $offset);
$total_transactions = get_transaction_count($filters);
$total_pages = ceil($total_transactions / $limit);

$categories = get_categories();
$income_categories = array_filter($categories, fn($c) => $c['type'] == 'income');
$expense_categories = array_filter($categories, fn($c) => $c['type'] == 'expense');

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-jm-primary dark:text-white">Transactions</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your income and expenses.</p>
        </div>
        <button onclick="openModal('add')" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Transaction
        </button>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                <select name="type" class="w-full text-sm border-gray-200 rounded-md focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option value="">All Types</option>
                    <option value="income" <?php echo $filters['type'] == 'income' ? 'selected' : ''; ?>>Income</option>
                    <option value="expense" <?php echo $filters['type'] == 'expense' ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select name="category" class="w-full text-sm border-gray-200 rounded-md focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['name']; ?>" <?php echo $filters['category'] == $cat['name'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" name="date_from" value="<?php echo $filters['date_from']; ?>" class="w-full text-sm border-gray-200 rounded-md focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" name="date_to" value="<?php echo $filters['date_to']; ?>" class="w-full text-sm border-gray-200 rounded-md focus:border-jm-primary focus:ring focus:ring-jm-primary focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <button type="submit" class="btn-secondary">Filter</button>
            <?php if (!empty(array_filter($filters))): ?>
            <a href="transactions.php" class="text-sm text-red-500 hover:text-red-700 self-center">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <th class="py-3 px-4 font-medium text-sm">Date</th>
                        <th class="py-3 px-4 font-medium text-sm">Recipient / Name</th>
                        <th class="py-3 px-4 font-medium text-sm">Category</th>
                        <th class="py-3 px-4 font-medium text-sm">Status</th>
                        <th class="py-3 px-4 font-medium text-sm text-right">Amount</th>
                        <th class="py-3 px-4 font-medium text-sm text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($transactions as $t): ?>
                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            <?php echo date('M d, Y', strtotime($t['transaction_date'])); ?>
                            <div class="text-xs text-gray-400"><?php echo date('h:i A', strtotime($t['transaction_date'])); ?></div>
                        </td>
                        <td class="py-3 px-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                <?php echo $t['type'] == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                                <?php echo strtoupper(substr($t['recipient_name'] ?: 'Unknown', 0, 2)); ?>
                            </div>
                            <div>
                                <p class="font-medium text-jm-black dark:text-white truncate max-w-[150px]"><?php echo htmlspecialchars($t['recipient_name'] ?: 'Unknown'); ?></p>
                                <?php if ($t['notes']): ?>
                                <p class="text-xs text-gray-400 truncate max-w-[150px]"><?php echo htmlspecialchars($t['notes']); ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <?php echo htmlspecialchars($t['category']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo $t['status'] == 'completed' ? 'bg-green-100 text-green-800' : ($t['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo ucfirst($t['status']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold <?php echo $t['type'] == 'income' ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo $t['type'] == 'income' ? '+' : '-'; ?><?php echo format_currency($t['amount']); ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openModal("edit", <?php echo json_encode($t); ?>)' class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" action="/actions/delete_transaction.php" onsubmit="return confirm('Are you sure you want to delete this transaction?');" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                    <button type="submit" class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                            No transactions found matching your criteria.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-between items-center px-4 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_transactions); ?> of <?php echo $total_transactions; ?> results
            </div>
            <div class="flex gap-1">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query($filters); ?>" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">Previous</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <a href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>" class="px-3 py-1 text-sm border <?php echo $i == $page ? 'bg-jm-primary text-white border-jm-primary' : 'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700'; ?> rounded">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query($filters); ?>" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Transaction Modal -->
<div id="transactionModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-jm-navy rounded-xl shadow-xl max-w-md w-full m-auto">
        <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-jm-primary dark:text-white" id="modalTitle">Add Transaction</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form method="POST" action="/actions/save_transaction.php" class="p-5 space-y-4">
            <input type="hidden" name="id" id="transactionId">

            <!-- Type Selector -->
            <div class="flex gap-4 p-1 bg-gray-100 dark:bg-gray-800 rounded-lg">
                <label class="flex-1 text-center cursor-pointer">
                    <input type="radio" name="type" value="expense" class="peer hidden" checked onchange="updateCategories()">
                    <span class="block py-2 text-sm font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm dark:peer-checked:bg-gray-700 dark:peer-checked:text-red-400 transition-all">Expense</span>
                </label>
                <label class="flex-1 text-center cursor-pointer">
                    <input type="radio" name="type" value="income" class="peer hidden" onchange="updateCategories()">
                    <span class="block py-2 text-sm font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm dark:peer-checked:bg-gray-700 dark:peer-checked:text-green-400 transition-all">Income</span>
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                    <input type="number" step="0.01" name="amount" id="amount" required class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select name="category" id="category" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <!-- Options populated by JS -->
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient / Name</label>
                <input type="text" name="recipient_name" id="recipient_name" placeholder="e.g. Starbucks, Salary" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="date" name="date" id="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 py-2 bg-jm-primary text-white rounded-lg hover:bg-jm-secondary transition-colors">Save Transaction</button>
            </div>
        </form>
    </div>
</div>

<script>
    const incomeCategories = <?php echo json_encode(array_column($income_categories, 'name')); ?>;
    const expenseCategories = <?php echo json_encode(array_column($expense_categories, 'name')); ?>;

    function updateCategories() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const select = document.getElementById('category');
        const categories = type === 'income' ? incomeCategories : expenseCategories;

        select.innerHTML = '';
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat;
            option.textContent = cat;
            select.appendChild(option);
        });
    }

    function openModal(mode, data = null) {
        const modal = document.getElementById('transactionModal');
        const title = document.getElementById('modalTitle');
        const form = modal.querySelector('form');

        modal.classList.remove('hidden');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Transaction';
            document.getElementById('transactionId').value = data.id;
            document.getElementById('amount').value = data.amount;
            document.getElementById('recipient_name').value = data.recipient_name;
            document.getElementById('date').value = data.transaction_date.split(' ')[0]; // Extract YYYY-MM-DD
            document.getElementById('notes').value = data.notes || '';

            // Set radio button
            const radio = document.querySelector(`input[name="type"][value="${data.type}"]`);
            if (radio) {
                radio.checked = true;
                // Update categories first, then select value
                updateCategories();
                document.getElementById('category').value = data.category;
            }
        } else {
            title.textContent = 'Add Transaction';
            form.reset();
            document.getElementById('transactionId').value = '';
            document.getElementById('date').value = new Date().toISOString().split('T')[0];
            // Default to expense
            document.querySelector('input[name="type"][value="expense"]').checked = true;
            updateCategories();
        }
    }

    function closeModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }

    // Initialize categories on load
    updateCategories();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
