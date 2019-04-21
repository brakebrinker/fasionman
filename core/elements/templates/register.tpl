{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">

	<div class="six columns centered">

		<h3 class="headline">Регистрация</h3><span class="line" style="margin-bottom:20px;"></span><div class="clearfix"></div>
    [[!Register?
        &submitVar=`registerbtn`
        &activation=`1`
        &activationEmailTpl=`RegisterActivationEmail`
        &activationEmailSubject=`Подтверждение регистрации Montirovka.by`
        &activationResourceId=`266`
        &successMsg=`<div class="alert alert-success">Спасибо за регистрацию. На вашу электронную почту <b>[[!+reg.email]]</b> отправлено письмо со ссылкой на активацию аккаунта. Пройдите по этой ссылке, чтобы завершить регистрацию. </div>`
        &usergroups=`Пользователи`
        &customValidators=`valueIn`
        &validate=`nospam:blank,
          password:required:minLength=^8^,
          password_confirm:password_confirm=^password^,
          fullname:required:minLength=^6^,
          email:required:email`
        &usernameField=`email`
        &placeholderPrefix=`reg.`
    ]]
    
    [[!+error.message:eq=``:then=`
         <div class="register">
          <form action="[[~[[*id]]]]" method="post"  class="register">
     
            <input type="hidden" name="nospam" value="[[!+reg.nospam]]" />

            <p class="form-row form-row-wide">
							<label for="reg_email">Электронный адрес: <span class="required">*</span></label>
							<input type="email" class="input-text" name="email" id="reg_email" value="[[!+reg.email]]" />
							<span class="help-block text-error">
                    [[!+reg.error.email]]
              </span>
						</p>
            
            <p class="form-row form-row-wide">
							<label for="reg_password">Пароль: <span class="required">*</span></label>
							<input type="password" class="input-text" name="password" id="reg_password" value="[[!+reg.password]]"/>
							<span class="help-block text-error">
                    [[!+reg.error.password]]
              </span>
						</p>
            
            <p class="form-row form-row-wide">
							<label for="reg_password2">Пароль еще раз: <span class="required">*</span></label>
							<input type="password" class="input-text" name="password_confirm" id="reg_password2" value="[[!+reg.password_confirm]]" />
							<span class="help-block text-error">
                    [[!+reg.error.password_confirm]]
              </span>
						</p>
            
            <p class="form-row form-row-wide">
							<label for="fullname">ФИО: <span class="required">*</span></label>
							<input type="text" class="input-text" name="fullname" id="fullname" value="[[!+reg.fullname:Jevix]]" />
							<span class="help-block text-error">
                    [[!+reg.error.fullname]]
                </span>
						</p>
            
            <p class="form-row">
							<input type="submit" class="button btn primary" name="registerbtn" value="Зарегистрироваться!" />
						</p>
						
           </form>
          </div>
    
    `:else=`<div class="alert alert-success">[[!+error.message]]</div>`]]
    
		<!--<form method="post" class="register">-->
			
		<!--	<p class="form-row form-row-wide">-->
		<!--		<label for="reg_email">Email Address: <span class="required">*</span></label>-->
		<!--		<input type="email" class="input-text" name="email" id="reg_email" value="" />-->
		<!--	</p>-->

			
		<!--	<p class="form-row form-row-wide">-->
		<!--		<label for="reg_password">Password: <span class="required">*</span></label>-->
		<!--		<input type="password" class="input-text" name="password" id="reg_password" />-->
		<!--	</p>-->

		<!--	<p class="form-row form-row-wide">-->
		<!--		<label for="reg_password2">Repeat Password: <span class="required">*</span></label>-->
		<!--		<input type="password" class="input-text" name="password" id="reg_password2" />-->
		<!--	</p>-->

						
		<!--	<p class="form-row">-->
		<!--		<input type="submit" class="button" name="register" value="Register" />-->
		<!--	</p>-->
			
		<!--</form>-->
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}