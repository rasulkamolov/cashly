<?php
require_once __DIR__ . '/includes/functions.php';

$cards = get_cards();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-jm-primary dark:text-white">Wallet</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your payment cards.</p>
        </div>
        <button onclick="openCardModal()" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add New Card
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($cards as $card): ?>
        <div class="bg-gradient-to-br from-jm-primary to-jm-secondary text-white rounded-xl p-6 relative overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i data-lucide="credit-card" class="w-32 h-32"></i>
            </div>
            <div class="relative z-10 flex flex-col justify-between h-48">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100 text-sm"><?php echo htmlspecialchars($card['nickname'] ?: 'My Card'); ?></p>
                        <i data-lucide="wifi" class="w-6 h-6 rotate-90 mt-2"></i>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-xl italic"><?php echo strtoupper($card['card_type']); ?></p>
                    </div>
                </div>

                <div class="mb-4">
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
            </div>

            <!-- Actions -->
            <div class="absolute bottom-4 right-4 flex gap-2">
                <form method="POST" action="/actions/delete_card.php" onsubmit="return confirm('Delete this card?');">
                    <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
                    <button type="submit" class="p-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors text-white" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Empty State / Add Card Placeholder -->
        <button onclick="openCardModal()" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 flex flex-col items-center justify-center h-48 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors group">
            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4 group-hover:bg-white dark:group-hover:bg-gray-600 transition-colors">
                <i data-lucide="plus" class="w-6 h-6 text-gray-400 group-hover:text-jm-primary dark:text-gray-300 dark:group-hover:text-white transition-colors"></i>
            </div>
            <p class="font-medium text-gray-500 dark:text-gray-400 group-hover:text-jm-primary dark:group-hover:text-white transition-colors">Add New Card</p>
        </button>
    </div>
</div>

<!-- Add Card Modal -->
<div id="cardModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-jm-navy rounded-xl shadow-xl max-w-md w-full m-auto">
        <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-jm-primary dark:text-white">Add New Card</h3>
            <button onclick="closeCardModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form method="POST" action="/actions/save_card.php" class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Number (Last 4 digits)</label>
                <input type="text" name="card_number" maxlength="4" pattern="\d{4}" required placeholder="1234" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">For security, only store last 4 digits.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cardholder Name</label>
                <input type="text" name="cardholder_name" required placeholder="John Doe" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date</label>
                    <input type="text" name="expiry_date" required placeholder="MM/YY" pattern="\d{2}/\d{2}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Type</label>
                    <select name="card_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="visa">Visa</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="amex">Amex</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nickname (Optional)</label>
                <input type="text" name="nickname" placeholder="Primary Card" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeCardModal()" class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 py-2 bg-jm-primary text-white rounded-lg hover:bg-jm-secondary transition-colors">Save Card</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCardModal() {
    document.getElementById('cardModal').classList.remove('hidden');
}
function closeCardModal() {
    document.getElementById('cardModal').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
