    </main>
    <!-- End Page Content -->

</div> <!-- End Main Content Wrapper -->

<!-- Scripts -->
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    themeToggleBtn.addEventListener('click', () => {
        if (htmlElement.classList.contains('dark')) {
            htmlElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            htmlElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });

    // Mobile Menu Logic
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const closeMobileMenuBtn = document.getElementById('close-mobile-menu-btn');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

    function toggleMobileMenu() {
        if (mobileSidebar.classList.contains('-translate-x-full')) {
            // Open
            mobileSidebar.classList.remove('-translate-x-full');
            mobileMenuOverlay.classList.remove('hidden');
            setTimeout(() => mobileMenuOverlay.classList.remove('opacity-0'), 10);
        } else {
            // Close
            mobileSidebar.classList.add('-translate-x-full');
            mobileMenuOverlay.classList.add('opacity-0');
            setTimeout(() => mobileMenuOverlay.classList.add('hidden'), 300);
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }
    if (closeMobileMenuBtn) {
        closeMobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', toggleMobileMenu);
    }
</script>

</body>
</html>
