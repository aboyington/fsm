<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">
                        <i class="bi bi-tools"></i> FSM Login
                    </h2>
                    
                    <div id="alertContainer">
                        <?php if (session()->getFlashdata('message')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('message') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center text-muted mb-0">
                        Field Service Management Platform<br>
                        <small>Integrated with Canvass Global</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const alertContainer = document.getElementById('alertContainer');
    
    try {
        const response = await fetch('<?= base_url('api/auth/login') ?>', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.status === 'success') {
            // Store token
            localStorage.setItem('authToken', data.data.token);
            sessionStorage.setItem('currentUser', JSON.stringify(data.data.user));
            
            // Also store in session for server-side auth
            // We'll use a form submission to set the session
            const sessionForm = document.createElement('form');
            sessionForm.method = 'POST';
            sessionForm.action = '<?= base_url('set-session') ?>';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'token';
            tokenInput.value = data.data.token;
            
            sessionForm.appendChild(tokenInput);
            document.body.appendChild(sessionForm);
            sessionForm.submit();
        } else {
            // Show error message
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${data.messages?.error || data.message || 'Login failed'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    } catch (error) {
        alertContainer.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Network error. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }
});
</script>
<?= $this->endSection() ?>
