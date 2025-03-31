<script>
        const accountOptions = document.querySelectorAll('.account-option');
        const accountTypeInput = document.getElementById('account_type');
        const businessFields = document.querySelectorAll('.business-fields');

        accountOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove 'selected' class and hide check icon from all options
                accountOptions.forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.check-icon').classList.add('hidden');
                });

                // Add 'selected' class and show check icon to the clicked option
                option.classList.add('selected');
                option.querySelector('.check-icon').classList.remove('hidden');

                // Update the hidden input value
                accountTypeInput.value = option.dataset.accountType;

                // Show/hide business fields based on selected account type
                if (option.dataset.accountType === 'business') {
                    businessFields.forEach(field => field.classList.remove('hidden'));
                } else {
                    businessFields.forEach(field => field.classList.add('hidden'));
                }
            });
        });
    </script>
</body>
</html>