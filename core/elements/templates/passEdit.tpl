{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
	<div class="six columns centered">
  	<!-- Login -->
		<h3 class="headline">Изменение пароля</h3><span class="line" style="margin-bottom:20px;"></span><div class="clearfix"></div>
    [[!ChangePassword?
      &submitVar=`change-password`
      &placeholderPrefix=`cp.`
      &validateOldPassword=`1`
      &validate=`nospam:blank`
    ]]

    <form class="callme" action="[[~[[*id]]]]" method="post">
      <input type="hidden" name="nospam" value="" />

      <p class="form-row form-row-wide">
				<label for="password_old">Старый пароль: <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_old" id="password_old" value="[[+cp.password_old]]" />
				<span class="help-block text-error">
				  <span class="error">
            [[!+cp.error.password_old]]
          </span>
        </span>
			</p>

      <p class="form-row form-row-wide">
				<label for="password_new">Новый пароль: <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_new" id="password_new" value="[[+cp.password_new]]" />
				<span class="help-block text-error">
				  <span class="error">
            [[!+cp.error.password_new]]
          </span>
        </span>
			</p>
      
      <p class="form-row form-row-wide">
				<label for="password_new_confirm">Новый пароль еще раз: <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_new_confirm" id="password_new_confirm" value="[[+cp.password_new_confirm]]" />
				<span class="help-block text-error">
				  <span class="error">
            [[!+cp.error.password_new_confirm]]
          </span>
        </span>
			</p>

      <p class="form-row">
				<input type="submit" class="button btn primary" name="change-password" value="Сменить пароль!" />
			</p>
    </form>
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}