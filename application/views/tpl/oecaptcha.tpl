[{assign var="oCaptcha" value=$oView->getCaptcha()}]
<input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>

<div class="form-group verify">
    <label class="req control-label col-lg-2" for="c_mac">[{oxmultilang ident="VERIFICATION_CODE"}]</label>

    <div class="col-lg-10 controls">
        <div class="input-group">
            <span class="input-group-addon">
                [{if $oCaptcha->isImageVisible()}]
                    <img src="[{$oCaptcha->getImageUrl()}]" alt="">
                [{else}]
                    <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
                [{/if}]
            </span>
            <input type="text" data-fieldsize="verify" name="c_mac" value="" class="form-control js-oxValidate js-oxValidate_notEmpty" required>
        </div>

        <div class="help-block"}]
    </div>
</div>