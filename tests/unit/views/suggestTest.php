<?php
/**
 * #PHPHEADER_OECAPTCHA_LICENSE_INFORMATION#
 */

use OxidEsales\EshopCommunity\Core\DatabaseProvider;

require_once __DIR__ . '/../CaptchaTestCase.php';

class Unit_suggestTest extends CaptchaTestCase
{
    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown()
    {
        $sDelete = "delete from oxrecommlists where oxid like 'testlist%'";
        DatabaseProvider::getDB()->execute($sDelete);

        $sDelete = "delete from oxobject2list where oxlistid like 'testlist%'";
        DatabaseProvider::getDB()->execute($sDelete);

        parent::tearDown();
    }

    /**
     * Test getter for suggest data.
     */
    public function testGetSuggestData()
    {
        oxTestModules::addFunction('oeCaptcha', 'passCaptcha', '{return true;}');
        $this->setRequestParameter('editval', array('name' => 'test', 'value' => 'testvalue'));

        $suggest = $this->getProxyClass('oecaptchasuggest');
        $suggest->send();

        $parameter = $suggest->getSuggestData();

        $this->assertEquals('test', $parameter->name);
        $this->assertEquals('testvalue', $parameter->value);
    }

    /**
     * Test captcha getter.
     */
    public function testGetCaptcha()
    {
        $suggest = $this->getProxyClass('oecaptchasuggest');
        $this->assertEquals(oxNew('oeCaptcha'), $suggest->getCaptcha());
    }

    /**
     * Test all is well.
     */
    public function testSendPass()
    {
        $this->setRequestParameter(
            'editval',
            array(
                'name'         => 'test',
                'value'        => 'testvalue',
                'rec_name'     => 'test1',
                'rec_email'    => 'recmail@oxid.lt',
                'send_name'    => 'test3',
                'send_email'   => 'sendmail@oxid.lt',
                'send_message' => 'test5',
                'send_subject' => 'test6',
            )
        );

        $email = $this->getMock('stdclass', array('sendSuggestMail'));
        $email->expects($this->once())->method('sendSuggestMail')
            ->will($this->returnValue(1));

        oxTestModules::addModuleObject('oxemail', $email);

        $product = $this->getMock('oxarticle', array('getId'));
        $product->expects($this->once())->method('getId')->will($this->returnValue('XProduct'));

        $captcha = $this->getMock('oeCaptcha', array('passCaptcha'));
        $captcha->expects($this->once())->method('passCaptcha')->will($this->returnValue(true));

        $suggest = $this->getMock('oecaptchasuggest', array('getProduct', 'getCaptcha'));
        $suggest->expects($this->once())->method('getProduct')->will($this->returnValue($product));
        $suggest->expects($this->once())->method('getCaptcha')->will($this->returnValue($captcha));

        $this->setRequestParameter('searchparam', 'searchparam&&A');
        $this->setRequestParameter('searchcnid', 'searchcnid&&A');
        $this->setRequestParameter('searchvendor', 'searchvendor&&A');
        $this->setRequestParameter('searchmanufacturer', 'searchmanufacturer&&A');
        $this->setRequestParameter('listtype', 'listtype&&A');

        $expected = 'details?anid=XProduct&searchparam=searchparam%26%26A&searchcnid=searchcnid&amp;&amp;A&searchvendor=searchvendor&amp;&amp;A&searchmanufacturer=searchmanufacturer&amp;&amp;A&listtype=listtype&amp;&amp;A';
        $this->assertEquals($expected, $suggest->send());
    }

    /**
     * Test invalid captcha case.
     */
    public function testSendInvalidCaptcha()
    {
        $captcha = $this->getMock('oeCaptcha', array('passCaptcha'));
        $captcha->expects($this->once())->method('passCaptcha')->will($this->returnValue(false));

        $suggest = $this->getMock('oecaptchasuggest', array('getProduct', 'getCaptcha'));
        $suggest->expects($this->never())->method('getProduct');
        $suggest->expects($this->once())->method('getCaptcha')->will($this->returnValue($captcha));

        $this->assertFalse($suggest->send());
    }
}
