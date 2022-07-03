<div>
	<div class="form-group">
    <label for="alias" class="col-sm-2 control-label">{{ __('Fetch Seen Mails') }}</label>

    <div class="col-sm-6">
			<div class="controls">
				<div class="onoffswitch-wrap">
					<div class="onoffswitch">
						<input type="checkbox" name="fetch_seen" value="1" @if (old('fetch_seen', $mailbox->fetch_seen))checked="checked"@endif id="fetch_seen" class="onoffswitch-checkbox">
						<label class="onoffswitch-label" for="fetch_seen"></label>
					</div>
				</div>
			</div>
    </div>
	</div>
	<hr />
</div>