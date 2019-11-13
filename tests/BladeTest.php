<?php

namespace hisorange\BrowserDetect\Test;

use Illuminate\Support\Facades\Blade;

/**
 * Class BladeTest
 *
 * @package hisorange\BrowserDetect\Test
 * @coversDefaultClass \hisorange\BrowserDetect\ServiceProvider
 */
class BladeTest extends TestCase
{
    /**
     * @return array
     */
    public function directiveProvider()
    {
        return [['mobile'], ['desktop'], ['tablet']];
    }

    /**
     * @dataProvider directiveProvider
     * @param string $directive
     * @covers       ::<protected>registerDirectives()
     */
    public function testDirectives($directive)
    {
        $actual   = Blade::compileString('@' . $directive . ' Ok @end' . $directive);
        $expected = "<?php if (\Illuminate\Support\Facades\Blade::check('$directive')): ?> Ok <?php endif; ?>";

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function directiveValuedProvider()
    {
        return [['mobile', false], ['desktop', true], ['tablet', false]];
    }

    /**
     * @dataProvider directiveValuedProvider
     * @param string $directive
     * @covers       ::<protected>registerDirectives()
     */
    public function testCheckingDirectives($directive, $expected)
    {
        $this->assertSame($expected, Blade::check($directive));
    }

    /**
     * @param string $directive
     * @covers       ::<protected>registerDirectives()
     */
    public function testBrowserDirectiveResult()
    {
        $this->assertSame(true, Blade::check('browser', 'isDesktop'));
        $this->assertSame(false, Blade::check('browser', 'ISMOBILE'));
    }

    /**
     * @covers ::<protected>registerDirectives()
     */
    public function testBrowserDirective()
    {
        $actual   = Blade::compileString('@browser("isMobile") Ok @endbrowser');
        $expected = '<?php if (\Illuminate\Support\Facades\Blade::check(\'browser\', "isMobile")): ?> Ok <?php endif; ?>';

        $this->assertSame($expected, $actual);
    }
}
