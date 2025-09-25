document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerFormContainer = document.getElementById('register-form');
    const registerForm = document.getElementById('register-form-inner');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const authFormContainer = document.getElementById('auth-form');

    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        authFormContainer.style.display = 'none';
        registerFormContainer.style.display = 'block';
    });

    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        registerFormContainer.style.display = 'none';
        authFormContainer.style.display = 'block';
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('login-username').value;
        const password = document.getElementById('login-password').value;

        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        try {
            const response = await fetch('../api/login.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }

            const result = await response.json();
            if (result.success) {
                window.location.href = 'chat.html';
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Login Error:', error);
            alert('An error occurred during login. Please check the console.');
        }
    });

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('register-username').value;
        const password = document.getElementById('register-password').value;

        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        try {
            const response = await fetch('../api/register.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }

            const result = await response.json();
            alert(result.message);
            if (result.success) {
                authFormContainer.style.display = 'block';
                registerFormContainer.style.display = 'none';
                registerForm.reset();
            }
        } catch (error) {
            console.error('Registration Error:', error);
            alert('An error occurred during registration. Please check the console.');
        }
    });
});