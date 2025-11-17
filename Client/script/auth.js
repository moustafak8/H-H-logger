
function isLoggedIn() {
  return localStorage.getItem('userEmail') !== null;
}
function logout() {
  localStorage.removeItem('userEmail');
  window.location.href = '../login/login.html';
}
function requireLogin() {
  if (!isLoggedIn()) {
    window.location.href = '../login/login.html';
  }
}

