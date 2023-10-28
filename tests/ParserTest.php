<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Template\CharReader;
use Zodream\Template\Engine\ParserCompiler;

final class ParserTest extends TestCase {

    public static function codeProvider(): array
    {
        return [
            ['{if:isset($keywords),$keywords}', '\<?php if (isset($keywords)) { echo $keywords; } ?>'],
            ['{>$a=$b}', '\<?php $a = $b; ?>'],
            ['{>$pns_beian=option:pns_beian}', '\<?php $pns_beian = option(\'pns_beian\'); ?>'],
            ['{request.host}', '\<?= request()->host() ?>'],
//            ['{>}',        '\<?php'],
            ['{@style.css}', ''],
            ['{@main.js}', ''],
/*            ['{/>}',  '?>'],*/
            ['{| $a==$b}', '\<?php if ($a == $b): ?>'],
            ['{+ $a > $c}', '\<?php elseif ($a > $c): ?>'],
            ['{+}', '\<?php else: ?>'],
            ['{-}', '\<?php endif; ?>'],
/*            ['{~}', '\<?php for(): ?>'],*/
/*            ['{/~}', '\<?php endfor; ?>'],*/

            ['{name}', '\<?= \'name\' ?>'],
            ['{$name.a}', '\<?= $name[\'a\'] ?>'],
            ['{$name??hh}', '\<?= $name ?? \'hh\' ?>'],

            ['{for:$name}', '\<?php while ($name): ?>'],
            ['{for:$name,$value}', '\<?php if (!empty($name)): foreach ($name as $value): ?>'],
            ['{for:$name,$key=>$value}', '\<?php if (!empty($name)): foreach ($name as $key => $value): ?>'],
            ['{for:$name,$key=>$value,$length}', '\<?php if (!empty($name)):  $i = 0; foreach($name as $key => $value): $i ++; if ($i > $length): break; endif; ?>'],
            ['{for:$name,$key=>$value,>=10}', '\<?php if (!empty($name)): foreach($name as $key=>$value): if (!($key >= 10)): break; endif; ?>'],
            ['{for:$i,$i>0,$i++}', '\<?php for($i; $i > 0; $i ++): ?>'],
/*            ['{/for}', '\<?php endforeach; ?>'],*/

            ['{$name=$qq?$v}', '\<?php $name = $qq ? $v; ?>'],
            ['{$name=$qq?$v:$b}', '\<?php $name = $qq ? $v : $b; ?>'],

            ['{if:$name=qq}', '\<?php if ($name = \'qq\'): ?>'],
            ['{if:$name=$qq,hh}', '\<?php if ($name = $qq) { echo \'hh\'; } ?>'],
            ['{if:$name>$qq,hh,gg}', '\<?php if ($name > $qq) { echo \'hh\'; } else { echo \'gg\';} ?>'],
            ['{/if}', '\<?php endif; ?>'],
            ['{else}', '\<?php else: ?>'],
            ['{elseif}', '\<?php elseif (): ?>'],

/*            ['{switch:name}', '\<?php $this->swi(name); ?>'],*/
/*            ['{switch:name,value}', '\<?php $this->swi(name, value); ?>'],*/
            ['{case:hhhh>0}', '\<?php case \'hhhh\' > 0: ?>'],
            ['{/switch}', '\<?php endswitch; ?>'],

           // ['{extend:file,hhh}'],

            ['{name=value}', '\<?php \'name\' = \'value\'; ?>'],
            ['{== $a }', '\<?= $this->text($a) ?>'],
           // ['{arg,...=value,...}', '\<?php arg = value;. = .;?\>'],

            ['{$channelid = isset($channel) ? $channel.id : product_center}', '\<?php $channelid = isset($channel)? $channel[\'id\'] : \'product_center\'; ?>']
        ];
    }

    public static function lineProvider(): array {
        return  [
            ['url:./aaa:$a:?query=:$b,false', '$this->url(\'./aaa\'.$a.\'?query=\'.$b,false)'],
            ['$a=b', '$a = \'b\''],
            ['.a=b', '$this->a = \'b\''],
            ['this.a=b', '$this->a = \'b\''],
            ['case:hhhh>0', 'case \'hhhh\' > 0:'],
            ['case:$hhhh>0', 'case $hhhh > 0:'],
            ['case:true', 'case true:'],
            ['tpl:file', '$this->extend(\'file\')'],
            ['tpl:file,a=b,1', '$this->extend(\'file\',[\'a\' => \'b\',1])'],
            ['tpl:file,[a=b],1', '$this->extend(\'file\',[\'a\' => \'b\'],1)'],
            ['if:authGuest:', 'if (authGuest()):', 'authGuest'],
            ['if:isset($a.a)', 'if (isset($a[\'a\'])):'],
            ['if:isset:$a.a', 'if (isset($a[\'a\'])):'],
            ['if:$a==qq', 'if ($a == \'qq\'):'],
            ['if:$a==\'qq\'', 'if ($a == \'qq\'):'],
            ['if:$name==qq,hh,false', 'if ($name == \'qq\') { echo \'hh\'; } else { echo false;}'],
            ['if:$name==qq,hh', 'if ($name == \'qq\') { echo \'hh\'; }'],
            ['for:$a,', 'if (!empty($a)): foreach ($a as $item):'],
            ['$a??q', '$a ?? \'q\''],
            ['if:$channel.children_count>0', 'if ($channel[\'children_count\'] > 0):'],
            ['contents:category=>banner,num=>4', 'contents([\'category\' => \'banner\',\'num\' => 4])', 'contents'],
        ];
    }

    public static function funcProvider(): array
    {
        return [
            ['p:a', 'p(\'a\')'],
            ['$p.a:', '$p->a()'],
            ['eval:h', 'null'],
            ['p(a,$b)', 'p(\'a\',$b)'],
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
            // ['pa', '\'pa\''],
            ['true', 'true'],
            ['false', 'false'],
            ['0', '0'],
            ['1', '1'],
            ['4', '4'],
            ['[]', '[]'],
            ['[1, p]', '[1,\'p\']'],
        ];
    }

    #[DataProvider('codeProvider')]
    public function testCode(string $input, string $output) {
        $parser = new ParserCompiler();
        $parser->registerFunc('option');
        $this->assertEquals($parser->parse($input), str_replace(['\<', '\>'], ['<', '>'], $output));
    }

    #[DataProvider('lineProvider')]
    public function testLine(string $input, string $output, string $func = '') {
        $parser = new ParserCompiler();
        if (!empty($func)) {
            $parser->registerFunc($func);
        }
        $reader = new CharReader($input);
        $this->assertEquals($parser->parseCode($reader, $reader->length())[0], $output);
    }

    #[DataProvider('funcProvider')]
    public function testFunc(string $input, string $output) {
        $parser = new ParserCompiler();
        $parser->registerFunc('p');
        $this->assertEquals($parser->parseFunc($input), $output);
    }

    #[DataProvider('valueProvider')]
    public function testValue(string $input, string $output) {
        $parser = new ParserCompiler();
        $this->assertEquals($parser->parseValue($input), $output);
    }
}