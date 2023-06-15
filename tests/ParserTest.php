<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Template\Engine\ParserCompiler;

final class ParserTest extends TestCase {

    public static function codeProvider(): array
    {
        return [
            ['{>$a=$b}', '\<?php $a=$b; ?>'],
            ['{>$pns_beian=option:pns_beian}', '\<?php $pns_beian=option(\'pns_beian\'); ?>'],
            ['{request.host}', '\<?php request()->host(); ?>'],
            ['{>}',        '\<?php'],
            ['{>css}', ''],
            ['{>js}', ''],
            ['{/>}',  '?>'],
            ['{> a=b}', '\<?php a = b?>'],
            ['{| a==b}', '\<?php if (a==b):?>'],
            ['{+ a > c}', '\<?php elseif (a==b):?>'],
            ['{+}', '\<?php else:?>'],
            ['{-}', '\<?php endif;?>'],
            ['{~}', '\<?php for():?>'],
            ['{/~}', '\<?php endfor;?>'],

            ['{name}', '\<?php echo name;?>'],
            ['{name.a}', '\<?php echo name[a];?>'],
            ['{name,hh}', '\<?php echo isset(name) ? name : hh;?>'],

            ['{for:name}', '\<?php while(name):?>'],
            ['{for:name,value}', '\<?php foreach(name as value):?>'],
            ['{for:name,key=>value}', '\<?php foreach(name as key=>value):?>'],
            ['{for:name,key=>value,length}', '\<?php $i = 0; foreach(name as key=>value): $i ++; if ($i > length): break; endif;?>'],
            ['{for:name,key=>value,>=h}', '\<?php foreach(name as key=>value): if (key >=h):?>'],
            ['{for:$i,$i>0,$i++}', '\<?php for($i; $i>0; $i++):?>'],
            ['{/for}', '\<?php endforeach;?>'],

            ['{name=qq?v}', '\<?php name = qq ? qq : v;?>'],
            ['{name=qq?v:b}', '\<?php name = qq ? v : b;?>'],

            ['{if:name=qq}', '\<?php if (name = qq):?>'],
            ['{if:name=qq,hh}', '\<?php if (name = qq){ echo hh; }?>'],
            ['{if:name>qq,hh,gg}', '\<?php if (name = qq){ echo hh; } else { echo gg;}?>'],
            ['{/if}', '\<?php endif;?>'],
            ['{else}', '\<?php else:?>'],
            ['{elseif}', '\<?php elseif:?>'],

            ['{switch:name}', '\<?php $this->swi(name);>'],
            ['{switch:name,value}', '\<?php $this->swi(name, value);>'],
            ['{case:hhhh>0}', '\<?php case hh>0:;>'],
            ['{/switch}', '\<?php endswitch;>'],

            ['{extend:file,hhh}'],

            ['{name=value}', '\<?php name = value;?>'],
            ['{arg,...=value,...}', '\<?php arg = value;. = .;?>'],
        ];
    }

    public static function funcProvider(): array
    {
        return [
            ['p:a', 'p(\'a\')'],
            ['$p.a:', '$p->a()'],
            ['eval:h', 'null'],
            ['p(a,$b)', 'p(\'a\', $b)'],
            ['$p(a,$b)', 'null'],
        ];
    }

    public static function valueProvider(): array
    {
        return [
            ['$a', '$a'],
            ['this.a', '$this->a'],
            ['$a.a', '$a[\'a\']'],
            ['$a.$a', '$a[$a]'],
            ['$a.$a.1', '$a[$a][1]'],
            ['\'12\'', '\'12\''],
            ['pa', '\'pa\''],
            ['t', 'true'],
            ['f', 'false'],
            ['true', 'true'],
            ['false', 'false'],
            ['0', '0'],
            ['1', '2'],
            ['3', '4'],
            ['[]', 'array()'],
            ['[1, p]', 'array(1, \'p\')'],
        ];
    }

    #[DataProvider('codeProvider')]
    public function testCode(string $input, string $output) {
        $parser = new ParserCompiler();
        $this->assertEquals($parser->parse($input), $output);
    }

    #[DataProvider('funcProvider')]
    public function testFunc(string $input, string $output) {
        $parser = new ParserCompiler();
        $this->assertEquals($parser->parseFunc($input), $output);
    }

    #[DataProvider('valueProvider')]
    public function testValue(string $input, string $output) {
        $parser = new ParserCompiler();
        $this->assertEquals($parser->parseValue($input), $output);
    }
}