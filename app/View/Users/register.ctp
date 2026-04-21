<?php
	$this->assign('Register', 'active');
	$this->Html->script('jquery/combobox', array('inline' => false));
	$this->Html->script('register', array('inline' => false));
?>

<div class="row-fluid">
	<div class="span12">

	<div class="whmcscontainer">
    	<div class="contentpadded">

			<div class="page-header">
				<div class="styled_title"><h1>Register</h1></div>
			</div>
	<?php
		echo $this->Session->flash();

		echo $this->Form->create('User', array(
			'class' => 'form-horizontal',
			 'inputDefaults' => array(
				'div' => array('class' => 'control-group'),
				'label' => array('class' => 'control-label'),
				'between' => '<div class="controls">',
				'after' => '</div>',
				'class' => '',
				'format' => array('before', 'label', 'between', 'input', 'after','error'),
				'error' => array('attributes' => array('class' => 'controls help-block')),
			 ),
		));
	?>

	<div class="row-fluid">
		<div class="span6">
			<div class="control-group required">
				<label for="UserUsername" class="control-label required">Username</label>
				<div class="controls">
					<input name="data[User][username]" class="" maxlength="255" type="text" id="UserUsername" required="required"/>
				</div>
			</div>
			<div class="control-group required">
				<label for="UserPassword" class="control-label required">Password</label>
				<div class="controls">
					<input name="data[User][password]" class="" type="password" id="UserPassword" required="required"/>
				</div>
			</div>
			<div class="control-group required">
				<label for="UserConfirmPassword" class="control-label required">Confirm Password</label>
				<div class="controls">
					<input name="data[User][confirm_password]" class="" type="password" id="UserConfirmPassword" required="required"/>
				</div>
			</div>
			<div class="control-group required">
				<label for="UserName" class="control-label required">Name</label>
				<div class="controls">
					<input name="data[User][name]" class="" maxlength="100" type="text" id="UserName" required="required"/>
				</div>
			</div>
			<div class="control-group required required">
				<label for="UserEmail" class="control-label required">E-MAIL ADDRESS</label>
				<div class="controls">
					<input name="data[User][email]" class="" maxlength="50" type="email" id="UserEmail" required="required"/>
				</div>
			</div>
			<div class="control-group">
				<label for="UserPhoneNo" class="control-label">Phone Number</label>
				<div class="controls">
					<input name="data[User][phone_no]" class="" maxlength="100" type="text" id="UserPhoneNo"/>
				</div>
			</div>
			<div class="control-group">
				<label for="UserDesignationId" class="control-label">Designation</label>
				<div class="controls">
					<select name="data[User][designation_id]" class="" id="UserDesignationId">
						<option value="34"></option>
						<option value="20">Anaesthetist</option>
						<option value="17">Biomedical engineer</option>
						<option value="21">Clinical officer</option>
						<option value="14">Dentist</option>
						<option value="13">Family Physician</option>
						<option value="12">Health Records and Information Officer</option>
						<option value="10">Laboratory Technician</option>
						<option value="15">Laboratory technologist</option>
						<option value="31">Lawyer</option>
						<option value="25">Medical officer</option>
						<option value="8">Nurse</option>
						<option value="16">Nutritionist</option>
						<option value="23">Obstetrician/Gynaecologist</option>
						<option value="33">Ophthalmologist</option>
						<option value="28">Other Health Care Professional</option>
						<option value="19">Paediatrician</option>
						<option value="26">Patient</option>
						<option value="9">Pharmaceutical technologist</option>
						<option value="11">Pharmacist</option>
						<option value="6">Physician</option>
						<option value="7">Physiotherapist</option>
						<option value="22">Psychiatrist</option>
						<option value="24">Radiographer</option>
						<option value="27">Relative/Friend/Guardian/Parent</option>
						<option value="32">Research assistant</option>
						<option value="18">Surgeon</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="controls checkbox">
					<input type="checkbox" name="data[User][user_type]" value="Market Authority" id="UserUserType"/>
					Are you reporting for market authority? 
				</label>
			</div>
			<div class="control-group ribidi">
				<label for="UserSponsorEmail" class="control-label required">Company's Email</label>
				<div class="controls">
					<input name="data[User][sponsor_email]" class="" maxlength="55" type="email" id="UserSponsorEmail"/>
				</div>
			</div>
		</div><!--/span-->
		<div class="span6">
			<div class="control-group">
				<label for="UserNameOfInstitution" class="control-label">Name of Institution</label>
				<div class="controls">
					<input name="data[User][name_of_institution]" class="" maxlength="100" type="text" id="UserNameOfInstitution"/>
					<p class="help-block"> Start typing and suggestions will appear </p>
				</div>
			</div>
			<div class="control-group">
				<label for="UserInstitutionCode" class="control-label">Institution Code</label>
				<div class="controls">
					<input name="data[User][institution_code]" class="" maxlength="100" type="text" id="UserInstitutionCode"/>
					<p class="help-block"> Start typing and suggestions will appear </p>
				</div>
			</div>
			<div class="control-group">
				<label for="UserInstitutionAddress" class="control-label">Institution Address</label>
				<div class="controls">
					<input name="data[User][institution_address]" class="" maxlength="100" type="text" id="UserInstitutionAddress"/>
				</div>
			</div>
			<div class="control-group">
				<label for="UserInstitutionContact" class="control-label">Institution Contacts</label>
				<div class="controls">
					<input name="data[User][institution_contact]" class="" maxlength="100" type="text" id="UserInstitutionContact"/>
				</div>
			</div>
			<div class="control-group">
				<label for="UserInstitutionEmail" class="control-label">Institution Email</label>
				<div class="controls">
					<input name="data[User][institution_email]" class="" maxlength="255" type="email" id="UserInstitutionEmail"/>
				</div>
			</div>
			<div class="control-group">
				<label for="UserCountyId" class="control-label required">County </label>
				<div class="controls ui-widget">
					<select name="data[User][county_id]" class="" id="UserCountyId">
						<option value=""></option>
						<option value="30">Baringo</option>
						<option value="36">Bomet</option>
						<option value="39">Bungoma</option>
						<option value="40">Busia</option>
						<option value="28">Elgeyo/Marakwet</option>
						<option value="14">Embu</option>
						<option value="7">Garissa</option>
						<option value="43">Homa Bay</option>
						<option value="11">Isiolo</option>
						<option value="34">Kajiado</option>
						<option value="37">Kakamega</option>
						<option value="35">Kericho</option>
						<option value="22">Kiambu</option>
						<option value="3">Kilifi</option>
						<option value="20">Kirinyaga</option>
						<option value="45">Kisii</option>
						<option value="42">Kisumu</option>
						<option value="15">Kitui</option>
						<option value="2">Kwale</option>
						<option value="31">Laikipia</option>
						<option value="5">Lamu</option>
						<option value="16">Machakos</option>
						<option value="17">Makueni</option>
						<option value="9">Mandera</option>
						<option value="10">Marsabit</option>
						<option value="12">Meru</option>
						<option value="44">Migori</option>
						<option value="1">Mombasa</option>
						<option value="21">Murang'a</option>
						<option value="47">Nairobi County</option>
						<option value="32">Nakuru</option>
						<option value="29">Nandi</option>
						<option value="33">Narok</option>
						<option value="46">Nyamira</option>
						<option value="18">Nyandarua</option>
						<option value="19">Nyeri</option>
						<option value="25">Samburu</option>
						<option value="41">Siaya</option>
						<option value="6">Taita Taveta</option>
						<option value="4">Tana River</option>
						<option value="13">Tharaka Nithi</option>
						<option value="26">Trans Nzoia</option>
						<option value="23">Turkana</option>
						<option value="27">Uasin Gishu</option>
						<option value="38">Vihiga</option>
						<option value="8">Wajir</option>
						<option value="24">West Pokot</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<span id="captchaCode">six + 2</span>
				<div style="display:none">
					<div class="control-group">
						<label for="UserCaptchaHash" class="control-label">Captcha Hash</label>
						<div class="controls">
							<input name="data[User][captcha_hash]" class="" value="1bbfec3902cdddcfe01721c955fdda65bae8dce6" type="text" id="UserCaptchaHash"/>
						</div>
					</div>
					<div class="control-group">
						<label for="UserCaptchaTime" class="control-label">Captcha Time</label>
						<div class="controls">
							<input name="data[User][captcha_time]" class="" value="1776790532" type="text" id="UserCaptchaTime"/>
						</div>
					</div>
					<div class="control-group">
						<label for="UserHomepage" class="control-label">Homepage</label>
						<div class="controls">
							<input name="data[User][homepage]" class="" value="" type="text" id="UserHomepage"/>
						</div>
					</div>
				</div>
				= <input name="data[User][captcha]" class="captcha" value="" maxlength="3" autocomplete="off" type="number" id="UserCaptcha"/> 
			</div>
		</div><!--/span-->
	</div><!--/row-->
	 <hr>

	<div class="input text" style="display:none">
		<label for="UserBotStop" class="control-label">Bot Stop</label>
		<div class="controls">
			<input name="data[User][bot_stop]" class="" type="text" id="UserBotStop"/>
		</div>
	</div>
	<?php
		echo $this->Form->end(array(
			'label' => 'Submit',
			'value' => 'Save',
			'class' => 'btn btn-primary',
			'id' => 'SadrSaveChanges',
			'div' => array(
				'class' => 'form-actions',
			)
		));
	?>
		</div>
	</div>
</div>
</div>