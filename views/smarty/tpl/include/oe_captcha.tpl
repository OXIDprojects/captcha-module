[{assign var="oCaptcha" value=$oView->getCaptcha()}]
[{block name="body"}]
    [{block name="style"}]
        <style>
        .oecaptcha-group {display: flex; align-items: center; gap: 2ch;}
        .oecaptcha-container {display: grid; place-items: center;}
    </style>
    [{/block}]

    [{block name="hidden"}]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]" />
    [{/block}]

    [{block name="form_group"}]
        <div class="form-group verify oecaptcha">
            [{block name="label"}]
                <label class="req control-label col-lg-2" for="c_mac">[{oxmultilang ident="VERIFICATION_CODE"}]</label>
            [{/block}]

            [{block name="input_control"}]
                <div class="col-lg-10 controls oecaptcha-group-container">
                    [{block name="input_group"}]
                        <div class="input-group oecaptcha-group">
                            [{block name="image_group"}]
                                <span class="input-group-addon oecaptcha-container">
                                    [{if $oCaptcha->isImageVisible()}]
                                        [{block name="image_visible"}]
                                            <img src="[{$oCaptcha->getImageUrl()}]" alt="">
                                        [{/block}]
                                    [{else}]
                                        [{block name="image_hidden"}]
                                            <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
                                        [{/block}]
                                    [{/if}]
                                </span>
                            [{/block}]
                            [{block name="input"}]
                                <input type="text" data-fieldsize="verify" name="c_mac" value=""
                                    class="form-control js-oxValidate js-oxValidate_notEmpty" required>
                            [{/block}]
                        </div>
                    [{/block}]
                </div>
            [{/block}]
            </div>
    [{/block}]
[{/block}]