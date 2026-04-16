document.addEventListener('DOMContentLoaded', () => {
  const profileUsername = document.querySelector('#profile-username');
  const avatar = document.querySelector('#avatar');
  const logoutButton = document.querySelector('#logout');
  const editAvatarButton = document.querySelector('#edit-avatar');
  const avatarDialog = document.querySelector('#avatar-dialog');
  const avatarOptions = document.querySelectorAll('.avatar-option');
  const closeDialogButton = document.querySelector('#close-dialog');

  // Redirect from Login Page if Already Logged In
  if (window.location.pathname.endsWith('login.html')) {
    const userProfile = JSON.parse(localStorage.getItem('userProfile'));
    if (userProfile) {
      window.location.href = 'profile.html';
    }
  }

  // Handle Login Form Submission
  const loginForm = document.querySelector('#loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (event) => {
      event.preventDefault();

      const username = document.querySelector('#username').value.trim();
      const password = document.querySelector('#password').value.trim();

      // Basic validation
      if (!username || !password) {
        document.querySelector('#error').textContent = 'Both fields are required!';
        return;
      } else if (password.length < 8) {
        document.querySelector('#error').textContent = 'Password must be at least 8 characters!';
        return;
      }

      // Save user profile to localStorage with default avatar
      localStorage.setItem('userProfile', JSON.stringify({ username, avatar: 'avatar1.png' }));

      // Redirect to My Profile page
      window.location.href = 'profile.html';
    });
  }

  // Populate My Profile Page
  const userProfile = JSON.parse(localStorage.getItem('userProfile'));
  if (userProfile && profileUsername) {
    profileUsername.textContent = userProfile.username;
    avatar.src = userProfile.avatar;

    // Open Avatar Dialog
    editAvatarButton.addEventListener('click', () => {
      avatarDialog.classList.remove('hidden');
    });

    // Handle Avatar Selection
    avatarOptions.forEach((option) => {
      option.addEventListener('click', () => {
        const selectedAvatar = option.dataset.avatar;
        avatar.src = selectedAvatar;
        userProfile.avatar = selectedAvatar;
        localStorage.setItem('userProfile', JSON.stringify(userProfile));
        avatarDialog.classList.add('hidden');
      });
    });

    // Close Dialog
    closeDialogButton.addEventListener('click', () => {
      avatarDialog.classList.add('hidden');
    });

    // Handle Logout
    logoutButton.addEventListener('click', () => {
      localStorage.removeItem('userProfile');
      window.location.href = 'login.html';
    });
  }
});