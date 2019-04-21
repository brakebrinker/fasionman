<div class="loginForm">
    <div class="loginMessage">[[+errors]]</div>
    <div class="loginLogin">
    <form action="[[~[[*id]]]]" method="post" class="login">
    	
    	<p class="form-row form-row-wide">
    		<label for="username">[[%login.username]]: <span class="required">*</span></label>
    		<input type="text" class="input-text" name="username" id="username" value="" />
    	</p>
    
    	<p class="form-row form-row-wide">
    		<label for="password">[[%login.password]]: <span class="required">*</span></label>
    		<input class="input-text" type="password" name="password" id="password" />
    	</p>
    	
    	<input class="returnUrl" type="hidden" name="returnUrl" value="[[+request_uri]]" />
    	
    	[[+login.recaptcha_html]]
    	
    	<input class="loginLoginValue" type="hidden" name="service" value="login" />
    
    	<p class="form-row">
    		<input type="submit" class="button" name="login" value="[[+actionMsg]]" />
    
    		<label for="rememberme" class="rememberme">
    		<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label>
    	</p>
    
    	<p class="lost_password">
    		<a href="#" >Lost Your Password?</a>
    	</p>
    	
    </form>
    </div>
</div>

<div class="loginForm">
    <div class="loginMessage">[[+errors]]</div>
    <div class="loginLogin">
        <form class="loginLoginForm" action="[[~[[*id]]]]" method="post">
            <fieldset class="loginLoginFieldset">
                <legend class="loginLegend">[[+actionMsg]]</legend>
                <label class="loginUsernameLabel">[[%login.username]]
                    <input class="loginUsername" type="text" name="username" />
                </label>
                
                <label class="loginPasswordLabel">[[%login.password]]
                    <input class="loginPassword" type="password" name="password" />
                </label>
                <input class="returnUrl" type="hidden" name="returnUrl" value="[[+request_uri]]" />

                [[+login.recaptcha_html]]
                
                <input class="loginLoginValue" type="hidden" name="service" value="login" />
                <span class="loginLoginButton"><input type="submit" name="Login" value="[[+actionMsg]]" /></span>
            </fieldset>
        </form>
    </div>
</div>