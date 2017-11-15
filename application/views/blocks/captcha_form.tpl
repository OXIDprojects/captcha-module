[{$smarty.block.parent}]

[{assign var="oCaptcha" value=$oView->getCaptcha()}]
<input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>

<li class="verify">
  <label class="req">[{oxmultilang ident="VERIFICATION_CODE" suffix="COLON"}]</label>
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
</li>
