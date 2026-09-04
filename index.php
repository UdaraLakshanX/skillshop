<?php

    session_start();
    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
        header("Location: home.php");
        exit();
    }

    $email = isset($_COOKIE["skillshop_user_email"]) ? $_COOKIE["skillshop_user_email"] : "";
    // $password = isset($_COOKIE["skillshop_user_password"]) ? $_COOKIE["skillshop_user_password"] : "";
    $rememberMe = isset($_COOKIE["skillshop_remember"]) ? true : false;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Shop</title>
    <link rel="icon" type="images/png" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Auth Container -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-10 text-center text-white">
                <h1 class="text-4xl font-bold mb-2">SkillShop</h1>
                <p class="text-blue-100">Buy and Sell Skills with Confidence</p>
            </div>

            <!-- Form Container -->
            <div class="px-8 py-8">

                <!-- Sign In Form -->
                <div id="signin-form" class="form-container active">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                    <p class="text-gray-600 mb-6">Sign in to your account to continue</p>
                    <form id="signin" class="space-y-4">
                        <input type="hidden" name="action" value="signin" />

                        <!-- Email Input -->
                        <div>
                            <label for="signin-email" class="block text-gray-700 text-sm font-semibold mb-2">
                                Email Address
                            </label>
                            <input type="email" name="email" id="signin-email" placeholder="you@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                               focus:outline-none focus:ring-2 focus:ring-blue-500
                                               focus:border-transparent transition duration-200"
                                   value="<?php echo $email; ?>"            
                            />
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <label for="signin-password" class="block text-gray-700 text-sm font-semibold mb-2">
                                Password
                            </label>
                            <input type="password" name="password" id="signin-password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                               focus:outline-none focus:ring-2 focus:ring-blue-500
                                               focus:border-transparent transition duration-200" 
                                   value="<?php // echo $password; ?>"
                            />
                            <button type="button" onclick="togglePassword('signin-password',this);"
                                class="absolute right-3 top-1/2 text-gray-500 
                                        focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">
                                👁️
                            </button>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember" class="flex items-center">
                                <input type="checkbox" name="remember" id="remember"
                                    class="w-4 h-4 text-blue-600 rounded"
                                    <?php if ($rememberMe) echo "checked"; ?> />
                                <span class="ml-2 text-sm text-gray-600">Remember Me</span>
                            </label>
                            <button type="button" onclick="openForgotPasswordModal();" class="text-sm text-blue-600 
                            hover:text-blue-700 font-medium bg-none border-none cursor-pointer">
                                Forgot Password
                            </button>
                        </div>

                        <button type="button" onclick="signIn();" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold
                                                 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-300
                                                 tranform hover:scale-105 mt-6">
                            Sign In
                        </button>

                        <!-- Error Message -->
                        <div id="signin-message" class="hidden text-red-500 mt-4 p-3 rounded-lg text-sm">
                        </div>
                    </form>

                    <p class="text-center text-gray-600 mt-6">
                        Don't have an account?
                        <button type="button" class="text-blue-600 hover:text-blue-700 font-bold cursor-pointer" onclick="toggleForms();">
                            Sign Up
                        </button>
                    </p>

                </div>

                <!-- Sign Up Form -->
                <div id="signup-form" class="form-container hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
                    <p class="text-gray-600 mb-6">Join thousands of skill sellers and buyers</p>

                    <form class="space-y-4">
                        <input type="hidden" name="action" value="signup" />

                        <!-- First Name Input -->
                        <div>
                            <label for="signup-firstname" class="block text-gray-700 text-sm font-semibold mb-2">
                                First Name
                            </label>
                            <input type="text" name="firstname" id="signup-firstname" placeholder="Sahan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none
                                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                                      duration-200" />
                            <span class="text-red-500 text-sm hidden" id="signup-firstname-error"></span>
                        </div>

                        <!-- Last Name Input -->
                        <div>
                            <label for="signup-lastname" class="block text-gray-700 text-sm font-semibold mb-2">
                                Last Name
                            </label>
                            <input type="text" name="lastname" id="signup-lastname" placeholder="Perera" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none
                                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                                      duration-200" />
                            <span class="text-red-500 text-sm hidden" id="signup-lastname-error"></span>
                        </div>

                        <!-- Email Address Input -->
                        <div>
                            <label for="signup-email" class="block text-gray-700 text-sm font-semibold mb-2">
                                Email Address
                            </label>
                            <input type="email" name="email" id="signup-email" placeholder="you@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none
                                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                                      duration-200" />
                            <span class="text-red-500 text-sm hidden" id="signup-email-error"></span>
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <label for="signup-password"
                                class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                            <input type="password" name="password" id="signup-password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none
                                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                                      duration-200" aria-describedby="signup-password-error" />
                            <button type="button" onclick="togglePassword('signup-password',this);" 
                                class="absolute right-3 top-1/2 -translate-y-2 text-gray-500 
                                                       focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1"
                                aria-label="Toggle password visibility" aria-pressed="false">
                                👁️
                            </button>
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            <span class="text-red-500 text-sm hidden" id="signup-password-error"></span>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="relative">
                            <label for="signup-confirm" class="block text-gray-700 text-sm font-semibold mb-2">Confirm
                                Password</label>
                            <input type="password" name="confirm_password" id="signup-confirm" placeholder="••••••••"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none
                                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                                      duration-200" aria-describedby="signup-confirm-error" />
                            <button type="button" onclick="togglePassword('signup-confirm',this);" 
                                class="absolute right-3 top-1/2 text-gray-500 
                                                       focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1"
                                aria-label="Toggle confirm password visibility" aria-pressed="false">
                                👁️
                            </button>
                            <span class="text-red-500 text-sm hidden" id="signup-confirm-error"></span>
                        </div>

                        <!-- Account Type Selection -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-3">I am a</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label for="account_type_seller" class="relative flex items-center cursor-pointer">
                                    <input type="radio" name="account_type" value="seller" class="w-4 h-4 text-blue-600"
                                        id="account_type_seller" />
                                    <span class="ml-2 text-sm text-gray-700">Skill Seller</span>
                                </label>
                                <label for="account_type_buyer" class="relative flex items-center cursor-pointer">
                                    <input type="radio" name="account_type" value="buyer" class="w-4 h-4 text-blue-600"
                                        id="account_type_buyer" />
                                    <span class="ml-2 text-sm text-gray-700">Skill Buyer</span>
                                </label>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <label for="terms_conditions" class="flex items-center">
                            <input type="checkbox" name="terms" id="terms_conditions"
                                class="w-4 h-4 text-blue-600 rounded" />
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the
                                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">
                                    Terms & Conditions
                                </a>
                            </span>
                        </label>

                        <!-- Create Account Button -->
                        <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white
                                                       font-bold py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700
                                                       transition duration-300 tranform hover:scale-105 mt-6"
                            type="button" onclick="createAccount();">
                            Create Account
                        </button>

                        <!-- Error Message -->
                        <div id="signup-message" class="hidden text-red-500 mt-1 p-3 rounded-lg text-sm"></div>
                    </form>

                    <p class="text-center text-gray-600 mt-6">
                        Already have an account?
                        <button type="button" class="text-blue-600 hover:text-blue-700 font-bold cursor-pointer"
                          onclick="toggleForms();">
                            Sign In
                        </button>
                    </p>

                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 text-center text-gray-600 text-sm">
                <p>© 2026 SkillShop. All Rights Reserved. |
                    <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                    <a href="#" class="text-blue-600 hover:text-blue-700">Terms of Service</a>
                </p>
            </div>

        </div>

        <!-- Forgot Password Modal -->
        <div id="forgot-password-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center 
                 justify-center p-4" >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Modal Header -->
                 <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white flex justify-between
                             items-center">
                    <h3 class="text-xl font-bold">Forgot Password</h3>
                    <button type="button" onclick="closeForgotPasswordModal();" class="text-white hover:text-gray-200">✕</button>
                 </div>

                 <!-- Step 1 : Email Entry -->
                <div class="p-6" id="forgot-step-1">
                    <p class="text-gray-600 mb-4">Enter your email to receive the verification code.</p>
                    <div class="mb-4">
                        <label for="forgot-email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                        <input type="email" id="forgot-email" placeholder="your@example.com" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
                               focus:ring-blue-500" />
                    </div>
                    <div id="forgot-message" class="hidden mb-4 p-3 rounded-lg text-sm"></div>
                    <div class="flex gap-3">
                        <button type="button" onclick="forgotPassword();" id="forgot-password-send-code-btn" class="flex-1 bg-gradient-to-r 
                              from-blue-600 to-indigo-600 text-white font-semibold py-2 rounded-lg hover:from-blue-700 
                              hover:to-indigo-700 ">Send Code</button>
                        <button type="button" onclick="closeForgotPasswordModal();" class="flex-1 text-gray-700
                        bg-gray-300 font-semibold py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    </div>
                </div>

                <!-- Step 2 : Verfication Code -->
                 <div class="hidden p-6" id="forgot-step-2">
                    <p class="text-gray-600 mb-4">Enter the 6-digit code sent to your email</p>
                    <div class="mb-4">
                        <label for="verify-code" class="block text-gray-700 text-sm font-semibold mb-2">Verification Code</label>
                        <input type="text" id="verify-code" placeholder="000000" maxlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
                               focus:ring-blue-500 text-center text-2xl tracking-widest" />
                    </div>
                    <div id="verify-message" class="hidden mb-4 p-3 rounded-lg text-sm"></div>
                    <div class="flex gap-3">
                        <button type="button" id="forgot-password-verify-code-btn" onclick="verifyCode();" class="flex-1 bg-gradient-to-r 
                              from-blue-600 to-indigo-600 text-white font-semibold py-2 rounded-lg hover:from-blue-700 
                              hover:to-indigo-700 ">Verify</button>
                        <button type="button" onclick="backToEmail();" class="flex-1 text-gray-700
                        bg-gray-300 font-semibold py-2 rounded-lg hover:bg-gray-400">Back</button>
                    </div>
                 </div>

                 <!-- Step 3 : Reset Password -->
                 <div class="hidden p-6" id="forgot-step-3">
                    <p class="text-gray-600 mb-4">Enter your new password.</p>
                    <div class="mb-4">
                        <label for="reset-password" class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
                        <input type="password" id="reset-password" placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
                               focus:ring-blue-500" />
                    </div>
                    <div class="mb-4">
                        <label for="reset-password-confirm" class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                        <input type="password" id="reset-password-confirm" placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
                               focus:ring-blue-500" />
                    </div>
                    <div id="reset-message" class="hidden mb-4 p-3 rounded-lg text-sm"></div>
                    <div class="flex gap-3">
                        <button type="button" id="forgot-password-reset-password-btn" onclick="resetPassword();" class="flex-1 bg-gradient-to-r 
                              from-blue-600 to-indigo-600 text-white font-semibold py-2 rounded-lg hover:from-blue-700 
                              hover:to-indigo-700 ">Reset Password</button>
                        <button type="button" onclick="closeForgotPasswordModal();" class="flex-1 text-gray-700
                        bg-gray-300 font-semibold py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    </div>
                 </div>                

            </div>
        </div>
        
    </div>

    <script src="js/auth.js"></script>
    <script src="js/script.js"></script>
</body>

</html>