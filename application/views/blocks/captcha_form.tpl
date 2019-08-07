[{$smarty.block.parent}]

[{assign var="oCaptcha" value=$oView->getCaptcha()}]
<div class="form-group">
    <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    <label class="control-label col-lg-2 req">[{oxmultilang ident="VERIFICATION_CODE" suffix="COLON"}]</label>
    <div class="col-lg-10 controls verify">

        [{assign var="oCaptcha" value=$oView->getCaptcha()}]
        [{if $oCaptcha->isImageVisible()}]
        <img src="[{$oCaptcha->getImageUrl()}]" alt="">
        [{else}]
        <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
        [{/if}]
        <input type="text" data-fieldsize="verify" name="c_mac" value="" class="js-oxValidate js-oxValidate_notEmpty">
        <p class="oxValidateError">
            <span class="js-oxError_notEmpty">[{oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS"}]</span>
        </p>
    </div>
</div>
