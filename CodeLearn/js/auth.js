const auth = {
    login: async (username, password) => {
        try {
            const response = await fetch('backend/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=login&username=${username}&password=${password}`
            });
            return await response.json();
        } catch (error) {
            console.error('Login error:', error);
            return { success: false, message: 'Login failed' };
        }
    },

    signup: async (username, password) => {
        try {
            const response = await fetch('backend/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=signup&username=${username}&password=${password}`
            });
            return await response.json();
        } catch (error) {
            console.error('Signup error:', error);
            return { success: false, message: 'Signup failed' };
        }
    },

    logout: async () => {
        try {
            await fetch('backend/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=logout'
            });
            window.location.href = 'login.html';
        } catch (error) {
            console.error('Logout error:', error);
        }
    }
};