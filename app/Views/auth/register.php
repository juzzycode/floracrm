<?php
// app/Views/auth/register.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Register - Florist CRM' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .register-container {
            max-width: 500px;
            margin: 0 auto;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .register-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-logo i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 10px;
        }
        .register-logo h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .register-logo p {
            color: #666;
            font-size: 14px;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b4ba8 100%);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
        }
        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            background: #e9ecef;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 3px;
        }
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #ffc107; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }
        .form-check-label {
            font-size: 14px;
            line-height: 1.4;
        }
        .form-check-label a {
            color: #667eea;
            text-decoration: none;
        }
        .form-check-label a:hover {
            text-decoration: underline;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            font-size: 12px;
            font-weight: bold;
            color: #6c757d;
        }
        .step.active {
            background: #667eea;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .step-line {
            height: 2px;
            background: #e9ecef;
            flex: 1;
            margin: 14px 0;
        }
        .step-line.completed {
            background: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-logo">
                    <i class="fas fa-seedling"></i>
                    <h2>Join BloomingWire</h2>
                    <p>Create your florist account</p>
                </div>
                
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1">1</div>
                    <div class="step-line" id="line1"></div>
                    <div class="step" id="step2">2</div>
                    <div class="step-line" id="line2"></div>
                    <div class="step" id="step3">3</div>
                </div>
                
                <!-- Display flash messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form action="/register" method="POST" id="registerForm">
                    <?= csrf_field() ?>
                    
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="formStep1">
                        <h5 class="mb-3">Personal Information</h5>
                        
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Full Name" value="<?= old('name') ?>" required>
                            <label for="name">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="name@example.com" value="<?= old('email') ?>" required>
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <div class="form-text" id="emailFeedback"></div>
                        </div>
                        
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   placeholder="Phone Number" value="<?= old('phone') ?>">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Phone Number (Optional)
                            </label>
                        </div>
                        
                        <button type="button" class="btn btn-primary btn-register w-100" onclick="nextStep(1)">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Business Information -->
                    <div class="form-step" id="formStep2">
                        <h5 class="mb-3">Business Information</h5>
                        
                        <div class="form-floating">
                            <input type="text" class="form-control" id="business_name" name="business_name" 
                                   placeholder="Business Name" value="<?= old('business_name') ?>" required>
                            <label for="business_name">
                                <i class="fas fa-building"></i> Business Name
                            </label>
                        </div>
                        
                        <div class="form-floating">
                            <select class="form-select" id="business_type" name="business_type" required>
                                <option value="">Select Business Type</option>
                                <option value="retail_florist" <?= old('business_type') === 'retail_florist' ? 'selected' : '' ?>>Retail Florist</option>
                                <option value="wholesale_florist" <?= old('business_type') === 'wholesale_florist' ? 'selected' : '' ?>>Wholesale Florist</option>
                                <option value="event_designer" <?= old('business_type') === 'event_designer' ? 'selected' : '' ?>>Event Designer</option>
                                <option value="wedding_specialist" <?= old('business_type') === 'wedding_specialist' ? 'selected' : '' ?>>Wedding Specialist</option>
                                <option value="funeral_director" <?= old('business_type') === 'funeral_director' ? 'selected' : '' ?>>Funeral Director</option>
                                <option value="other" <?= old('business_type') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <label for="business_type">
                                <i class="fas fa-store"></i> Business Type
                            </label>
                        </div>
                        
                        <div class="form-floating">
                            <textarea class="form-control" id="business_address" name="business_address" 
                                      placeholder="Business Address" style="height: 80px"><?= old('business_address') ?></textarea>
                            <label for="business_address">
                                <i class="fas fa-map-marker-alt"></i> Business Address (Optional)
                            </label>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary btn-register w-100" onclick="nextStep(2)">
                                    Next Step <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Security -->
                    <div class="form-step" id="formStep3">
                        <h5 class="mb-3">Security Settings</h5>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Password" required>
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="form-text" id="passwordFeedback"></div>
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirm Password" required>
                            <label for="confirm_password">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <div class="form-text" id="confirmPasswordFeedback"></div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="/terms" target="_blank">Terms of Service</a> and 
                                <a href="/privacy" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                            <label class="form-check-label" for="newsletter">
                                Subscribe to our newsletter for industry updates and tips
                            </label>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="prevStep(3)">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                    <i class="fas fa-user-plus"></i> Create Account
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="login-link">
                    <p class="mb-0">Already have an account? <a href="/login">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 3;
        
        // Form steps functionality
        function nextStep(step) {
            if (validateStep(step)) {
                if (step < totalSteps) {
                    document.getElementById(`formStep${step}`).classList.remove('active');
                    document.getElementById(`formStep${step + 1}`).classList.add('active');
                    
                    // Update step indicator
                    document.getElementById(`step${step}`).classList.add('completed');
                    document.getElementById(`step${step}`).classList.remove('active');
                    document.getElementById(`step${step + 1}`).classList.add('active');
                    document.getElementById(`line${step}`).classList.add('completed');
                    
                    currentStep = step + 1;
                }
            }
        }
        
        function prevStep(step) {
            if (step > 1) {
                document.getElementById(`formStep${step}`).classList.remove('active');
                document.getElementById(`formStep${step - 1}`).classList.add('active');
                
                // Update step indicator
                document.getElementById(`step${step}`).classList.remove('active');
                document.getElementById(`step${step - 1}`).classList.add('active');
                document.getElementById(`step${step - 1}`).classList.remove('completed');
                document.getElementById(`line${step - 1}`).classList.remove('completed');
                
                currentStep = step - 1;
            }
        }
        
        function validateStep(step) {
            let isValid = true;
            let fields = [];
            
            switch(step) {
                case 1:
                    fields = ['name', 'email'];
                    break;
                case 2:
                    fields = ['business_name', 'business_type'];
                    break;
                case 3:
                    fields = ['password', 'confirm_password'];
                    break;
            }
            
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Additional validation for step 3
            if (step === 3) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                const terms = document.getElementById('terms').checked;
                
                if (password !== confirmPassword) {
                    document.getElementById('confirm_password').classList.add('is-invalid');
                    document.getElementById('confirmPasswordFeedback').innerHTML = 
                        '<span class="text-danger">Passwords do not match</span>';
                    isValid = false;
                }
                
                if (!terms) {
                    document.getElementById('terms').classList.add('is-invalid');
                    isValid = false;
                }
            }
            
            return isValid;
        }
        
        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            if (email) {
                // Basic email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    document.getElementById('emailFeedback').innerHTML = 
                        '<span class="text-danger">Please enter a valid email address</span>';
                    this.classList.add('is-invalid');
                } else {
                    document.getElementById('emailFeedback').innerHTML = 
                        '<span class="text-success">Email format is valid</span>';
                    this.classList.remove('is-invalid');
                }
            }
        });
        
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const feedback = document.getElementById('passwordFeedback');
            
            let strength = 0;
            let messages = [];
            
            if (password.length >= 8) strength++;
            else messages.push('At least 8 characters');
            
            if (/[a-z]/.test(password)) strength++;
            else messages.push('One lowercase letter');
            
            if (/[A-Z]/.test(password)) strength++;
            else messages.push('One uppercase letter');
            
            if (/\d/.test(password)) strength++;
            else messages.push('One number');
            
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
            else messages.push('One special character');
            
            // Update strength bar
            strengthBar.className = 'password-strength-bar';
            if (strength === 1) strengthBar.classList.add('strength-weak');
            else if (strength === 2) strengthBar.classList.add('strength-fair');
            else if (strength === 3) strengthBar.classList.add('strength-good');
            else if (strength >= 4) strengthBar.classList.add('strength-strong');
            
            // Update feedback
            if (messages.length > 0) {
                feedback.innerHTML = '<span class="text-warning">Password needs: ' + messages.join(', ') + '</span>';
            } else {
                feedback.innerHTML = '<span class="text-success">Strong password!</span>';
            }
        });
        
        // Confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const feedback = document.getElementById('confirmPasswordFeedback');
            
            if (confirmPassword) {
                if (password === confirmPassword) {
                    feedback.innerHTML = '<span class="text-success">Passwords match</span>';
                    this.classList.remove('is-invalid');
                } else {
                    feedback.innerHTML = '<span class="text-danger">Passwords do not match</span>';
                    this.classList.add('is-invalid');
                }
            } else {
                feedback.innerHTML = '';
                this.classList.remove('is-invalid');
            }
        });
        
        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            
            // Format as (123) 456-7890 if US number (10 digits)
            if (value.length > 3 && value.length <= 6) {
                value = value.replace(/(\d{3})(\d{1,3})/, '($1) $2');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{1,4})/, '($1) $2-$3');
            }
            
            this.value = value;
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
             const phoneInput = document.getElementById('phone');
            if (phoneInput.value) {
                phoneInput.value = phoneInput.value.replace(/\D/g, '');
            }
            if (!validateStep(3)) {
                e.preventDefault();
                return false;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            submitBtn.disabled = true;
        });
        
        // CSS for form steps
        const style = document.createElement('style');
        style.textContent = `
            .form-step {
                display: none;
            }
            .form-step.active {
                display: block;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
