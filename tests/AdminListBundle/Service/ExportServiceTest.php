<?php

namespace Tests\Kunstmaan\AdminListBundle\Service;

use Exception;
use Kunstmaan\AdminListBundle\AdminList\Field;
use Kunstmaan\AdminListBundle\AdminList\FieldAlias;
use Kunstmaan\AdminListBundle\Service\ExportService;
use Kunstmaan\MenuBundle\Entity\MenuItem;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Translation\Translator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-03-19 at 09:56:53.
 */
class ExportServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExportService
     */
    protected $object;

    /**
     * @var bool $ob
     */
    private $ob;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $trans = $this->createMock(Translator::class);
        $this->object = new ExportService;
        $this->object->setTranslator($trans);
        $this->ob = false;
    }

    protected function tearDown()
    {
        if ($this->ob == true) {
            ob_end_clean();
        }
    }

    public function testGetSupportedExtensions()
    {
        $extensions = ExportService::getSupportedExtensions();
        $this->assertEquals(array('Csv' => 'csv', 'Excel' => 'xlsx'), $extensions);
    }

    /**
     * @dataProvider createFromTemplateProvider
     */
    public function testCreateFromTemplate($template = null)
    {
        $adminList = $this->createMock('Kunstmaan\AdminListBundle\AdminList\ExportableInterface');
        $iterator = $this->createMock('\Iterator');
        $adminList->expects($this->once())->method('getIterator')->willReturn($iterator);

        $templateName = is_null($template) ? 'KunstmaanAdminListBundle:Default:export.csv.twig' : $template;
        $renderer = $this->createMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $renderer->expects($this->once())
            ->method('render')
            ->with($templateName,
                array(
                    'iterator'    => $iterator,
                    'adminlist'   => $adminList,
                    'queryparams' => array()
                )
            );

        $this->object->setRenderer($renderer);
        $this->object->createFromTemplate($adminList, ExportService::EXT_CSV, $template);
    }

    public function createFromTemplateProvider()
    {
        return array(
            array(null),
            array('MyBundle:Default:export.csv.twig'),
        );
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * This code sends headers, so we need to use @ runInSeparateProcess
     *
     * @throws \Exception
     */
    public function testStreamExcelSheet()
    {
        $view = $this->createMock(EngineInterface::class);
        $view->expects($this->any())->method('exists')->willReturn(true);
        $this->object->setRenderer($view);

        $adminList = $this->createMock('Kunstmaan\AdminListBundle\AdminList\ExportableInterface');
        $adminList->expects($this->any())->method('getIterator')->willReturn([
            new MenuItem(),
            [0 => 'value']
        ]);
        $adminList->expects($this->any())->method('getExportColumns')->willReturn([
            new Field('name', 'Name', false, 'template'),
            new Field('number', 'number', false, 'template'),
            new Field('photos.photo', 'Photo', false, 'template', new FieldAlias('photos', 'test')),
        ]);

        /** @var StreamedResponse $response */
        $response = $this->object->streamExcelSheet($adminList);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\StreamedResponse', $response);
        ob_start();
        $response->sendContent();
        $content = ob_get_contents();
        ob_end_clean();
        $this->assertNotEmpty($content); // binary string seems to change every time! so just check it isn't empty 😐
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * This code sends headers, so we need to use @ runInSeparateProcess
     *
     * @throws \Exception
     */
    public function testStreamExcelSheetThrowsException()
    {
        $this->ob = true;

        $this->expectException(Exception::class);

        $view = $this->createMock(EngineInterface::class);
        $view->expects($this->any())->method('exists')->willReturn(false);
        $this->object->setRenderer($view);

        $adminList = $this->createMock('Kunstmaan\AdminListBundle\AdminList\ExportableInterface');
        $adminList->expects($this->any())->method('getIterator')->willReturn([
            new MenuItem(),
            [0 => 'value']
        ]);
        $adminList->expects($this->any())->method('getExportColumns')->willReturn([
            new Field('name', 'Name', true, 'template.twig'),
        ]);

        /** @var StreamedResponse $response */
        $response = $this->object->streamExcelSheet($adminList);
        ob_start();
        $response->sendContent();
    }

    public function testCreateResponse()
    {
        $response = $this->object->createResponse('content', ExportService::EXT_CSV);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetDownloadableResponse()
    {
        $adminList = $this->createMock('Kunstmaan\AdminListBundle\AdminList\ExportableInterface');
        $adminList->expects($this->any())->method('getIterator')->willReturn([
            new MenuItem(),
            [0 => 'value']
        ]);
        $adminList->expects($this->any())->method('getExportColumns')->willReturn([
            new Field('name', 'Name', false, 'template'),
            new Field('number', 'number', false, 'template'),
            new Field('photos.photo', 'Photo', false, 'template', new FieldAlias('photos', 'test')),
        ]);

        $response = $this->object->getDownloadableResponse($adminList, ExportService::EXT_EXCEL);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);

        $view = $this->createMock(EngineInterface::class);
        $view->expects($this->any())->method('render')->willReturn('rendered text');
        $this->object->setRenderer($view);

        $response = $this->object->getDownloadableResponse($adminList, ExportService::EXT_CSV);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}