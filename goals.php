<?php
require_once __DIR__ . '/includes/functions.php';

$goals = get_goals();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-jm-primary dark:text-white">Savings Goals</h1>
            <p class="text-gray-500 dark:text-gray-400">Track your progress towards financial targets.</p>
        </div>
        <button onclick="openGoalModal()" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Create New Goal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($goals as $goal):
            $percent = $goal['target_amount'] > 0 ? min(100, ($goal['current_amount'] / $goal['target_amount']) * 100) : 0;
            $remaining = max(0, $goal['target_amount'] - $goal['current_amount']);
        ?>
        <div class="card relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-jm-primary dark:text-blue-300">
                    <i data-lucide="target" class="w-6 h-6"></i>
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick='openGoalModal(<?php echo json_encode($goal); ?>)' class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </button>
                    <form method="POST" action="/actions/delete_goal.php" onsubmit="return confirm('Delete this goal?');">
                        <input type="hidden" name="id" value="<?php echo $goal['id']; ?>">
                        <button type="submit" class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

            <h3 class="font-bold text-lg mb-1 text-jm-black dark:text-white"><?php echo htmlspecialchars($goal['name']); ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Target Date: <?php echo date('M d, Y', strtotime($goal['target_date'])); ?></p>

            <div class="mb-2 flex justify-between text-sm font-medium">
                <span class="text-gray-600 dark:text-gray-300"><?php echo format_currency($goal['current_amount']); ?></span>
                <span class="text-gray-400">of <?php echo format_currency($goal['target_amount']); ?></span>
            </div>

            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 mb-4">
                <div class="bg-jm-secondary h-3 rounded-full transition-all duration-1000" style="width: <?php echo $percent; ?>%"></div>
            </div>

            <?php if ($remaining > 0): ?>
            <p class="text-xs text-center text-gray-500">
                <span class="font-semibold text-jm-primary dark:text-blue-400"><?php echo format_currency($remaining); ?></span> to go!
            </p>
            <?php else: ?>
            <p class="text-xs text-center text-green-600 font-semibold flex items-center justify-center gap-1">
                <i data-lucide="check-circle" class="w-3 h-3"></i> Goal Reached!
            </p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if (empty($goals)): ?>
        <div class="col-span-full text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                <i data-lucide="flag" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No goals yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set a financial goal to start tracking your savings.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Goal Modal -->
<div id="goalModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-jm-navy rounded-xl shadow-xl max-w-md w-full m-auto">
        <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-jm-primary dark:text-white" id="goalModalTitle">Create Goal</h3>
            <button onclick="closeGoalModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form method="POST" action="/actions/save_goal.php" class="p-5 space-y-4">
            <input type="hidden" name="id" id="goalId">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Goal Name</label>
                <input type="text" name="name" id="goalName" required placeholder="e.g. New Car" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Amount</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                    <input type="number" step="0.01" name="target_amount" id="targetAmount" required class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Amount (Initial Deposit)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                    <input type="number" step="0.01" name="current_amount" id="currentAmount" value="0" class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Date</label>
                <input type="date" name="target_date" id="targetDate" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeGoalModal()" class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 py-2 bg-jm-primary text-white rounded-lg hover:bg-jm-secondary transition-colors">Save Goal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGoalModal(data = null) {
    const modal = document.getElementById('goalModal');
    const title = document.getElementById('goalModalTitle');
    const form = modal.querySelector('form');

    modal.classList.remove('hidden');

    if (data) {
        title.textContent = 'Edit Goal';
        document.getElementById('goalId').value = data.id;
        document.getElementById('goalName').value = data.name;
        document.getElementById('targetAmount').value = data.target_amount;
        document.getElementById('currentAmount').value = data.current_amount;
        document.getElementById('targetDate').value = data.target_date.split(' ')[0];
    } else {
        title.textContent = 'Create Goal';
        form.reset();
        document.getElementById('goalId').value = '';
    }
}
function closeGoalModal() {
    document.getElementById('goalModal').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
